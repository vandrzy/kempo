<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\ListKategori;
use App\Models\MainKategori;
use App\Models\Pertandingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(isset($_GET['cari'])) {
            $data['kegiatan'] = Kegiatan::where('nama','like','%'.$_GET['cari'].'%')->orderBy('created_at', 'DESC')->paginate(10);
        } else {
            $data['kegiatan'] = Kegiatan::orderBy('created_at', 'DESC')->paginate(10);
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

        $data['main_kategori'] = Kategori::get();

        return view('admin.kegiatan.tambah', $data);
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
            $kegiatan = Kegiatan::create([
                'nama' => $request->nama,
                'user_id' => Auth::user()->id,
            ]);

            foreach($request->list_kategori_id as $index => $a) {
                if (!empty($request->list_pertandingan_id[$a])) {
                    foreach($request->list_pertandingan_id[$a] as $index2 => $b) {
                        ListKategori::create([
                            'kegiatan_id' => $kegiatan->id,
                            'pertandingan_id' => $b,
                            'kategori_id' => $a,
                        ]);
                    }
                }
            }




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


        $data['kegiatan'] = Kegiatan::find($id);

        $data['kategori'] = ListKategori::select('kategori_id', DB::raw('count(*) as total'))->where('kegiatan_id', $id)->groupBy('kategori_id')->get();
        $data['pertandingan'] = ListKategori::where('kegiatan_id', $id)->orderBy('kategori_id','ASC')->paginate(10);

        // dd($data['kategori']);
        // dd($data['kategori']);
        // dd(count($data['kategori']));
        return view('admin.kegiatan.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data['kegiatan'] = Kegiatan::find($id);
        $data['main_kategori'] = Kategori::get();
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


            // dd($idLah);

        try {
            $kegiatan = Kegiatan::find($id);
            $kegiatan->nama = $request->nama;
            $kegiatan->user_id = Auth::user()->id;


            ListKategori::where('kegiatan_id', $kegiatan->id)->delete();
            foreach($request->list_kategori_id as $index => $a) {
                if (!empty($request->list_pertandingan_id[$a])) {
                    foreach($request->list_pertandingan_id[$a] as $index2 => $b) {
                        ListKategori::create([
                            'kegiatan_id' => $kegiatan->id,
                            'pertandingan_id' => $b,
                            'kategori_id' => $a,
                        ]);
                    }
                }
            }

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
