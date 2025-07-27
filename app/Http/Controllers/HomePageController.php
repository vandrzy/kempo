<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Penulis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePageController extends Controller
{
    //
    public function index() {
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

        return view('pengunjung.dashboard', $data);
    }

    public function detail($id) {
        $data['buku'] = Buku::find($id);
        return view('pengunjung.detail', $data);
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

        return redirect('/login');
    }

}
