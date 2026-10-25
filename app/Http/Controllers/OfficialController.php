<?php

namespace App\Http\Controllers;

use App\Exports\OfficialExport;
use App\Models\Kabupaten;
use App\Models\Kegiatan;
use App\Models\ListKategori;
use App\Models\Official;
use App\Models\PertandinganPeserta;
use App\Models\Peserta;
use App\Models\UrutanKomposisiPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use App\Helpers\FotoHelper;

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



            $fotoPath = FotoHelper::simpanFoto($request->file('foto'), $request->existing_foto) ?? '';

            $peserta = Official::create([
                'nama' => $request->nama,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'golongan_darah'=> $request->golongan_darah,
                'nik'=> $request->nik,
                // 'tingkat'=> $request->tingkat,
                'status'=> $request->status ?? 'O',
                'tempat'=> $request->tempat,
                'tanggal_lahir'=> $request->tanggal_lahir,
                'alamat'=> $request->alamat,
                'telepon'=> $request->telepon,
                // 'berat_badan'=> $request->berat_badan,
                'keterangan'=> $request->keterangan,
                'sertifikat'=> $request->sertifikat,
                'foto' => $fotoPath,
                'kabupaten_id' => $request->kabupaten_id,
                // 'kecamatan_id' => $request->kecamatan_id,
                // 'kelurahan_id' => $request->kelurahan_id,
                'kegiatan_id' => $request->kegiatan_id,
                'tingkat' => $request->tingkatan_sabuk_a."-".$request->tingkatan_sabuk_b,
            ]);


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
        if (Auth::user()->role === 'wasit') {
            abort(403, 'Unauthorized');
        }

        $data['peserta'] = Official::where('status', 'O')->whereNotNull('created_at');

        if(isset($_GET['delete_duplikat']) || isset($_POST['delete_duplikat'])) {
            return $this->hapusDuplikat(request());
        }

        if(isset($_GET['submit']) && $_GET['submit'] == 'export') {
            $kegiatanId = !empty($_GET['kegiatan_id']) ? $_GET['kegiatan_id'] : optional(Kegiatan::orderBy('created_at', 'DESC')->first())->id;
            $nama = !empty($_GET['nama']) ? $_GET['nama'] : null;
            return Excel::download(new OfficialExport($kegiatanId, $nama), 'rekap_official.xlsx');
        } else {
            if(isset($_GET['nama'])  && $_GET['nama'] != "") {
                $data['peserta'] = $data['peserta']->where('nama','like','%'.$_GET['nama'].'%');
            }

            if(isset($_GET['kegiatan_id'])  && $_GET['kegiatan_id'] != "") {
                $kegiatan = Kegiatan::find($_GET['kegiatan_id']);
                $data['kegiatan_get'] = $kegiatan;

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

    /**
     * Membersihkan data duplikat official secara cerdas dan aman:
     * 1. Menghapus record duplikat berdasarkan NIK pada kegiatan yang sama.
     * 2. Menghapus record duplikat berdasarkan normalisasi Nama + Kabupaten pada kegiatan yang sama.
     * 3. Menghapus file fisik duplikat (foto & sertifikat) dari storage jika tidak digunakan oleh primary.
     */
    public function hapusDuplikat(Request $request)
    {
        if (Auth::user()->role === 'wasit') {
            abort(403, 'Unauthorized');
        }

        $kegiatan_id = $request->kegiatan_id ?? optional(Kegiatan::orderBy('created_at', 'DESC')->first())->id;

        if (!$kegiatan_id) {
            return back()->with('error', 'Kegiatan tidak ditemukan.');
        }

        $totalDeleted = 0;

        DB::beginTransaction();
        try {
            $processedIds = [];

            // 1. DUPLIKAT BERDASARKAN NIK PADA KEGIATAN YANG SAMA
            $duplicateNiks = Official::where('kegiatan_id', $kegiatan_id)
                ->whereNotNull('nik')
                ->where('nik', '!=', '')
                ->select('nik', DB::raw('COUNT(*) as total'))
                ->groupBy('nik')
                ->having('total', '>', 1)
                ->pluck('nik');

            foreach ($duplicateNiks as $nik) {
                $officials = Official::where('kegiatan_id', $kegiatan_id)
                    ->where('nik', $nik)
                    ->orderBy('id', 'asc')
                    ->get();

                if ($officials->count() <= 1) {
                    continue;
                }

                $primary = $officials->first();
                $processedIds[] = $primary->id;
                $duplicates = $officials->slice(1);

                foreach ($duplicates as $dup) {
                    $processedIds[] = $dup->id;

                    // Hapus file fisik foto jika berbeda dari primary
                    if (!empty($dup->foto) && $dup->foto !== $primary->foto && file_exists(public_path($dup->foto))) {
                        @unlink(public_path($dup->foto));
                    }

                    // Hapus file fisik sertifikat jika berbeda dari primary
                    if (!empty($dup->sertifikat) && $dup->sertifikat !== $primary->sertifikat && file_exists(public_path($dup->sertifikat))) {
                        @unlink(public_path($dup->sertifikat));
                    }

                    $dup->delete();
                    $totalDeleted++;
                }
            }

            // 2. DUPLIKAT BERDASARKAN NORMALISASI NAMA + KABUPATEN
            $allOfficials = Official::where('kegiatan_id', $kegiatan_id)
                ->whereNotIn('id', $processedIds)
                ->orderBy('id', 'asc')
                ->get();

            $groupedByNameKab = [];
            foreach ($allOfficials as $off) {
                $normalizedName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $off->nama));
                $key = $off->kabupaten_id . '_' . $normalizedName;
                $groupedByNameKab[$key][] = $off;
            }

            foreach ($groupedByNameKab as $group) {
                if (count($group) > 1) {
                    $primary = $group[0];
                    for ($i = 1; $i < count($group); $i++) {
                        $dup = $group[$i];

                        if (!empty($dup->foto) && $dup->foto !== $primary->foto && file_exists(public_path($dup->foto))) {
                            @unlink(public_path($dup->foto));
                        }

                        if (!empty($dup->sertifikat) && $dup->sertifikat !== $primary->sertifikat && file_exists(public_path($dup->sertifikat))) {
                            @unlink(public_path($dup->sertifikat));
                        }

                        $dup->delete();
                        $totalDeleted++;
                    }
                }
            }

            DB::commit();

            return back()->with('success', "Pembersihan duplikat official berhasil! {$totalDeleted} data duplikat dibersihkan.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membersihkan data duplikat official: ' . $e->getMessage());
        }
    }

    public function create() {
        $data['kabupatens'] = Kabupaten::get();
        $data['kegiatan'] = Kegiatan::find($_GET['kegiatan_id']);

        return view('admin.official.tambah', $data);
    }

    public function store(Request $request) {
            $fotoPath = FotoHelper::simpanFoto($request->file('foto'), $request->existing_foto) ?? '';

            $peserta = Official::create([
                'nama' => $request->nama,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'golongan_darah'=> $request->golongan_darah,
                'nik'=> $request->nik,
                'status'=> "O",
                'tempat'=> $request->tempat,
                'tanggal_lahir'=> $request->tanggal_lahir,
                'alamat'=> $request->alamat,
                'telepon'=> $request->telepon,
                // 'berat_badan'=> $request->berat_badan,
                'keterangan'=> $request->keterangan,
                'sertifikat'=> $request->sertifikat,
                'foto' => $fotoPath,
                'kabupaten_id' => $request->kabupaten_id,
                // 'kecamatan_id' => $request->kecamatan_id,
                // 'kelurahan_id' => $request->kelurahan_id,
                'kegiatan_id' => $request->kegiatan_id,
                'tingkat' => $request->tingkatan_sabuk_a."-".$request->tingkatan_sabuk_b,
            ]);


            return redirect('official')->with('success', 'Official telah disimpan');
        try {

        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('gagal', 'Terjadi kesalahan saat menyimpan data!')
                ->withInput();
        }
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
                $fotoPath = FotoHelper::simpanFoto($request->file('foto'));
                if ($fotoPath) {
                    $peserta_image = Official::find($id);
                    $peserta_image->foto = $fotoPath;
                    $peserta_image->save();
                }
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
