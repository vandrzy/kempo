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
use App\Helpers\FotoHelper;

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



            $oldPeserta = null;
            if ($request->filled('existing_reference_id')) {
                $oldPeserta = Peserta::find($request->existing_reference_id);
            }

            $unlocked = [];
            if ($request->filled('unlocked_fields')) {
                $unlocked = is_array($request->unlocked_fields) 
                    ? $request->unlocked_fields 
                    : explode(',', $request->unlocked_fields);
                $unlocked = array_map('trim', $unlocked);
            }

            // Helper untuk mengambil nilai: jika ada data lama dan field TIDAK di-unlock oleh user, ambil dari data lama
            $getValue = function($field, $default = null) use ($request, $oldPeserta, $unlocked) {
                if ($oldPeserta && !in_array($field, $unlocked)) {
                    return $oldPeserta->$field ?? $default;
                }
                return $request->$field ?? $default;
            };

            $nik = $getValue('nik', $request->nik);
            $jenis_kelamin = $getValue('jenis_kelamin', $request->jenis_kelamin);
            $golongan_darah = $getValue('golongan_darah', $request->golongan_darah);
            $status = $getValue('status', $request->status ?? 'P');
            $tempat = $getValue('tempat', $request->tempat);
            $alamat = $getValue('alamat', $request->alamat);
            $telepon = $getValue('telepon', $request->telepon);
            $keterangan = $getValue('keterangan', $request->keterangan);
            $kabupaten_id = $getValue('kabupaten_id', $request->kabupaten_id);
            $kecamatan_id = $getValue('kecamatan_id', $request->kecamatan_id);
            $kelurahan_id = $getValue('kelurahan_id', $request->kelurahan_id);
            $disabilitas = $getValue('disabilitas', $request->disabilitas);

            // Foto: jika upload file baru, simpan file baru. Jika tidak, pakai foto lama.
            $fotoPath = '';
            if ($request->hasFile('foto')) {
                $fotoPath = FotoHelper::simpanFoto($request->file('foto')) ?? '';
            } elseif ($oldPeserta && !empty($oldPeserta->foto)) {
                $fotoPath = $oldPeserta->foto;
            } elseif (!empty($request->existing_foto) && $request->existing_foto !== 'USE_OLD_PHOTO') {
                $fotoPath = $request->existing_foto;
            }

            $dataPeserta = [
                'nama' => $request->nama,
                'jenis_kelamin' => $jenis_kelamin,
                'golongan_darah' => $golongan_darah,
                'nik' => $nik,
                'status' => $status,
                'tempat' => $tempat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $alamat,
                'telepon' => $telepon,
                'berat_badan' => $request->berat_badan,
                'keterangan' => $keterangan,
                'foto' => $fotoPath,
                'kabupaten_id' => $kabupaten_id,
                'kecamatan_id' => $kecamatan_id,
                'kelurahan_id' => $kelurahan_id,
                'disabilitas' => $disabilitas,
                'tingkatan_sabuk' => $request->tingkatan_sabuk_a . "-" . $request->tingkatan_sabuk_b,
            ];

            if ($oldPeserta) {
                // Update peserta lama agar tidak terjadi duplikasi data (data foto lama vs baru)
                $oldPeserta->update($dataPeserta);
                $peserta = $oldPeserta;
            } else {
                $peserta = Peserta::create($dataPeserta);
            }

            if ($request->has('pertandingan_id') && is_array($request->pertandingan_id)) {
                foreach($request->pertandingan_id as $index => $a) {
                    // Hindari duplikasi nomor tanding yang sama untuk peserta yang sama
                    $alreadyInPertandingan = PertandinganPeserta::where('peserta_id', $peserta->id)
                        ->where('list_kategori_id', $a)
                        ->first();

                    if ($alreadyInPertandingan) {
                        continue;
                    }
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
                    $UKP->urutan_komposisi = $deskripsiUKP;
                    $UKP->save();
                }
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

            // Cek apakah akun sudah diverifikasi oleh admin
            if (is_null($user->email_verified_at)) {
                Auth::logout(); // langsung logout
                return back()->with('error', 'Akun Anda belum diverifikasi oleh admin. Mohon tunggu verifikasi terlebih dahulu.')
                             ->withErrors([
                                 'email' => 'Akun Anda belum diverifikasi oleh admin. Mohon tunggu verifikasi terlebih dahulu.',
                             ])
                             ->withInput($request->only('email'));
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
                'role' => 'operator',
            ]);
            return redirect('/')->with('success', 'Mohon tunggu, data anda akan diverifikasi admin.');
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
            // Cari 1 data official yang paling sesuai
            $record = Official::with('kegiatan')
                ->whereRaw('LOWER(TRIM(nama)) = ?', [strtolower($nama)])
                ->latest()
                ->first();

            if (!$record) {
                $record = Official::with('kegiatan')
                    ->where('nama', 'LIKE', "{$nama}%")
                    ->latest()
                    ->first();
            }

            if (!$record) {
                $record = Official::with('kegiatan')
                    ->where('nama', 'LIKE', "%{$nama}%")
                    ->latest()
                    ->first();
            }

            if (!$record) {
                return response()->json(['found' => false, 'items' => []]);
            }

            $namaKegiatan = $record->kegiatan->nama ?? 'Kegiatan Sebelumnya';
            $tingkat = explode('-', $record->tingkat ?? '');
            $isGuest = !Auth::check();

            $personalData = [
                'nama' => $record->nama,
                'tingkatan_sabuk_a' => $tingkat[0] ?? '',
                'tingkatan_sabuk_b' => $tingkat[1] ?? '',
            ];

            if (!$isGuest) {
                $personalData['jenis_kelamin'] = $record->jenis_kelamin;
                $personalData['golongan_darah'] = $record->golongan_darah;
                $personalData['nik'] = $record->nik;
                $personalData['tempat'] = $record->tempat;
                $personalData['tanggal_lahir'] = $record->tanggal_lahir ? date('Y-m-d', strtotime($record->tanggal_lahir)) : '';
                $personalData['alamat'] = $record->alamat;
                $personalData['telepon'] = $record->telepon;
                $personalData['keterangan'] = $record->keterangan;
                $personalData['sertifikat'] = $record->sertifikat;
                $personalData['kabupaten_id'] = $record->kabupaten_id;
            }

            $item = [
                'id' => $record->id,
                'nama' => $record->nama,
                'peran' => 'official',
                'nama_kegiatan' => $namaKegiatan,
                'foto_url' => !empty($record->foto) ? url($record->foto) : '',
                'foto_path' => $record->foto ?? '',
                'is_guest' => $isGuest,
                'personal_data' => $personalData,
            ];

            return response()->json([
                'found' => true,
                'items' => [$item]
            ]);
        }

        // Tipe 'peserta' - Hanya mengambil 1 data yang paling sesuai
        $record = Peserta::whereRaw('LOWER(TRIM(nama)) = ?', [strtolower($nama)])
            ->latest()
            ->first();

        if (!$record) {
            $record = Peserta::where('nama', 'LIKE', "{$nama}%")
                ->latest()
                ->first();
        }

        if (!$record) {
            $record = Peserta::where('nama', 'LIKE', "%{$nama}%")
                ->latest()
                ->first();
        }

        if (!$record) {
            return response()->json(['found' => false, 'items' => []]);
        }

        $lastPivot = $record->PertandinganPeserta()->with('kegiatan')->latest()->first();
        $namaKegiatan = $lastPivot?->kegiatan?->nama ?? 'Kegiatan Sebelumnya';
        $tingkat = explode('-', $record->tingkatan_sabuk ?? '');
        $isGuest = !Auth::check();

        // Untuk tamu / user yang tidak login: hanya berikan 4 data yang boleh dilihat/diedit
        // (nama, tingkatan sabuk/grade, berat badan, tanggal lahir)
        $personalData = [
            'nama' => $record->nama,
            'tingkatan_sabuk_a' => $tingkat[0] ?? '',
            'tingkatan_sabuk_b' => $tingkat[1] ?? '',
            'berat_badan' => $record->berat_badan,
            'tanggal_lahir' => $record->tanggal_lahir ? date('Y-m-d', strtotime($record->tanggal_lahir)) : '',
        ];

        // Jika user login (admin/operator): sediakan semua data asli
        if (!$isGuest) {
            $personalData['jenis_kelamin'] = $record->jenis_kelamin;
            $personalData['golongan_darah'] = $record->golongan_darah;
            $personalData['nik'] = $record->nik;
            $personalData['status'] = $record->status ?? 'P';
            $personalData['tempat'] = $record->tempat;
            $personalData['alamat'] = $record->alamat;
            $personalData['telepon'] = $record->telepon;
            $personalData['keterangan'] = $record->keterangan;
            $personalData['kabupaten_id'] = $record->kabupaten_id;
            $personalData['kecamatan_id'] = $record->kecamatan_id;
            $personalData['kelurahan_id'] = $record->kelurahan_id;
            $personalData['disabilitas'] = $record->disabilitas;
        }

        $item = [
            'id' => $record->id,
            'nama' => $record->nama,
            'peran' => 'peserta',
            'nama_kegiatan' => $namaKegiatan,
            'foto_url' => !empty($record->foto) ? url($record->foto) : '',
            'foto_path' => $record->foto ?? '',
            'is_guest' => $isGuest,
            'personal_data' => $personalData,
        ];

        return response()->json([
            'found' => true,
            'items' => [$item]
        ]);
    }
}
