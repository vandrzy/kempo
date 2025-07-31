<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pertandingan;
use App\Models\PertandinganPeserta;
use App\Models\PesertaTerpilih;
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
        if(isset($_GET['cari'])) {
            $data['pertandingan'] = Pertandingan::where('nama','like','%'.$_GET['cari'].'%')
                                    ->orderBy('kategori_id','ASC')
                                    ->orderBy('jenis_kelamin','ASC')
                                    ->paginate(10);
        } else {
            $data['pertandingan'] = Pertandingan::orderBy('kategori_id','ASC')
                                    ->orderBy('jenis_kelamin','DESC')
                                    ->paginate(10);
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
                'emas' => $request->emas,
                'perak' => $request->perak,
                'perunggu' => $request->perunggu,
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
        $data['peserta_ready'] = PertandinganPeserta::where('sudah_terpilih', 'false')->where('pertandingan_id', $id)->count();
        $data['peserta_lawan'] = PesertaTerpilih::where('pertandingan_id', $id)->paginate(10);
        $data['pertandingan'] = Pertandingan::find($id);
        return view('admin.pertandingan.tanding', $data);
    }

    public function tanding($id) {
        while (true) {
            $terpilih = 0;
            $pertandinganPertama = PertandinganPeserta::where('sudah_terpilih', 'false')->where('pertandingan_id', $id)->inRandomOrder()->first();
            if ($pertandinganPertama) {
                $pertandinganPertama->update(['sudah_terpilih' => 'true']);
                $terpilih += 1;
            } else {
                return redirect()->back()
                    ->with('success', 'Peserta sudah terpilih semua!')
                    ->withInput();
                break;
            }

            $pertandinganKedua = PertandinganPeserta::where('sudah_terpilih', 'false')->where('pertandingan_id', $id)->inRandomOrder()->first();
            if ($pertandinganKedua) {
                $pertandinganKedua->update(['sudah_terpilih' => 'true']);
                $terpilih += 1;
            } else {
                PesertaTerpilih::create([
                    'pertandingan_id' => $id,
                    'pertandingan_peserta_kiri_id' => $pertandinganPertama->id,
                ]);
            }
            if($terpilih > 1) {
                PesertaTerpilih::create([
                    'pertandingan_id' => $id,
                    'pertandingan_peserta_kiri_id' => $pertandinganPertama->id,
                    'pertandingan_peserta_kanan_id' => $pertandinganKedua->id,
                ]);
            }
        }
    }

    public function batal_tanding($id) {
        $pesertaTerpilih = PesertaTerpilih::find($id);
        PertandinganPeserta::where('id', $pesertaTerpilih->pertandingan_peserta_kiri_id)->update(['sudah_terpilih' => 'false']);
        PertandinganPeserta::where('id', $pesertaTerpilih->pertandingan_peserta_kanan_id)->update(['sudah_terpilih' => 'false']);
        $pesertaTerpilih->delete();
        return redirect()->back()
            ->with('success', 'Peserta telah dibatalkan bertanding')
            ->withInput();
    }

    public function get_pertandingan(string $tahun, string $kelamin) {

        $data['pertandingan'] = Pertandingan::whereHas('kategori', function($query) use ($tahun) {
            $query->where('tahun_awal', '<=', Date('Y', strtotime($tahun)))->where('tahun_akhir', '>=', Date('Y', strtotime($tahun)));
        })->where(function($query) use ($kelamin) {
            $query->where('jenis_kelamin', $kelamin)
                ->orWhereNull('jenis_kelamin');
        })->get();

        return response()->json([
            'status' => 'true',
            'data' => $data['pertandingan'],
        ]);
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
            $pertandingan->emas = $request->emas;
            $pertandingan->perak = $request->perak;
            $pertandingan->perunggu = $request->perunggu;
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
