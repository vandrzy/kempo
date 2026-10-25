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

        if(isset($_GET['delete_duplikat'])) {
            $data['peserta'] = Peserta::select('nama', DB::raw('COUNT(*) as jumlah'));
            $kegiatan_pertama = Kegiatan::first();

            $kegiatan_id = $_GET['kegiatan_id'] ?? $kegiatan_pertama->id;
            $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $kegiatan_id)->get()->unique('kategori_id')->values();



            $data['peserta'] = $data['peserta']->WhereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id) {
                $q1->WhereHas('list_kategori', function ($q2) use ($kegiatan_id) {
                    $q2 = $q2->where('kegiatan_id', $kegiatan_id);
                });
            });


            $data['peserta'] = $data['peserta']->groupBy('nama')->having('jumlah', '>', 1)->get();
            foreach ($data['peserta'] as $item) {
                // ambil semua peserta dengan nama yang sama, urutkan terbaru dulu
                $pesertas = Peserta::where('nama', $item->nama)->WhereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id) {
                    $q1->WhereHas('list_kategori', function ($q2) use ($kegiatan_id) {
                        $q2 = $q2->where('kegiatan_id', $kegiatan_id);
                    });
                })->orderBy('id', 'desc')->get();

                // simpan 1 data terbaru, hapus sisanya
                $pesertas->skip(1)->each(function ($peserta) {

                    // pastikan relasinya di-load dulu agar aman
                    $peserta->load(['PertandinganPeserta.UrutanKomposisiPeserta']);

                    // hapus relasi terdalam dulu (UrutanKomposisiPeserta)
                    foreach ($peserta->PertandinganPeserta as $pertandinganPeserta) {
                        $pertandinganPeserta->UrutanKomposisiPeserta()->delete();
                    }

                    // lalu hapus PertandinganPeserta
                    $peserta->PertandinganPeserta()->delete();

                    // terakhir hapus Peserta itu sendiri
                    $peserta->delete();
                });
            }

            return back()->with('success', 'Data Duplikat Berhasil Dihapus');

        }

        if(isset($_GET['submit']) && $_GET['submit'] == 'export') {
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
                $data['kegiatan_get'] = Kegiatan::find($_GET['kegiatan_id']);

                $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $kegiatan->id)->get()->unique('kategori_id')->values();

                $kegiatan_id = $kegiatan->id;
                $kategori_id = $_GET['kategori_id'] ?? "";
                $pertandingan_id = $_GET['pertandingan_id'] ?? "";


                $data['peserta'] = $data['peserta']->WhereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                    $q1->WhereHas('list_kategori', function ($q2) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                        $q2 = $q2->where('kegiatan_id', $kegiatan_id);

                        if(isset($kategori_id)  && $kategori_id != "") {
                            $q2 = $q2->where('kategori_id', $kategori_id);
                        }

                        if(isset($pertandingan_id)  && $pertandingan_id != "") {
                            $q2 = $q2->where('pertandingan_id', $pertandingan_id);
                        }
                    });
                });
            } else {
                $data['kegiatan_get'] = Kegiatan::orderBy('created_at', 'DESC')->first();
                $kegiatan_id = $data['kegiatan_get']->id;
                $kategori_id = $_GET['kategori_id'] ?? "";

                $data['peserta'] = $data['peserta']->WhereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id, $kategori_id) {
                    $q1 = $q1->where('kegiatan_id', $kegiatan_id);
                });
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

    public function export() {
        // return view('admin.peserta.excel', $data);
    }


    public function get_pertandingan(string $id) {

        if(isset($_GET['type'])) {
            $data = [];
            if($_GET['type'] == 'kategori_id') {
                $data = ListKategori::with('kategori')->where('kegiatan_id', $id)->get()->unique('kategori_id')->values();
                // $data = Kategori::where('kegiatan_id', $id)->get();
            } else {
                $data = ListKategori::with('pertandingan')->where('kegiatan_id', $_GET['kegiatan_id'])->where('kategori_id', $id)->get();
            }
                // dd($data);

            return response()->json([
                'status' => 'true',
                'data' => $data,
            ]);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $data['kabupatens'] = Kabupaten::get();
        $data['kegiatan'] = Kegiatan::find($_GET['kegiatan_id']);

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
            $peserta = Peserta::create([
                'nama' => $request->nama,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'golongan_darah'=> $request->golongan_darah,
                'nik'=> $request->nik,
                // 'tingkat'=> $request->tingkat,
                'status'=> $request->status,
                'tempat'=> $request->tempat,
                'tanggal_lahir'=> $request->tanggal_lahir,
                'alamat'=> $request->alamat,
                'telepon'=> $request->telepon,
                'berat_badan'=> $request->berat_badan,
                'keterangan'=> $request->keterangan,
                'foto' => 'lol',
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'disabilitas' => $request->disabilitas,
                'tingkatan_sabuk' => $request->tingkatan_sabuk_a."-".$request->tingkatan_sabuk_b,
            ]);

            $image = $request->file('foto');

            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = storage_path('app/public/foto_peserta/' . $filename);

            // 'foto' => 'storage/'.$request->foto->store('foto_peserta', 'public'),
            // Resize dan compress (quality 75)


            $manager = new ImageManager(new GdDriver());

            $manager->read($image->getRealPath())
                ->scale(width: 1200) // gunakan scale() di v3, bukan resize() sds
                ->save($path, quality: 50);


            $peserta_image = Peserta::find($peserta->id);
            $peserta_image->foto = 'storage/foto_peserta/' . $filename;
            $peserta_image->save();


            foreach($request->pertandingan_id as $index => $a) {
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

                $UKP = new UrutanKomposisiPeserta();
                $UKP->pertandingan_peserta_id = $PPA->id;
                $UKP->nama = $request->nama_tanding[$a] ?? "-";
                $UKP->tingkat = $request->tingkat_tanding[$a] ?? "-";
                $deskripsiUKP = "";
                // foreach($request->urutan_tanding[$a] as $b) {
                //     if($b != "") {
                //         $deskripsiUKP .= ($b ?? "-").",";
                //     }
                // }
                $UKP->urutan_komposisi = $deskripsiUKP;
                $UKP->save();
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
        //
        $data['peserta'] = Peserta::find($id);
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
        $dataPertandinganKegiatan = PertandinganPeserta::where('peserta_id', $data['peserta']->id)->first();
        $data['kabupatens'] = Kabupaten::get();
        $data['kegiatan'] = Kegiatan::find($dataPertandinganKegiatan->kegiatan_id);

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
            $peserta = Peserta::where('id', $id)->update([
                'nama' => $request->nama,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'golongan_darah'=> $request->golongan_darah,
                'nik'=> $request->nik,
                // 'tingkat'=> $request->tingkat,
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
                'tingkatan_sabuk' => $request->tingkatan_sabuk_a."-".$request->tingkatan_sabuk_b,
            ]);

            $peserta = Peserta::find($id);
            foreach($peserta->PertandinganPeserta() as $a) {
                $a->UrutanKomposisiPeserta()->delete();
            }
            $peserta->PertandinganPeserta()->delete();

            if($request->foto) {
                $image = $request->file('foto');

                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = storage_path('app/public/foto_peserta/' . $filename);

                // 'foto' => 'storage/'.$request->foto->store('foto_peserta', 'public'),
                // Resize dan compress (quality 75)


                $manager = new ImageManager(new GdDriver());

                $manager->read($image->getRealPath())
                    ->scale(width: 1200) // gunakan scale() di v3, bukan resize() sds
                    ->save($path, quality: 50);


                $peserta_image = Peserta::find($peserta->id);
                $peserta_image->foto = 'storage/foto_peserta/' . $filename;
                $peserta_image->save();
            }


            foreach($request->pertandingan_id as $index => $a) {
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

                $UKP = new UrutanKomposisiPeserta();
                $UKP->pertandingan_peserta_id = $PPA->id;
                $UKP->nama = $request->nama_tanding[$a] ?? "-";
                $UKP->tingkat = $request->tingkat_tanding[$a] ?? "-";
                $deskripsiUKP = "";
                // foreach($request->urutan_tanding[$a] as $b) {
                //     if($b != "") {
                //         $deskripsiUKP .= ($b ?? "-").",";
                //     }
                // }
                $UKP->urutan_komposisi = $deskripsiUKP;
                $UKP->save();
            }


            return redirect('peserta')->with('success', 'Peserta telah tersimpan');
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
