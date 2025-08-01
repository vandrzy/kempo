<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(isset($_GET['cari'])) {
            $data['kegiatan'] = Kegiatan::where('nama','like','%'.$_GET['cari'].'%')->paginate(10);
        } else {
            $data['kegiatan'] = Kegiatan::paginate(10);
        }

        // dd(count($data['kategori']));
        return view('admin.kegiatan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.kegiatan.tambah');
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
            'nama.required' => 'Nama Kegiatan Wajib Diisi!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }

        try {
            Kegiatan::create([
                'nama' => $request->nama,
                'user_id' => Auth::user()->id,
            ]);

            return redirect('kegiatan')->with('success', 'Data Berhasil Disimpan');
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
        $data['kegiatan'] = Kegiatan::find($id);
        return view('admin.kegiatan.edit', $data);

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
            'nama.required' => 'Nama Kegiatan Wajib Diisi!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }


        try {
            $kegiatan = Kegiatan::find($id);
            $kegiatan->nama = $request->nama;
            $kegiatan->user_id = Auth::user()->id;

            $kegiatan->save();

            return redirect('kegiatan')->with('success', 'Data Berhasil Diubah');
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
            $kegiatan = Kegiatan::find($id);
            $kegiatan->delete();

            return redirect('kegiatan')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
