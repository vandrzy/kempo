<?php

namespace App\Http\Controllers;

use App\Exports\PesertaExport;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\Pertandingan;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //


        $data['peserta'] = Peserta::whereNotNull('created_at');

        if(isset($_GET['submit']) && $_GET['submit'] == 'export') {
            return Excel::download(new PesertaExport($_GET['kegiatan_id']), 'rekap_peserta.xlsx');
        } else {
            if(isset($_GET['nama'])  && $_GET['nama'] != "") {
                $data['peserta'] = $data['peserta']->where('nama','like','%'.$_GET['nama'].'%');
            }

            if(isset($_GET['kegiatan_id'])  && $_GET['kegiatan_id'] != "") {
                $kegiatan = Kegiatan::find($_GET['kegiatan_id']);
                $data['kategori'] = Kategori::where('kegiatan_id', $kegiatan->id)->get();

                $kegiatan_id = $kegiatan->id;
                $data['peserta'] = $data['peserta']->WhereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id) {
                    $q1->WhereHas('Pertandingan', function ($q2) use ($kegiatan_id) {
                        $q2->WhereHas('kategori', function ($q3) use ($kegiatan_id) {
                            $q3->where('kegiatan_id', $kegiatan_id);
                        });
                    });
                });
            } else {
                $data['kegiatan_get'] = Kegiatan::orderBy('created_at', 'DESC')->first();
                $kegiatan_id = $data['kegiatan_get']->id;
                $data['peserta'] = $data['peserta']->WhereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id) {
                    $q1->WhereHas('Pertandingan', function ($q2) use ($kegiatan_id) {
                        $q2->WhereHas('kategori', function ($q3) use ($kegiatan_id) {
                            $q3->where('kegiatan_id', $kegiatan_id);
                        });
                    });
                });
                // dd($data['kegiatan']->id);
            }

            if(isset($_GET['kategori_id'])  && $_GET['kategori_id'] != "") {
                $kategori = Kategori::find($_GET['kategori_id']);
                $data['pertandingan'] = Pertandingan::where('kategori_id', $kategori->id)->get();

                $data['peserta'] = $data['peserta']->whereYear('tanggal_lahir', '>=', $kategori->tahun_awal)->whereYear('tanggal_lahir', '<=', $kategori->tahun_akhir);

            }

            if(isset($_GET['pertandingan_id']) && $_GET['pertandingan_id'] != "") {
                $pertandingan_id = $_GET['pertandingan_id'];


                $data['peserta'] = $data['peserta']->whereHas('PertandinganPeserta', function ($query) use ($pertandingan_id) {
                    $query->where('pertandingan_id', $pertandingan_id);
                });
            }
        }



        $data['peserta'] = $data['peserta']->paginate(10);
        $data['kegiatan'] = Kegiatan::orderBy('created_at', 'DESC')->get();
        return view('admin.peserta.index', $data);
    }

    public function export() {
        // return view('admin.peserta.excel', $data);
    }

    public function get_pertandingan(string $id) {

        if(isset($_GET['type'])) {
            $data = [];
            if($_GET['type'] == 'kategori_id') {
                $data = Kategori::where('kegiatan_id', $id)->get();
            } else {
                $data = Pertandingan::where('kategori_id', $id)->get();
            }
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $peserta = Peserta::find($id);
            $peserta->PertandinganPeserta()->delete();
            $peserta->delete();

            return redirect('peserta')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
