<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\KontigenPertandingan;
use App\Models\ListKategori;
use App\Models\Pertandingan;
use App\Models\PertandinganPeserta;
use App\Models\Peserta;
use App\Models\PesertaTerpilih;
use Barryvdh\DomPDF\Facade\Pdf;
use Dotenv\Util\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PertandinganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role === 'wasit') {
            return redirect('/kegiatan');
        }
        $data['pertandingan'] = Pertandingan::with('kategori')->whereNotNull('created_at');

        if(isset($_GET['kategori_id'])  && $_GET['kategori_id'] != "") {
            $data['pertandingan'] = $data['pertandingan']->where('kategori_id', $_GET['kategori_id']);
        }

        if(isset($_GET['cari']) && $_GET['cari'] != "") {
            $cari = $_GET['cari'];
            $data['pertandingan'] = $data['pertandingan']->where(function($q) use ($cari) {
                $q->where('nama', 'like', '%'.$cari.'%')
                  ->orWhere('kelas', 'like', '%'.$cari.'%');
            });
        }

        $data['pertandingan'] = $data['pertandingan']->orderBy('kategori_id', 'ASC')->orderBy('jenis_kelamin','DESC')->paginate(10);
        $data['kategori'] = Kategori::get();
        return view('admin.pertandingan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 1, '1', 2, '2'])) {
            abort(403, 'Unauthorized');
        }
        $data['kategori'] = Kategori::get();
        return view('admin.pertandingan.tambah', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 1, '1', 2, '2'])) {
            abort(403, 'Unauthorized');
        }
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'kelas' => 'required',
            'kategori_id' => 'required',
            'jenis_kelamin' => 'required',
        ], [
            'nama.required' => 'Nama Tanding Wajib Diisi!',
            'kelas.required' => 'Kelas Tanding Wajib Diisi!',
            'kategori_id.required' => 'Kategori Wajib Diisi!',
            'jenis_kelamin.required' => 'Jenis Kelamin Wajib Diisi!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }

        try {
            Pertandingan::create([
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'kategori_id' => $request->kategori_id,
                'jenis_kelamin' => $request->jenis_kelamin == "-" ? null : $request->jenis_kelamin,
                'emas' => $request->emas ?? 1,
                'perak' => $request->perak ?? 1,
                'perunggu' => $request->perunggu ?? 1,
                'user_id' => Auth::user()->id,
                'bagan' => (!empty($request->bagan) && $request->bagan == '1') ? 1 : null,
            ]);
            $redirectUrl = $request->filled('kegiatan_id') ? 'pertandingan?kegiatan_id=' . $request->kegiatan_id : 'pertandingan';
            return redirect($redirectUrl)->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            Log::error('Gagal simpan pertandingan: ' . $th->getMessage(), ['exception' => $th]);
            return redirect()->back()
                ->with('gagal', 'Terjadi kesalahan saat menyimpan data: ' . (config('app.debug') ? $th->getMessage() : 'Silakan periksa kembali data Anda.'))
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 'wasit', 1, '1', 2, '2'])) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $data['pertandingan'] = ListKategori::with(['pertandingan', 'kategori', 'kegiatan'])->where('id', $id)->first();
        $kegiatan_id = request('kegiatan_id') ?? ($_GET['kegiatan_id'] ?? optional($data['pertandingan'])->kegiatan_id);
        $data['kegiatan'] = Kegiatan::find($kegiatan_id);

        $check = optional($data['pertandingan'])->pertandingan;
        $isRandori = ($check && $check->bagan == 1) ||
                     stripos($check->nama ?? '', 'randori') !== false ||
                     stripos(optional($data['pertandingan']->kategori)->nama ?? '', 'randori') !== false;
        $data['isRandori'] = $isRandori;

        if ($isRandori) {
            $is_diacak = PesertaTerpilih::where('list_kategori_id', $id)->exists();
            $data['is_diacak'] = $is_diacak;
            $data['peserta_ready'] = $is_diacak ? 0 : PertandinganPeserta::where('list_kategori_id', $id)->count();
        } else {
            $is_diacak = KontigenPertandingan::where('list_kategori_id', $id)->exists();
            $data['is_diacak'] = $is_diacak;
            $data['peserta_ready'] = $is_diacak ? 0 : PertandinganPeserta::where('sudah_terpilih', 'false')->where('list_kategori_id', $id)->count();
        }

        $filterBabak = request('filter_babak') ?? request('babak');
        $queryPesertaLawan = PesertaTerpilih::with([
                'PertandinganPesertaPertama.Peserta.kabupaten',
                'PertandinganPesertaKedua.Peserta.kabupaten',
                'pemenang.Peserta.kabupaten'
            ])
            ->where('list_kategori_id', $id);

        if ($filterBabak && $filterBabak !== 'all' && is_numeric($filterBabak)) {
            $queryPesertaLawan->where('babak', $filterBabak);
        }

        $data['peserta_lawan'] = $queryPesertaLawan
            ->orderBy('babak', 'asc')
            ->orderBy('nomor_partai', 'asc')
            ->paginate(100)
            ->appends(request()->query());

        $data['kabupaten'] = PertandinganPeserta::with('Peserta.kabupaten')->where('list_kategori_id', $id)->get()->groupBy(function ($item) {
            return $item->Peserta->kabupaten_id;
        });


        if(isset($_GET['print']) && $_GET['print'] != "") {
            $data['pertandingan'] = ListKategori::with(['pertandingan', 'kategori', 'kegiatan'])->where('id', $id)->first();
            $data['kolom1'] = KontigenPertandingan::where('urutan', $_GET['print'])->where('list_kategori_id', $id)->orderByRaw('kabupaten_id IS NULL ASC')->orderBy('kabupaten_id','ASC')->get();

            // FASE 4: Ambil seluruh data bagan peserta_terpilihs beserta relasi atlet dan kontingen
            $data['matches'] = PesertaTerpilih::with([
                'PertandinganPesertaPertama.Peserta.kabupaten',
                'PertandinganPesertaKedua.Peserta.kabupaten',
                'pemenang.Peserta.kabupaten'
            ])
            ->where('list_kategori_id', $id)
            ->orderBy('babak', 'asc')
            ->orderBy('nomor_partai', 'asc')
            ->get();

            $data['pesertaList'] = PertandinganPeserta::with('Peserta.kabupaten')
                ->where('list_kategori_id', $id)
                ->get();

            $data['totalPeserta'] = $data['pesertaList']->count();
            $data['slotCount'] = pow(2, ceil(log(max(2, $data['totalPeserta']), 2)));
            $data['totalBabak'] = max((int) log($data['slotCount'], 2), (int) ($data['matches']->max('babak') ?? 1));

            return view('admin.pertandingan.print_bagan', $data);
        }

        if ($isRandori && !$is_diacak) {
            $data['kolom1'] = collect();
        } else {
            $data['kolom1'] = KontigenPertandingan::with([
                'kabupaten.KontigenPertandinganKedua' => function ($q) use ($data) {
                    $q->where('list_kategori_id', $data['pertandingan']->id);
                }
            ])
            ->where('urutan', 1)
            ->where('list_kategori_id', $id)
            ->orderByRaw('kabupaten_id IS NULL ASC')
            ->orderBy('kabupaten_id', 'ASC')
            ->get();
        }

        if(isset($_GET['kabupaten_id'])) {
            $data['kabupaten_first'] = Kabupaten::find($_GET['kabupaten_id']) ?? null;
            $kabupaten_id = $data['kabupaten_first']->id ?? null;

            $data['peserta'] = PertandinganPeserta::with('Peserta')->wherehas('Peserta', function($q) use ($kabupaten_id) {
                $q->where('kabupaten_id', $kabupaten_id);
            })->where('list_kategori_id', $id)->get();

            $kegiatan_id = $data['kegiatan']->id;
            $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $kegiatan_id)->get()->unique('kategori_id')->values();

            $kategori_id = $data['pertandingan']->pertandingan->kategori->id;
            $pertandingan_id = $data['pertandingan']->pertandingan_id;

            $data['pesertas'] = Peserta::with([
                'PertandinganPeserta' => function ($q) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                    $q->where('kegiatan_id', $kegiatan_id)
                    ->whereHas('list_kategori', function ($w) use ($kategori_id, $pertandingan_id, $kegiatan_id) {
                        $w->where('pertandingan_id', $pertandingan_id)
                            ->where('kategori_id', $kategori_id)
                            ->where('kegiatan_id', $kegiatan_id);
                    });
                },
                'PertandinganPeserta.list_kategori' => function ($q) use ($kategori_id, $pertandingan_id, $kegiatan_id) {
                    $q->where('pertandingan_id', $pertandingan_id)
                    ->where('kategori_id', $kategori_id)
                    ->where('kegiatan_id', $kegiatan_id);
                }
            ])
            ->whereHas('PertandinganPeserta', function ($q) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                $q->where('kegiatan_id', $kegiatan_id)
                ->whereHas('list_kategori', function ($w) use ($kategori_id, $pertandingan_id, $kegiatan_id) {
                    $w->where('pertandingan_id', $pertandingan_id)
                        ->where('kategori_id', $kategori_id)
                        ->where('kegiatan_id', $kegiatan_id);
                });
            })
            ->where('kabupaten_id', $kabupaten_id)->get();


            if(isset($_GET['status'])) {
                if($_GET['status'] == 'print') {
                    $data['tanding'] = ListKategori::where('id', $id)->first();
                    $data['kolom1'] = KontigenPertandingan::find($_GET['kontigen_id_1']);
                    $data['kolom2'] = KontigenPertandingan::find($_GET['kontigen_id_2']);

                    $pdf = Pdf::loadView('admin.pertandingan.print_babak', $data);

                    // 🟢 Tampilkan langsung di browser
                    return $pdf->stream('Data_Peserta_Kontigen_Pertandingan_Babak_1_'.date('Y-m-d').'.pdf');
                }
                return response()->json([
                    'message' => 'data berhasil ditampilkan!',
                    'data' => $data['peserta'],
                    'total_data' => $data['peserta']->count(),
                ]);
            }
            // dd($data['peserta']);

        }
        // dd($data['kabupaten']);

        if ($isRandori && !$is_diacak) {
            $data['kontigen_pertandingan'] = collect();
            $data['kolom2'] = collect();
        } else {
            $data['kontigen_pertandingan'] = KontigenPertandingan::where('list_kategori_id', $id)
                ->get()
                ->groupBy('urutan');
            $data['kolom2'] = KontigenPertandingan::where('urutan', 2)->where('list_kategori_id', $id)->whereNull('kabupaten_id')->get();
        }

        return view('admin.pertandingan.tanding', $data);
    }

    public function print(string $id) {
        $kegiatan_id = request('kegiatan_id') ?? ($_GET['kegiatan_id'] ?? null);
        $data['pertandingan'] = ListKategori::with(['pertandingan.kategori', 'kegiatan'])->where('id', $id)->first();
        if (!$kegiatan_id && $data['pertandingan']) {
            $kegiatan_id = $data['pertandingan']->kegiatan_id;
        }
        $data['peserta_ready'] = PertandinganPeserta::where('sudah_terpilih', 'false')->where('list_kategori_id', $id)->count();
        $data['peserta_lawan'] = PesertaTerpilih::with([
                'PertandinganPesertaPertama.Peserta.kabupaten',
                'PertandinganPesertaPertama.Peserta.PertandinganPesertaUtama.list_kategori.pertandingan',
                'PertandinganPesertaKedua.Peserta.kabupaten',
                'PertandinganPesertaKedua.Peserta.PertandinganPesertaUtama.list_kategori.pertandingan'
            ])
            ->where('list_kategori_id', $id)
            ->orderBy('babak', 'asc')
            ->orderBy('nomor_partai', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.pertandingan.peserta_tanding', $data)->setPaper('a4', 'landscape');

        return $pdf->stream("nomor_tanding.pdf");
    }

    public function tanding(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 'wasit', 1, '1', 2, '2'])) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $listKategori = ListKategori::with(['pertandingan', 'kegiatan', 'kategori'])->findOrFail($id);
        $check = $listKategori->pertandingan;

        $isRandori = ($check && $check->bagan == 1) ||
                     stripos($check->nama ?? '', 'randori') !== false ||
                     stripos(optional($listKategori->kategori)->nama ?? '', 'randori') !== false;

        if ($isRandori) {
            // ========================================================
            // FASE 2: ALGORITMA UNDI BAGAN OTOMATIS UNTUK RANDORI
            // ========================================================
            $pesertaList = PertandinganPeserta::with('Peserta.kabupaten')
                ->where('list_kategori_id', $id)
                ->get();

            $totalPeserta = $pesertaList->count();
            if ($totalPeserta < 2) {
                return redirect()->back()->with('error', 'Jumlah peserta minimal 2 untuk dapat mengundi bagan pertandingan.');
            }

            // 1. Bersihkan undian lama jika ada
            KontigenPertandingan::where('list_kategori_id', $id)->delete();
            PesertaTerpilih::where('list_kategori_id', $id)->delete();
            PertandinganPeserta::where('list_kategori_id', $id)->update([
                'medali'      => null,
                'total_nilai' => 0,
            ]);
            $listKategori->data_juara = null;
            $listKategori->save();

            // 2. Tentukan slot terdekat pangkat 2 ($2^k)
            $slotCount = pow(2, ceil(log(max(2, $totalPeserta), 2)));
            $totalBabak = (int) log($slotCount, 2);
            $totalByes = $slotCount - $totalPeserta;

            // 3. Lakukan pengacakan (shuffle) posisi peserta
            $shuffledPeserta = $pesertaList->shuffle()->values();

            // Simpan ke kontigen_pertandingans untuk konsistensi nomor undian
            foreach ($shuffledPeserta as $index => $atlet) {
                KontigenPertandingan::create([
                    'list_kategori_id' => $id,
                    'kabupaten_id'     => optional($atlet->Peserta)->kabupaten_id,
                    'urutan'           => 1,
                    'undian'           => $index,
                ]);
            }

            if ($totalByes == 0) {
                // Semua atlet bertanding mulai dari Babak 1
                $totalPartai = $slotCount / 2;
                for ($p = 1; $p <= $totalPartai; $p++) {
                    $kiri = $shuffledPeserta->get(($p - 1) * 2);
                    $kanan = $shuffledPeserta->get(($p - 1) * 2 + 1);

                    PesertaTerpilih::create([
                        'list_kategori_id'             => $id,
                        'pertandingan_id'              => $check->id,
                        'kegiatan_id'                  => $listKategori->kegiatan_id,
                        'babak'                        => 1,
                        'nomor_partai'                 => $p,
                        'pertandingan_peserta_kiri_id'  => $kiri?->id,
                        'pertandingan_peserta_kanan_id' => $kanan?->id,
                        'pemenang_id'                  => null,
                        'keterangan_menang'            => null,
                    ]);

                    if ($kiri) {
                        $kiri->urutan = ($p - 1) * 2;
                        $kiri->babak = 1;
                        $kiri->sudah_terpilih = 'true';
                        $kiri->save();
                    }
                    if ($kanan) {
                        $kanan->urutan = ($p - 1) * 2 + 1;
                        $kanan->babak = 1;
                        $kanan->sudah_terpilih = 'true';
                        $kanan->save();
                    }
                }

                // Inisialisasi slot babak 2 sampai final agar bagan tree langsung lengkap
                for ($b = 2; $b <= $totalBabak; $b++) {
                    $partiesInRound = (int) ($slotCount / pow(2, $b));
                    for ($pb = 1; $pb <= $partiesInRound; $pb++) {
                        PesertaTerpilih::create([
                            'list_kategori_id'             => $id,
                            'pertandingan_id'              => $check->id,
                            'kegiatan_id'                  => $listKategori->kegiatan_id,
                            'babak'                        => $b,
                            'nomor_partai'                 => $pb,
                            'pertandingan_peserta_kiri_id'  => null,
                            'pertandingan_peserta_kanan_id' => null,
                            'pemenang_id'                  => null,
                            'keterangan_menang'            => null,
                        ]);
                    }
                }

                $msg = 'Bagan pertandingan Randori berhasil diundi (' . $totalPartai . ' partai Babak 1).';
            } else {
                // Ada slot kosong: Atlet yang mendapat slot kosong LANGSUNG melaju ke Babak 2 tanpa membuat partai BYE di Babak 1
                $totalPartaiBabak1 = $totalPeserta - ($slotCount / 2);
                $pesertaBabak1Count = 2 * $totalPartaiBabak1;

                // 1. Buat partai riil Babak 1
                for ($p = 1; $p <= $totalPartaiBabak1; $p++) {
                    $kiri = $shuffledPeserta->get(($p - 1) * 2);
                    $kanan = $shuffledPeserta->get(($p - 1) * 2 + 1);

                    PesertaTerpilih::create([
                        'list_kategori_id'             => $id,
                        'pertandingan_id'              => $check->id,
                        'kegiatan_id'                  => $listKategori->kegiatan_id,
                        'babak'                        => 1,
                        'nomor_partai'                 => $p,
                        'pertandingan_peserta_kiri_id'  => $kiri?->id,
                        'pertandingan_peserta_kanan_id' => $kanan?->id,
                        'pemenang_id'                  => null,
                        'keterangan_menang'            => null,
                    ]);

                    if ($kiri) {
                        $kiri->urutan = ($p - 1) * 2;
                        $kiri->babak = 1;
                        $kiri->sudah_terpilih = 'true';
                        $kiri->save();
                    }
                    if ($kanan) {
                        $kanan->urutan = ($p - 1) * 2 + 1;
                        $kanan->babak = 1;
                        $kanan->sudah_terpilih = 'true';
                        $kanan->save();
                    }
                }

                // 2. Buat Babak 2: Tempatkan atlet yang langsung lolos ke slot kosong Babak 2
                $directIdx = $pesertaBabak1Count;
                $totalPartaiBabak2 = (int) ($slotCount / 4);

                for ($p2 = 1; $p2 <= $totalPartaiBabak2; $p2++) {
                    $p1Kiri = 2 * $p2 - 1;
                    $p1Kanan = 2 * $p2;

                    $kiriAtlet = null;
                    if ($p1Kiri > $totalPartaiBabak1) {
                        $kiriAtlet = $shuffledPeserta->get($directIdx++);
                        if ($kiriAtlet) {
                            $kiriAtlet->urutan = $directIdx - 1;
                            $kiriAtlet->babak = 2;
                            $kiriAtlet->sudah_terpilih = 'true';
                            $kiriAtlet->save();
                        }
                    }

                    $kananAtlet = null;
                    if ($p1Kanan > $totalPartaiBabak1) {
                        $kananAtlet = $shuffledPeserta->get($directIdx++);
                        if ($kananAtlet) {
                            $kananAtlet->urutan = $directIdx - 1;
                            $kananAtlet->babak = 2;
                            $kananAtlet->sudah_terpilih = 'true';
                            $kananAtlet->save();
                        }
                    }

                    PesertaTerpilih::create([
                        'list_kategori_id'             => $id,
                        'pertandingan_id'              => $check->id,
                        'kegiatan_id'                  => $listKategori->kegiatan_id,
                        'babak'                        => 2,
                        'nomor_partai'                 => $p2,
                        'pertandingan_peserta_kiri_id'  => $kiriAtlet?->id,
                        'pertandingan_peserta_kanan_id' => $kananAtlet?->id,
                        'pemenang_id'                  => null,
                        'keterangan_menang'            => null,
                    ]);
                }

                // 3. Inisialisasi Babak 3 s/d Final (jika ada)
                for ($b = 3; $b <= $totalBabak; $b++) {
                    $partiesInRound = (int) ($slotCount / pow(2, $b));
                    for ($pb = 1; $pb <= $partiesInRound; $pb++) {
                        PesertaTerpilih::create([
                            'list_kategori_id'             => $id,
                            'pertandingan_id'              => $check->id,
                            'kegiatan_id'                  => $listKategori->kegiatan_id,
                            'babak'                        => $b,
                            'nomor_partai'                 => $pb,
                            'pertandingan_peserta_kiri_id'  => null,
                            'pertandingan_peserta_kanan_id' => null,
                            'pemenang_id'                  => null,
                            'keterangan_menang'            => null,
                        ]);
                    }
                }

                $msg = 'Bagan pertandingan Randori berhasil diundi (' . $totalPartaiBabak1 . ' partai Babak 1, ' . $totalByes . ' atlet langsung melaju ke Babak 2 tanpa bagan BYE).';
            }

            return redirect()->back()
                ->with('success', $msg)
                ->withInput();

        } else {
            // ========================================================
            // LOGIKA PENGACAKAN EMBU (TERISOLASI DARI RANDORI)
            // ========================================================
            $data['kabupaten'] = PertandinganPeserta::with('Peserta.kabupaten')
                ->where('list_kategori_id', $id)
                ->whereHas('Peserta', function ($q) {
                    $q->whereNotNull('kabupaten_id');
                })
                ->get()
                ->groupBy(function ($item) {
                    return $item->Peserta->kabupaten_id;
                })
                ->values();

            $totalKabupaten = count($data['kabupaten']);
            if ($totalKabupaten == 0) {
                return redirect()->back()->with('error', 'Tidak ada kontingen/peserta yang terdaftar pada nomor tanding ini.');
            }

            if ($totalKabupaten < 2) {
                return redirect()->back()->with('error', 'Jumlah kontingen minimal 2 untuk dapat mengacak nomor tanding.');
            }

            // Hapus undian sebelumnya jika ada
            KontigenPertandingan::where('list_kategori_id', $id)->whereIn('urutan', [1, 2])->delete();

            $kabupaten_list_1 = [];
            $kabupaten_list_2 = [];

            $limit = $totalKabupaten;

            for ($i = 0; $i < $limit; $i++) {
                if (isset($data['kabupaten'][$i])) {
                    $kabupatenId = optional($data['kabupaten'][$i]->first()->Peserta->Kabupaten)->id;
                    $kabupaten_list_1[] = $kabupatenId;
                    $kabupaten_list_2[] = $kabupatenId;
                } else {
                    $kabupaten_list_1[] = null;
                    $kabupaten_list_2[] = null;
                }
            }

            shuffle($kabupaten_list_1);

            $attempts = 0;
            $maxAttempts = 100;
            do {
                shuffle($kabupaten_list_2);

                $valid = true;
                foreach ($kabupaten_list_1 as $i => $val) {
                    // Abaikan slot Bye (null); hanya cek bentrok jika ID kabupaten bukan null dan berada di indeks yang sama
                    if ($val !== null && $kabupaten_list_2[$i] === $val) {
                        $valid = false;
                        break;
                    }
                }
                $attempts++;
            } while (!$valid && $attempts < $maxAttempts);

            foreach ($kabupaten_list_1 as $index => $a) {
                KontigenPertandingan::create([
                    'list_kategori_id' => $id,
                    'kabupaten_id'     => $a,
                    'urutan'           => 1,
                    'undian'           => $index,
                ]);
            }

            foreach ($kabupaten_list_2 as $index => $a) {
                KontigenPertandingan::create([
                    'list_kategori_id' => $id,
                    'kabupaten_id'     => $a,
                    'urutan'           => 2,
                    'undian'           => $index,
                ]);
            }

            // Update status peserta agar sistem mencatat nomor tanding ini sudah diacak
            PertandinganPeserta::where('list_kategori_id', $id)
                ->update(['sudah_terpilih' => 'true']);

            return redirect()->back()
                ->with('success', 'Kontingen dan nomor undian tanding berhasil diacak!')
                ->withInput();
        }
    }

    public function batal_tanding(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 'wasit', 1, '1', 2, '2'])) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $list_kategori_id = $request->list_kategori_id;

        // 1. Hapus bagan undian aktif
        KontigenPertandingan::where('list_kategori_id', $list_kategori_id)->delete();

        // 2. Reset status peserta menjadi belum terpilih & reset medali
        PertandinganPeserta::where('list_kategori_id', $list_kategori_id)
            ->update([
                'sudah_terpilih' => 'false',
                'urutan'         => 0,
                'medali'         => null,
                'total_nilai'    => 0,
            ]);

        // 3. Bersihkan PesertaTerpilih lama jika ada
        PesertaTerpilih::where('list_kategori_id', $list_kategori_id)->delete();

        // 4. Reset data_juara pada list_kategoris
        $lkReset = ListKategori::find($list_kategori_id);
        if ($lkReset) {
            $lkReset->data_juara = null;
            $lkReset->save();
        }

        return redirect()->back()
            ->with('success', 'Bagan nomor tanding dan medali telah berhasil dihapus/direset.')
            ->withInput();
    }

    public function kategori_select($kategori_id) {
        $pertandingan = Pertandingan::where('kategori_id', $kategori_id)->get();

        return response()->json([
            'status' => 'true',
            'data' => $pertandingan,
            // 'data' => $data['pertandingan'],
        ]);
    }

    public function get_pertandingan(string $tahun, string $kelamin) {
        // $data['pertandingan'] = Pertandingan::whereHas('kategori', function($query) use ($tahun) {
        //     $query->where('kegiatan_id', $_GET['kegiatan_id'])->where('tahun_awal', '<=', Date('Y', strtotime($tahun)))->where('tahun_akhir', '>=', Date('Y', strtotime($tahun)));
        // })->where(function($query) use ($kelamin) {
        //     $query->where('jenis_kelamin', $kelamin)
        //         ->orWhereNull('jenis_kelamin');
        // })->get();


        // $kegiatan = ListKategori::with([ 'kategori' => function ($q) use ($tahun, $kelamin) {
        //         $q->with(['pertandingan' => function ($q2) use ($tahun, $kelamin) {
        //             $q2->where('jenis_kelamin', $kelamin)->orWhereNull('jenis_kelamin');
        //     }]);
        // }] )->where('kegiatan_id',$_GET['kegiatan_id'])->whereHas('kategori', function($query) use ($tahun) {
        //     $query->where('kegiatan_id', $_GET['kegiatan_id'])->where('tahun_awal', '<=', Date('Y', strtotime($tahun)))->where('tahun_akhir', '>=', Date('Y', strtotime($tahun)));
        // })->get();
        // s

        $kategoriId = request('kategori_id', $_GET['kategori_id'] ?? null);
        $kegiatanId = request('kegiatan_id', $_GET['kegiatan_id'] ?? null);

        if (!$kategoriId || !$kegiatanId) {
            return response()->json([
                'status' => 'false',
                'message' => 'Parameter kategori_id dan kegiatan_id wajib diisi!',
                'kategori' => null,
                'data' => [],
                'urutan' => [],
            ]);
        }

        $kategori = Kategori::find($kategoriId);
        if (!$kategori) {
            return response()->json([
                'status' => 'false',
                'message' => 'Kategori tidak ditemukan!',
                'kategori' => null,
                'data' => [],
                'urutan' => [],
            ]);
        }

        $pertandingan = ListKategori::with(['kategori', 'pertandingan'])->whereHas('kategori', function($query) use ($tahun, $kategori) {
            $query->where('tahun_awal', '<=', Date('Y', strtotime($tahun)))->where('tahun_akhir', '>=', Date('Y', strtotime($tahun)))->where('id', $kategori->id);
        })->whereHas('pertandingan', function($qw) use ($kelamin) {
            $qw->where(function($q) use ($kelamin) {
                $q->where('jenis_kelamin', $kelamin)->orWhereNull('jenis_kelamin');
            });
        })->where('kegiatan_id', $kegiatanId)->get();

        $urutan_number = [];
        foreach($pertandingan as $index => $a) {
            $existing = PertandinganPeserta::where('list_kategori_id', $a->id)
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

            $urutan_number[] = $urutanBaru;
        }

        return response()->json([
            'status' => 'true',
            'kategori' => $kategori,
            'data' => $pertandingan,
            'urutan' => $urutan_number,
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 1, '1', 2, '2'])) {
            abort(403, 'Unauthorized');
        }
        $data['kategori'] = Kategori::get();
        $data['pertandingan'] = Pertandingan::find($id);

        return view('admin.pertandingan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 1, '1', 2, '2'])) {
            abort(403, 'Unauthorized');
        }
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'kelas' => 'required',
            'kategori_id' => 'required',
            // 'jenis_kelamin' => 'required',
        ], [
            'nama.required' => 'Nama Tanding Wajib Diisi!',
            'kelas.required' => 'Kelas Tanding Wajib Diisi!',
            'kategori_id.required' => 'Kategori Wajib Diisi!',
            'jenis_kelamin.required' => 'Jenis Kelamin Wajib Diisi!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }

        try {
            $pertandingan = Pertandingan::find($id);
            if (!$pertandingan) {
                return redirect('pertandingan')->with('gagal', 'Data pertandingan tidak ditemukan!');
            }
            $pertandingan->kategori_id = $request->kategori_id;
            $pertandingan->nama = $request->nama;
            $pertandingan->kelas = $request->kelas;
            $pertandingan->jenis_kelamin = $request->jenis_kelamin == "-" ? null : $request->jenis_kelamin;
            $pertandingan->emas = $request->emas ?? $pertandingan->emas ?? 1;
            $pertandingan->perak = $request->perak ?? $pertandingan->perak ?? 1;
            $pertandingan->perunggu = $request->perunggu ?? $pertandingan->perunggu ?? 1;
            $pertandingan->user_id = Auth::user()->id;
            $pertandingan->bagan = (!empty($request->bagan) && $request->bagan == '1') ? 1 : null;

            $pertandingan->save();

            $redirectUrl = $request->filled('kegiatan_id') ? 'pertandingan?kegiatan_id=' . $request->kegiatan_id : 'pertandingan';
            return redirect($redirectUrl)->with('success', 'Data Berhasil Diubah');
        } catch (\Throwable $th) {
            Log::error('Gagal update pertandingan: ' . $th->getMessage(), ['exception' => $th]);
            return redirect()->back()
                ->with('gagal', 'Terjadi kesalahan saat menyimpan data: ' . (config('app.debug') ? $th->getMessage() : 'Silakan periksa kembali data Anda.'))
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 1, '1', 2, '2'])) {
            abort(403, 'Unauthorized');
        }
        try {
            $pertandingan = Pertandingan::find($id);
            $pertandingan->delete();

            return redirect('pertandingan?kegiatan_id='.Request('kegiatan_id'))->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('gagal', 'Terjadi kesalahan saat menghapus data!');
        }
    }

    /**
     * TUGAS 1: Halaman Rekapitulasi Informasi Berdasarkan Kegiatan
     */
    public function laporanKegiatan(Request $request, $kegiatan_id = null)
    {
        if (Auth::user()->role === 'wasit') {
            return redirect('/kegiatan');
        }
        $semua_kegiatan = Kegiatan::orderBy('created_at', 'DESC')->get();
        $default_kegiatan = $semua_kegiatan->first(function ($kg) {
            return PertandinganPeserta::where('kegiatan_id', $kg->id)->exists();
        }) ?? $semua_kegiatan->first();

        $active_kegiatan_id = $kegiatan_id ?: ($request->kegiatan_id ?: optional($default_kegiatan)->id);
        $kegiatan_aktif = Kegiatan::find($active_kegiatan_id);

        $rekap = $this->getRekapitulasiData($active_kegiatan_id);

        return view('admin.pertandingan.laporan_kegiatan', array_merge([
            'semua_kegiatan' => $semua_kegiatan,
            'kegiatan_aktif' => $kegiatan_aktif,
        ], $rekap));
    }

    /**
     * Cetak PDF Buku Laporan Hasil Pertandingan Kejuaraan Shorinji Kempo
     */
    public function cetakLaporanPdf(Request $request, $kegiatan_id = null)
    {
        if (Auth::user()->role === 'wasit') {
            return redirect('/kegiatan');
        }

        $semua_kegiatan = Kegiatan::orderBy('created_at', 'DESC')->get();
        $default_kegiatan = $semua_kegiatan->first(function ($kg) {
            return PertandinganPeserta::where('kegiatan_id', $kg->id)->exists();
        }) ?? $semua_kegiatan->first();

        $active_kegiatan_id = $kegiatan_id ?: ($request->kegiatan_id ?: optional($default_kegiatan)->id);
        $kegiatan_aktif = Kegiatan::findOrFail($active_kegiatan_id);

        $rekap = $this->getRekapitulasiData($active_kegiatan_id);

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $now = \Carbon\Carbon::now('Asia/Makassar');
        $timestamp_cetak = (int)$now->format('d') . ' ' . $bulanIndo[(int)$now->format('m')] . ' ' . $now->format('Y, H:i') . ' WITA';
        $tanggal_penetapan = (int)$now->format('d') . ' ' . $bulanIndo[(int)$now->format('m')] . ' ' . $now->format('Y');

        $kota_penetapan = 'Samarinda';
        if (!empty($kegiatan_aktif->nama) && stripos($kegiatan_aktif->nama, 'Paser') !== false) {
            $kota_penetapan = 'Paser';
        }

        $pdf = Pdf::loadView('admin.pertandingan.pdf_laporan_kegiatan', array_merge([
            'kegiatan_aktif'    => $kegiatan_aktif,
            'timestamp_cetak'   => $timestamp_cetak,
            'tanggal_penetapan' => $tanggal_penetapan,
            'kota_penetapan'    => $kota_penetapan,
        ], $rekap))->setPaper('a4', 'portrait');

        $safeTitle = preg_replace('/[^A-Za-z0-9_\-]/', '_', $kegiatan_aktif->nama ?? 'Laporan_Hasil_Pertandingan');
        return $pdf->stream('Buku_Laporan_Hasil_Pertandingan_' . $safeTitle . '.pdf');
    }

    /**
     * Helper untuk menyelesaikan data juara kategori:
     * Mengambil dari data_juara, atau fallback langsung dari peserta di tabel pertandingan_pesertas
     */
    public static function resolveJuaraKategori($lk)
    {
        $juara = $lk->data_juara ?? [];
        $hasJuaraSet = !empty($juara['emas']['kabupaten_id']) || !empty($juara['perak']['kabupaten_id']) || !empty($juara['perunggu']['kabupaten_id']);

        if ($hasJuaraSet) {
            return $juara;
        }

        // Ambil data peserta dari relasi atau query
        $pesertaList = $lk->relationLoaded('list_kategori') ? $lk->list_kategori : $lk->list_kategori()->with('Peserta.kabupaten')->get();
        $pesertaMedali = $pesertaList->filter(function ($p) {
            return !empty($p->medali) && trim($p->medali) !== '';
        });

        if ($pesertaMedali->isEmpty()) {
            return $juara;
        }

        $fallbackJuara = [];

        // Emas
        $emasGroup = $pesertaMedali->filter(fn($p) => strtolower(trim($p->medali)) === 'emas');
        if ($emasGroup->isNotEmpty()) {
            $firstEmas = $emasGroup->first();
            $fallbackJuara['emas'] = [
                'kabupaten_id'   => optional($firstEmas->Peserta)->kabupaten_id,
                'kabupaten_nama' => optional(optional($firstEmas->Peserta)->kabupaten)->name,
                'peserta_nama'   => $emasGroup->map(fn($p) => optional($p->Peserta)->nama)->filter()->unique()->implode(', '),
            ];
        }

        // Perak
        $perakGroup = $pesertaMedali->filter(fn($p) => strtolower(trim($p->medali)) === 'perak');
        if ($perakGroup->isNotEmpty()) {
            $firstPerak = $perakGroup->first();
            $fallbackJuara['perak'] = [
                'kabupaten_id'   => optional($firstPerak->Peserta)->kabupaten_id,
                'kabupaten_nama' => optional(optional($firstPerak->Peserta)->kabupaten)->name,
                'peserta_nama'   => $perakGroup->map(fn($p) => optional($p->Peserta)->nama)->filter()->unique()->implode(', '),
            ];
        }

        // Perunggu & Perunggu 2 (untuk Juara 3 Bersama)
        $perungguGroup = $pesertaMedali->filter(fn($p) => strtolower(trim($p->medali)) === 'perunggu');
        if ($perungguGroup->isNotEmpty()) {
            $perKab = $perungguGroup->groupBy(fn($p) => optional($p->Peserta)->kabupaten_id);
            $kabKeys = $perKab->keys()->all();

            if (isset($kabKeys[0])) {
                $g1 = $perKab[$kabKeys[0]];
                $fallbackJuara['perunggu'] = [
                    'kabupaten_id'   => $kabKeys[0],
                    'kabupaten_nama' => optional(optional($g1->first()->Peserta)->kabupaten)->name,
                    'peserta_nama'   => $g1->map(fn($p) => optional($p->Peserta)->nama)->filter()->unique()->implode(', '),
                ];
            }

            if (isset($kabKeys[1])) {
                $g2 = $perKab[$kabKeys[1]];
                $fallbackJuara['perunggu_2'] = [
                    'kabupaten_id'   => $kabKeys[1],
                    'kabupaten_nama' => optional(optional($g2->first()->Peserta)->kabupaten)->name,
                    'peserta_nama'   => $g2->map(fn($p) => optional($p->Peserta)->nama)->filter()->unique()->implode(', '),
                ];
            }
        }

        if (!empty($fallbackJuara)) {
            $juara = array_merge($juara, $fallbackJuara);
            try {
                $lk->data_juara = $juara;
                $lk->save();
            } catch (\Throwable $e) {}
        }

        return $juara;
    }

    /**
     * Helper untuk mengambil seluruh data rekapitulasi kegiatan (Klasemen & Daftar Juara Embu/Randori)
     */
    public static function getRekapitulasiData($active_kegiatan_id)
    {
        $klasemen = [];
        $daftar_juara = [];
        $total_kategori = 0;
        $total_medali_emas = 0;
        $total_medali_perak = 0;
        $total_medali_perunggu = 0;

        if (!$active_kegiatan_id) {
            return [
                'klasemen'              => [],
                'daftar_juara'          => [],
                'daftar_juara_embu'     => [],
                'daftar_juara_randori'  => [],
                'total_kategori'        => 0,
                'total_medali_emas'     => 0,
                'total_medali_perak'    => 0,
                'total_medali_perunggu' => 0,
                'total_seluruh_medali'  => 0,
            ];
        }

        // Ambil semua list kategori pada kegiatan ini
        $listKategori = ListKategori::with(['kategori', 'pertandingan', 'list_kategori.Peserta.kabupaten'])
            ->where('kegiatan_id', $active_kegiatan_id)
            ->get();

        $total_kategori = $listKategori->count();

        // Ambil semua kabupaten / kontingen yang terlibat
        $kabupatenIds = PertandinganPeserta::where('kegiatan_id', $active_kegiatan_id)
            ->orWhereIn('list_kategori_id', $listKategori->pluck('id'))
            ->with('Peserta')
            ->get()
            ->pluck('Peserta.kabupaten_id')
            ->filter()
            ->unique();

        // Inisialisasi klasemen per kabupaten
        $semuaKabupaten = Kabupaten::whereIn('id', $kabupatenIds)->get();
        foreach ($semuaKabupaten as $kab) {
            $klasemen[$kab->id] = [
                'id' => $kab->id,
                'nama' => $kab->name,
                'emas' => 0,
                'perak' => 0,
                'perunggu' => 0,
                'total' => 0,
            ];
        }

        // Proses setiap kategori untuk mendapatkan juara dan klasemen medali
        foreach ($listKategori as $lk) {
            $isRandori = stripos($lk->pertandingan->nama ?? '', 'randori') !== false || stripos($lk->kategori->nama ?? '', 'randori') !== false;
            $jenis = $isRandori ? 'Randori' : 'Embu';

            $juara = self::resolveJuaraKategori($lk);

            $emas_kab_id = $juara['emas']['kabupaten_id'] ?? null;
            $emas_kab_nama = $juara['emas']['kabupaten_nama'] ?? null;
            $emas_peserta = $juara['emas']['peserta_nama'] ?? null;

            $perak_kab_id = $juara['perak']['kabupaten_id'] ?? null;
            $perak_kab_nama = $juara['perak']['kabupaten_nama'] ?? null;
            $perak_peserta = $juara['perak']['peserta_nama'] ?? null;

            $perunggu_kab_id = $juara['perunggu']['kabupaten_id'] ?? null;
            $perunggu_kab_nama = $juara['perunggu']['kabupaten_nama'] ?? null;
            $perunggu_peserta = $juara['perunggu']['peserta_nama'] ?? null;

            $perunggu2_kab_id = $juara['perunggu_2']['kabupaten_id'] ?? null;
            $perunggu2_kab_nama = $juara['perunggu_2']['kabupaten_nama'] ?? null;
            $perunggu2_peserta = $juara['perunggu_2']['peserta_nama'] ?? null;

            // Hitung medali ke klasemen
            if ($emas_kab_id) {
                if (!isset($klasemen[$emas_kab_id])) {
                    $klasemen[$emas_kab_id] = [
                        'id' => $emas_kab_id,
                        'nama' => $emas_kab_nama ?: optional(Kabupaten::find($emas_kab_id))->name,
                        'emas' => 0, 'perak' => 0, 'perunggu' => 0, 'total' => 0,
                    ];
                }
                $klasemen[$emas_kab_id]['emas'] += 1;
                $total_medali_emas += 1;
            }

            if ($perak_kab_id) {
                if (!isset($klasemen[$perak_kab_id])) {
                    $klasemen[$perak_kab_id] = [
                        'id' => $perak_kab_id,
                        'nama' => $perak_kab_nama ?: optional(Kabupaten::find($perak_kab_id))->name,
                        'emas' => 0, 'perak' => 0, 'perunggu' => 0, 'total' => 0,
                    ];
                }
                $klasemen[$perak_kab_id]['perak'] += 1;
                $total_medali_perak += 1;
            }

            if ($perunggu_kab_id) {
                if (!isset($klasemen[$perunggu_kab_id])) {
                    $klasemen[$perunggu_kab_id] = [
                        'id' => $perunggu_kab_id,
                        'nama' => $perunggu_kab_nama ?: optional(Kabupaten::find($perunggu_kab_id))->name,
                        'emas' => 0, 'perak' => 0, 'perunggu' => 0, 'total' => 0,
                    ];
                }
                $klasemen[$perunggu_kab_id]['perunggu'] += 1;
                $total_medali_perunggu += 1;
            }

            if ($perunggu2_kab_id) {
                if (!isset($klasemen[$perunggu2_kab_id])) {
                    $klasemen[$perunggu2_kab_id] = [
                        'id' => $perunggu2_kab_id,
                        'nama' => $perunggu2_kab_nama ?: optional(Kabupaten::find($perunggu2_kab_id))->name,
                        'emas' => 0, 'perak' => 0, 'perunggu' => 0, 'total' => 0,
                    ];
                }
                $klasemen[$perunggu2_kab_id]['perunggu'] += 1;
                $total_medali_perunggu += 1;
            }

            $daftar_juara[] = [
                'list_kategori_id' => $lk->id,
                'pertandingan_id' => $lk->pertandingan_id,
                'kategori_nama' => $lk->kategori->nama ?? '-',
                'pertandingan_nama' => $lk->pertandingan->nama ?? '-',
                'kelas' => $lk->pertandingan->kelas ?? '-',
                'jenis' => $jenis,
                'emas' => [
                    'kabupaten' => $emas_kab_nama,
                    'peserta' => $emas_peserta,
                ],
                'perak' => [
                    'kabupaten' => $perak_kab_nama,
                    'peserta' => $perak_peserta,
                ],
                'perunggu' => [
                    'kabupaten' => $perunggu_kab_nama,
                    'peserta' => $perunggu_peserta,
                ],
                'perunggu_2' => [
                    'kabupaten' => $perunggu2_kab_nama,
                    'peserta' => $perunggu2_peserta,
                ],
                'is_set' => !empty($emas_kab_id) || !empty($perak_kab_id) || !empty($perunggu_kab_id),
            ];
        }

        // Hitung total medali per kabupaten & urutkan klasemen
        foreach ($klasemen as $kId => $v) {
            $klasemen[$kId]['total'] = $v['emas'] + $v['perak'] + $v['perunggu'];
        }

        // Urutkan: Emas DESC, Perak DESC, Perunggu DESC, Total DESC, Nama ASC
        usort($klasemen, function ($a, $b) {
            if ($a['emas'] !== $b['emas']) {
                return $b['emas'] <=> $a['emas'];
            }
            if ($a['perak'] !== $b['perak']) {
                return $b['perak'] <=> $a['perak'];
            }
            if ($a['perunggu'] !== $b['perunggu']) {
                return $b['perunggu'] <=> $a['perunggu'];
            }
            if ($a['total'] !== $b['total']) {
                return $b['total'] <=> $a['total'];
            }
            return strcmp($a['nama'], $b['nama']);
        });

        // Pecah daftar juara menjadi 2 kategori: Embu dan Randori
        $daftar_juara_embu = array_values(array_filter($daftar_juara, fn($item) => $item['jenis'] === 'Embu'));
        $daftar_juara_randori = array_values(array_filter($daftar_juara, fn($item) => $item['jenis'] === 'Randori'));

        return [
            'klasemen'              => $klasemen,
            'daftar_juara'          => $daftar_juara,
            'daftar_juara_embu'     => $daftar_juara_embu,
            'daftar_juara_randori'  => $daftar_juara_randori,
            'total_kategori'        => $total_kategori,
            'total_medali_emas'     => $total_medali_emas,
            'total_medali_perak'    => $total_medali_perak,
            'total_medali_perunggu' => $total_medali_perunggu,
            'total_seluruh_medali'  => $total_medali_emas + $total_medali_perak + $total_medali_perunggu,
        ];
    }

    /**
     * TUGAS 2: Simpan Isian PDF Scoresheet Pertandingan (Embu & Randori)
     */
    public function simpanIsianPdf(Request $request, string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 'wasit', 1, '1', 2, '2'])) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $listKategori = ListKategori::findOrFail($id);
        $pertandingan = Pertandingan::find($listKategori->pertandingan_id);

        $payload = $request->except(['_token', 'kegiatan_id']);

        // Simpan ke ListKategori
        $listKategori->data_scoresheet = $payload;
        $listKategori->save();

        // Simpan juga ke Pertandingan untuk kompatibilitas data
        if ($pertandingan) {
            $pertandingan->data_scoresheet = $payload;
            $pertandingan->save();
        }

        $kegiatan_id = $request->kegiatan_id ?? $listKategori->kegiatan_id;

        return redirect('pertandingan/' . $id . '?kegiatan_id=' . $kegiatan_id)
            ->with('success', 'Data isian scoresheet pertandingan berhasil disimpan!');
    }

    /**
     * TUGAS 2: Cetak PDF Berdasarkan Jenis Kategori (Embu vs Randori)
     */
    public function cetakPdf(Request $request, string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 'wasit', 1, '1', 2, '2'])) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $listKategori = ListKategori::with(['kegiatan', 'kategori', 'pertandingan'])->findOrFail($id);

        $isRandori = stripos($listKategori->pertandingan->nama ?? '', 'randori') !== false ||
                     stripos($listKategori->kategori->nama ?? '', 'randori') !== false;

        $scoresheet = $listKategori->data_scoresheet ?? ($listKategori->pertandingan->data_scoresheet ?? []);
        $juara = self::resolveJuaraKategori($listKategori);

        // Ambil data peserta yang bertanding di kategori ini
        $peserta_list = PertandinganPeserta::with('Peserta.kabupaten')
            ->where('list_kategori_id', $id)
            ->get();

        $kabupatenId = $request->query('kabupaten_id');
        $filteredKabupaten = null;
        if ($kabupatenId) {
            $filteredKabupaten = Kabupaten::find($kabupatenId);
            $filterName = $filteredKabupaten?->name;

            // Filter peserta_list
            $peserta_list = $peserta_list->filter(function ($item) use ($kabupatenId, $filterName) {
                if ($item->Peserta?->kabupaten_id == $kabupatenId) {
                    return true;
                }
                if ($filterName && !empty($item->Peserta?->kontigen) && strcasecmp(trim($item->Peserta->kontigen), trim($filterName)) === 0) {
                    return true;
                }
                return false;
            });

            // Filter data scoresheet peserta jika ada
            if (!empty($scoresheet['peserta']) && is_array($scoresheet['peserta'])) {
                $scoresheet['peserta'] = array_values(array_filter($scoresheet['peserta'], function ($p) use ($filterName) {
                    if (!$filterName) return true;
                    return stripos($p['kontingen'] ?? '', $filterName) !== false;
                }));
            }

            // Filter data scoresheet partai (untuk Randori) jika ada
            if (!empty($scoresheet['partai']) && is_array($scoresheet['partai'])) {
                $scoresheet['partai'] = array_values(array_filter($scoresheet['partai'], function ($p) use ($filterName) {
                    if (!$filterName) return true;
                    return (stripos($p['merah_kontingen'] ?? '', $filterName) !== false) ||
                           (stripos($p['putih_kontingen'] ?? '', $filterName) !== false);
                }));
            }
        }

        $namaPertandingan = $listKategori->pertandingan->nama ?? 'Pertandingan';
        $safeFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $namaPertandingan);
        if ($filteredKabupaten) {
            $safeFileName .= '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $filteredKabupaten->name);
        }

        $dataBabak1 = [];
        $dataBabak2 = [];
        $dataNilaiAkhir = [];

        if (!$isRandori) {
            // Ambil seluruh peserta kategori ini lengkap dengan relasi
            $allPp = PertandinganPeserta::with('Peserta.kabupaten')
                ->where('list_kategori_id', $id)
                ->get();

            $basePeserta = $allPp->where(fn($p) => $p->babak == 1 || is_null($p->babak));
            if ($basePeserta->isEmpty()) {
                $basePeserta = $allPp->unique('peserta_id');
            }

            $b1Records = $allPp->where(fn($p) => $p->babak == 1 || is_null($p->babak))->keyBy('peserta_id');
            $b2Records = $allPp->where(fn($p) => $p->babak == 2)->keyBy('peserta_id');

            // Data scoresheet JSON per_babak jika ada
            $jsonB1 = $scoresheet['per_babak'][1]['peserta'] ?? ($scoresheet['peserta'] ?? []);
            $jsonB2 = $scoresheet['per_babak'][2]['peserta'] ?? [];

            $grouped = $basePeserta->groupBy(function ($item) {
                if ($item->Peserta?->kabupaten_id) {
                    return 'kab_' . $item->Peserta->kabupaten_id;
                }
                if (!empty($item->Peserta?->kontigen)) {
                    return 'kontigen_' . trim(strtolower($item->Peserta->kontigen));
                }
                return 'peserta_' . $item->id;
            });

            foreach ($grouped as $kabId => $items) {
                $first = $items->first();
                $kontingenName = optional($first->Peserta?->kabupaten)->name ?? ($first->Peserta?->kontigen ?? 'Tanpa Kontingen');
                $namaKenshi = $items->pluck('Peserta.nama')->filter()->implode(', ') ?: ($first->Peserta?->nama ?? '-');

                // Filter kabupaten jika ada parameter kabupaten_id
                if ($kabupatenId) {
                    $matchesKab = ($first->Peserta?->kabupaten_id == $kabupatenId) ||
                                  ($filterName && !empty($first->Peserta?->kontigen) && strcasecmp(trim($first->Peserta->kontigen), trim($filterName)) === 0);
                    if (!$matchesKab) {
                        continue;
                    }
                }

                // Babak 1
                $b1Items = $items->map(fn($it) => $b1Records->get($it->peserta_id) ?? $it)->filter();
                $b1_n1 = $b1Items->pluck('nilai_1')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b1_n2 = $b1Items->pluck('nilai_2')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b1_n3 = $b1Items->pluck('nilai_3')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b1_n4 = $b1Items->pluck('nilai_4')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b1_n5 = $b1Items->pluck('nilai_5')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b1_minus = $b1Items->pluck('minus')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b1_total = $b1Items->pluck('total_nilai')->filter(fn($v) => !is_null($v) && $v !== '')->first()
                    ?? $b1Items->pluck('total_babak_1')->filter(fn($v) => !is_null($v) && $v !== '')->first();

                if ($b1_total === null && !empty($jsonB1)) {
                    $foundJ = collect($jsonB1)->first(fn($p) => strcasecmp(trim($p['kontingen'] ?? ''), trim($kontingenName)) === 0);
                    if ($foundJ) {
                        $b1_n1 = $foundJ['juri_1'] ?? $b1_n1;
                        $b1_n2 = $foundJ['juri_2'] ?? $b1_n2;
                        $b1_n3 = $foundJ['juri_3'] ?? $b1_n3;
                        $b1_n4 = $foundJ['juri_4'] ?? $b1_n4;
                        $b1_n5 = $foundJ['juri_5'] ?? $b1_n5;
                        $b1_minus = $foundJ['hukuman'] ?? $b1_minus;
                        $b1_total = $foundJ['total'] ?? $b1_total;
                    }
                }

                // Babak 2
                $b2Items = $items->map(fn($it) => $b2Records->get($it->peserta_id))->filter();
                $b2_n1 = $b2Items->pluck('nilai_1')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b2_n2 = $b2Items->pluck('nilai_2')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b2_n3 = $b2Items->pluck('nilai_3')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b2_n4 = $b2Items->pluck('nilai_4')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b2_n5 = $b2Items->pluck('nilai_5')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b2_minus = $b2Items->pluck('minus')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $b2_total = $b2Items->pluck('total_nilai')->filter(fn($v) => !is_null($v) && $v !== '')->first()
                    ?? $b2Items->pluck('total_babak_2')->filter(fn($v) => !is_null($v) && $v !== '')->first();

                if ($b2_total === null && !empty($jsonB2)) {
                    $foundJ2 = collect($jsonB2)->first(fn($p) => strcasecmp(trim($p['kontingen'] ?? ''), trim($kontingenName)) === 0);
                    if ($foundJ2) {
                        $b2_n1 = $foundJ2['juri_1'] ?? $b2_n1;
                        $b2_n2 = $foundJ2['juri_2'] ?? $b2_n2;
                        $b2_n3 = $foundJ2['juri_3'] ?? $b2_n3;
                        $b2_n4 = $foundJ2['juri_4'] ?? $b2_n4;
                        $b2_n5 = $foundJ2['juri_5'] ?? $b2_n5;
                        $b2_minus = $foundJ2['hukuman'] ?? $b2_minus;
                        $b2_total = $foundJ2['total'] ?? $b2_total;
                    }
                }

                // Nilai Akhir
                $nilaiAkhir = $b1Items->pluck('nilai_akhir')->filter(fn($v) => !is_null($v) && $v !== '')->first()
                    ?? $b2Items->pluck('nilai_akhir')->filter(fn($v) => !is_null($v) && $v !== '')->first();

                if ($nilaiAkhir === null) {
                    if ($b1_total !== null && $b2_total !== null) {
                        $nilaiAkhir = round(((float)$b1_total + (float)$b2_total) / 2, 2);
                    } elseif ($b1_total !== null) {
                        $nilaiAkhir = (float)$b1_total;
                    } elseif ($b2_total !== null) {
                        $nilaiAkhir = (float)$b2_total;
                    }
                }

                $dataBabak1[] = [
                    'kontingen' => $kontingenName,
                    'nama'      => $namaKenshi,
                    'juri_1'    => $b1_n1,
                    'juri_2'    => $b1_n2,
                    'juri_3'    => $b1_n3,
                    'juri_4'    => $b1_n4,
                    'juri_5'    => $b1_n5,
                    'hukuman'   => $b1_minus,
                    'total'     => $b1_total,
                    'urutan'    => $first->urutan ?? 0,
                ];

                $dataBabak2[] = [
                    'kontingen' => $kontingenName,
                    'nama'      => $namaKenshi,
                    'juri_1'    => $b2_n1,
                    'juri_2'    => $b2_n2,
                    'juri_3'    => $b2_n3,
                    'juri_4'    => $b2_n4,
                    'juri_5'    => $b2_n5,
                    'hukuman'   => $b2_minus,
                    'total'     => $b2_total,
                    'urutan'    => $first->urutan ?? 0,
                ];

                $dataNilaiAkhir[] = [
                    'kontingen'     => $kontingenName,
                    'nama'          => $namaKenshi,
                    'nilai_babak_1' => $b1_total,
                    'nilai_babak_2' => $b2_total,
                    'nilai_akhir'   => $nilaiAkhir,
                    'medali'        => $b1Items->pluck('medali')->filter()->first() ?? $b2Items->pluck('medali')->filter()->first(),
                    'urutan'        => $first->urutan ?? 0,
                ];
            }

            // Urutkan dataNilaiAkhir berdasarkan nilai_akhir DESC
            usort($dataNilaiAkhir, function ($a, $b) {
                $naA = $a['nilai_akhir'] ?? -1;
                $naB = $b['nilai_akhir'] ?? -1;
                if ($naA != $naB) {
                    return $naB <=> $naA;
                }
                $b2A = $a['nilai_babak_2'] ?? -1;
                $b2B = $b['nilai_babak_2'] ?? -1;
                if ($b2A != $b2B) {
                    return $b2B <=> $b2A;
                }
                $b1A = $a['nilai_babak_1'] ?? -1;
                $b1B = $b['nilai_babak_1'] ?? -1;
                if ($b1A != $b1B) {
                    return $b1B <=> $b1A;
                }
                return ($a['urutan'] ?? 0) <=> ($b['urutan'] ?? 0);
            });

            // Beri label Juara pada dataNilaiAkhir
            $rank = 1;
            foreach ($dataNilaiAkhir as &$row) {
                if (($row['nilai_akhir'] ?? 0) > 0) {
                    if ($rank === 1) {
                        $row['juara'] = 'Juara 1';
                    } elseif ($rank === 2) {
                        $row['juara'] = 'Juara 2';
                    } elseif ($rank === 3) {
                        $row['juara'] = 'Juara 3';
                    } else {
                        $row['juara'] = 'Peringkat ' . $rank;
                    }
                    $rank++;
                } else {
                    $row['juara'] = '-';
                }
            }
            unset($row);

            // Sinkronkan peraih medali $juara dari dataNilaiAkhir jika belum diset manual
            if (empty($juara['emas']['kabupaten_nama']) && isset($dataNilaiAkhir[0]) && ($dataNilaiAkhir[0]['nilai_akhir'] ?? 0) > 0) {
                $juara['emas'] = [
                    'kabupaten_nama' => $dataNilaiAkhir[0]['kontingen'],
                    'peserta_nama'   => $dataNilaiAkhir[0]['nama'],
                ];
            }
            if (empty($juara['perak']['kabupaten_nama']) && isset($dataNilaiAkhir[1]) && ($dataNilaiAkhir[1]['nilai_akhir'] ?? 0) > 0) {
                $juara['perak'] = [
                    'kabupaten_nama' => $dataNilaiAkhir[1]['kontingen'],
                    'peserta_nama'   => $dataNilaiAkhir[1]['nama'],
                ];
            }
            if (empty($juara['perunggu']['kabupaten_nama']) && isset($dataNilaiAkhir[2]) && ($dataNilaiAkhir[2]['nilai_akhir'] ?? 0) > 0) {
                $juara['perunggu'] = [
                    'kabupaten_nama' => $dataNilaiAkhir[2]['kontingen'],
                    'peserta_nama'   => $dataNilaiAkhir[2]['nama'],
                ];
            }
        }

        if ($isRandori && !empty($scoresheet['partai']) && empty($scoresheet['peserta'])) {
            $pdf = Pdf::loadView('pertandingan.pdf_randori', compact('listKategori', 'scoresheet', 'juara', 'peserta_list', 'filteredKabupaten'))
                ->setPaper('a4', 'landscape');
            return $pdf->stream('Randori_' . $safeFileName . '.pdf');
        } else {
            $pdf = Pdf::loadView('pertandingan.pdf_embu', compact('listKategori', 'scoresheet', 'juara', 'peserta_list', 'isRandori', 'filteredKabupaten', 'dataBabak1', 'dataBabak2', 'dataNilaiAkhir'))
                ->setPaper('a4', 'portrait');
            return $pdf->stream(($isRandori ? 'Randori_' : 'Embu_') . $safeFileName . '.pdf');
        }
    }

    /**
     * TUGAS 3: Set Peraih Medali Berdasarkan Kategori (Embu & Randori)
     */
    public function setMedali(Request $request, string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 'wasit', 1, '1', 2, '2'])) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $listKategori = ListKategori::with(['list_kategori.Peserta.kabupaten'])->findOrFail($id);

        $emas_kab_id = $request->emas_kabupaten_id;
        $perak_kab_id = $request->perak_kabupaten_id;
        $perunggu_kab_id = $request->perunggu_kabupaten_id;

        $dataJuara = [
            'emas' => [
                'kabupaten_id' => $emas_kab_id,
                'kabupaten_nama' => $emas_kab_id ? optional(Kabupaten::find($emas_kab_id))->name : null,
                'peserta_nama' => $this->getPesertaNamesByKabupaten($listKategori, $emas_kab_id),
            ],
            'perak' => [
                'kabupaten_id' => $perak_kab_id,
                'kabupaten_nama' => $perak_kab_id ? optional(Kabupaten::find($perak_kab_id))->name : null,
                'peserta_nama' => $this->getPesertaNamesByKabupaten($listKategori, $perak_kab_id),
            ],
            'perunggu' => [
                'kabupaten_id' => $perunggu_kab_id,
                'kabupaten_nama' => $perunggu_kab_id ? optional(Kabupaten::find($perunggu_kab_id))->name : null,
                'peserta_nama' => $this->getPesertaNamesByKabupaten($listKategori, $perunggu_kab_id),
            ],
        ];

        $listKategori->data_juara = $dataJuara;
        $listKategori->save();

        // Update medali di tabel pertandingan_pesertas
        PertandinganPeserta::where('list_kategori_id', $id)->update(['medali' => null]);

        if ($emas_kab_id) {
            PertandinganPeserta::where('list_kategori_id', $id)
                ->whereHas('Peserta', function ($q) use ($emas_kab_id) {
                    $q->where('kabupaten_id', $emas_kab_id);
                })->update(['medali' => 'emas']);
        }

        if ($perak_kab_id) {
            PertandinganPeserta::where('list_kategori_id', $id)
                ->whereHas('Peserta', function ($q) use ($perak_kab_id) {
                    $q->where('kabupaten_id', $perak_kab_id);
                })->update(['medali' => 'perak']);
        }

        if ($perunggu_kab_id) {
            PertandinganPeserta::where('list_kategori_id', $id)
                ->whereHas('Peserta', function ($q) use ($perunggu_kab_id) {
                    $q->where('kabupaten_id', $perunggu_kab_id);
                })->update(['medali' => 'perunggu']);
        }

        $kegiatan_id = $request->kegiatan_id ?? $listKategori->kegiatan_id;

        return redirect('pertandingan/' . $id . '?kegiatan_id=' . $kegiatan_id)
            ->with('success', 'Peraih medali berhasil disimpan dan tercatat di Laporan Kegiatan!');
    }

    /**
     * Helper untuk mengambil nama peserta berdasarkan kabupaten pada list_kategori
     */
    private function getPesertaNamesByKabupaten($listKategori, $kabupaten_id)
    {
        if (!$kabupaten_id) {
            return '';
        }

        $pesertas = PertandinganPeserta::with('Peserta')
            ->where('list_kategori_id', $listKategori->id)
            ->whereHas('Peserta', function ($q) use ($kabupaten_id) {
                $q->where('kabupaten_id', $kabupaten_id);
            })
            ->get();

        return $pesertas->pluck('Peserta.nama')->filter()->implode(', ');
    }

    /**
     * Menampilkan halaman Papan Skor Publik berdasarkan Kegiatan ID (Tanpa Login)
     */
    public function publicPapanSkor($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Ambil semua list_kategori dalam kegiatan ini beserta relasinya dari database
        $listKategoris = ListKategori::with([
            'kategori',
            'pertandingan',
            'list_kategori.Peserta.kabupaten'
        ])
        ->where('kegiatan_id', $id)
        ->get();

        $structuredData = [];
        $groupedByKategori = $listKategoris->groupBy('kategori_id');

        foreach ($groupedByKategori as $katId => $lks) {
            $firstLk = $lks->first();
            $kategori = $firstLk->kategori;
            if (!$kategori) continue;

            $tandingList = [];

            foreach ($lks as $lk) {
                $tanding = $lk->pertandingan;
                if (!$tanding) continue;

                $allPeserta = PertandinganPeserta::with(['Peserta.kabupaten'])
                    ->where(function ($q) use ($lk, $tanding) {
                        $q->where('list_kategori_id', $lk->id)
                          ->orWhere('pertandingan_id', $lk->id);
                        if ($tanding && $tanding->id) {
                            $q->orWhere('pertandingan_id', $tanding->id);
                        }
                    })
                    ->orderBy('urutan')
                    ->get();

                $groupedKabupaten = $allPeserta->groupBy(function ($item) {
                    if ($item->Peserta?->kabupaten_id) {
                        return 'kab_' . $item->Peserta->kabupaten_id;
                    }
                    if (!empty($item->Peserta?->kontigen)) {
                        return 'kontigen_' . trim(strtolower($item->Peserta->kontigen));
                    }
                    return 'peserta_' . $item->id;
                });

                $pesertaGroups = [];
                $groupIndex = 0;

                foreach ($groupedKabupaten as $kabIdKey => $items) {
                    $first = $items->first();
                    $names = $items->pluck('Peserta.nama')->filter()->implode(' / ');
                    $kName = optional($first->Peserta?->kabupaten)->name ?? ($first->Peserta?->kontigen ?? 'Tanpa Kontingen');
                    $urutan = $first->urutan ?? ($groupIndex + 1);
                    $kabId = $first->Peserta?->kabupaten_id;
                    $ppIds = $items->pluck('id')->toArray();

                    $b1Items = $items->filter(fn($it) => $it->babak == 1 || is_null($it->babak));
                    $b2Items = $items->filter(fn($it) => $it->babak == 2);

                    // Data Babak 1
                    $b1_waktu = $b1Items->pluck('waktu')->filter(fn($v) => !empty($v) && $v !== '00:00')->first() ?? '';
                    $b1_n1 = $b1Items->pluck('nilai_1')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $b1_n2 = $b1Items->pluck('nilai_2')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $b1_n3 = $b1Items->pluck('nilai_3')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $b1_n4 = $b1Items->pluck('nilai_4')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $b1_n5 = $b1Items->pluck('nilai_5')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $b1_minus = $b1Items->pluck('minus')->filter(fn($v) => !is_null($v) && $v !== '')->first() ?? 0;
                    $b1_total = $b1Items->pluck('total_nilai')->filter(fn($v) => !is_null($v) && $v !== '')->first();

                    // Data Babak 2
                    $b2_waktu = $b2Items->pluck('waktu')->filter(fn($v) => !empty($v) && $v !== '00:00')->first() ?? '';
                    $b2_n1 = $b2Items->pluck('nilai_1')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $b2_n2 = $b2Items->pluck('nilai_2')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $b2_n3 = $b2Items->pluck('nilai_3')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $b2_n4 = $b2Items->pluck('nilai_4')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $b2_n5 = $b2Items->pluck('nilai_5')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $b2_minus = $b2Items->pluck('minus')->filter(fn($v) => !is_null($v) && $v !== '')->first() ?? 0;
                    $b2_total = $b2Items->pluck('total_nilai')->filter(fn($v) => !is_null($v) && $v !== '')->first();

                    // Nilai Akumulasi
                    $totB1 = $items->pluck('total_babak_1')->filter(fn($v) => !is_null($v))->first() ?? $b1_total;
                    $totB2 = $items->pluck('total_babak_2')->filter(fn($v) => !is_null($v))->first() ?? $b2_total;
                    $nilaiAkhir = $items->pluck('nilai_akhir')->filter(fn($v) => !is_null($v))->first();
                    if ($nilaiAkhir === null && $totB1 !== null && $totB2 !== null) {
                        $nilaiAkhir = round(((float)$totB1 + (float)$totB2) / 2, 2);
                    } elseif ($nilaiAkhir === null && $totB1 !== null) {
                        $nilaiAkhir = (float)$totB1;
                    }

                    $pesertaGroups[] = [
                        'row_index'     => $groupIndex,
                        'id'            => $first->id,
                        'pp_ids'        => $ppIds,
                        'urutan'        => is_numeric($urutan) ? (int)$urutan : ($groupIndex + 1),
                        'nama_gabungan' => $names ?: ('Peserta #' . ($groupIndex + 1)),
                        'kontingen'     => $kName,
                        'kabupaten_id'  => $kabId,
                        'babak_1'       => [
                            'waktu'       => $b1_waktu,
                            'scores'      => [floatval($b1_n1 ?? 0), floatval($b1_n2 ?? 0), floatval($b1_n3 ?? 0), floatval($b1_n4 ?? 0), floatval($b1_n5 ?? 0)],
                            'nilai_1'     => $b1_n1,
                            'nilai_2'     => $b1_n2,
                            'nilai_3'     => $b1_n3,
                            'nilai_4'     => $b1_n4,
                            'nilai_5'     => $b1_n5,
                            'genten'      => floatval($b1_minus ?? 0),
                            'minus'       => floatval($b1_minus ?? 0),
                            'total_nilai' => $b1_total !== null ? floatval($b1_total) : null,
                        ],
                        'babak_2'       => [
                            'waktu'       => $b2_waktu,
                            'scores'      => [floatval($b2_n1 ?? 0), floatval($b2_n2 ?? 0), floatval($b2_n3 ?? 0), floatval($b2_n4 ?? 0), floatval($b2_n5 ?? 0)],
                            'nilai_1'     => $b2_n1,
                            'nilai_2'     => $b2_n2,
                            'nilai_3'     => $b2_n3,
                            'nilai_4'     => $b2_n4,
                            'nilai_5'     => $b2_n5,
                            'genten'      => floatval($b2_minus ?? 0),
                            'minus'       => floatval($b2_minus ?? 0),
                            'total_nilai' => $b2_total !== null ? floatval($b2_total) : null,
                        ],
                        'total_babak_1' => $totB1 !== null ? floatval($totB1) : null,
                        'total_babak_2' => $totB2 !== null ? floatval($totB2) : null,
                        'nilai_akhir'   => $nilaiAkhir !== null ? floatval($nilaiAkhir) : null,
                        'waktu'         => $b1_waktu ?: $b2_waktu,
                        'scores'        => [floatval($b1_n1 ?? 0), floatval($b1_n2 ?? 0), floatval($b1_n3 ?? 0), floatval($b1_n4 ?? 0), floatval($b1_n5 ?? 0)],
                        'nilai_1'       => $b1_n1,
                        'nilai_2'       => $b1_n2,
                        'nilai_3'       => $b1_n3,
                        'nilai_4'       => $b1_n4,
                        'nilai_5'       => $b1_n5,
                        'genten'        => floatval($b1_minus ?? 0),
                        'minus'         => floatval($b1_minus ?? 0),
                        'total_nilai'   => $b1_total !== null ? floatval($b1_total) : null,
                    ];
                    $groupIndex++;
                }

                $scoresheetData = $lk->data_scoresheet ?? ($tanding->data_scoresheet ?? []);

                $isRandori = ($tanding && $tanding->bagan == 1) ||
                             stripos($tanding->nama ?? '', 'randori') !== false ||
                             stripos($kategori->nama ?? '', 'randori') !== false;

                // FASE 5: Ambil seluruh data partai duel jika Randori
                $partaiDuel = [];
                if ($isRandori || PesertaTerpilih::where('list_kategori_id', $lk->id)->exists()) {
                    $duelMatches = PesertaTerpilih::with([
                        'PertandinganPesertaPertama.Peserta.kabupaten',
                        'PertandinganPesertaKedua.Peserta.kabupaten',
                        'pemenang.Peserta.kabupaten'
                    ])
                    ->where('list_kategori_id', $lk->id)
                    ->orderBy('babak', 'asc')
                    ->orderBy('nomor_partai', 'asc')
                    ->get();

                    $totP = $allPeserta->count();
                    $sCount = pow(2, ceil(log(max(2, $totP), 2)));
                    $tBabak = (int) log($sCount, 2);

                    $partaiDuel = $duelMatches->map(function ($m) use ($tBabak) {
                        $namaBabak = 'Babak ' . $m->babak;
                        if ($m->babak == $tBabak) $namaBabak = 'Final';
                        elseif ($tBabak >= 2 && $m->babak == $tBabak - 1) $namaBabak = 'Semifinal';
                        elseif ($tBabak >= 3 && $m->babak == $tBabak - 2) $namaBabak = 'Perempatfinal';

                        $kiri = $m->PertandinganPesertaPertama;
                        $kanan = $m->PertandinganPesertaKedua;
                        $isBye = ($kanan === null && $m->keterangan_menang === 'BYE');

                        $kiriNama = optional($kiri?->Peserta)->nama ?? ($m->pertandingan_peserta_kiri_id ? 'Atlet' : 'Menunggu Pemenang B' . ($m->babak - 1));
                        $kananNama = optional($kanan?->Peserta)->nama ?? ($isBye ? 'BYE (Slot Kosong)' : ($m->pertandingan_peserta_kanan_id ? 'Atlet' : 'Menunggu Pemenang B' . ($m->babak - 1)));

                        return [
                            'id'                => $m->id,
                            'babak'             => $m->babak,
                            'nama_babak'        => $namaBabak,
                            'nomor_partai'      => $m->nomor_partai,
                            'label'             => "{$namaBabak} - Partai #{$m->nomor_partai}: {$kiriNama} vs {$kananNama}",
                            'is_bye'            => $isBye,
                            'kiri_id'           => $m->pertandingan_peserta_kiri_id,
                            'kiri_nama'         => $kiriNama,
                            'kiri_kontingen'    => optional(optional($kiri?->Peserta)->kabupaten)->name ?? (optional($kiri?->Peserta)->kontigen ?? '-'),
                            'skor_kiri'         => $m->skor_kiri !== null ? floatval($m->skor_kiri) : 0,
                            'kanan_id'          => $m->pertandingan_peserta_kanan_id,
                            'kanan_nama'        => $kananNama,
                            'kanan_kontingen'   => optional(optional($kanan?->Peserta)->kabupaten)->name ?? (optional($kanan?->Peserta)->kontigen ?? '-'),
                            'skor_kanan'        => $m->skor_kanan !== null ? floatval($m->skor_kanan) : 0,
                            'pemenang_id'       => $m->pemenang_id,
                            'keterangan_menang' => $m->keterangan_menang,
                        ];
                    })->values()->toArray();
                }

                $tandingList[] = [
                    'id'              => $lk->id,
                    'pertandingan_id' => $tanding->id,
                    'nama_tanding'    => $tanding->nama . ($tanding->kelas ? " ({$tanding->kelas})" : ''),
                    'jenis'           => $isRandori ? 'randori' : 'embu',
                    'scoresheet_data' => $scoresheetData,
                    'peserta_groups'  => $pesertaGroups,
                    'partai_duel'     => $partaiDuel,
                ];
            }

            $structuredData[] = [
                'id'            => $kategori->id,
                'nama_kategori' => $kategori->nama,
                'tandings'      => $tandingList,
            ];
        }

        return view('pages.papan_skor_publik', compact('kegiatan', 'structuredData'));
    }

    /**
     * Mengambil data penilaian juri & denda (genten) terbaru langsung dari database
     */
    public function getPublicPapanSkorData($pertandingan_id)
    {
        $listKategori = ListKategori::with(['pertandingan.kategori', 'kategori'])->find($pertandingan_id);
        $pertandingan = null;
        if ($listKategori) {
            $pertandingan = $listKategori->pertandingan ?? Pertandingan::find($listKategori->pertandingan_id);
            $scoresheetData = $listKategori->data_scoresheet ?? ($pertandingan?->data_scoresheet ?? []);
        } else {
            $pertandingan = Pertandingan::with('kategori')->find($pertandingan_id);
            $scoresheetData = $pertandingan->data_scoresheet ?? [];
        }

        $allPeserta = PertandinganPeserta::with(['Peserta.kabupaten'])
            ->where(function ($q) use ($pertandingan_id, $listKategori, $pertandingan) {
                $q->where('list_kategori_id', $pertandingan_id)
                  ->orWhere('pertandingan_id', $pertandingan_id);
                if ($listKategori && $listKategori->pertandingan_id) {
                    $q->orWhere('pertandingan_id', $listKategori->pertandingan_id);
                }
                if ($pertandingan && $pertandingan->id) {
                    $q->orWhere('pertandingan_id', $pertandingan->id);
                }
            })
            ->orderBy('urutan')
            ->get();

        $grouped = $allPeserta->groupBy(function ($item) {
            if ($item->Peserta?->kabupaten_id) {
                return 'kab_' . $item->Peserta->kabupaten_id;
            }
            if (!empty($item->Peserta?->kontigen)) {
                return 'kontigen_' . trim(strtolower($item->Peserta->kontigen));
            }
            return 'peserta_' . $item->id;
        });

        $pesertaGroups = [];
        $groupIndex = 0;

        foreach ($grouped as $kabId => $items) {
            $first = $items->first();
            $pNames = $items->pluck('Peserta.nama')->filter()->implode(', ');
            $kName = optional($first->Peserta?->kabupaten)->name ?? ($first->Peserta?->kontigen ?? 'Tanpa Kontingen');
            $ppIds = $items->pluck('id')->toArray();

            $b1Items = $items->filter(fn($it) => $it->babak == 1 || is_null($it->babak));
            $b2Items = $items->filter(fn($it) => $it->babak == 2);

            // Data Babak 1
            $b1_waktu = $b1Items->pluck('waktu')->filter(fn($v) => !empty($v) && $v !== '00:00')->first() ?? '';
            $b1_n1 = $b1Items->pluck('nilai_1')->filter(fn($v) => !is_null($v) && $v !== '')->first();
            $b1_n2 = $b1Items->pluck('nilai_2')->filter(fn($v) => !is_null($v) && $v !== '')->first();
            $b1_n3 = $b1Items->pluck('nilai_3')->filter(fn($v) => !is_null($v) && $v !== '')->first();
            $b1_n4 = $b1Items->pluck('nilai_4')->filter(fn($v) => !is_null($v) && $v !== '')->first();
            $b1_n5 = $b1Items->pluck('nilai_5')->filter(fn($v) => !is_null($v) && $v !== '')->first();
            $b1_minus = $b1Items->pluck('minus')->filter(fn($v) => !is_null($v) && $v !== '')->first() ?? 0;
            $b1_total = $b1Items->pluck('total_nilai')->filter(fn($v) => !is_null($v) && $v !== '')->first();

            // Data Babak 2
            $b2_waktu = $b2Items->pluck('waktu')->filter(fn($v) => !empty($v) && $v !== '00:00')->first() ?? '';
            $b2_n1 = $b2Items->pluck('nilai_1')->filter(fn($v) => !is_null($v) && $v !== '')->first();
            $b2_n2 = $b2Items->pluck('nilai_2')->filter(fn($v) => !is_null($v) && $v !== '')->first();
            $b2_n3 = $b2Items->pluck('nilai_3')->filter(fn($v) => !is_null($v) && $v !== '')->first();
            $b2_n4 = $b2Items->pluck('nilai_4')->filter(fn($v) => !is_null($v) && $v !== '')->first();
            $b2_n5 = $b2Items->pluck('nilai_5')->filter(fn($v) => !is_null($v) && $v !== '')->first();
            $b2_minus = $b2Items->pluck('minus')->filter(fn($v) => !is_null($v) && $v !== '')->first() ?? 0;
            $b2_total = $b2Items->pluck('total_nilai')->filter(fn($v) => !is_null($v) && $v !== '')->first();

            // Nilai Akumulasi
            $totB1 = $items->pluck('total_babak_1')->filter(fn($v) => !is_null($v))->first() ?? $b1_total;
            $totB2 = $items->pluck('total_babak_2')->filter(fn($v) => !is_null($v))->first() ?? $b2_total;
            $nilaiAkhir = $items->pluck('nilai_akhir')->filter(fn($v) => !is_null($v))->first();
            if ($nilaiAkhir === null && $totB1 !== null && $totB2 !== null) {
                $nilaiAkhir = round(((float)$totB1 + (float)$totB2) / 2, 2);
            } elseif ($nilaiAkhir === null && $totB1 !== null) {
                $nilaiAkhir = (float)$totB1;
            }

            $pesertaGroups[] = [
                'row_index'    => $groupIndex,
                'id'           => $first->id,
                'pp_ids'       => $ppIds,
                'urutan'       => $first->urutan ?? ($groupIndex + 1),
                'nama'         => $pNames ?: ($first->Peserta?->nama ?? '-'),
                'kontingen'    => $kName,
                'kabupaten_id' => $first->Peserta?->kabupaten_id,
                'babak_1'      => [
                    'waktu'       => $b1_waktu,
                    'scores'      => [floatval($b1_n1 ?? 0), floatval($b1_n2 ?? 0), floatval($b1_n3 ?? 0), floatval($b1_n4 ?? 0), floatval($b1_n5 ?? 0)],
                    'nilai_1'     => $b1_n1,
                    'nilai_2'     => $b1_n2,
                    'nilai_3'     => $b1_n3,
                    'nilai_4'     => $b1_n4,
                    'nilai_5'     => $b1_n5,
                    'genten'      => floatval($b1_minus ?? 0),
                    'minus'       => floatval($b1_minus ?? 0),
                    'total_nilai' => $b1_total !== null ? floatval($b1_total) : null,
                ],
                'babak_2'      => [
                    'waktu'       => $b2_waktu,
                    'scores'      => [floatval($b2_n1 ?? 0), floatval($b2_n2 ?? 0), floatval($b2_n3 ?? 0), floatval($b2_n4 ?? 0), floatval($b2_n5 ?? 0)],
                    'nilai_1'     => $b2_n1,
                    'nilai_2'     => $b2_n2,
                    'nilai_3'     => $b2_n3,
                    'nilai_4'     => $b2_n4,
                    'nilai_5'     => $b2_n5,
                    'genten'      => floatval($b2_minus ?? 0),
                    'minus'       => floatval($b2_minus ?? 0),
                    'total_nilai' => $b2_total !== null ? floatval($b2_total) : null,
                ],
                'total_babak_1' => $totB1 !== null ? floatval($totB1) : null,
                'total_babak_2' => $totB2 !== null ? floatval($totB2) : null,
                'nilai_akhir'   => $nilaiAkhir !== null ? floatval($nilaiAkhir) : null,
                'waktu'         => $b1_waktu ?: $b2_waktu,
                'scores'        => [floatval($b1_n1 ?? 0), floatval($b1_n2 ?? 0), floatval($b1_n3 ?? 0), floatval($b1_n4 ?? 0), floatval($b1_n5 ?? 0)],
                'nilai_1'       => $b1_n1,
                'nilai_2'       => $b1_n2,
                'nilai_3'       => $b1_n3,
                'nilai_4'       => $b1_n4,
                'nilai_5'       => $b1_n5,
                'minus'         => floatval($b1_minus ?? 0),
                'genten'        => floatval($b1_minus ?? 0),
                'total_nilai'   => $b1_total !== null ? floatval($b1_total) : null,
            ];
            $groupIndex++;
        }

        $lkId = $listKategori ? $listKategori->id : $pertandingan_id;
        $isRandori = ($pertandingan && $pertandingan->bagan == 1) ||
                     stripos($pertandingan?->nama ?? '', 'randori') !== false ||
                     stripos(optional($listKategori?->kategori)->nama ?? '', 'randori') !== false;

        $partaiDuel = [];
        if ($isRandori || PesertaTerpilih::where('list_kategori_id', $lkId)->exists()) {
            $duelMatches = PesertaTerpilih::with([
                'PertandinganPesertaPertama.Peserta.kabupaten',
                'PertandinganPesertaKedua.Peserta.kabupaten',
                'pemenang.Peserta.kabupaten'
            ])
            ->where('list_kategori_id', $lkId)
            ->orderBy('babak', 'asc')
            ->orderBy('nomor_partai', 'asc')
            ->get();

            $totP = $allPeserta->count();
            $sCount = pow(2, ceil(log(max(2, $totP), 2)));
            $tBabak = (int) log($sCount, 2);

            $partaiDuel = $duelMatches->map(function ($m) use ($tBabak) {
                $namaBabak = 'Babak ' . $m->babak;
                if ($m->babak == $tBabak) $namaBabak = 'Final';
                elseif ($tBabak >= 2 && $m->babak == $tBabak - 1) $namaBabak = 'Semifinal';
                elseif ($tBabak >= 3 && $m->babak == $tBabak - 2) $namaBabak = 'Perempatfinal';

                $kiri = $m->PertandinganPesertaPertama;
                $kanan = $m->PertandinganPesertaKedua;
                $isBye = ($kanan === null && $m->keterangan_menang === 'BYE');

                $kiriNama = optional($kiri?->Peserta)->nama ?? ($m->pertandingan_peserta_kiri_id ? 'Atlet' : 'Menunggu Pemenang B' . ($m->babak - 1));
                $kananNama = optional($kanan?->Peserta)->nama ?? ($isBye ? 'BYE (Slot Kosong)' : ($m->pertandingan_peserta_kanan_id ? 'Atlet' : 'Menunggu Pemenang B' . ($m->babak - 1)));

                return [
                    'id'                => $m->id,
                    'babak'             => $m->babak,
                    'nama_babak'        => $namaBabak,
                    'nomor_partai'      => $m->nomor_partai,
                    'label'             => "{$namaBabak} - Partai #{$m->nomor_partai}: {$kiriNama} vs {$kananNama}",
                    'is_bye'            => $isBye,
                    'kiri_id'           => $m->pertandingan_peserta_kiri_id,
                    'kiri_nama'         => $kiriNama,
                    'kiri_kontingen'    => optional(optional($kiri?->Peserta)->kabupaten)->name ?? (optional($kiri?->Peserta)->kontigen ?? '-'),
                    'skor_kiri'         => $m->skor_kiri !== null ? floatval($m->skor_kiri) : 0,
                    'kanan_id'          => $m->pertandingan_peserta_kanan_id,
                    'kanan_nama'        => $kananNama,
                    'kanan_kontingen'   => optional(optional($kanan?->Peserta)->kabupaten)->name ?? (optional($kanan?->Peserta)->kontigen ?? '-'),
                    'skor_kanan'        => $m->skor_kanan !== null ? floatval($m->skor_kanan) : 0,
                    'pemenang_id'       => $m->pemenang_id,
                    'keterangan_menang' => $m->keterangan_menang,
                ];
            })->values()->toArray();
        }

        return response()->json([
            'status'          => 'success',
            'pertandingan_id' => $pertandingan_id,
            'is_randori'      => $isRandori,
            'scoresheet_data' => $scoresheetData,
            'peserta'         => $pesertaGroups,
            'partai_duel'     => $partaiDuel,
        ]);
    }

    /**
     * Menyimpan / Memperbarui nilai Papan Skor Publik ke kolom data_scoresheet di database
     */
    public function savePublicPapanSkor(Request $request, $pertandingan_id)
    {
        $listKategori = ListKategori::find($pertandingan_id);
        $pertandingan = null;

        if ($listKategori) {
            $pertandingan = Pertandingan::find($listKategori->pertandingan_id);
            $scoresheetData = $listKategori->data_scoresheet ?? ($pertandingan?->data_scoresheet ?? []);
        } else {
            $pertandingan = Pertandingan::findOrFail($pertandingan_id);
            $scoresheetData = $pertandingan->data_scoresheet ?? [];
        }

        $rowIndex = (int) $request->input('row_index', 0);
        $wasit    = $request->input('wasit', [0, 0, 0, 0, 0]);
        $waktu    = $request->input('waktu', '00:00');
        $genten   = (float) $request->input('genten', 0);

        if (!is_array($scoresheetData)) {
            $scoresheetData = [];
        }

        if (!isset($scoresheetData['rows'])) {
            $scoresheetData['rows'] = [];
        }

        $numericScores = array_map(function ($val) {
            return is_numeric($val) ? (float) $val : 0;
        }, (array) $wasit);

        while (count($numericScores) < 5) {
            $numericScores[] = 0;
        }

        // Rumus: hilangkan nilai terbesar dan terkecil dari wasit yang aktif (> 0), jumlahkan nilai di tengah, lalu dikurangi denda
        $activeScores = array_values(array_filter($numericScores, function ($v) {
            return $v > 0;
        }));

        if (count($activeScores) >= 3) {
            sort($activeScores);
            $middleScores = array_slice($activeScores, 1, count($activeScores) - 2);
            $nilai = array_sum($middleScores);
            $totalNilai = max(0, $nilai - $genten);
        } elseif (!empty($activeScores) || $genten > 0) {
            $nilai = 0;
            $totalNilai = max(0, 0 - $genten);
        } else {
            $nilai = 0;
            $totalNilai = 0;
        }

        // Simpan ke struktur rows
        $scoresheetData['rows'][$rowIndex] = [
            'scores'       => $numericScores,
            'total'        => $nilai,
            'waktu'        => $waktu,
            'genten'       => $genten,
            'pengurangan'  => $genten,
            'total_akhir'  => $totalNilai,
        ];

        // Simpan juga ke struktur peserta untuk kompatibilitas penuh dengan modal scoresheet
        if (!isset($scoresheetData['peserta'])) {
            $scoresheetData['peserta'] = [];
        }
        if (!isset($scoresheetData['peserta'][$rowIndex])) {
            $scoresheetData['peserta'][$rowIndex] = [];
        }
        $scoresheetData['peserta'][$rowIndex]['juri_1'] = $numericScores[0];
        $scoresheetData['peserta'][$rowIndex]['juri_2'] = $numericScores[1];
        $scoresheetData['peserta'][$rowIndex]['juri_3'] = $numericScores[2];
        $scoresheetData['peserta'][$rowIndex]['juri_4'] = $numericScores[3];
        $scoresheetData['peserta'][$rowIndex]['juri_5'] = $numericScores[4];
        $scoresheetData['peserta'][$rowIndex]['hukuman'] = $genten;
        $scoresheetData['peserta'][$rowIndex]['waktu'] = $waktu;
        $scoresheetData['peserta'][$rowIndex]['total'] = $totalNilai;

        if ($listKategori) {
            $listKategori->data_scoresheet = $scoresheetData;
            $listKategori->save();
        }

        if ($pertandingan) {
            $pertandingan->data_scoresheet = $scoresheetData;
            $pertandingan->save();
        }

        // Update ke tabel PertandinganPeserta jika ada pp_ids
        $targetPpIds = $request->input('pp_ids');
        if (!empty($targetPpIds)) {
            $ids = is_array($targetPpIds) ? $targetPpIds : explode(',', $targetPpIds);
            foreach ($ids as $ppId) {
                $pp = PertandinganPeserta::find($ppId);
                if ($pp) {
                    $pp->update([
                        'waktu'       => $waktu,
                        'nilai_1'     => $numericScores[0],
                        'nilai_2'     => $numericScores[1],
                        'nilai_3'     => $numericScores[2],
                        'nilai_4'     => $numericScores[3],
                        'nilai_5'     => $numericScores[4],
                        'minus'       => $genten,
                        'total_nilai' => $totalNilai,
                    ]);
                }
            }
        }

        return response()->json([
            'status'          => 'success',
            'message'         => 'Nilai papan skor berhasil disimpan ke database',
            'nilai'           => $nilai,
            'total_nilai'     => $totalNilai,
            'scoresheet_data' => $scoresheetData,
        ]);
    }

    /**
     * Mengambil data peserta pertandingan dikelompokkan per kontingen untuk modal scoresheet & medali
     */
    public function getPesertaPertandingan(Request $request, $id)
    {
        $babakParam = $request->query('babak', '1');
        $isTampilanAkhir = ($babakParam === 'akhir');
        $babak = ($babakParam === '2') ? 2 : 1;

        $listKategori = ListKategori::with(['pertandingan.kategori', 'kategori'])->find($id);
        $pertandingan = null;

        if ($listKategori) {
            $pertandingan = $listKategori->pertandingan ?? Pertandingan::find($listKategori->pertandingan_id);
        } else {
            $pertandingan = Pertandingan::with('kategori')->find($id);
        }

        $isRandori = false;
        if ($listKategori) {
            $isRandori = stripos($listKategori->pertandingan->nama ?? '', 'randori') !== false ||
                         stripos($listKategori->kategori->nama ?? '', 'randori') !== false;
        } elseif ($pertandingan) {
            $isRandori = stripos($pertandingan->nama ?? '', 'randori') !== false ||
                         stripos($pertandingan->kategori->nama ?? '', 'randori') !== false;
        }

        if ($isRandori) {
            $babak = 1;
            $isTampilanAkhir = false;
            $babakParam = '1';
        }

        // Ambil peserta dasar pada nomor pertandingan ini (babak 1 atau tanpa babak)
        $basePeserta = PertandinganPeserta::with(['Peserta.kabupaten'])
            ->where(function ($q) use ($id, $listKategori) {
                $q->where('list_kategori_id', $id)
                  ->orWhere('pertandingan_id', $id);
                if ($listKategori && $listKategori->pertandingan_id) {
                    $q->orWhere('pertandingan_id', $listKategori->pertandingan_id);
                }
            })
            ->where(function ($q) {
                $q->where('babak', 1)->orWhereNull('babak');
            })
            ->orderBy('urutan')
            ->get();

        // Jika peserta dasar kosong, coba fallback ambil tanpa filter babak
        if ($basePeserta->isEmpty()) {
            $basePeserta = PertandinganPeserta::with(['Peserta.kabupaten'])
                ->where(function ($q) use ($id, $listKategori) {
                    $q->where('list_kategori_id', $id)
                      ->orWhere('pertandingan_id', $id);
                    if ($listKategori && $listKategori->pertandingan_id) {
                        $q->orWhere('pertandingan_id', $listKategori->pertandingan_id);
                    }
                })
                ->orderBy('urutan')
                ->get()
                ->unique('peserta_id');
        }

        // Ambil data Babak 1 dan Babak 2 lengkap untuk nomor pertandingan ini
        $b1Records = collect();
        $b2Records = collect();

        if (!$isRandori) {
            $b1Records = PertandinganPeserta::where(function ($q) use ($id, $listKategori) {
                    $q->where('list_kategori_id', $id)
                      ->orWhere('pertandingan_id', $id);
                    if ($listKategori && $listKategori->pertandingan_id) {
                        $q->orWhere('pertandingan_id', $listKategori->pertandingan_id);
                    }
                })
                ->where(function ($q) {
                    $q->where('babak', 1)->orWhereNull('babak');
                })
                ->get()
                ->keyBy('peserta_id');

            $b2Records = PertandinganPeserta::where(function ($q) use ($id, $listKategori) {
                    $q->where('list_kategori_id', $id)
                      ->orWhere('pertandingan_id', $id);
                    if ($listKategori && $listKategori->pertandingan_id) {
                        $q->orWhere('pertandingan_id', $listKategori->pertandingan_id);
                    }
                })
                ->where('babak', 2)
                ->get()
                ->keyBy('peserta_id');
        }

        // Kelompokkan peserta langsung berdasarkan kontingen (kabupaten_id atau kontigen nama)
        $grouped = $basePeserta->groupBy(function ($item) {
            if ($item->Peserta?->kabupaten_id) {
                return 'kab_' . $item->Peserta->kabupaten_id;
            }
            if (!empty($item->Peserta?->kontigen)) {
                return 'kontigen_' . trim(strtolower($item->Peserta->kontigen));
            }
            return 'peserta_' . $item->id;
        });

        $groups = [];
        $groupIndex = 0;

        foreach ($grouped as $kabId => $items) {
            $first = $items->first();
            $pNames = $items->pluck('Peserta.nama')->filter()->implode(', ');
            $kName = optional($first->Peserta?->kabupaten)->name ?? ($first->Peserta?->kontigen ?? 'Tanpa Kontingen');
            $ppIds = $items->pluck('id')->toArray();

            // Kumpulkan record babak 1 dan babak 2 untuk kontingen ini
            $b1Items = $items->map(function ($it) use ($b1Records) {
                return $b1Records->get($it->peserta_id) ?? $it;
            })->filter();

            $b2Items = $items->map(function ($it) use ($b2Records) {
                return $b2Records->get($it->peserta_id);
            })->filter();

            // Total Nilai Babak 1
            $b1Total = $b1Items->pluck('total_nilai')->filter(fn($v) => !is_null($v) && $v !== '')->first()
                ?? $b1Items->pluck('total_babak_1')->filter(fn($v) => !is_null($v) && $v !== '')->first();

            // Total Nilai Babak 2
            $b2Total = $b2Items->pluck('total_nilai')->filter(fn($v) => !is_null($v) && $v !== '')->first()
                ?? $b2Items->pluck('total_babak_2')->filter(fn($v) => !is_null($v) && $v !== '')->first();

            // Nilai Akhir: rata-rata Babak 1 dan Babak 2
            $nilaiAkhir = $b1Items->pluck('nilai_akhir')->filter(fn($v) => !is_null($v) && $v !== '')->first()
                ?? $b2Items->pluck('nilai_akhir')->filter(fn($v) => !is_null($v) && $v !== '')->first();

            if ($nilaiAkhir === null) {
                if ($b1Total !== null && $b2Total !== null) {
                    $nilaiAkhir = round(((float)$b1Total + (float)$b2Total) / 2, 2);
                } elseif ($b1Total !== null) {
                    $nilaiAkhir = (float)$b1Total;
                } elseif ($b2Total !== null) {
                    $nilaiAkhir = (float)$b2Total;
                }
            }

            // Data formulir aktif (sesuai babak yang sedang dipilih)
            if ($babak == 2 && !$isRandori) {
                if ($b2Items->isNotEmpty()) {
                    $waktu = $b2Items->pluck('waktu')->filter(fn($v) => !empty($v) && $v !== '00:00')->first() ?? '';
                    $n1 = $b2Items->pluck('nilai_1')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $n2 = $b2Items->pluck('nilai_2')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $n3 = $b2Items->pluck('nilai_3')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $n4 = $b2Items->pluck('nilai_4')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $n5 = $b2Items->pluck('nilai_5')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $totalNilai = $b2Items->pluck('total_nilai')->filter(fn($v) => !is_null($v) && $v !== '')->first();

                    $hasAnyJudgeScore = $n1 !== null || $n2 !== null || $n3 !== null || $n4 !== null || $n5 !== null || $totalNilai !== null;
                    $rawMinus = $b2Items->pluck('minus')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                    $minus = ($hasAnyJudgeScore || (floatval($rawMinus) > 0)) ? $rawMinus : null;
                    $medali = $b2Items->pluck('medali')->filter()->first() ?? $items->pluck('medali')->filter()->first();
                } else {
                    $waktu = '';
                    $n1 = null;
                    $n2 = null;
                    $n3 = null;
                    $n4 = null;
                    $n5 = null;
                    $minus = null;
                    $totalNilai = null;
                    $medali = $items->pluck('medali')->filter()->first();
                }
            } else {
                // Babak 1 (atau Randori)
                $waktu = $b1Items->pluck('waktu')->filter(fn($v) => !empty($v) && $v !== '00:00')->first() ?? '';
                $n1 = $b1Items->pluck('nilai_1')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $n2 = $b1Items->pluck('nilai_2')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $n3 = $b1Items->pluck('nilai_3')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $n4 = $b1Items->pluck('nilai_4')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $n5 = $b1Items->pluck('nilai_5')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $totalNilai = $b1Items->pluck('total_nilai')->filter(fn($v) => !is_null($v) && $v !== '')->first();

                $hasAnyJudgeScore = $n1 !== null || $n2 !== null || $n3 !== null || $n4 !== null || $n5 !== null || $totalNilai !== null;
                $rawMinus = $b1Items->pluck('minus')->filter(fn($v) => !is_null($v) && $v !== '')->first();
                $minus = ($hasAnyJudgeScore || (floatval($rawMinus) > 0)) ? $rawMinus : null;
                $medali = $b1Items->pluck('medali')->filter()->first() ?? $items->pluck('medali')->filter()->first();
            }

            $groups[] = [
                'row_index'     => $groupIndex,
                'id'            => $first->id,
                'pp_ids'        => $ppIds,
                'urutan'        => $first->urutan ?? ($groupIndex + 1),
                'nama'          => $pNames ?: ($first->Peserta?->nama ?? '-'),
                'kontingen'     => $kName,
                'kabupaten_id'  => $first->Peserta?->kabupaten_id,
                'waktu'         => $waktu,
                'nilai_1'       => $n1,
                'nilai_2'       => $n2,
                'nilai_3'       => $n3,
                'nilai_4'       => $n4,
                'nilai_5'       => $n5,
                'minus'         => $minus,
                'total_nilai'   => $totalNilai,
                'total_babak_1' => $b1Total !== null ? (float)$b1Total : null,
                'total_babak_2' => $b2Total !== null ? (float)$b2Total : null,
                'nilai_akhir'   => $nilaiAkhir !== null ? (float)$nilaiAkhir : null,
                'medali'        => $medali,
            ];
            $groupIndex++;
        }

        // Khusus tampilan Nilai Akhir (Rekapitulasi), urutkan peserta berdasarkan Nilai Akhir tertinggi (ORDER BY nilai_akhir DESC)
        if ($isTampilanAkhir) {
            usort($groups, function ($a, $b) {
                $naA = $a['nilai_akhir'] ?? -1;
                $naB = $b['nilai_akhir'] ?? -1;
                if ($naA != $naB) {
                    return $naB <=> $naA;
                }
                // Tie-breaker 1: Nilai Babak 2 tertinggi
                $b2A = $a['total_babak_2'] ?? -1;
                $b2B = $b['total_babak_2'] ?? -1;
                if ($b2A != $b2B) {
                    return $b2B <=> $b2A;
                }
                // Tie-breaker 2: Nilai Babak 1 tertinggi
                $b1A = $a['total_babak_1'] ?? -1;
                $b1B = $b['total_babak_1'] ?? -1;
                if ($b1A != $b1B) {
                    return $b1B <=> $b1A;
                }
                // Tie-breaker 3: Denda (minus) terendah
                $mA = $a['minus'] ?? 999;
                $mB = $b['minus'] ?? 999;
                if ($mA != $mB) {
                    return $mA <=> $mB;
                }
                return ($a['urutan'] ?? 0) <=> ($b['urutan'] ?? 0);
            });

            // Tentukan predikat Juara berdasarkan urutan Nilai Akhir
            $rank = 1;
            foreach ($groups as $idx => &$grp) {
                $grp['row_index'] = $idx;
                if (($grp['nilai_akhir'] ?? 0) > 0) {
                    if ($rank === 1) {
                        $grp['juara_label'] = 'Juara 1';
                        $grp['juara_tingkat'] = 1;
                        $grp['medali_auto'] = 'emas';
                    } elseif ($rank === 2) {
                        $grp['juara_label'] = 'Juara 2';
                        $grp['juara_tingkat'] = 2;
                        $grp['medali_auto'] = 'perak';
                    } elseif ($rank === 3) {
                        $grp['juara_label'] = 'Juara 3';
                        $grp['juara_tingkat'] = 3;
                        $grp['medali_auto'] = 'perunggu';
                    } else {
                        $grp['juara_label'] = 'Peringkat ' . $rank;
                        $grp['juara_tingkat'] = $rank;
                        $grp['medali_auto'] = null;
                    }
                    $rank++;
                } else {
                    $grp['juara_label'] = '-';
                    $grp['juara_tingkat'] = null;
                    $grp['medali_auto'] = null;
                }
            }
            unset($grp);
        }

        if ($listKategori) {
            $listKategori->data_juara = self::resolveJuaraKategori($listKategori);
        }

        return response()->json([
            'status'       => 'success',
            'pertandingan' => $listKategori ?: $pertandingan,
            'is_randori'   => $isRandori,
            'babak'        => $babakParam,
            'peserta'      => $groups
        ]);
    }

    /**
     * Simpan nilai Scoresheet per kontingen dan tentukan otomatis Peraih Medali
     */
    public function saveScoresheet(Request $request, $id)
    {
        try {
            $listKategori = ListKategori::find($id);
            $pertandingan = null;
            if ($listKategori) {
                $pertandingan = Pertandingan::find($listKategori->pertandingan_id);
            } else {
                $pertandingan = Pertandingan::find($id);
            }

            $isRandori = false;
            if ($listKategori) {
                $isRandori = stripos($listKategori->pertandingan->nama ?? '', 'randori') !== false ||
                             stripos($listKategori->kategori->nama ?? '', 'randori') !== false;
            } elseif ($pertandingan) {
                $isRandori = stripos($pertandingan->nama ?? '', 'randori') !== false ||
                             stripos($pertandingan->kategori->nama ?? '', 'randori') !== false;
            }

            $babak = (int) $request->input('babak', $request->input('babak_penilaian', 1));
            if ($babak < 1 || $babak > 2 || $isRandori) {
                $babak = 1;
            }

            $scores = $request->input('scores', []);

            $scoresheetData = ($listKategori && $listKategori->data_scoresheet) ? $listKategori->data_scoresheet : ($pertandingan?->data_scoresheet ?? []);
            if (!is_array($scoresheetData)) {
                $scoresheetData = [];
            }
            if (!isset($scoresheetData['per_babak']) || !is_array($scoresheetData['per_babak'])) {
                $scoresheetData['per_babak'] = [];
            }

            $babakPeserta = [];
            $babakRows = [];

            $rIdx = 0;
            $updatedPesertaIds = [];

            foreach ($scores as $rowId => $val) {
                $waktu = !empty($val['waktu']) && $val['waktu'] !== '00:00' ? trim($val['waktu']) : null;
                $hasN1 = isset($val['nilai_1']) && $val['nilai_1'] !== '';
                $hasN2 = isset($val['nilai_2']) && $val['nilai_2'] !== '';
                $hasN3 = isset($val['nilai_3']) && $val['nilai_3'] !== '';
                $hasN4 = isset($val['nilai_4']) && $val['nilai_4'] !== '';
                $hasN5 = isset($val['nilai_5']) && $val['nilai_5'] !== '';
                $hasGenten = isset($val['minus']) && $val['minus'] !== '';

                $n1 = $hasN1 ? max(0, min(100, floatval($val['nilai_1']))) : null;
                $n2 = $hasN2 ? max(0, min(100, floatval($val['nilai_2']))) : null;
                $n3 = $hasN3 ? max(0, min(100, floatval($val['nilai_3']))) : null;
                $n4 = $hasN4 ? max(0, min(100, floatval($val['nilai_4']))) : null;
                $n5 = $hasN5 ? max(0, min(100, floatval($val['nilai_5']))) : null;
                $hasAnyScore = $hasN1 || $hasN2 || $hasN3 || $hasN4 || $hasN5 || $hasGenten;
                $genten = $hasGenten ? max(0, min(100, floatval($val['minus']))) : ($hasAnyScore ? 0 : null);

                $activeScores = array_values(array_filter([$n1, $n2, $n3, $n4, $n5], function ($v) {
                    return !is_null($v) && $v > 0;
                }));

                if (count($activeScores) >= 3) {
                    sort($activeScores);
                    // Hilangkan nilai terbesar dan terkecil dari wasit yang aktif (> 0)
                    $middleScores = array_slice($activeScores, 1, count($activeScores) - 2);
                    $nilai = array_sum($middleScores);
                    $totalNilaiAkhir = max(0, $nilai - ($genten ?? 0));
                } elseif (!empty($activeScores) || ($genten ?? 0) > 0) {
                    $nilai = 0;
                    $totalNilaiAkhir = max(0, 0 - ($genten ?? 0));
                } else {
                    $nilai = null;
                    $totalNilaiAkhir = null;
                }

                // Kumpulkan ID seluruh peserta dalam kontingen tersebut
                $targetIds = [];
                if (!empty($val['pp_ids'])) {
                    $targetIds = is_array($val['pp_ids']) ? $val['pp_ids'] : explode(',', $val['pp_ids']);
                }
                if (empty($targetIds)) {
                    $targetIds = [$rowId];
                }

                // Update / create record PertandinganPeserta untuk babak ini
                foreach ($targetIds as $ppId) {
                    $basePp = PertandinganPeserta::find($ppId);
                    if (!$basePp) continue;

                    $pesertaId = $basePp->peserta_id;
                    $lkId = $basePp->list_kategori_id ?? ($listKategori?->id ?? null);
                    $pertandinganId = $basePp->pertandingan_id ?? ($pertandingan?->id ?? null);
                    $kegiatanId = $basePp->kegiatan_id;

                    // Cari atau buat record untuk babak aktif
                    $targetPp = PertandinganPeserta::where('peserta_id', $pesertaId)
                        ->where(function ($q) use ($lkId, $pertandinganId) {
                            if ($lkId) {
                                $q->where('list_kategori_id', $lkId);
                            } elseif ($pertandinganId) {
                                $q->where('pertandingan_id', $pertandinganId);
                            }
                        })
                        ->where('babak', $babak)
                        ->first();

                    if (!$targetPp) {
                        $targetPp = new PertandinganPeserta();
                        $targetPp->peserta_id = $pesertaId;
                        $targetPp->list_kategori_id = $lkId;
                        $targetPp->pertandingan_id = $pertandinganId;
                        $targetPp->kegiatan_id = $kegiatanId;
                        $targetPp->urutan = $basePp->urutan;
                        $targetPp->sudah_terpilih = $basePp->sudah_terpilih;
                        $targetPp->babak = $babak;
                    }

                    $targetPp->waktu = $waktu;
                    $targetPp->nilai_1 = $n1;
                    $targetPp->nilai_2 = $n2;
                    $targetPp->nilai_3 = $n3;
                    $targetPp->nilai_4 = $n4;
                    $targetPp->nilai_5 = $n5;
                    $targetPp->minus = $genten;
                    $targetPp->total_nilai = $totalNilaiAkhir;
                    if ($babak == 1) {
                        $targetPp->total_babak_1 = $totalNilaiAkhir;
                    } else {
                        $targetPp->total_babak_2 = $totalNilaiAkhir;
                    }
                    $targetPp->save();

                    $updatedPesertaIds[$pesertaId] = [
                        'peserta_id'       => $pesertaId,
                        'list_kategori_id' => $lkId,
                        'pertandingan_id'  => $pertandinganId,
                    ];
                }

                // Rekam ke scoresheetData per_babak
                $kontingenText = $val['kontingen'] ?? '';
                $namaPesertaText = $val['nama'] ?? '';

                $babakPeserta[$rIdx] = [
                    'undian'    => $rIdx + 1,
                    'kontingen' => $kontingenText,
                    'peserta'   => $namaPesertaText,
                    'waktu'     => $waktu ?? '',
                    'juri_1'    => $n1 ?? '',
                    'juri_2'    => $n2 ?? '',
                    'juri_3'    => $n3 ?? '',
                    'juri_4'    => $n4 ?? '',
                    'juri_5'    => $n5 ?? '',
                    'hukuman'   => $genten ?? '',
                    'total'     => $totalNilaiAkhir ?? '',
                ];

                $babakRows[$rIdx] = [
                    'scores'      => [$n1 ?? 0, $n2 ?? 0, $n3 ?? 0, $n4 ?? 0, $n5 ?? 0],
                    'total'       => $nilai ?? 0,
                    'waktu'       => $waktu ?? '',
                    'genten'      => $genten ?? 0,
                    'pengurangan' => $genten ?? 0,
                    'total_akhir' => $totalNilaiAkhir ?? 0,
                ];
                $rIdx++;
            }

            // Sinkronkan nilai total_babak_1, total_babak_2, dan nilai_akhir untuk setiap peserta
            foreach ($updatedPesertaIds as $pInfo) {
                $pesertaId = $pInfo['peserta_id'];
                $lkId = $pInfo['list_kategori_id'];
                $pertandinganId = $pInfo['pertandingan_id'];

                $scopeQuery = fn($q) => $lkId ? $q->where('list_kategori_id', $lkId) : $q->where('pertandingan_id', $pertandinganId);

                $b1Rec = PertandinganPeserta::where('peserta_id', $pesertaId)->where($scopeQuery)->where('babak', 1)->first();
                $b2Rec = PertandinganPeserta::where('peserta_id', $pesertaId)->where($scopeQuery)->where('babak', 2)->first();

                $tot1 = $b1Rec?->total_nilai;
                $tot2 = $b2Rec?->total_nilai;

                $akhir = null;
                if ($tot1 !== null && $tot2 !== null) {
                    $akhir = round(((float)$tot1 + (float)$tot2) / 2, 2);
                } elseif ($tot1 !== null) {
                    $akhir = (float)$tot1;
                } elseif ($tot2 !== null) {
                    $akhir = (float)$tot2;
                }

                PertandinganPeserta::where('peserta_id', $pesertaId)
                    ->where($scopeQuery)
                    ->update([
                        'total_babak_1' => $tot1,
                        'total_babak_2' => $tot2,
                        'nilai_akhir'   => $akhir,
                    ]);
            }

            // Simpan JSON scoresheetData
            $scoresheetData['per_babak'][$babak] = [
                'peserta' => $babakPeserta,
                'rows'    => $babakRows,
            ];
            $scoresheetData['active_babak'] = $babak;
            $scoresheetData['peserta'] = $babakPeserta;
            $scoresheetData['rows'] = $babakRows;

            if ($listKategori) {
                $listKategori->data_scoresheet = $scoresheetData;
                $listKategori->save();
            }
            if ($pertandingan) {
                $pertandingan->data_scoresheet = $scoresheetData;
                $pertandingan->save();
            }

            // Otomatis tentukan Peraih Medali berdasarkan nilai_akhir / total_nilai tertinggi
            $ranked = PertandinganPeserta::with('Peserta.kabupaten')
                ->where(function ($q) use ($id, $listKategori) {
                    $q->where('list_kategori_id', $id)
                      ->orWhere('pertandingan_id', $id);
                    if ($listKategori && $listKategori->pertandingan_id) {
                        $q->orWhere('pertandingan_id', $listKategori->pertandingan_id);
                    }
                })
                ->where(function ($q) {
                    $q->whereNotNull('nilai_akhir')
                      ->orWhereNotNull('total_nilai');
                })
                ->orderByRaw('COALESCE(nilai_akhir, total_nilai) DESC, COALESCE(total_babak_2, 0) DESC, COALESCE(total_babak_1, total_nilai, 0) DESC, COALESCE(minus, 999) ASC, urutan ASC')
                ->get();

            if ($ranked->count() > 0) {
                $candidates = [];
                $candidatesKab = [];
                foreach ($ranked as $item) {
                    if (!$item->Peserta) continue;
                    $kontigenVal = optional($item->Peserta->kabupaten)->name ?? ($item->Peserta->kontigen ?? '');
                    $kabId = optional($item->Peserta)->kabupaten_id;
                    // Penilaian langsung berdasarkan kontingen
                    $val = $kontigenVal ?: ($item->Peserta->nama ?? ('Peserta #' . $item->id));
                    if ($val && !in_array($val, $candidates)) {
                        $candidates[] = $val;
                    }
                    if ($kabId && !in_array($kabId, $candidatesKab)) {
                        $candidatesKab[] = $kabId;
                    }
                }

                // Simpan ke data_juara pada list_kategori
                if ($listKategori) {
                    $juaraData = [
                        'emas' => [
                            'kabupaten_id'   => $candidatesKab[0] ?? null,
                            'kabupaten_nama' => optional(\App\Models\Kabupaten::find($candidatesKab[0] ?? null))->name,
                            'peserta_nama'   => $this->getPesertaNamesByKabupaten($listKategori, $candidatesKab[0] ?? null),
                        ],
                        'perak' => [
                            'kabupaten_id'   => $candidatesKab[1] ?? null,
                            'kabupaten_nama' => optional(\App\Models\Kabupaten::find($candidatesKab[1] ?? null))->name,
                            'peserta_nama'   => $this->getPesertaNamesByKabupaten($listKategori, $candidatesKab[1] ?? null),
                        ],
                        'perunggu' => [
                            'kabupaten_id'   => $candidatesKab[2] ?? null,
                            'kabupaten_nama' => optional(\App\Models\Kabupaten::find($candidatesKab[2] ?? null))->name,
                            'peserta_nama'   => $this->getPesertaNamesByKabupaten($listKategori, $candidatesKab[2] ?? null),
                        ],
                    ];
                    $listKategori->data_juara = $juaraData;
                    $listKategori->save();

                    // Sinkronkan peraih medali di tabel pertandingan_pesertas
                    $lkId = $listKategori->id;
                    PertandinganPeserta::where('list_kategori_id', $lkId)->update(['medali' => null]);
                    if (!empty($candidatesKab[0])) {
                        PertandinganPeserta::where('list_kategori_id', $lkId)
                            ->whereHas('Peserta', fn($q) => $q->where('kabupaten_id', $candidatesKab[0]))
                            ->update(['medali' => 'emas']);
                    }
                    if (!empty($candidatesKab[1])) {
                        PertandinganPeserta::where('list_kategori_id', $lkId)
                            ->whereHas('Peserta', fn($q) => $q->where('kabupaten_id', $candidatesKab[1]))
                            ->update(['medali' => 'perak']);
                    }
                    if (!empty($candidatesKab[2])) {
                        PertandinganPeserta::where('list_kategori_id', $lkId)
                            ->whereHas('Peserta', fn($q) => $q->where('kabupaten_id', $candidatesKab[2]))
                            ->update(['medali' => 'perunggu']);
                    }
                }
            }

            return response()->json([
                'status'  => 'success',
                'babak'   => $babak,
                'message' => "Nilai scoresheet Babak {$babak} kontingen dan peraih medali berhasil disimpan!"
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('saveScoresheet error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan nilai: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FASE 3: Simpan Scoresheet Duel Randori & Auto-Advance Bagan
     */
    public function saveDuelScoresheet(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 'wasit', 1, '1', 2, '2'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'peserta_terpilih_id' => 'required|exists:peserta_terpilihs,id',
            'skor_kiri'           => 'required|numeric|min:0',
            'skor_kanan'          => 'required|numeric|min:0',
            'pemenang_id'         => 'nullable|exists:pertandingan_pesertas,id',
            'keterangan_menang'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $match = PesertaTerpilih::with(['PertandinganPesertaPertama.Peserta', 'PertandinganPesertaKedua.Peserta'])->findOrFail($request->peserta_terpilih_id);

            // Pastikan kedua sisi ada
            if (!$match->pertandingan_peserta_kiri_id || !$match->pertandingan_peserta_kanan_id) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Partai ini belum memiliki pasangan lawan yang lengkap (belum siap ditandingkan atau BYE).'
                ], 400);
            }

            $skorKiri = floatval($request->skor_kiri);
            $skorKanan = floatval($request->skor_kanan);
            $pemenangId = $request->pemenang_id;
            $keteranganMenang = $request->keterangan_menang;

            // Jika pemenang_id tidak dipilih manual, tentukan dari skor tertinggi
            if (empty($pemenangId)) {
                if ($skorKiri > $skorKanan) {
                    $pemenangId = $match->pertandingan_peserta_kiri_id;
                    if (empty($keteranganMenang)) $keteranganMenang = 'POIN';
                } elseif ($skorKanan > $skorKiri) {
                    $pemenangId = $match->pertandingan_peserta_kanan_id;
                    if (empty($keteranganMenang)) $keteranganMenang = 'POIN';
                } else {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Skor kedua atlet seri! Silakan tentukan pemenang berdasarkan keputusan wasit (HANTEI).'
                    ], 422);
                }
            } else {
                if (empty($keteranganMenang)) {
                    $keteranganMenang = ($skorKiri != $skorKanan) ? 'POIN' : 'HANTEI';
                }
            }

            // Simpan skor duel ke peserta_terpilihs
            $match->skor_kiri = $skorKiri;
            $match->skor_kanan = $skorKanan;
            $match->pemenang_id = $pemenangId;
            $match->keterangan_menang = $keteranganMenang;
            $match->save();

            // Akumulasi total skor pada pertandingan_pesertas
            if ($match->PertandinganPesertaPertama) {
                $match->PertandinganPesertaPertama->total_nilai = $skorKiri;
                $match->PertandinganPesertaPertama->save();
            }
            if ($match->PertandinganPesertaKedua) {
                $match->PertandinganPesertaKedua->total_nilai = $skorKanan;
                $match->PertandinganPesertaKedua->save();
            }

            // Simpan detail riwayat skor ke data_scoresheet di list_kategoris jika diperlukan
            $listKategori = ListKategori::find($match->list_kategori_id);
            if ($listKategori) {
                $currentScoresheet = $listKategori->data_scoresheet ?? [];
                $currentScoresheet['randori_partai_' . $match->id] = [
                    'babak'             => $match->babak,
                    'nomor_partai'      => $match->nomor_partai,
                    'kiri_id'           => $match->pertandingan_peserta_kiri_id,
                    'kiri_nama'         => optional($match->PertandinganPesertaPertama?->Peserta)->nama,
                    'kanan_id'          => $match->pertandingan_peserta_kanan_id,
                    'kanan_nama'        => optional($match->PertandinganPesertaKedua?->Peserta)->nama,
                    'skor_kiri'         => $skorKiri,
                    'skor_kanan'        => $skorKanan,
                    'pemenang_id'       => $pemenangId,
                    'keterangan_menang' => $keteranganMenang,
                    'updated_at'        => now()->toDateTimeString(),
                ];
                $listKategori->data_scoresheet = $currentScoresheet;
                $listKategori->save();
            }

            // Jalankan alur Auto-Advance dan Medali
            self::processPartaiWinner($match, $pemenangId, $keteranganMenang);

            $pemenangAtlet = PertandinganPeserta::with('Peserta')->find($pemenangId);
            $namaPemenang = optional($pemenangAtlet?->Peserta)->nama ?? 'Pemenang';

            return response()->json([
                'status'            => 'success',
                'message'           => "Skor Partai #{$match->nomor_partai} berhasil disimpan! {$namaPemenang} melaju ke babak berikutnya.",
                'pemenang_id'       => $pemenangId,
                'pemenang_nama'     => $namaPemenang,
                'keterangan_menang' => $keteranganMenang,
                'skor_kiri'         => $skorKiri,
                'skor_kanan'        => $skorKanan,
            ]);

        } catch (\Throwable $e) {
            Log::error('saveDuelScoresheet error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan skor duel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FASE 3: Logika Auto-Advance & Medali Turnamen Randori Kempo
     */
    public static function processPartaiWinner(PesertaTerpilih $match, $pemenangId, $keteranganMenang = null)
    {
        $match->pemenang_id = $pemenangId;
        if ($keteranganMenang) {
            $match->keterangan_menang = $keteranganMenang;
        }
        $match->save();

        $currentBabak = (int) $match->babak;
        $currentPartai = (int) $match->nomor_partai;

        $totalPeserta = PertandinganPeserta::where('list_kategori_id', $match->list_kategori_id)->count();
        $slotCount = pow(2, ceil(log(max(2, $totalPeserta), 2)));
        $totalBabak = (int) log($slotCount, 2);

        $kiriId = $match->pertandingan_peserta_kiri_id;
        $kananId = $match->pertandingan_peserta_kanan_id;
        $loserId = null;
        if ($kiriId && $kananId) {
            $loserId = ($pemenangId == $kiriId) ? $kananId : $kiriId;
        }

        // 1. Penanganan Medali Turnamen
        if ($currentBabak == $totalBabak) {
            // Babak FINAL: Pemenang dapat Emas, Kalah dapat Perak
            PertandinganPeserta::where('id', $pemenangId)->update(['medali' => 'emas']);
            if ($loserId) {
                PertandinganPeserta::where('id', $loserId)->update(['medali' => 'perak']);
            }
            self::syncMedaliRandori($match->list_kategori_id);
        } elseif ($totalBabak >= 2 && $currentBabak == ($totalBabak - 1)) {
            // Babak SEMIFINAL: Yang kalah otomatis dapat Medali Perunggu Bersama (Standar Kempo)
            if ($loserId) {
                PertandinganPeserta::where('id', $loserId)->update(['medali' => 'perunggu']);
            }
            self::syncMedaliRandori($match->list_kategori_id);
        }

        // 2. Auto-Advance ke Babak Berikutnya jika belum Final
        if ($currentBabak < $totalBabak) {
            $nextBabak = $currentBabak + 1;
            $nextPartai = (int) ceil($currentPartai / 2);
            $isKiri = ($currentPartai % 2 !== 0); // Ganjil = Kiri (Merah), Genap = Kanan (Putih/Biru)

            $nextMatch = PesertaTerpilih::firstOrNew([
                'list_kategori_id' => $match->list_kategori_id,
                'babak'            => $nextBabak,
                'nomor_partai'     => $nextPartai,
            ]);

            $nextMatch->pertandingan_id = $match->pertandingan_id;
            $nextMatch->kegiatan_id = $match->kegiatan_id;

            if ($isKiri) {
                $nextMatch->pertandingan_peserta_kiri_id = $pemenangId;
            } else {
                $nextMatch->pertandingan_peserta_kanan_id = $pemenangId;
            }

            $nextMatch->save();
        }
    }

    /**
     * FASE 3: Sinkronisasi Medali Randori ke Kolom data_juara pada list_kategoris
     */
    public static function syncMedaliRandori($listKategoriId)
    {
        $lk = ListKategori::find($listKategoriId);
        if (!$lk) return;

        $pesertaMedali = PertandinganPeserta::with('Peserta.kabupaten')
            ->where('list_kategori_id', $listKategoriId)
            ->whereNotNull('medali')
            ->get();

        $emasGroup = $pesertaMedali->filter(fn($p) => strtolower(trim($p->medali)) === 'emas');
        $perakGroup = $pesertaMedali->filter(fn($p) => strtolower(trim($p->medali)) === 'perak');
        $perungguGroup = $pesertaMedali->filter(fn($p) => strtolower(trim($p->medali)) === 'perunggu');

        $firstEmas = $emasGroup->first();
        $firstPerak = $perakGroup->first();

        $perKabPerunggu = $perungguGroup->groupBy(fn($p) => optional($p->Peserta)->kabupaten_id);
        $kabKeysPerunggu = $perKabPerunggu->keys()->all();

        $g1 = isset($kabKeysPerunggu[0]) ? $perKabPerunggu[$kabKeysPerunggu[0]] : null;
        $g2 = isset($kabKeysPerunggu[1]) ? $perKabPerunggu[$kabKeysPerunggu[1]] : null;

        $juaraData = [
            'emas' => [
                'kabupaten_id'   => optional($firstEmas?->Peserta)->kabupaten_id,
                'kabupaten_nama' => optional(optional($firstEmas?->Peserta)->kabupaten)->name,
                'peserta_nama'   => $emasGroup->map(fn($p) => optional($p->Peserta)->nama)->filter()->unique()->implode(', '),
            ],
            'perak' => [
                'kabupaten_id'   => optional($firstPerak?->Peserta)->kabupaten_id,
                'kabupaten_nama' => optional(optional($firstPerak?->Peserta)->kabupaten)->name,
                'peserta_nama'   => $perakGroup->map(fn($p) => optional($p->Peserta)->nama)->filter()->unique()->implode(', '),
            ],
            'perunggu' => [
                'kabupaten_id'   => optional($g1?->first()?->Peserta)->kabupaten_id,
                'kabupaten_nama' => optional(optional($g1?->first()?->Peserta)->kabupaten)->name,
                'peserta_nama'   => $g1 ? $g1->map(fn($p) => optional($p->Peserta)->nama)->filter()->unique()->implode(', ') : null,
            ],
            'perunggu_2' => [
                'kabupaten_id'   => optional($g2?->first()?->Peserta)->kabupaten_id,
                'kabupaten_nama' => optional(optional($g2?->first()?->Peserta)->kabupaten)->name,
                'peserta_nama'   => $g2 ? $g2->map(fn($p) => optional($p->Peserta)->nama)->filter()->unique()->implode(', ') : null,
            ],
        ];

        $lk->data_juara = $juaraData;
        $lk->save();
    }

    /**
     * Endpoint ringan untuk mengecek perubahan data papan skor (Smart Polling)
     */
    public function checkScoreUpdate($pertandinganId)
    {
        $pertandingan = \App\Models\Pertandingan::find($pertandinganId);
        $listKategori = null;

        if (!$pertandingan) {
            $listKategori = \App\Models\ListKategori::find($pertandinganId);
            if ($listKategori && $listKategori->pertandingan_id) {
                $pertandingan = \App\Models\Pertandingan::find($listKategori->pertandingan_id);
            }
        } else {
            $listKategori = \App\Models\ListKategori::where('pertandingan_id', $pertandingan->id)->first();
        }

        if (!$pertandingan && !$listKategori) {
            return response()->json(['hash' => null], 404);
        }

        // Ambil agregasi data peserta untuk mendeteksi segala bentuk perubahan nilai/waktu/baris
        $pesertaRecords = \App\Models\PertandinganPeserta::where(function ($q) use ($pertandinganId, $pertandingan, $listKategori) {
            $q->where('pertandingan_id', $pertandinganId)
              ->orWhere('list_kategori_id', $pertandinganId);
            if ($pertandingan && $pertandingan->id) {
                $q->orWhere('pertandingan_id', $pertandingan->id);
            }
            if ($listKategori && $listKategori->id) {
                $q->orWhere('list_kategori_id', $listKategori->id);
            }
        })
        ->orderBy('id')
        ->get();

        $duelRecords = \App\Models\PesertaTerpilih::where(function ($q) use ($pertandinganId, $pertandingan, $listKategori) {
            $q->where('pertandingan_id', $pertandinganId)
              ->orWhere('list_kategori_id', $pertandinganId);
            if ($pertandingan && $pertandingan->id) {
                $q->orWhere('pertandingan_id', $pertandingan->id);
            }
            if ($listKategori && $listKategori->id) {
                $q->orWhere('list_kategori_id', $listKategori->id);
            }
        })
        ->orderBy('id')
        ->get();

        // Gabungkan seluruh atribut penting menjadi satu string untuk di-hash
        $statePayload = [
            'pertandingan_updated' => (string) ($pertandingan ? $pertandingan->updated_at : ($listKategori ? $listKategori->updated_at : '')),
            'pertandingan_attrs'   => md5(json_encode($pertandingan ? $pertandingan->getAttributes() : [])),
            'list_kategori_attrs'  => $listKategori ? md5(json_encode($listKategori->getAttributes())) : '',
            'peserta_count'        => $pesertaRecords->count(),
            'peserta_data'         => md5($pesertaRecords->toJson()),
            'duel_count'           => $duelRecords->count(),
            'duel_data'            => md5($duelRecords->toJson()),
        ];

        return response()->json([
            'hash' => md5(json_encode($statePayload)),
        ]);
    }
}
