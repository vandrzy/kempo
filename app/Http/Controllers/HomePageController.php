<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Penulis;
use App\Models\Pertandingan;
use App\Models\PertandinganPeserta;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomePageController extends Controller
{
    //
    public function index() {

        $tahun = old('tanggal_lahir');
        $kelamin = old('jenis_kelamin');

        if ($tahun && $kelamin) {
            $data['pertandingan'] = Pertandingan::whereHas('kategori', function($query) use ($tahun) {
                $query->where('tahun_awal', '<=', Date('Y', strtotime($tahun)))->where('tahun_akhir', '>=', Date('Y', strtotime($tahun)));
            })->where(function($query) use ($kelamin) {
                $query->where('jenis_kelamin', $kelamin)
                    ->orWhereNull('jenis_kelamin');
            })->get();
        } else {
            $data['pertandingan'] = Pertandingan::get();
        }


        return view('pengunjung.dashboard', $data);
    }

    public function daftarProcess(Request $request) {

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jenis_kelamin'=> 'required',
            'golongan_darah'=> 'required',
            'nik'=> 'required|unique:pesertas,nik',
            'tingkat'=> 'required',
            'status'=> 'required',
            'tempat'=> 'required',
            'tanggal_lahir'=> 'required',
            'alamat'=> 'required',
            'telepon'=> 'required',
            'keterangan'=> 'required',
            'pertandingan_id'=> 'required',
            'foto'=> 'required',
        ], [
            'nama.required' => 'Nama Peserta Wajib Diisi!',
            'jenis_kelamin.required' => 'Jenis Kelamin Wajib Diisi!',
            'golongan_darah.required' => 'Golongan Darah Wajib Diisi!',
            'nik.required' => 'NIK Wajib Diisi!',
            'tingkat.required' => 'Tingkat Wajib Diisi!',
            'status.required' => 'Status Wajib Diisi!',
            'tempat.required' => 'Tempat Lahir Wajib Diisi!',
            'tanggal_lahir.required' => 'Tanggal Lahir Wajib Diisi!',
            'alamat.required' => 'Alamat Peserta Wajib Diisi!',
            'telepon.required' => 'Nomor Telepon Peserta Wajib Diisi!',
            'keterangan.required' => 'Keterangan Wajib Diisi!',
            'pertandingan_id.required' => 'Pertandingan Wajib Diisi!',
            'foto.required' => 'Foto Peserta Wajib Diisi!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Lengkapi Form yang tidak terisi!');
        }

            
            $peserta = Peserta::create([
                'nama' => $request->nama,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'golongan_darah'=> $request->golongan_darah,
                'nik'=> $request->nik,
                'tingkat'=> $request->tingkat,
                'status'=> $request->status,
                'tempat'=> $request->tempat,
                'tanggal_lahir'=> $request->tanggal_lahir,
                'alamat'=> $request->alamat,
                'telepon'=> $request->telepon,
                'keterangan'=> $request->keterangan,
                'foto' => 'storage/'.$request->foto->store('foto_peserta', 'public'),
            ]);

            foreach($request->pertandingan_id as $a) {
                PertandinganPeserta::create([
                    'peserta_id' => $peserta->id,
                    'pertandingan_id' => $a,
                ]);
            }

            return redirect('/')->with('success', 'Peserta telah terdaftar, mohon tunggu konfirmasi berikutnya!');

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
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // ganti dengan halaman tujuan
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
