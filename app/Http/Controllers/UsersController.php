<?php

namespace App\Http\Controllers;

use App\Helpers\WaHelpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role != 1) {
            abort(403, 'Unauthorized');
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
        if (Auth::user()->role !== 'admin' && Auth::user()->role != 1) {
            abort(403, 'Unauthorized');
        }
        return view('admin.users.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role != 1) {
            abort(403, 'Unauthorized');
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'no_wa' => ['required', 'numeric'],
            'role' => ['required', 'in:admin,operator,wasit,1,2,user'],
        ],[
            'name.required' => 'Nama Lengkap wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Email harus diisi dengan format email yang valid!',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain!',
            'no_wa.required' => 'Nomor Whatsapp wajib diisi!',
            'no_wa.numeric' => 'Nomor Whatsapp harus pake angka!',
            'role.required' => 'Role Pengguna wajib dipilih!',
            'role.in' => 'Role Pengguna tidak valid!',
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
                'role' => $request->role,
                'email_verified_at' => $request->verifikasi == 1 ? now() : null,
                'password' => Hash::make($request->password),
            ]);

            return redirect('users')->with('success', 'Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            Log::error('Terjadi kesalahan saat simpan user baru: ' . $th->getMessage());
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
        if (Auth::user()->role !== 'admin' && Auth::user()->role != 1) {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role != 1) {
            abort(403, 'Unauthorized');
        }
        $data['user'] = User::find($id);
        return view('admin.users.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role != 1) {
            abort(403, 'Unauthorized');
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'no_wa' => ['required', 'numeric'],
            'role' => ['required', 'in:admin,operator,wasit,1,2,user'],
            'verifikasi' => ['nullable', 'in:0,1'],
        ],[
            'name.required' => 'Nama Lengkap wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Email harus diisi dengan format email yang valid!',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain!',
            'no_wa.required' => 'Nomor Whatsapp wajib diisi!',
            'no_wa.numeric' => 'Nomor Whatsapp harus pake angka!',
            'role.required' => 'Role Pengguna wajib dipilih!',
            'role.in' => 'Role Pengguna tidak valid!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Data Gagal Disimpan!');
        }

        try {
            $user = User::find($id);
            if (!$user) {
                return redirect('users')->with('gagal', 'Data pengguna tidak ditemukan!');
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_wa = $request->no_wa;
            $user->role = $request->role;

            $checkValidasi = !is_null($user->email_verified_at) ? 1 : 0;
            $newVerifikasi = (int) $request->verifikasi;

            if ($newVerifikasi === 1) {
                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                }
            } else {
                $user->email_verified_at = null;
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // Kirim notifikasi secara aman (kegagalan kirim email/WA tidak membatalkan penyimpanan data)
            if ($newVerifikasi !== $checkValidasi) {
                try {
                    if ($newVerifikasi === 0) {
                        WaHelpers::sendEmail($request->email, 'Akun registrasi anda telah ditolak!');
                        WaHelpers::sendWa($request->no_wa, 'Akun registrasi anda telah ditolak!');
                    } else {
                        WaHelpers::sendEmail($request->email, 'Akun Registrasi anda telah diterima!');
                        WaHelpers::sendWa($request->no_wa, 'Akun Registrasi anda telah diterima!');
                    }
                } catch (\Throwable $notifEx) {
                    Log::warning('Gagal mengirim notifikasi email/WA verifikasi user: ' . $notifEx->getMessage());
                }
            }

            return redirect('users')->with('success', 'Data Berhasil Diubah');
        } catch (\Throwable $th) {
            Log::error('Terjadi kesalahan saat update user: ' . $th->getMessage(), [
                'exception' => $th
            ]);
            return redirect()->back()
                ->with('gagal', 'Terjadi kesalahan saat menyimpan data: ' . (config('app.debug') ? $th->getMessage() : 'Silakan periksa kembali data Anda.'))
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role != 1) {
            abort(403, 'Unauthorized');
        }
        try {
            $user = User::find($id);
            if ($user) {
                $user->delete();
            }

            return redirect('users')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            Log::error('Terjadi kesalahan saat hapus user: ' . $th->getMessage());
            return redirect()->back()->with('gagal', 'Terjadi kesalahan saat menghapus data!');
        }
    }
}
