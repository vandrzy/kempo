<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kabupaten;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Kegiatan;
use App\Models\Kelurahan;
use App\Models\ListKategori;
use App\Models\Penulis;
use App\Models\Pertandingan;
use App\Models\PertandinganPeserta;
use App\Models\Peserta;
use App\Models\UrutanKomposisiPeserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class HomePageController extends Controller
{
    //
    public function index($id) {

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


        return view('pengunjung.dashboard', $data);
    }

    public function getWilayah($type, $id) {

        if($type == "kabupaten_id") {
            $data = Kecamatan::where('kabupaten_id',$id)->get();
        }

        if($type == "kecamatan_id") {
            $data = Kelurahan::where('kecamatan_id',$id)->get();
        }
        return response()->json([
            'status' => 'true',
            'data' => $data,
            // 'data' => $data['pertandingan'],
        ]);
    }

    public function daftarProcess(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jenis_kelamin'=> 'required',
            'golongan_darah'=> 'required',
            'nik'=> 'required|numeric|digits_between:1,16',
            // 'tingkat'=> 'required',
            'status'=> 'required',
            'tingkatan_sabuk_a'=> 'required',
            'tingkatan_sabuk_b'=> 'required',

            'tempat'=> 'required',
            'tanggal_lahir'=> 'required',
            'alamat'=> 'required',
            'telepon'=> 'required|numeric|digits_between:1,13',
            'berat_badan'=> 'required',
            'keterangan'=> 'required',
            'kabupaten_id'=> 'required',
            'kecamatan_id'=> 'required',
            'kelurahan_id'=> 'required',
            'foto'=> 'required|image|mimes:jpeg,png,jpg,svg',
        ], [
            'nama.required' => 'Nama Peserta Wajib Diisi!',
            'jenis_kelamin.required' => 'Jenis Kelamin Wajib Diisi!',
            'golongan_darah.required' => 'Golongan Darah Wajib Diisi!',
            'nik.required' => 'NIK Wajib Diisi!',
            'nik.numeric' => 'NIK Wajib Diisi dengan Angka!',
            'nik.digits_between' => 'NIK Maksimal 16 digit!',

            'tingkat.required' => 'Kategori Wajib Diisi!',
            'tingkatan_sabuk_a.required' => 'Tingkat Wajib Diisi!',
            'tingkatan_sabuk_b.required' => 'Tingkat Wajib Diisi!',
            'status.required' => 'Status Wajib Diisi!',
            'tempat.required' => 'Tempat Lahir Wajib Diisi!',
            'tanggal_lahir.required' => 'Tanggal Lahir Wajib Diisi!',
            'alamat.required' => 'Alamat Peserta Wajib Diisi!',

            'telepon.required' => 'Nomor Telepon Peserta Wajib Diisi!',
            'telepon.numeric' => 'Nomor Telepon Peserta Wajib Diisi! dengan Angka',
            'telepon.digits_between' => 'Nomor Telepon Peserta Tidak boleh melebihi 13 digit!',
            'berat_badan.required' => 'Berat Badan Peserta Wajib Diisi!',

            'keterangan.required' => 'Keterangan Wajib Diisi!',
            'pertandingan_id.required' => 'Pertandingan Wajib Diisi!',
            'foto.required' => 'Foto Peserta Wajib Diisi!',
            'foto.max' => 'Foto Peserta tidak boleh sizenya lebih dari 1mb, Mohon dicompres dulu!',
        ]);



        if ($validator->fails()) {
            // dd($validator->errors()->keys());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Lengkapi Form yang tidak terisi!');
        }

        if(!$request->nama_tanding) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Uratan Komposisi tidak boleh kosong!');
        }



            $peserta = Peserta::create([
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
                'berat_badan'=> $request->berat_badan,
                'keterangan'=> $request->keterangan,
                'foto' => 'lol',
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'disabilitas' => $request->disabilitas,
                'tingkatan_sabuk' => $request->tingkatan_sabuk_a."-".$request->tingkatan_sabuk_b,
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


            $peserta_image = Peserta::find($peserta->id);
            $peserta_image->foto = 'storage/foto_peserta/' . $filename;
            $peserta_image->save();


            foreach($request->pertandingan_id as $index => $a) {
                $existing = PertandinganPeserta::where('list_kategori_id', $a)
                ->orderBy('urutan')
                ->pluck('urutan')
                ->toArray();

                // Cari urutan kosong
                $urutanBaru = 1;
                foreach ($existing as $num) {
                    if ($num == $urutanBaru) {
                        $urutanBaru++;
                    } else {
                        break; // lubang ditemukan
                    }
                }

                $PPA = PertandinganPeserta::create([
                    'peserta_id' => $peserta->id,
                    'list_kategori_id' => $a,
                    'urutan' => $urutanBaru,
                    'kegiatan_id' => $request->kegiatan_id
                ]);

                $UKP = new UrutanKomposisiPeserta();
                $UKP->pertandingan_peserta_id = $PPA->id;
                $UKP->nama = $request->nama_tanding[$a] ?? "-";
                $UKP->tingkat = $request->tingkat_tanding[$a] ?? "-";
                $deskripsiUKP = "";
                foreach($request->urutan_tanding[$a] as $b) {
                    $deskripsiUKP .= $b ?? "-".",";
                }
                $UKP->urutan_komposisi = $deskripsiUKP;
                $UKP->save();
            }


            return back()->with('success', 'Peserta telah terdaftar, mohon tunggu konfirmasi berikutnya!');

        try {

        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('gagal', 'Terjadi kesalahan saat menyimpan data!')
                ->withInput();
        }
    }


    public function home() {
        return view('admin.dashboard');
    }

    public function login() {
        return view('admin.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            // Ambil user yang sedang login
            $user = Auth::user();

            // Cek apakah email sudah diverifikasi
            if (is_null($user->email_verified_at) && $user->role != 0) {
                Auth::logout(); // langsung logout
                return back()->withErrors([
                    'error' => 'Email Anda belum diverifikasi. Mohon bersabar untuk menerima konfirmasi Whatsapp yang akan diberikan kepada anda!.',
                ]);
            }

            // Kalau verified, lanjut
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function register() {
        return view('admin.register');
    }

    public function register_proses(Request $request) {
        $credentials = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'no_wa' => ['required', 'numeric'],
            'password' => [
                'required',
                'same:konfirmasi_password',
                'min:8',                 // minimal 8 karakter
                'max:20',                // maksimal 20 karakter (opsional)
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/' // regex: harus ada huruf kecil, huruf besar, angka, dan simbol
            ],
            'konfirmasi_password' => ['required', 'same:password'],
        ],[
            'name.required' => 'Nama Lengkap wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Email harus diisi dengan format email diisi!',

            'no_wa.required' => 'Nomor Whatsapp wajib diisi!',
            'no_wa.numeric' => 'Nomor Whatsapp harus pake angka!',

            'password.required' => 'Password wajib diisi!',
            'password.same' => 'Password harus sama dengan Konfirmasi Password!',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
            'password.min'   => 'Password minimal 8 karakter.',
            'password.max'   => 'Password maksimal 20 karakter.',

            'konfirmasi_password.required' => 'Konfirmasi Password wajib diisi!',
            'konfirmasi_password.same' => 'Konfirmasi Password harus sama dengan Password!',

        ]);


        try {

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'no_wa' => $request->no_wa,
                'password' => Hash::make($request->password),
                'role' => 0,
            ]);
            return redirect('/')->with('success', 'Registrasi telah Berhasil mohon tunggu akan dikonfirmasi ke Whatsapp anda!!');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', 'Terjadi Kesalahan!');

        }


    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
