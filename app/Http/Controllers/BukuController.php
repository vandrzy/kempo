<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\KategoriBuku;
use App\Models\Penulis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data['buku'] = Buku::whereNotNull('created_at');

        if(isset($_GET['judul'])  && $_GET['judul'] != "") {
            $data['buku'] = $data['buku']->where('judul','like','%'.$_GET['judul'].'%');
        }

        if(isset($_GET['penulis'])  && $_GET['penulis'] != "") {
            $data['buku'] = $data['buku']->where('penulis_id',$_GET['penulis']);
        }

        if(isset($_GET['kategori']) && $_GET['kategori'] != "") {
            $kategori_id = $_GET['kategori'];

            $data['buku'] = $data['buku']->whereHas('KategoriBuku', function ($query) use ($kategori_id) {
                $query->where('kategori_id', $kategori_id);
            });
        }

        $data['buku'] = $data['buku']->paginate(10);

        $data['penulis'] = Penulis::get();
        $data['kategori'] = Kategori::get();
        return view('admin.buku.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['penulis'] = Penulis::get();
        $data['kategori'] = Kategori::get();

        return view('admin.buku.tambah', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        // dd($request->kategori_id);

        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'cover' => 'required',
            'penulis_id' => 'required',
            'kategori_id' => 'required',
            'publisher' => 'required',
            'tahun' => 'required',
            'deskripsi' => 'required',
        ], [
            'judul.required' => 'Judul Buku Wajib Diisi!',
            'cover.required' => 'Cover Buku Wajib Diisi!',
            'penulis_id.required' => 'Penulis Buku Wajib Diisi!',
            'kategori_id.required' => 'Kategori Buku Wajib Diisi!',
            'publisher.required' => 'Publisher Buku Wajib Diisi!',
            'tahun.required' => 'Tahun Buku Wajib Diisi!',
            'deskripsi.required' => 'Deskripsi Buku Wajib Diisi!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }

        try {
            $buku = Buku::create([
                'judul' => $request->judul,
                'cover' =>  $request->file('cover')->store('uploads', 'public'),
                'penulis_id' => $request->penulis_id,
                'publisher' => $request->publisher,
                'tahun' => $request->tahun,
                'deskripsi' => $request->deskripsi,
                'user_id' => Auth::user()->id,
            ]);

            foreach($request->kategori_id as $a) {
                $kategori_buku = KategoriBuku::create([
                    'buku_id' => $buku->id,
                    'kategori_id' => $a,
                ]);
            }

            return redirect('buku')->with('success', 'Data Berhasil Disimpan');
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
        $data['buku'] = Buku::find($id);
        return view('admin.buku.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data['penulis'] = Penulis::get();
        $data['kategori'] = Kategori::get();

        $data['buku'] = Buku::find($id);


        return view('admin.buku.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            // 'cover' => 'required',
            'penulis_id' => 'required',
            'kategori_id' => 'required',
            'publisher' => 'required',
            'tahun' => 'required',
            'deskripsi' => 'required',
        ], [
            'judul.required' => 'Judul Buku Wajib Diisi!',
            'cover.required' => 'Cover Buku Wajib Diisi!',
            'penulis_id.required' => 'Penulis Buku Wajib Diisi!',
            'kategori_id.required' => 'Kategori Buku Wajib Diisi!',
            'publisher.required' => 'Publisher Buku Wajib Diisi!',
            'tahun.required' => 'Tahun Buku Wajib Diisi!',
            'deskripsi.required' => 'Deskripsi Buku Wajib Diisi!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }

        try {
            $buku = Buku::find($id);
            $buku->judul = $request->judul;
            if($request->cover) {
                $buku->cover = $request->file('cover')->store('uploads', 'public');
            }
            $buku->penulis_id = $request->penulis_id;
            $buku->publisher = $request->publisher;
            $buku->tahun = $request->tahun;
            $buku->deskripsi = $request->deskripsi;
            $buku->user_id = Auth::user()->id;
            $buku->save();

            KategoriBuku::where('buku_id', $id)->delete();

            foreach($request->kategori_id as $a) {
                $kategori_buku = KategoriBuku::create([
                    'buku_id' => $buku->id,
                    'kategori_id' => $a,
                ]);
            }

            return redirect('buku')->with('success', 'Data Berhasil Diubah');
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
            $buku = Buku::find($id);
            KategoriBuku::where('buku_id', $id)->delete();
            $buku->delete();

            return redirect('buku')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
