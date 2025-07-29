<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pertandingan;
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
        if(isset($_GET['cari'])) {
            $data['pertandingan'] = Pertandingan::where('nama','like','%'.$_GET['cari'].'%')->orderBy('kategori_id','ASC')->paginate(10);
        } else {
            $data['pertandingan'] = Pertandingan::orderBy('kategori_id','ASC')->paginate(10);
        }

        // dd(count($data['kategori']));
        return view('admin.pertandingan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
            'kategori_id' => 'required',
            // 'jenis_kelamin' => 'required',
        ], [
            'nama.required' => 'Nama Pertandingan Wajib Diisi!',
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
                'kategori_id' => $request->kategori_id,
                'jenis_kelamin' => $request->jenis_kelamin,

                'user_id' => Auth::user()->id,
            ]);

            return redirect('pertandingan')->with('success', 'Data Berhasil Disimpan');
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
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
            'kategori_id' => 'required',
            // 'jenis_kelamin' => 'required',
        ], [
            'nama.required' => 'Nama Pertandingan Wajib Diisi!',
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
            $pertandingan->jenis_kelamin = $request->jenis_kelamin;

            $pertandingan->user_id = Auth::user()->id;

            $pertandingan->save();

            return redirect('pertandingan')->with('success', 'Data Berhasil Diubah');
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

            return redirect('pertandingan')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
