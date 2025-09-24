<?php

namespace App\Http\Controllers;

use App\Helpers\WaHelpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->role != 1) {
            return redirect('/');
        }
        if(isset($_GET['cari'])) {
            $data['users'] = User::where('name','like','%'.$_GET['cari'].'%')->orWhere('email','like','%'.$_GET['cari'].'%')->paginate(10);

        } else {
            $data['users'] = User::paginate(10);

        }
        return view('admin.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
       if(Auth::user()->role != 1) {
            return redirect('/');
        }
        return view('admin.users.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        if(Auth::user()->role != 1) {
            return redirect('/');
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'no_wa' => ['required', 'numeric'],
        ],[
            'name.required' => 'Nama Lengkap wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Email harus diisi dengan format email diisi!',

            'no_wa.required' => 'Nomor Whatsapp wajib diisi!',
            'no_wa.numeric' => 'Nomor Whatsapp harus pake angka!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'no_wa' => $request->no_wa,
                'email_verified_at' => $request->verifikasi == 1 ? date('Y-m-d h:i:s') : null,
                'password' => Hash::make($request->password),
            ]);

            return redirect('users')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            //throw $th;
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
        if(Auth::user()->role != 1) {
            return redirect('/');
        }
        $data['user'] = User::find($id);
        return view('admin.users.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        if(Auth::user()->role != 1) {
            return redirect('/');
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'no_wa' => ['required', 'numeric'],
        ],[
            'name.required' => 'Nama Lengkap wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Email harus diisi dengan format email diisi!',

            'no_wa.required' => 'Nomor Whatsapp wajib diisi!',
            'no_wa.numeric' => 'Nomor Whatsapp harus pake angka!',
        ]);



        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }




            $checkValidasi = null;
            $user = User::find($id);


            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_wa = $request->no_wa;
            $user->role = $request->role;


            if($user->email_verified_at != null) {
                $checkValidasi = 1;
            } else {
                $checkValidasi = 0;

            }

            $user->email_verified_at = $request->verifikasi == 1 ? date('Y-m-d h:i:s') : null;

            if($request->password) {
                $user->password = $request->password;
            }
            $user->save();

            if($request->verifikasi != $checkValidasi) {
                if($request->verifikasi == 0) {
                    WaHelpers::sendEmail($request->email, 'Akun registrasi anda telah ditolak!');
                    WaHelpers::sendWa($request->no_wa, 'Akun registrasi anda telah ditolak!');
                } else {
                    WaHelpers::sendEmail($request->email, 'Akun Registrasi anda telah diterima!');
                    WaHelpers::sendWa($request->no_wa, 'Akun Registrasi anda telah diterima!');
                }
            }

            return redirect('users')->with('success', 'Data Berhasil Diubah');
                    try {
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
        if(Auth::user()->role != 1) {
            return redirect('/');
        }
        try {
            $user = User::find($id);
            $user->delete();

            return redirect('users')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
