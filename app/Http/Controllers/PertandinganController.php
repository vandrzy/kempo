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
        $data['pertandingan'] = Pertandingan::whereNotNull('created_at');

        if(isset($_GET['kategori_id'])  && $_GET['kategori_id'] != "") {
            $data['pertandingan'] = $data['pertandingan']->where('kategori_id', $_GET['kategori_id']);
        }

        if(isset($_GET['cari'])) {
            $data['pertandingan'] = $data['pertandingan']->where('nama','like','%'.$_GET['cari'].'%');
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
                'bagan' => $request->bagan ?? '',

            ]);
            return redirect('pertandingan?kegiatan_id='.$request->kegiatan_id)->with('success', 'Data Berhasil Disimpan');
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
        if (!in_array(Auth::user()->role, ['admin', 'operator', 'wasit', 1, '1', 2, '2'])) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $data['peserta_ready'] = PertandinganPeserta::where('sudah_terpilih', 'false')->where('kegiatan_id', $_GET['kegiatan_id'])->where('list_kategori_id', $id)->count();
        $data['peserta_lawan'] = PesertaTerpilih::where('list_kategori_id', $id)->where('kegiatan_id', $_GET['kegiatan_id'])->paginate(10);
        // $data['pertandingan'] = Pertandingan::find($id);
        $data['kegiatan'] = Kegiatan::find($_GET['kegiatan_id']);
        // dd("las");
        $data['pertandingan'] = ListKategori::where('id', $id)->first();
        $data['kabupaten'] = PertandinganPeserta::with('Peserta.kabupaten')->where('list_kategori_id', $id)->get()->groupBy(function ($item) {
            return $item->Peserta->kabupaten_id;
        });


        if(isset($_GET['print']) && $_GET['print'] != "") {
            // $data['kolom'] = KontigenPertandingan::where('urutan', $_GET['print'])->where('list_kategori_id', $id)->get();
            // $data['kolom2'] = KontigenPertandingan::where('urutan', $_GET['print'])->where('list_kategori_id', $id)->orderByRaw('kabupaten_id IS NULL ASC')->orderBy('kabupaten_id','ASC')->get();
            $data['pertandingan'] = ListKategori::with('pertandingan')->where('id', $id)->first();
            $data['kolom1'] = KontigenPertandingan::where('urutan', $_GET['print'])->where('list_kategori_id', $id)->orderByRaw('kabupaten_id IS NULL ASC')->orderBy('kabupaten_id','ASC')->get();
            // dd($data['kolom1']);
            // dd($data['pertandingan']);
            // $pdf = Pdf::loadView('admin.pertandingan.print_bagan', $data);

            // // 🟢 Tampilkan langsung di browser
            // return $pdf->stream('Data_Peserta_Kontigen_Pertandingan_'.date('Y-m-d').'.pdf');
            return view('admin.pertandingan.print_bagan', $data);

        }
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

        $data['kontigen_pertandingan'] = KontigenPertandingan::where('list_kategori_id', $id)
            ->get()
            ->groupBy('urutan');
        $data['kolom2'] = KontigenPertandingan::where('urutan', 2)->where('list_kategori_id', $id)->whereNull('kabupaten_id')->get();
        // dd($data['kolom1']);

        return view('admin.pertandingan.tanding', $data);
    }

    public function print(string $id) {
        $data['peserta_ready'] = PertandinganPeserta::where('sudah_terpilih', 'false')->where('kegiatan_id', $_GET['kegiatan_id'])->where('list_kategori_id', $id)->count();
        $data['peserta_lawan'] = PesertaTerpilih::where('list_kategori_id', $id)->where('kegiatan_id', $_GET['kegiatan_id'])->paginate(10);
        // $data['pertandingan'] = Pertandingan::find($id);
        // $data['kegiatan'] = Kegiatan::find($_GET['kegiatan_id']);

        $data['pertandingan'] = ListKategori::where('id', $id)->first();
        // return view('admin.pertandingan.peserta_tanding', $data);


        $pdf = Pdf::loadView('admin.pertandingan.peserta_tanding', $data)->setPaper('a4', 'landscape');

        return $pdf->stream("nomor_tanding.pdf"); // atau ->download()
    }

    public function tanding(Request $request,$id) {
        if (!in_array(Auth::user()->role, ['admin', 'operator', 'wasit', 1, '1', 2, '2'])) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $check = Pertandingan::find($request->pertandingan_id);
        $kontigen_delete = KontigenPertandingan::where('list_kategori_id', $id)->where('urutan', 1)->delete();
        $kontigen_delete = KontigenPertandingan::where('list_kategori_id', $id)->where('urutan', 2)->delete();

        $data['kabupaten'] = PertandinganPeserta::with('Peserta.kabupaten')
            ->where('list_kategori_id', $id)
            ->whereHas('Peserta', function ($q) {
                $q->whereNotNull('kabupaten_id');
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->Peserta->kabupaten_id;
            })
            ->values(); // ←


        // dd($data['kabupaten']);

        $kabupaten_list_1 = [];
        $kabupaten_list_2 = [];

        $limit = count($data['kabupaten']);
        if( count($data['kabupaten']) <= 8) {
            $limit = 8;
        }

        if( count($data['kabupaten']) <= 4) {
            $limit = 4;


        }

        if($check->bagan == null) {
            $limit = count($data['kabupaten']);
        }

        // $limit = count($data['kabupaten']) <= 8 ? 8 : 10 ;
        for($i = 0; $i < $limit; $i++) {
            if(isset($data['kabupaten'][$i])) {
                $kabupatenId = optional($data['kabupaten'][$i]->first()->Peserta->Kabupaten)->id;
                $kabupatenName = optional($data['kabupaten'][$i]->first()->Peserta->Kabupaten)->name;
                $kabupatenTotalPeserta = $data['kabupaten'][$i]->count();
                // dd("ASD");
                $kabupaten_list_1[] = $kabupatenId;
                $kabupaten_list_2[] = $kabupatenId;

            } else {
                $kabupaten_list_1[] = null;
                $kabupaten_list_2[] = null;

            }
        }

        // dd($kabupaten_list_1);



        shuffle($kabupaten_list_1);

        // dd($kabupaten_list_1);

        do {
            shuffle($kabupaten_list_2);

            $valid = true;
            foreach ($kabupaten_list_1 as $i => $val) {
                if ($kabupaten_list_2[$i] === $val) {
                    $valid = false;
                    break;
                }
            }

        } while (!$valid);
        // dd($kabupaten_list_2);

        foreach($kabupaten_list_1 as $index => $a) {
            KontigenPertandingan::create([
                'list_kategori_id' => $id,
                'kabupaten_id' => $a,
                'urutan' => 1,
                'undian' => $index,
            ]);
        }


        foreach($kabupaten_list_2 as $index => $a) {
            KontigenPertandingan::create([
                'list_kategori_id' => $id,
                'kabupaten_id' => $a,
                'urutan' => 2,
                'undian' => $index,
            ]);
        }
        // dd($kabupaten_list);
        // foreach ($data['kabupaten'] as $kabId => $items) {
        //     $kabupatenId = optional($items->first()->Peserta->Kabupaten)->id;
        //     $kabupatenName = optional($items->first()->Peserta->Kabupaten)->name;
        //     $kabupatenTotalPeserta = $items->count();
        // }
        // dd($data['kabupaten']);
        return redirect()->back()
            ->with('success', 'Kontigen sudah terpilih semua!')
            ->withInput();
        // while (true) {
        //     $terpilih = 0;
        //     $listKategori = ListKategori::find($id);
        //     $pertandinganPertama = PertandinganPeserta::where('sudah_terpilih', 'false')->where('list_kategori_id', $id)->inRandomOrder()->first();
        //     // dd($pertandinganPertama->Peserta);
        //     if ($pertandinganPertama) {
        //         $pertandinganPertama->update(['sudah_terpilih' => 'true']);
        //         $terpilih += 1;
        //     } else {
        //         return redirect()->back()
        //             ->with('success', 'Peserta sudah terpilih semua!')
        //             ->withInput();
        //         break;
        //     }

        //     // while(true) {
        //     //     if($pertandinganKedua->Peserta->kabupaten_id != $pertandinganPertama->Peserta->kabupaten_id) {
        //     //         break;
        //     //     }
        //     // }
        //     $pertandinganKedua = PertandinganPeserta::whereHas('Peserta', function($q) use ($pertandinganPertama) {
        //         $q->where('kabupaten_id','<>', $pertandinganPertama->Peserta->kabupaten_id);
        //     })->where('sudah_terpilih', 'false')->where('list_kategori_id', $id)->inRandomOrder()->first();


        //     if ($pertandinganKedua) {
        //         $pertandinganKedua->update(['sudah_terpilih' => 'true']);
        //         $terpilih += 1;
        //     } else {
        //         PesertaTerpilih::create([
        //             'list_kategori_id' => $id,
        //             'pertandingan_peserta_kiri_id' => $pertandinganPertama->id,
        //             'kegiatan_id' => $listKategori->kegiatan_id,
        //         ]);
        //     }
        //     if($terpilih > 1) {
        //         PesertaTerpilih::create([
        //             'list_kategori_id' => $id,
        //             'pertandingan_peserta_kiri_id' => $pertandinganPertama->id,
        //             'pertandingan_peserta_kanan_id' => $pertandinganKedua->id,
        //             'kegiatan_id' => $listKategori->kegiatan_id,
        //         ]);
        //     }
        // }
    }

    public function batal_tanding(Request $request) {
        $pesertaTerpilih = PesertaTerpilih::where('list_kategori_id', $request->list_kategori_id)->get();

        if ($pesertaTerpilih) {
            foreach($pesertaTerpilih as $a) {
                PertandinganPeserta::where('id', $a->pertandingan_peserta_kiri_id)
                    ->update(['sudah_terpilih' => 'false']);

                PertandinganPeserta::where('id', $a->pertandingan_peserta_kanan_id)
                    ->update(['sudah_terpilih' => 'false']);

                $a->delete();
            }
            // $pesertaTerpilih->delete();
        }
        return redirect()->back()
            ->with('success', 'Peserta telah dibatalkan bertanding')
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

        $kategori = Kategori::find($_GET['kategori_id']);
        $pertandingan = ListKategori::with(['kategori', 'pertandingan'])->whereHas('kategori', function($query) use ($tahun, $kategori) {
            $query->where('tahun_awal', '<=', Date('Y', strtotime($tahun)))->where('tahun_akhir', '>=', Date('Y', strtotime($tahun)))->where('id', $kategori->id);
        })->whereHas('pertandingan', function($qw) use ($kelamin) {
            $qw->where('jenis_kelamin', $kelamin)->orWhereNull('jenis_kelamin');
        })->where('kegiatan_id', $_GET['kegiatan_id'])->get();

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
            'kategori' =>$kategori,
            'data' => $pertandingan,
            'urutan' => $urutan_number,
            // 'data' => $data['pertandingan'],
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
            $pertandingan->kategori_id = $request->kategori_id;
            $pertandingan->nama = $request->nama;
            $pertandingan->kelas = $request->kelas;
            $pertandingan->jenis_kelamin = $request->jenis_kelamin == "-" ? null : $request->jenis_kelamin;
            $pertandingan->emas = $request->emas;
            $pertandingan->perak = $request->perak;
            $pertandingan->perunggu = $request->perunggu;
            $pertandingan->user_id = Auth::user()->id;
            $pertandingan->bagan = $request->bagan;

            $pertandingan->save();

            return redirect('pertandingan?kegiatan_id='.$request->kegiatan_id)->with('success', 'Data Berhasil Diubah');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('gagal', 'Terjadi kesalahan saat menyimpan data!')
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
        $active_kegiatan_id = $kegiatan_id ?: ($request->kegiatan_id ?: optional($semua_kegiatan->first())->id);
        $kegiatan_aktif = Kegiatan::find($active_kegiatan_id);

        $klasemen = [];
        $daftar_juara = [];
        $total_kategori = 0;
        $total_medali_emas = 0;
        $total_medali_perak = 0;
        $total_medali_perunggu = 0;

        if ($kegiatan_aktif) {
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

                $juara = $lk->data_juara ?? [];

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
                    'is_set' => !empty($emas_kab_id),
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
        }

        return view('admin.pertandingan.laporan_kegiatan', compact(
            'semua_kegiatan',
            'kegiatan_aktif',
            'klasemen',
            'daftar_juara',
            'total_kategori',
            'total_medali_emas',
            'total_medali_perak',
            'total_medali_perunggu'
        ));
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
        $juara = $listKategori->data_juara ?? [];

        // Ambil data peserta yang bertanding di kategori ini
        $peserta_list = PertandinganPeserta::with('Peserta.kabupaten')
            ->where('list_kategori_id', $id)
            ->get();

        $namaPertandingan = $listKategori->pertandingan->nama ?? 'Pertandingan';
        $safeFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $namaPertandingan);

        if ($isRandori && !empty($scoresheet['partai']) && empty($scoresheet['peserta'])) {
            $pdf = Pdf::loadView('pertandingan.pdf_randori', compact('listKategori', 'scoresheet', 'juara', 'peserta_list'))
                ->setPaper('a4', 'landscape');
            return $pdf->stream('Randori_' . $safeFileName . '.pdf');
        } else {
            $pdf = Pdf::loadView('pertandingan.pdf_embu', compact('listKategori', 'scoresheet', 'juara', 'peserta_list', 'isRandori'))
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

                $pesertaRows = $lk->list_kategori;
                $groupedKabupaten = $pesertaRows->groupBy(function ($item) {
                    return $item->Peserta?->kabupaten_id ?? ('p_' . $item->id);
                });

                $pesertaGroups = [];
                $groupIndex = 0;

                foreach ($groupedKabupaten as $kabIdKey => $items) {
                    $names = $items->pluck('Peserta.nama')->filter()->implode(' / ');
                    $kName = optional($items->first()->Peserta?->kabupaten)->name ?? '-';
                    $urutan = $items->first()->urutan ?? ($groupIndex + 1);

                    $pesertaGroups[] = [
                        'row_index'     => $groupIndex,
                        'urutan'        => is_numeric($urutan) ? (int)$urutan : ($groupIndex + 1),
                        'nama_gabungan' => $names ?: ('Peserta #' . ($groupIndex + 1)),
                        'kontingen'     => $kName,
                    ];
                    $groupIndex++;
                }

                $scoresheetData = $lk->data_scoresheet ?? ($tanding->data_scoresheet ?? []);

                $isRandori = stripos($tanding->nama ?? '', 'randori') !== false ||
                             stripos($kategori->nama ?? '', 'randori') !== false;

                $tandingList[] = [
                    'id'              => $lk->id,
                    'pertandingan_id' => $tanding->id,
                    'nama_tanding'    => $tanding->nama . ($tanding->kelas ? " ({$tanding->kelas})" : ''),
                    'jenis'           => $isRandori ? 'randori' : 'embu',
                    'scoresheet_data' => $scoresheetData,
                    'peserta_groups'  => $pesertaGroups,
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
        $listKategori = ListKategori::with('pertandingan')->find($pertandingan_id);
        if ($listKategori) {
            $scoresheetData = $listKategori->data_scoresheet ?? ($listKategori->pertandingan?->data_scoresheet ?? []);
        } else {
            $pertandingan = Pertandingan::findOrFail($pertandingan_id);
            $scoresheetData = $pertandingan->data_scoresheet ?? [];
        }

        return response()->json([
            'status'          => 'success',
            'pertandingan_id' => $pertandingan_id,
            'scoresheet_data' => $scoresheetData,
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

        $sorted = $numericScores;
        sort($sorted);
        // Ambil 3 nilai tengah (index 1, 2, 3) setelah diurutkan dari 5 nilai
        $nilaiTengah = $sorted[1] + $sorted[2] + $sorted[3];
        $totalNilai  = max(0, $nilaiTengah - $genten);

        // Simpan ke struktur rows
        $scoresheetData['rows'][$rowIndex] = [
            'scores'       => $numericScores,
            'total'        => $nilaiTengah,
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

        return response()->json([
            'status'          => 'success',
            'message'         => 'Nilai papan skor berhasil disimpan ke database',
            'nilai'           => $nilaiTengah,
            'total_nilai'     => $totalNilai,
            'scoresheet_data' => $scoresheetData,
        ]);
    }

    /**
     * Mengambil data peserta pertandingan dikelompokkan per kontingen untuk modal scoresheet & medali
     */
    public function getPesertaPertandingan($id)
    {
        $listKategori = ListKategori::with(['pertandingan.kategori', 'kategori'])->find($id);
        $pertandingan = null;

        if ($listKategori) {
            $pertandingan = $listKategori->pertandingan ?? Pertandingan::find($listKategori->pertandingan_id);
        } else {
            $pertandingan = Pertandingan::with('kategori')->find($id);
        }

        $allPeserta = PertandinganPeserta::with(['Peserta.kabupaten'])
            ->where(function ($q) use ($id, $listKategori) {
                $q->where('list_kategori_id', $id)
                  ->orWhere('pertandingan_id', $id);
                if ($listKategori && $listKategori->pertandingan_id) {
                    $q->orWhere('pertandingan_id', $listKategori->pertandingan_id);
                }
            })
            ->orderBy('urutan')
            ->get();

        // Kelompokkan peserta langsung berdasarkan kontingen (kabupaten_id atau kontigen nama)
        $grouped = $allPeserta->groupBy(function ($item) {
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

            // Cari nilai pertama yang tidak kosong dalam kontingen ini
            $waktu = $items->pluck('waktu')->filter()->first() ?? '';
            $n1 = $items->pluck('nilai_1')->filter(fn($v) => !is_null($v))->first();
            $n2 = $items->pluck('nilai_2')->filter(fn($v) => !is_null($v))->first();
            $n3 = $items->pluck('nilai_3')->filter(fn($v) => !is_null($v))->first();
            $n4 = $items->pluck('nilai_4')->filter(fn($v) => !is_null($v))->first();
            $n5 = $items->pluck('nilai_5')->filter(fn($v) => !is_null($v))->first();
            $minus = $items->pluck('minus')->filter(fn($v) => !is_null($v))->first() ?? 0;
            $totalNilai = $items->pluck('total_nilai')->filter(fn($v) => !is_null($v))->first();

            $groups[] = [
                'row_index'    => $groupIndex,
                'id'           => $first->id,
                'pp_ids'       => $ppIds,
                'urutan'       => $first->urutan ?? ($groupIndex + 1),
                'nama'         => $pNames ?: ($first->Peserta?->nama ?? '-'),
                'kontingen'    => $kName,
                'kabupaten_id' => $first->Peserta?->kabupaten_id,
                'waktu'        => $waktu,
                'nilai_1'      => $n1,
                'nilai_2'      => $n2,
                'nilai_3'      => $n3,
                'nilai_4'      => $n4,
                'nilai_5'      => $n5,
                'minus'        => $minus,
                'total_nilai'  => $totalNilai,
            ];
            $groupIndex++;
        }

        return response()->json([
            'status'       => 'success',
            'pertandingan' => $pertandingan ?: $listKategori,
            'peserta'      => $groups
        ]);
    }

    /**
     * Simpan nilai Scoresheet per kontingen dan tentukan otomatis Peraih Medali
     */
    public function saveScoresheet(Request $request, $id)
    {
        $listKategori = ListKategori::find($id);
        $pertandingan = null;
        if ($listKategori) {
            $pertandingan = Pertandingan::find($listKategori->pertandingan_id);
        } else {
            $pertandingan = Pertandingan::find($id);
        }

        $scores = $request->input('scores', []);

        $scoresheetData = ($listKategori && $listKategori->data_scoresheet) ? $listKategori->data_scoresheet : ($pertandingan?->data_scoresheet ?? []);
        if (!is_array($scoresheetData)) {
            $scoresheetData = [];
        }
        $scoresheetData['peserta'] = [];
        $scoresheetData['rows'] = [];

        $rIdx = 0;
        foreach ($scores as $rowId => $val) {
            $waktu = $val['waktu'] ?? '00:00';
            $n1 = isset($val['nilai_1']) && $val['nilai_1'] !== '' ? floatval($val['nilai_1']) : 0;
            $n2 = isset($val['nilai_2']) && $val['nilai_2'] !== '' ? floatval($val['nilai_2']) : 0;
            $n3 = isset($val['nilai_3']) && $val['nilai_3'] !== '' ? floatval($val['nilai_3']) : 0;
            $n4 = isset($val['nilai_4']) && $val['nilai_4'] !== '' ? floatval($val['nilai_4']) : 0;
            $n5 = isset($val['nilai_5']) && $val['nilai_5'] !== '' ? floatval($val['nilai_5']) : 0;
            $genten = isset($val['minus']) && $val['minus'] !== '' ? floatval($val['minus']) : 0;

            // Rumus: Jumlahkan ke-5 nilai wasit lalu dikurangi genten (minus)
            $nilai = $n1 + $n2 + $n3 + $n4 + $n5;
            $totalNilaiAkhir = $nilai - $genten;

            // Kumpulkan ID seluruh peserta dalam kontingen tersebut
            $targetIds = [];
            if (!empty($val['pp_ids'])) {
                $targetIds = is_array($val['pp_ids']) ? $val['pp_ids'] : explode(',', $val['pp_ids']);
            }
            if (empty($targetIds)) {
                $targetIds = [$rowId];
            }

            // Seluruh peserta dari satu kontingen mendapatkan nilai yang sama persis
            foreach ($targetIds as $ppId) {
                $pp = PertandinganPeserta::find($ppId);
                if ($pp) {
                    $pp->update([
                        'waktu'       => $waktu,
                        'nilai_1'     => $val['nilai_1'] !== '' ? $n1 : null,
                        'nilai_2'     => $val['nilai_2'] !== '' ? $n2 : null,
                        'nilai_3'     => $val['nilai_3'] !== '' ? $n3 : null,
                        'nilai_4'     => $val['nilai_4'] !== '' ? $n4 : null,
                        'nilai_5'     => $val['nilai_5'] !== '' ? $n5 : null,
                        'minus'       => $val['minus'] !== '' ? $genten : 0,
                        'total_nilai' => $totalNilaiAkhir
                    ]);
                }
            }

            // Rekam ke scoresheetData untuk cetak PDF & Papan Skor
            $kontingenText = $val['kontingen'] ?? '';
            $namaPesertaText = $val['nama'] ?? '';

            $scoresheetData['peserta'][$rIdx] = [
                'undian'    => $rIdx + 1,
                'kontingen' => $kontingenText,
                'peserta'   => $namaPesertaText,
                'waktu'     => $waktu,
                'juri_1'    => $n1,
                'juri_2'    => $n2,
                'juri_3'    => $n3,
                'juri_4'    => $n4,
                'juri_5'    => $n5,
                'hukuman'   => $genten,
                'total'     => $totalNilaiAkhir,
            ];

            $scoresheetData['rows'][$rIdx] = [
                'scores'      => [$n1, $n2, $n3, $n4, $n5],
                'total'       => $nilai,
                'waktu'       => $waktu,
                'genten'      => $genten,
                'pengurangan' => $genten,
                'total_akhir' => $totalNilaiAkhir,
            ];
            $rIdx++;
        }

        if ($listKategori) {
            $listKategori->data_scoresheet = $scoresheetData;
            $listKategori->save();
        }
        if ($pertandingan) {
            $pertandingan->data_scoresheet = $scoresheetData;
            $pertandingan->save();
        }

        // Otomatis tentukan Peraih Medali berdasarkan total_nilai akhir tertinggi per kontingen
        $ranked = PertandinganPeserta::with('Peserta.kabupaten')
            ->where(function ($q) use ($id, $listKategori) {
                $q->where('list_kategori_id', $id)
                  ->orWhere('pertandingan_id', $id);
                if ($listKategori && $listKategori->pertandingan_id) {
                    $q->orWhere('pertandingan_id', $listKategori->pertandingan_id);
                }
            })
            ->whereNotNull('total_nilai')
            ->orderByDesc('total_nilai')
            ->get();

        $targetPertandingan = $pertandingan ?: ($listKategori ? $listKategori->pertandingan : null);
        $kategori = $listKategori ? $listKategori->kategori : ($targetPertandingan ? $targetPertandingan->kategori : null);

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

            if ($targetPertandingan) {
                $targetPertandingan->update([
                    'emas'      => $candidates[0] ?? null,
                    'perak'     => $candidates[1] ?? null,
                    'perunggu'  => $candidates[2] ?? null,
                    'perunggu1' => $candidates[2] ?? null,
                    'perunggu2' => null,
                ]);
            }

            // Simpan juga ke data_juara pada list_kategori
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
            }
        }

        return response()->json(['status' => 'success']);
    }
}
