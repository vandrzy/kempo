<?php

namespace App\Http\Controllers;

use App\Exports\PesertaExport;
use App\Models\Kabupaten;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\ListKategori;
use App\Models\Pertandingan;
use App\Models\PertandinganPeserta;
use App\Models\Peserta;
use App\Models\UrutanKomposisiPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use App\Helpers\FotoHelper;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role === 'wasit') {
            abort(403, 'Unauthorized');
        }

        $data['peserta'] = Peserta::where('status', 'P')->whereNotNull('created_at');

        if(isset($_GET['delete_duplikat']) || isset($_POST['delete_duplikat'])) {
            return $this->hapusDuplikat(request());
        }

        if((isset($_GET['submit']) && $_GET['submit'] == 'export') || (isset($_GET['action']) && $_GET['action'] == 'export')) {
            $kegiatanId = !empty($_GET['kegiatan_id']) ? $_GET['kegiatan_id'] : optional(Kegiatan::orderBy('created_at', 'DESC')->first())->id;
            $kategoriId = !empty($_GET['kategori_id']) ? $_GET['kategori_id'] : null;
            $pertandinganId = !empty($_GET['pertandingan_id']) ? $_GET['pertandingan_id'] : null;
            $nama = !empty($_GET['nama']) ? $_GET['nama'] : null;
            return Excel::download(new PesertaExport($kegiatanId, $kategoriId, $pertandinganId, $nama), 'rekap_peserta.xlsx');
        } else {
            if(isset($_GET['nama'])  && $_GET['nama'] != "") {
                $data['peserta'] = $data['peserta']->where('nama','like','%'.$_GET['nama'].'%');
            }

            if(isset($_GET['kegiatan_id'])  && $_GET['kegiatan_id'] != "") {

                $kegiatan = Kegiatan::find($_GET['kegiatan_id']);
                $data['kegiatan_get'] = $kegiatan;

                if ($kegiatan) {
                    $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $kegiatan->id)->get()->unique('kategori_id')->values();

                    $kegiatan_id = $kegiatan->id;
                    $kategori_id = $_GET['kategori_id'] ?? "";
                    $pertandingan_id = $_GET['pertandingan_id'] ?? "";

                    $data['peserta'] = $data['peserta']->whereHas('PertandinganPeserta', function ($q1) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                        $q1->where(function ($subQ) use ($kegiatan_id) {
                            $subQ->where('kegiatan_id', $kegiatan_id)
                                 ->orWhereHas('list_kategori', function ($q2) use ($kegiatan_id) {
                                     $q2->where('kegiatan_id', $kegiatan_id);
                                 });
                        });

                        if(isset($kategori_id)  && $kategori_id != "") {
                            $q1->whereHas('list_kategori', function ($q2) use ($kategori_id) {
                                $q2->where('kategori_id', $kategori_id);
                            });
                        }

                        if(isset($pertandingan_id)  && $pertandingan_id != "") {
                            $q1->whereHas('list_kategori', function ($q2) use ($pertandingan_id) {
                                $q2->where('pertandingan_id', $pertandingan_id);
                            });
                        }
                    });
                }
            } else {
                $data['kegiatan_get'] = Kegiatan::orderBy('created_at', 'DESC')->first();
                if ($data['kegiatan_get']) {
                    $kegiatan_id = $data['kegiatan_get']->id;
                    $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $kegiatan_id)->get()->unique('kategori_id')->values();

                    $data['peserta'] = $data['peserta']->whereHas('PertandinganPeserta', function ($q1) use ($kegiatan_id) {
                        $q1->where(function ($subQ) use ($kegiatan_id) {
                            $subQ->where('kegiatan_id', $kegiatan_id)
                                 ->orWhereHas('list_kategori', function ($q2) use ($kegiatan_id) {
                                     $q2->where('kegiatan_id', $kegiatan_id);
                                 });
                        });
                    });
                }
            }

            if(isset($_GET['kategori_id'])  && $_GET['kategori_id'] != "") {
                $data['pertandingan'] = ListKategori::with('pertandingan')->where('kegiatan_id', $kegiatan_id)->where('kategori_id', $kategori_id)->get();
            }
        }


        $data['peserta'] = $data['peserta']->orderBy('nama', 'ASC')->paginate(10);
        $data['kegiatan'] = Kegiatan::orderBy('created_at', 'DESC')->get();
        // dd($data);
                return view('admin.peserta.index', $data);
    }

    /**
     * Membersihkan data duplikat secara cerdas dan aman:
     * 1. Menjaga nomor tanding yang sah (multi-nomor tanding) agar tidak terhapus.
     * 2. Menghapus pendaftaran ganda pada nomor tanding yang sama persis.
     * 3. Membersihkan record orphan/sampah (tanpa pertandingan).
     * 4. Menghapus file fisik duplikat dari storage.
     */
    public function hapusDuplikat(Request $request)
    {
        if (Auth::user()->role === 'wasit') {
            abort(403, 'Unauthorized');
        }

        $kegiatan_id = $request->kegiatan_id ?? optional(Kegiatan::orderBy('created_at', 'DESC')->first())->id;

        if (!$kegiatan_id) {
            return back()->with('error', 'Kegiatan tidak ditemukan.');
        }

        $totalMerged = 0;
        $totalDeleted = 0;

        DB::beginTransaction();
        try {
            // 1. CARI SEMUA ATLET YANG TERDAFTAR DI KEGIATAN INI BERDASARKAN NIK
            $duplicateNiks = Peserta::where('status', 'P')
                ->whereNotNull('nik')
                ->where('nik', '!=', '')
                ->whereHas('PertandinganPeserta', function ($q) use ($kegiatan_id) {
                    $q->where('kegiatan_id', $kegiatan_id);
                })
                ->select('nik', DB::raw('COUNT(*) as total'))
                ->groupBy('nik')
                ->having('total', '>', 1)
                ->pluck('nik');

            foreach ($duplicateNiks as $nik) {
                // Ambil semua record peserta dengan NIK tersebut di kegiatan ini
                $pesertaList = Peserta::where('status', 'P')
                    ->where('nik', $nik)
                    ->whereHas('PertandinganPeserta', function ($q) use ($kegiatan_id) {
                        $q->where('kegiatan_id', $kegiatan_id);
                    })
                    ->with('PertandinganPeserta.UrutanKomposisiPeserta')
                    ->orderBy('id', 'asc') // Record ID terkecil dijadikan primary/survivor
                    ->get();

                if ($pesertaList->count() <= 1) {
                    continue;
                }

                $primary = $pesertaList->first();
                $duplicates = $pesertaList->slice(1);

                foreach ($duplicates as $dup) {
                    foreach ($dup->PertandinganPeserta as $dupPP) {
                        // Periksa apakah primary sudah terdaftar di nomor tanding yang sama
                        $existingPP = PertandinganPeserta::where('peserta_id', $primary->id)
                            ->where('list_kategori_id', $dupPP->list_kategori_id)
                            ->first();

                        if (!$existingPP) {
                            // BUKAN DUPLIKAT: Ini nomor tanding lain yang sah (misal Randori vs Embu Beregu)
                            // Pindahkan PertandinganPeserta ke primary record agar nomor tanding atlet tidak hilang!
                            $dupPP->update(['peserta_id' => $primary->id]);
                            $totalMerged++;
                        } else {
                            // DUPLIKAT SEJATI PADA NOMOR TANDING YANG SAMA
                            $dupPP->UrutanKomposisiPeserta()->delete();
                            $dupPP->delete();
                        }
                    }

                    // Hapus file foto duplikat jika berbeda dari primary
                    if (!empty($dup->foto) && $dup->foto !== $primary->foto && file_exists(public_path($dup->foto))) {
                        @unlink(public_path($dup->foto));
                    }

                    $dup->delete();
                    $totalDeleted++;
                }
            }

            // 2. BERSIHKAN DATA SAMPAH / ORPHAN (Peserta tanpa pertandingan sama sekali)
            $orphanPesertas = Peserta::where('status', 'P')
                ->whereDoesntHave('PertandinganPeserta')
                ->get();

            foreach ($orphanPesertas as $orphan) {
                if (!empty($orphan->foto) && file_exists(public_path($orphan->foto))) {
                    @unlink(public_path($orphan->foto));
                }
                $orphan->delete();
                $totalDeleted++;
            }

            DB::commit();

            $pesan = "Pembersihan duplikat peserta berhasil! {$totalDeleted} data duplikat/orphan dibersihkan";
            if ($totalMerged > 0) {
                $pesan .= ", dan {$totalMerged} nomor tanding atlet multi-tanding berhasil diamankan dan disatukan.";
            } else {
                $pesan .= ".";
            }

            return back()->with('success', $pesan);

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membersihkan data duplikat: ' . $e->getMessage());
        }
    }

    public function export() {
        // return view('admin.peserta.excel', $data);
    }


    public function get_pertandingan(string $id) {
        $type = request('type', $_GET['type'] ?? null);
        $kegiatanId = request('kegiatan_id', $_GET['kegiatan_id'] ?? null);

        if ($type) {
            $data = [];
            if ($type == 'kategori_id') {
                $data = ListKategori::with('kategori')->where('kegiatan_id', $id)->get()->unique('kategori_id')->values();
            } else {
                $data = ListKategori::with('pertandingan')->where('kegiatan_id', $kegiatanId)->where('kategori_id', $id)->get();
            }

            return response()->json([
                'status' => 'true',
                'data' => $data,
            ]);
        }

        return response()->json([
            'status' => 'false',
            'data' => [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $kegiatanId = request('kegiatan_id', $_GET['kegiatan_id'] ?? null);
        $data['kabupatens'] = Kabupaten::get();
        $data['kegiatan'] = Kegiatan::find($kegiatanId);

        $tahun = old('tanggal_lahir');
        $kelamin = old('jenis_kelamin');




        // if ($tahun && $kelamin) {
        //     $data['pertandingan'] = Pertandingan::whereHas('kategori', function($query) use ($tahun) {
        //         $query->where('tahun_awal', '<=', Date('Y', strtotime($tahun)))->where('tahun_akhir', '>=', Date('Y', strtotime($tahun)));
        //     })->where(function($query) use ($kelamin) {
        //         $query->where('jenis_kelamin', $kelamin)
        //             ->orWhereNull('jenis_kelamin');
        //     })->get();
        // } else {

        // }

        // $data['pertandingan'] = Pertandingan::get();
        $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $data['kegiatan']->id)
        ->select('kategori_id')
        ->groupBy('kategori_id')
        ->get();
        // dd($kategori);


        return view('admin.peserta.tambah', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
            $oldPeserta = null;
            if ($request->filled('existing_reference_id')) {
                $oldPeserta = Peserta::find($request->existing_reference_id);
            }

            $unlocked = [];
            if ($request->filled('unlocked_fields')) {
                $unlocked = is_array($request->unlocked_fields) 
                    ? $request->unlocked_fields 
                    : explode(',', $request->unlocked_fields);
                $unlocked = array_map('trim', $unlocked);
            }

            $getValue = function($field, $default = null) use ($request, $oldPeserta, $unlocked) {
                if ($oldPeserta && !in_array($field, $unlocked)) {
                    return $oldPeserta->$field ?? $default;
                }
                return $request->$field ?? $default;
            };

            $nik = $getValue('nik', $request->nik);
            $jenis_kelamin = $getValue('jenis_kelamin', $request->jenis_kelamin);
            $golongan_darah = $getValue('golongan_darah', $request->golongan_darah);
            $status = $getValue('status', $request->status ?? 'P');
            $tempat = $getValue('tempat', $request->tempat);
            $alamat = $getValue('alamat', $request->alamat);
            $telepon = $getValue('telepon', $request->telepon);
            $keterangan = $getValue('keterangan', $request->keterangan);
            $kabupaten_id = $getValue('kabupaten_id', $request->kabupaten_id);
            $kecamatan_id = $getValue('kecamatan_id', $request->kecamatan_id);
            $kelurahan_id = $getValue('kelurahan_id', $request->kelurahan_id);
            $disabilitas = $getValue('disabilitas', $request->disabilitas);

            $fotoPath = '';
            if ($request->hasFile('foto')) {
                $fotoPath = FotoHelper::simpanFoto($request->file('foto')) ?? '';
            } elseif ($oldPeserta && !empty($oldPeserta->foto)) {
                $fotoPath = $oldPeserta->foto;
            } elseif (!empty($request->existing_foto) && $request->existing_foto !== 'USE_OLD_PHOTO') {
                $fotoPath = $request->existing_foto;
            }

            $dataPeserta = [
                'nama' => $request->nama,
                'jenis_kelamin' => $jenis_kelamin,
                'golongan_darah' => $golongan_darah,
                'nik' => $nik,
                'status' => $status,
                'tempat' => $tempat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $alamat,
                'telepon' => $telepon,
                'berat_badan' => $request->berat_badan,
                'keterangan' => $keterangan,
                'foto' => $fotoPath,
                'kabupaten_id' => $kabupaten_id,
                'kecamatan_id' => $kecamatan_id,
                'kelurahan_id' => $kelurahan_id,
                'disabilitas' => $disabilitas,
                'tingkatan_sabuk' => $request->tingkatan_sabuk_a . "-" . $request->tingkatan_sabuk_b,
            ];

            // Selalu buat record Peserta baru untuk pendaftaran ini agar tidak mengganggu record lama di kegiatan lain
            $peserta = Peserta::create($dataPeserta);

            if ($request->has('pertandingan_id') && is_array($request->pertandingan_id)) {
                foreach($request->pertandingan_id as $index => $a) {
                    // Hindari duplikasi nomor tanding yang sama untuk peserta yang sama
                    $alreadyInPertandingan = PertandinganPeserta::where('peserta_id', $peserta->id)
                        ->where('list_kategori_id', $a)
                        ->first();

                    if ($alreadyInPertandingan) {
                        continue;
                    }
                    $existing = PertandinganPeserta::where('list_kategori_id', $a)
                    ->orderBy('urutan')
                    ->pluck('urutan')
                    ->toArray();

                    // Cari urutan kosong
                    $urutanBaru = 1;
                    foreach ($existing as $num) {
                        if ($num == $urutanBaru) {
                            $urutanBaru++;
                        } else {
                            break; // lubang ditemukan
                        }
                    }

                    $PPA = PertandinganPeserta::create([
                        'peserta_id' => $peserta->id,
                        'list_kategori_id' => $a,
                        'urutan' => $urutanBaru,
                        'kegiatan_id' => $request->kegiatan_id
                    ]);

                    $namaTanding = (!empty($request->nama_tanding[$a]) && $request->nama_tanding[$a] !== '-')
                        ? $request->nama_tanding[$a]
                        : $peserta->nama;
                    $tingkatTanding = (!empty($request->tingkat_tanding[$a]) && $request->tingkat_tanding[$a] !== '-')
                        ? $request->tingkat_tanding[$a]
                        : $peserta->tingkatan_sabuk;

                    $UKP = new UrutanKomposisiPeserta();
                    $UKP->pertandingan_peserta_id = $PPA->id;
                    $UKP->nama = $namaTanding ?? "-";
                    $UKP->tingkat = $tingkatTanding ?? "-";
                    $deskripsiUKP = "";
                    $UKP->urutan_komposisi = $deskripsiUKP;
                    $UKP->save();
                }
            }


            return redirect('peserta')->with('success', 'Peserta telah tersimpan');


        try {

        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('gagal', 'Terjadi kesalahan saat menyimpan data!')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['peserta'] = Peserta::find($id);
        if (!$data['peserta']) {
            return redirect()->back()->with('gagal', 'Data Peserta tidak ditemukan!');
        }
        return view('admin.peserta.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $kegiatan_id = explode('-', $id);

        // $data['peserta'] = Peserta::find($id);
        $data['peserta'] = Peserta::with(['PertandinganPeserta.list_kategori.kategori', 'PertandinganPeserta.UrutanKomposisiPeserta'])->find($id);

        // ambil semua kategori dari peserta tsb
        $data['kategori_terpilih'] = $data['peserta']->PertandinganPeserta->pluck('list_kategori.kategori')->filter()->toArray();
        $data['list_kategori_terpilih'] = $data['peserta']->PertandinganPeserta->filter()->toArray();

        // dd($data['kategori_terpilih']);
        $kegiatanId = request('kegiatan_id');
        $dataPertandinganKegiatan = $data['peserta']->PertandinganPeserta->first(function ($registration) use ($kegiatanId) {
            return !$kegiatanId
                || (string) $registration->kegiatan_id === (string) $kegiatanId
                || (string) $registration->list_kategori?->kegiatan_id === (string) $kegiatanId;
        });
        $data['kabupatens'] = Kabupaten::get();
        $data['kegiatan'] = Kegiatan::find(
            $kegiatanId
                ?? $dataPertandinganKegiatan?->kegiatan_id
                ?? $dataPertandinganKegiatan?->list_kategori?->kegiatan_id
        );

        $tahun = old('tanggal_lahir');
        $kelamin = old('jenis_kelamin');




        // if ($tahun && $kelamin) {
        //     $data['pertandingan'] = Pertandingan::whereHas('kategori', function($query) use ($tahun) {
        //         $query->where('tahun_awal', '<=', Date('Y', strtotime($tahun)))->where('tahun_akhir', '>=', Date('Y', strtotime($tahun)));
        //     })->where(function($query) use ($kelamin) {
        //         $query->where('jenis_kelamin', $kelamin)
        //             ->orWhereNull('jenis_kelamin');
        //     })->get();
        // } else {

        // }

        // $data['pertandingan'] = Pertandingan::get();
        $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $data['kegiatan']->id)
        ->select('kategori_id')
        ->groupBy('kategori_id')
        ->get();
        // dd($kategori);


        return view('admin.peserta.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kegiatanId = $request->input('kegiatan_id');

        DB::transaction(function () use ($request, $id, $kegiatanId) {
            $peserta = Peserta::findOrFail($id);
            $peserta->update([
                'nama' => $request->nama,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'golongan_darah'=> $request->golongan_darah,
                'nik'=> $request->nik,
                'status'=> $request->status,
                'tempat'=> $request->tempat,
                'tanggal_lahir'=> $request->tanggal_lahir,
                'alamat'=> $request->alamat,
                'telepon'=> $request->telepon,
                'berat_badan'=> $request->berat_badan,
                'keterangan'=> $request->keterangan,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'disabilitas' => $request->disabilitas,
                'tingkatan_sabuk' => $request->tingkatan_sabuk_a . '-' . $request->tingkatan_sabuk_b,
            ]);

            if ($request->hasFile('foto')) {
                $fotoPath = FotoHelper::simpanFoto($request->file('foto'));
                if ($fotoPath) {
                    $peserta->update(['foto' => $fotoPath]);
                }
            }

            $kategoriIds = collect($request->input('kategori_id', []))
                ->filter()
                ->map(fn ($kategoriId) => (int) $kategoriId)
                ->unique()
                ->values();

            // Treat a missing selection as an incomplete form submission, not a request to
            // remove every registration for this participant.
            if (!$kegiatanId || $kategoriIds->isEmpty()) {
                return;
            }

            $submittedListKategoriIds = $request->input('pertandingan_id', []);
            $availableListKategoriIds = $request->input('available_pertandingan_id', []);
            $submittedListKategoriIds = is_array($submittedListKategoriIds) ? $submittedListKategoriIds : [];
            $availableListKategoriIds = is_array($availableListKategoriIds) ? $availableListKategoriIds : [];

            $selectedListKategori = ListKategori::where('kegiatan_id', $kegiatanId)
                ->whereIn('kategori_id', $kategoriIds)
                ->whereIn('id', $submittedListKategoriIds)
                ->get();
            $availableListKategoriIds = ListKategori::where('kegiatan_id', $kegiatanId)
                ->whereIn('kategori_id', $kategoriIds)
                ->whereIn('id', $availableListKategoriIds)
                ->pluck('id')
                ->filter()
                ->map(fn ($listKategoriId) => (int) $listKategoriId)
                ->unique();

            $registrations = PertandinganPeserta::where('peserta_id', $peserta->id)
                ->where(function ($query) use ($kegiatanId) {
                    $query->where('kegiatan_id', $kegiatanId)
                        ->orWhereHas('list_kategori', fn ($listKategori) => $listKategori->where('kegiatan_id', $kegiatanId));
                })
                ->with('list_kategori')
                ->get();

            foreach ($registrations as $registration) {
                $kategoriId = $registration->list_kategori?->kategori_id;
                if ($kategoriId === null) {
                    continue;
                }

                if (!$kategoriIds->contains((int) $kategoriId)) {
                    $registration->UrutanKomposisiPeserta()->delete();
                    $registration->delete();
                    continue;
                }

                if (
                    $availableListKategoriIds->contains((int) $registration->list_kategori_id)
                    && !$selectedListKategori->contains('id', $registration->list_kategori_id)
                ) {
                    $registration->UrutanKomposisiPeserta()->delete();
                    $registration->delete();
                }
            }

            foreach ($selectedListKategori as $listKategori) {
                $registration = PertandinganPeserta::where('peserta_id', $peserta->id)
                    ->where('list_kategori_id', $listKategori->id)
                    ->first();

                if ($registration) {
                    $komposisi = $registration->UrutanKomposisiPeserta()->firstOrNew();
                    $komposisi->nama = $request->input("nama_tanding.{$listKategori->id}")
                        ?: ($komposisi->nama ?: $peserta->nama);
                    $komposisi->tingkat = $request->input("tingkat_tanding.{$listKategori->id}")
                        ?: ($komposisi->tingkat ?: $peserta->tingkatan_sabuk);
                    $komposisi->save();
                    continue;
                }

                $existingUrutans = PertandinganPeserta::where('list_kategori_id', $listKategori->id)
                    ->orderBy('urutan')
                    ->pluck('urutan')
                    ->toArray();

                $urutanBaru = 1;
                foreach ($existingUrutans as $urutan) {
                    if ($urutan == $urutanBaru) {
                        $urutanBaru++;
                    } else {
                        break;
                    }
                }

                $registration = PertandinganPeserta::create([
                    'peserta_id' => $peserta->id,
                    'list_kategori_id' => $listKategori->id,
                    'urutan' => $urutanBaru,
                    'kegiatan_id' => $kegiatanId,
                ]);

                UrutanKomposisiPeserta::create([
                    'pertandingan_peserta_id' => $registration->id,
                    'nama' => $request->input("nama_tanding.{$listKategori->id}") ?: $peserta->nama,
                    'tingkat' => $request->input("tingkat_tanding.{$listKategori->id}") ?: $peserta->tingkatan_sabuk,
                    'urutan_komposisi' => '',
                ]);
            }
        });

        $redirectUrl = url('peserta');
        if ($kegiatanId) {
            $redirectUrl .= '?kegiatan_id=' . urlencode($kegiatanId);
        }

        return redirect($redirectUrl)->with('success', 'Peserta telah tersimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $peserta = Peserta::find($id);
            foreach($peserta->PertandinganPeserta() as $a) {
                $a->UrutanKomposisiPeserta()->delete();
            }
            $peserta->PertandinganPeserta()->delete();
            $peserta->delete();

            return back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
