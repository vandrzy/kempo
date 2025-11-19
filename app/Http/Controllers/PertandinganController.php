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
        $data['pertandingan'] = Pertandingan::whereNotNull('created_at');
        // if(isset($_GET['kegiatan_id'])  && $_GET['kegiatan_id'] != "") {
        //     $kegiatan = Kegiatan::find($_GET['kegiatan_id']);
        //     $data['kategori'] = Kategori::where('kegiatan_id', $kegiatan->id)->get();

        //     $kegiatan_id = $kegiatan->id;
        //     $data['pertandingan'] = $data['pertandingan']->WhereHas('kategori', function ($q3) use ($kegiatan_id) {
        //         $q3->where('kegiatan_id', $kegiatan_id);
        //     });
        // } else {
        //     $data['kegiatan_get'] = Kegiatan::orderBy('created_at', 'DESC')->first();
        //     $kegiatan_id = $data['kegiatan_get']->id;
        //     $data['pertandingan'] = $data['pertandingan']->WhereHas('kategori', function ($q3) use ($kegiatan_id) {
        //         $q3->where('kegiatan_id', $kegiatan_id);
        //     });

        // }

        if(isset($_GET['kategori_id'])  && $_GET['kategori_id'] != "") {
            $data['pertandingan'] = $data['pertandingan']->where('kategori_id', $_GET['kategori_id']);
        }

        if(isset($_GET['cari'])) {
            $data['pertandingan'] = $data['pertandingan']->where('nama','like','%'.$_GET['cari'].'%');
        }

        $data['pertandingan'] = $data['pertandingan']->orderBy('kategori_id', 'ASC')->orderBy('jenis_kelamin','DESC')->paginate(10);
        $data['kategori'] = Kategori::get();
        // dd(count($data['kategori']));
        return view('admin.pertandingan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $data['kegiatan'] = Kegiatan::find($_GET['kegiatan_id']);
        $data['kategori'] = Kategori::get();
        return view('admin.pertandingan.tambah', $data);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
            $data['pertandingan'] = ListKategori::where('id', $id)->first();
            $data['kolom1'] = KontigenPertandingan::where('urutan', $_GET['print'])->where('list_kategori_id', $id)->orderByRaw('kabupaten_id IS NULL ASC')->orderBy('kabupaten_id','ASC')->get();

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


        // $kontigen_tersedia = KontigenPertandingan::where('list_kategori_id', $id)->('urutan')

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
        // $data['kegiatan'] = Kegiatan::find($_GET['kegiatan_id']);
        $data['kategori'] = Kategori::get();
        $data['pertandingan'] = Pertandingan::find($id);

        return view('admin.pertandingan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
        try {
            $pertandingan = Pertandingan::find($id);
            $pertandingan->delete();

            return redirect('pertandingan?kegiatan_id='.Request('kegiatan_id'))->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
