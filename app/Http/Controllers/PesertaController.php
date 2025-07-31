<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pertandingan;
use App\Models\Peserta;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //


        $data['peserta'] = Peserta::whereNotNull('created_at');

        if(isset($_GET['nama'])  && $_GET['nama'] != "") {
            $data['peserta'] = $data['peserta']->where('nama','like','%'.$_GET['nama'].'%');
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

        $data['peserta'] = $data['peserta']->paginate(10);
        $data['kategori'] = Kategori::get();
        return view('admin.peserta.index', $data);
    }

    public function get_pertandingan(string $kategori) {
        $data['pertandingan'] = Pertandingan::where('kategori_id', $kategori)->get();

        return response()->json([
            'status' => 'true',
            'data' => $data['pertandingan'],
        ]);

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
