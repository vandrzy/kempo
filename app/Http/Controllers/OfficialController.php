<?php

namespace App\Http\Controllers;

use App\Exports\PesertaExport;
use App\Models\Kabupaten;
use App\Models\Kegiatan;
use App\Models\ListKategori;
use App\Models\Official;
use App\Models\PertandinganPeserta;
use App\Models\Peserta;
use App\Models\UrutanKomposisiPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class OfficialController extends Controller
{
    //
    public function daftar($id) {

        $kegiatan_id = explode('-', $id);

        $data['kabupatens'] = Kabupaten::get();
        $data['kegiatan'] = Kegiatan::find(base64_decode($kegiatan_id[1]));

        $tahun = old('tanggal_lahir');
        $kelamin = old('jenis_kelamin');




        // if ($tahun && $kelamin) {
        //     $data['pertandingan'] = Pertandingan::whereHas('kategori', function($query) use ($tahun) {
        //         $query->where('tahun_awal', '<=', Date('Y', strtotime($tahun)))->where('tahun_akhir', '>=', Date('Y', strtotime($tahun)));
        //     })->where(function($query) use ($kelamin) {
        //         $query->where('jenis_kelamin', $kelamin)
        //             ->orWhereNull('jenis_kelamin');
        //     })->get();
        // } else {

        // }

        // $data['pertandingan'] = Pertandingan::get();
        $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $data['kegiatan']->id)
        ->select('kategori_id')
        ->groupBy('kategori_id')
        ->get();
        // dd($kategori);


        return view('pengunjung.official_daftar', $data);
    }

    public function daftar_proses(Request $request) {

        // $validator = Validator::make($request->all(), [
        //     'nama' => 'required',
        //     'jenis_kelamin'=> 'required',
        //     'golongan_darah'=> 'required',
        //     'nik'=> 'required|numeric|digits_between:1,16',
        //     // 'tingkat'=> 'required',
        //     'status'=> 'required',
        //     'tingkatan_sabuk_a'=> 'required',
        //     'tingkatan_sabuk_b'=> 'required',

        //     'tempat'=> 'required',
        //     'tanggal_lahir'=> 'required',
        //     'alamat'=> 'required',
        //     'telepon'=> 'required|numeric|digits_between:1,13',
        //     'berat_badan'=> 'required',
        //     'keterangan'=> 'required',
        //     'kabupaten_id'=> 'required',
        //     'kecamatan_id'=> 'required',
        //     'kelurahan_id'=> 'required',
        //     'foto'=> 'required|image|mimes:jpeg,png,jpg,svg',
        // ], [
        //     'nama.required' => 'Nama Peserta Wajib Diisi!',
        //     'jenis_kelamin.required' => 'Jenis Kelamin Wajib Diisi!',
        //     'golongan_darah.required' => 'Golongan Darah Wajib Diisi!',
        //     'nik.required' => 'NIK Wajib Diisi!',
        //     'nik.numeric' => 'NIK Wajib Diisi dengan Angka!',
        //     'nik.digits_between' => 'NIK Maksimal 16 digit!',

        //     'tingkat.required' => 'Kategori Wajib Diisi!',
        //     'tingkatan_sabuk_a.required' => 'Tingkat Wajib Diisi!',
        //     'tingkatan_sabuk_b.required' => 'Tingkat Wajib Diisi!',
        //     'status.required' => 'Status Wajib Diisi!',
        //     'tempat.required' => 'Tempat Lahir Wajib Diisi!',
        //     'tanggal_lahir.required' => 'Tanggal Lahir Wajib Diisi!',
        //     'alamat.required' => 'Alamat Peserta Wajib Diisi!',

        //     'telepon.required' => 'Nomor Telepon Peserta Wajib Diisi!',
        //     'telepon.numeric' => 'Nomor Telepon Peserta Wajib Diisi! dengan Angka',
        //     'telepon.digits_between' => 'Nomor Telepon Peserta Tidak boleh melebihi 13 digit!',
        //     'berat_badan.required' => 'Berat Badan Peserta Wajib Diisi!',

        //     'keterangan.required' => 'Keterangan Wajib Diisi!',
        //     'pertandingan_id.required' => 'Pertandingan Wajib Diisi!',
        //     'foto.required' => 'Foto Peserta Wajib Diisi!',
        //     'foto.max' => 'Foto Peserta tidak boleh sizenya lebih dari 1mb, Mohon dicompres dulu!',
        // ]);



        // if ($validator->fails()) {
        //     // dd($validator->errors()->keys());
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput()
        //         ->with('gagal', 'Lengkapi Form yang tidak terisi!');
        // }

        // if(!$request->nama_tanding) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput()
        //         ->with('gagal', 'Uratan Komposisi tidak boleh kosong!');
        // }

        // dd("ASD");



            $peserta = Official::create([
                'nama' => $request->nama,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'golongan_darah'=> $request->golongan_darah,
                'nik'=> $request->nik,
                // 'tingkat'=> $request->tingkat,
                'status'=> $request->status,
                'tempat'=> $request->tempat,
                'tanggal_lahir'=> $request->tanggal_lahir,
                'alamat'=> $request->alamat,
                'telepon'=> $request->telepon,
                // 'berat_badan'=> $request->berat_badan,
                'keterangan'=> $request->keterangan,
                'sertifikat'=> $request->sertifikat,
                'foto' => 'lol',
                'kabupaten_id' => $request->kabupaten_id,
                // 'kecamatan_id' => $request->kecamatan_id,
                // 'kelurahan_id' => $request->kelurahan_id,
                'kegiatan_id' => $request->kegiatan_id,
                'tingkat' => $request->tingkatan_sabuk_a."-".$request->tingkatan_sabuk_b,
            ]);

            $image = $request->file('foto');

            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = storage_path('app/public/foto_peserta/' . $filename);

            // 'foto' => 'storage/'.$request->foto->store('foto_peserta', 'public'),
            // Resize dan compress (quality 75)


            $manager = new ImageManager(new GdDriver());

            $manager->read($image->getRealPath())
                ->scale(width: 1200) // gunakan scale() di v3, bukan resize() sds
                ->save($path, quality: 50);


            $peserta_image = Official::find($peserta->id);
            $peserta_image->foto = 'storage/foto_peserta/' . $filename;
            $peserta_image->save();


            return back()->with('success', 'Official telah terdaftar, mohon tunggu konfirmasi berikutnya!');

        try {

        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('gagal', 'Terjadi kesalahan saat menyimpan data!')
                ->withInput();
        }
    }

    public function index()
    {
        //



        $data['peserta'] = Official::where('status', 'O')->whereNotNull('created_at');

        if(isset($_GET['delete_duplikat'])) {
            $kegiatan_pertama = Kegiatan::first();

            $kegiatan_id = $_GET['kegiatan_id'] ?? $kegiatan_pertama->id;
            $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $kegiatan_id)->get()->unique('kategori_id')->values();

            $data['peserta'] = Official::select('nama', DB::raw('COUNT(*) as jumlah'))->where('kegiatan_id', $kegiatan_id);
            $data['peserta'] = $data['peserta']->groupBy('nama')->having('jumlah', '>', 1)->get();

            foreach ($data['peserta'] as $item) {
                // ambil semua peserta dengan nama yang sama, urutkan terbaru dulu
                $pesertas = Official::where('nama', $item->nama)->where('kegiatan_id', $kegiatan_id)->orderBy('id', 'desc')->get();

                // simpan 1 data terbaru, hapus sisanya
                $pesertas->skip(1)->each(function ($peserta) {
                    // terakhir hapus Peserta itu sendiri
                    $peserta->delete();
                });

            }

            return back()->with('success', 'Data Duplikat Berhasil Dihapus');

        }

        if(isset($_GET['submit']) && $_GET['submit'] == 'export') {
            return Excel::download(new PesertaExport($_GET['kegiatan_id'], $_GET['kategori_id'],  $_GET['pertandingan_id']), 'rekap_peserta.xlsx');
        } else {
            if(isset($_GET['nama'])  && $_GET['nama'] != "") {
                $data['peserta'] = $data['peserta']->where('nama','like','%'.$_GET['nama'].'%');
            }

            if(isset($_GET['kegiatan_id'])  && $_GET['kegiatan_id'] != "") {
                $kegiatan = Kegiatan::find($_GET['kegiatan_id']);
                $data['peserta'] = $data['peserta']->where('kegiatan_id','=',$_GET['kegiatan_id']);
            } else {
                $data['kegiatan_get'] = Kegiatan::orderBy('created_at', 'DESC')->first();
                $kegiatan_id = $data['kegiatan_get']->id;
                $data['peserta'] = $data['peserta']->where('kegiatan_id','=',$kegiatan_id);

            }

        }


        $data['peserta'] = $data['peserta']->orderBy('nama', 'ASC')->paginate(10);
        $data['kegiatan'] = Kegiatan::orderBy('created_at', 'DESC')->get();
        // dd($data);
        return view('admin.official.index', $data);

    }

    public function show(string $id)
    {
        //
        $data['peserta'] = Official::find($id);
        return view('admin.official.detail', $data);
    }

    public function edit(string $id)
    {
        // $kegiatan_id = explode('-', $id);

        // $data['peserta'] = Peserta::find($id);
        // $data['peserta'] = Peserta::with(['PertandinganPeserta.list_kategori.kategori', 'PertandinganPeserta.UrutanKomposisiPeserta'])->find($id);

        // // ambil semua kategori dari peserta tsb
        // $data['kategori_terpilih'] = $data['peserta']->PertandinganPeserta->pluck('list_kategori.kategori')->filter()->toArray();
        // $data['list_kategori_terpilih'] = $data['peserta']->PertandinganPeserta->filter()->toArray();

        // // dd($data['kategori_terpilih']);
        // $dataPertandinganKegiatan = PertandinganPeserta::where('peserta_id', $data['peserta']->id)->first();
        $data['kabupatens'] = Kabupaten::get();
        $data['peserta'] = Official::find($id);

        $data['kegiatan'] = Kegiatan::find($data['peserta']->kegiatan_id);

        $tahun = old('tanggal_lahir');
        $kelamin = old('jenis_kelamin');




        // if ($tahun && $kelamin) {
        //     $data['pertandingan'] = Pertandingan::whereHas('kategori', function($query) use ($tahun) {
        //         $query->where('tahun_awal', '<=', Date('Y', strtotime($tahun)))->where('tahun_akhir', '>=', Date('Y', strtotime($tahun)));
        //     })->where(function($query) use ($kelamin) {
        //         $query->where('jenis_kelamin', $kelamin)
        //             ->orWhereNull('jenis_kelamin');
        //     })->get();
        // } else {

        // }

        // $data['pertandingan'] = Pertandingan::get();
        // $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $data['kegiatan']->id)
        // ->select('kategori_id')
        // ->groupBy('kategori_id')
        // ->get();
        // dd($kategori);

        // dd("ASD");


        return view('admin.official.edit', $data);
    }

    public function update(Request $request, string $id)
    {
            $peserta = Official::where('id', $id)->update([
                'nama' => $request->nama,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'golongan_darah'=> $request->golongan_darah,
                'nik'=> $request->nik,
                // 'tingkat'=> $request->tingkat,
                // 'status'=> $request->status,
                'tempat'=> $request->tempat,
                'tanggal_lahir'=> $request->tanggal_lahir,
                'alamat'=> $request->alamat,
                'telepon'=> $request->telepon,
                // 'berat_badan'=> $request->berat_badan,
                'keterangan'=> $request->keterangan,
                'sertifikat'=> $request->sertifikat,
                // 'foto' => 'lol',
                'kabupaten_id' => $request->kabupaten_id,
                // 'kecamatan_id' => $request->kecamatan_id,
                // 'kelurahan_id' => $request->kelurahan_id,
                'kegiatan_id' => $request->kegiatan_id,
                'tingkat' => $request->tingkatan_sabuk_a."-".$request->tingkatan_sabuk_b,
            ]);

            if($request->foto) {
                $image = $request->file('foto');

                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = storage_path('app/public/foto_peserta/' . $filename);

                // 'foto' => 'storage/'.$request->foto->store('foto_peserta', 'public'),
                // Resize dan compress (quality 75)


                $manager = new ImageManager(new GdDriver());

                $manager->read($image->getRealPath())
                    ->scale(width: 1200) // gunakan scale() di v3, bukan resize() sds
                    ->save($path, quality: 50);


                $peserta_image = Peserta::find($peserta->id);
                $peserta_image->foto = 'storage/foto_peserta/' . $filename;
                $peserta_image->save();
            }



            return redirect('official')->with('success', 'Official telah tersimpan, mohon tunggu konfirmasi berikutnya!');
    }

    public function destroy(string $id)
    {
        try {
            $peserta = Official::find($id);
            $peserta->delete();

            return back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}
