<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kabupaten;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Kegiatan;
use App\Models\Kelurahan;
use App\Models\ListKategori;
use App\Models\Official;
use App\Models\Penulis;
use App\Models\Pertandingan;
use App\Models\PertandinganPeserta;
use App\Models\Peserta;
use App\Models\UrutanKomposisiPeserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function teknik_input($id) {
        $kegiatan_id = explode('-', $id);

        $data['kegiatan'] = Kegiatan::find(base64_decode($kegiatan_id[1]));
        $data['kabupatens'] = Kabupaten::get();
        $data['kategoris'] = ListKategori::with('kategori')->where('kegiatan_id', base64_decode($kegiatan_id[1]))->get()->groupBy('kategori_id');
        // dd($data['kategoris']);
        if(isset($_GET['pertandingan_id'])) {
            $data['back'] = $id;
            $kegiatan_id = $_GET['kegiatan_id'];
            $kabupaten_id = $_GET['kabupaten_id'];
            $kategori_id = $_GET['kategori_id'];
            $pertandingan_id = $_GET['pertandingan_id'];

            $data['kabupaten'] = Kabupaten::find($kabupaten_id);



            $data['pesertas'] = Peserta::with([
                'PertandinganPeserta' => function ($q) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                    $q->where('kegiatan_id', $kegiatan_id)
                    ->whereHas('list_kategori', function ($w) use ($kategori_id, $pertandingan_id, $kegiatan_id) {
                        $w->where('pertandingan_id', $pertandingan_id)
                            ->where('kategori_id', $kategori_id)
                            ->where('kegiatan_id', $kegiatan_id);
                    });
                },
                'PertandinganPeserta.list_kategori' => function ($q) use ($kategori_id, $pertandingan_id, $kegiatan_id) {
                    $q->where('pertandingan_id', $pertandingan_id)
                    ->where('kategori_id', $kategori_id)
                    ->where('kegiatan_id', $kegiatan_id);
                }
            ])
            ->whereHas('PertandinganPeserta', function ($q) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                $q->where('kegiatan_id', $kegiatan_id)
                ->whereHas('list_kategori', function ($w) use ($kategori_id, $pertandingan_id, $kegiatan_id) {
                    $w->where('pertandingan_id', $pertandingan_id)
                        ->where('kategori_id', $kategori_id)
                        ->where('kegiatan_id', $kegiatan_id);
                });
            })
            ->where('kabupaten_id', $kabupaten_id)->get();

            // dd($data['peserta']);
        }

        $tahun = old('tanggal_lahir');
        $kelamin = old('jenis_kelamin');
        // $data['peserta_id'] = $id;


        return view('pengunjung.teknik', $data);
    }

    public function teknik_cari_peserta(Request $request) {
        if($request->kabupaten_id && $request->kegiatan_id && $request->kategori_id && $request->pertandingan_id) {

            $kegiatan = Kegiatan::find($request->kegiatan_id);
            $data['kategori'] = ListKategori::with('kategori')->where('kegiatan_id', $kegiatan->id)->get()->unique('kategori_id')->values();

            $data['peserta'] = Peserta::where('status', 'P')->where('kabupaten_id', $request->kabupaten_id)->whereNotNull('created_at');

            $kegiatan_id = $kegiatan->id;
            $kategori_id = $request->kategori_id;
            $pertandingan_id = $request->pertandingan_id;


            $data['peserta'] = $data['peserta']->WhereHas('PertandinganPesertaUtama', function ($q1) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                $q1->WhereHas('list_kategori', function ($q2) use ($kegiatan_id, $kategori_id, $pertandingan_id) {
                    $q2 = $q2->where('kegiatan_id', $kegiatan_id);

                    if(isset($kategori_id)  && $kategori_id != "") {
                        $q2 = $q2->where('kategori_id', $kategori_id);
                    }

                    if(isset($pertandingan_id)  && $pertandingan_id != "") {
                        $q2 = $q2->where('pertandingan_id', $pertandingan_id);
                    }
                });
            });

            $data['peserta'] = $data['peserta']->get();

            return response()->json([
                'message' => 'Data tersedia!',
                'peserta' => $data['peserta'],
            ]);
        } else {
            return response()->json([
                'message' => 'Isi semua form dulu',
                'peserta' => [],
            ]);
        }

    }

    public function teknik_process(Request $request, $id) {
            foreach($request->pertandingan_peserta_id as $a => $index) {
                if($request->komposisi_id[$a] == "") {
                    // dd($request->nama_tanding[$a]);

                    $UKP = new UrutanKomposisiPeserta();
                    $UKP->pertandingan_peserta_id = $index;
                    $UKP->nama = $request->nama_tanding[$a] ?? "-";
                    $UKP->tingkat = $request->tingkat_tanding[$a] ?? "-";
                    $deskripsiUKP = "";
                    foreach($request->urutan_tanding[0] as $b) {
                        if($b != "") {
                            $deskripsiUKP .= ($b ?? "-").",";
                        }
                    }
                    $UKP->urutan_komposisi = $deskripsiUKP;
                    $UKP->save();
                } else {
                    // dd($request->komposisi_id[$a]);
                    $UKP = UrutanKomposisiPeserta::find($request->komposisi_id[$a]);
                    $UKP->pertandingan_peserta_id = $index;
                    $UKP->nama = $request->nama_tanding[$a] ?? "-";
                    $UKP->tingkat = $request->tingkat_tanding[$a] ?? "-";
                    $deskripsiUKP = "";

                    // dd($request->urutan_tanding);
                    foreach($request->urutan_tanding[0] as $b) {
                        if($b != "") {
                            $deskripsiUKP .= ($b ?? "-").",";
                        }
                    }
                    $UKP->urutan_komposisi = $deskripsiUKP;
                    $UKP->save();
                }

            }


            return back()->with('success', 'Tahapan Teknik berhasil diinput');
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
                // foreach($request->urutan_tanding[$a] as $b) {
                //     if($b != "") {
                //         $deskripsiUKP .= ($b ?? "-").",";
                //     }
                // }
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


    public function home(Request $request) {
        if (Auth::user()->role === 'wasit') {
            return redirect('/kegiatan');
        }

        // Ambil semua kegiatan (urutkan paling awal dulu: default data paling awal)
        $kegiatans = Kegiatan::orderBy('id', 'asc')->get();

        // Default: data paling awal
        $defaultKegiatan = $kegiatans->first();
        $selectedKegiatanId = $request->get('kegiatan_id');

        if (!$selectedKegiatanId && $defaultKegiatan) {
            $selectedKegiatanId = $defaultKegiatan->id;
        }

        $kegiatanAktif = $kegiatans->firstWhere('id', $selectedKegiatanId) ?? $defaultKegiatan;
        $selectedKegiatanId = $kegiatanAktif ? $kegiatanAktif->id : null;

        $jumlahPeserta = 0;
        $jumlahOfficial = 0;
        $totalSeluruhMedali = 0;
        $totalMedaliEmas = 0;
        $totalMedaliPerak = 0;
        $totalMedaliPerunggu = 0;
        $totalKategori = 0;
        $klasemen = [];

        if ($selectedKegiatanId) {
            // Jumlah Peserta terdaftar pada kegiatan yang dipilih
            $jumlahPeserta = Peserta::where('status', 'P')
                ->whereHas('PertandinganPeserta', function ($q) use ($selectedKegiatanId) {
                    $q->where('kegiatan_id', $selectedKegiatanId);
                })->count();

            // Jumlah Official pada kegiatan yang dipilih
            $jumlahOfficial = Official::where('kegiatan_id', $selectedKegiatanId)->count();

            // Klasemen dan statistik medali dari helper rekapitulasi kegiatan
            $rekap = PertandinganController::getRekapitulasiData($selectedKegiatanId);
            $klasemen = $rekap['klasemen'] ?? [];
            $totalSeluruhMedali = $rekap['total_seluruh_medali'] ?? 0;
            $totalMedaliEmas = $rekap['total_medali_emas'] ?? 0;
            $totalMedaliPerak = $rekap['total_medali_perak'] ?? 0;
            $totalMedaliPerunggu = $rekap['total_medali_perunggu'] ?? 0;
            $totalKategori = $rekap['total_kategori'] ?? 0;
        }

        return view('admin.dashboard', compact(
            'kegiatans',
            'kegiatanAktif',
            'selectedKegiatanId',
            'jumlahPeserta',
            'jumlahOfficial',
            'totalSeluruhMedali',
            'totalMedaliEmas',
            'totalMedaliPerak',
            'totalMedaliPerunggu',
            'totalKategori',
            'klasemen'
        ));
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
            if (is_null($user->email_verified_at) && $user->role != 0 && $user->role !== 'user') {
                Auth::logout(); // langsung logout
                return back()->withErrors([
                    'error' => 'Email Anda belum diverifikasi. Mohon bersabar untuk menerima konfirmasi Whatsapp yang akan diberikan kepada anda!.',
                ]);
            }

            // Kalau verified, lanjut
            $request->session()->regenerate();

            if ($user->role === 'wasit') {
                return redirect('/kegiatan');
            } elseif ($user->role === 'admin' || $user->role == 1) {
                return redirect()->intended('/dashboard');
            } else {
                return redirect()->intended('/dashboard');
            }
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
                'role' => 'user',
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

    /**
     * API pencarian & auto-fill data lama pendaftar (Peserta & Official)
     */
    public function checkExistingPendaftar(Request $request)
    {
        $nama = trim($request->query('nama', ''));
        $tipe = $request->query('tipe', 'peserta'); // 'peserta' atau 'official'

        if (strlen($nama) < 3) {
            return response()->json(['found' => false, 'items' => []]);
        }

        if ($tipe === 'official') {
            $records = Official::with('kegiatan')
                ->where('nama', 'LIKE', "%{$nama}%")
                ->latest()
                ->limit(10)
                ->get()
                ->unique(fn ($item) => strtolower(trim($item->nama)))
                ->take(3);

            $items = $records->map(function ($row) {
                $namaKegiatan = $row->kegiatan->nama ?? 'Kegiatan Sebelumnya';
                $tingkat = explode('-', $row->tingkat ?? '');

                return [
                    'id' => $row->id,
                    'nama' => $row->nama,
                    'peran' => 'official',
                    'nama_kegiatan' => $namaKegiatan,
                    'personal_data' => [
                        'nama' => $row->nama,
                        'jenis_kelamin' => $row->jenis_kelamin,
                        'golongan_darah' => $row->golongan_darah,
                        'nik' => $row->nik,
                        'tempat' => $row->tempat,
                        'tanggal_lahir' => $row->tanggal_lahir ? date('Y-m-d', strtotime($row->tanggal_lahir)) : '',
                        'alamat' => $row->alamat,
                        'telepon' => $row->telepon,
                        'keterangan' => $row->keterangan,
                        'sertifikat' => $row->sertifikat,
                        'kabupaten_id' => $row->kabupaten_id,
                        'tingkatan_sabuk_a' => $tingkat[0] ?? '',
                        'tingkatan_sabuk_b' => $tingkat[1] ?? '',
                    ],
                ];
            })->values();

            return response()->json([
                'found' => $items->isNotEmpty(),
                'items' => $items
            ]);
        }

        // Tipe 'peserta'
        $records = Peserta::where('nama', 'LIKE', "%{$nama}%")
            ->latest()
            ->limit(10)
            ->get()
            ->unique(fn ($item) => strtolower(trim($item->nama)) . ($item->tanggal_lahir ?? ''))
            ->take(3);

        $items = $records->map(function ($row) {
            $lastPivot = $row->PertandinganPeserta()->with('kegiatan')->latest()->first();
            $namaKegiatan = $lastPivot?->kegiatan?->nama ?? 'Kegiatan Sebelumnya';
            $tingkat = explode('-', $row->tingkatan_sabuk ?? '');

            return [
                'id' => $row->id,
                'nama' => $row->nama,
                'peran' => 'peserta',
                'nama_kegiatan' => $namaKegiatan,
                'personal_data' => [
                    'nama' => $row->nama,
                    'jenis_kelamin' => $row->jenis_kelamin,
                    'golongan_darah' => $row->golongan_darah,
                    'nik' => $row->nik,
                    'status' => $row->status ?? 'P',
                    'tempat' => $row->tempat,
                    'tanggal_lahir' => $row->tanggal_lahir ? date('Y-m-d', strtotime($row->tanggal_lahir)) : '',
                    'alamat' => $row->alamat,
                    'telepon' => $row->telepon,
                    'berat_badan' => $row->berat_badan,
                    'keterangan' => $row->keterangan,
                    'kabupaten_id' => $row->kabupaten_id,
                    'kecamatan_id' => $row->kecamatan_id,
                    'kelurahan_id' => $row->kelurahan_id,
                    'disabilitas' => $row->disabilitas,
                    'tingkatan_sabuk_a' => $tingkat[0] ?? '',
                    'tingkatan_sabuk_b' => $tingkat[1] ?? '',
                ],
            ];
        })->values();

        return response()->json([
            'found' => $items->isNotEmpty(),
            'items' => $items
        ]);
    }
}
