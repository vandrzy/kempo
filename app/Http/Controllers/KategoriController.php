<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(isset($_GET['cari'])) {
            $data['kategori'] = Kategori::where('nama','like','%'.$_GET['cari'].'%')->paginate(10);
        } else {
            $data['kategori'] = Kategori::paginate(10);
        }

        // dd(count($data['kategori']));
        return view('admin.kategori.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.kategori.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama Kategori Wajib Diisi!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }

        try {
            Kategori::create([
                'nama' => $request->nama,
                'usia_awal' => $request->usia_awal,
                'usia_akhir' => $request->usia_akhir,
                'tahun_awal' => $request->tahun_awal,
                'tahun_akhir' => $request->tahun_akhir,
                'user_id' => Auth::user()->id,
            ]);

            return redirect('kategori')->with('success', 'Data Berhasil Disimpan');
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
        //
        $data['kategori'] = Kategori::find($id);
        return view('admin.kategori.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama Kategori Wajib Diisi!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }


        try {
            $kategori = Kategori::find($id);
            $kategori->nama = $request->nama;
            $kategori->usia_awal = $request->usia_awal;
            $kategori->usia_akhir = $request->usia_akhir;
            $kategori->tahun_awal = $request->tahun_awal;
            $kategori->tahun_akhir = $request->tahun_akhir;
            $kategori->user_id = Auth::user()->id;

            $kategori->save();

            return redirect('kategori')->with('success', 'Data Berhasil Diubah');
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
            $kategori = Kategori::find($id);
            $kategori->delete();

            return redirect('kategori')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
