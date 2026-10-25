@extends('tamplate')
@section('title', 'Detail Peserta')
@section('content')
    <div class="space-y-6">
        <!-- Top Bar Header Card -->
        <div class="kempo-card p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                @if(isset($_GET['kontigen']))
                    <a href="{{ url('kontingen/'.$_GET['kontigen'].'?kegiatan_id='.$_GET['ehh']) }}" class="btn-kempo-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                @else
                    <a href="{{ url('peserta') }}" class="btn-kempo-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                @endif
                <div>
                    <h2 class="text-xl font-heading font-bold text-foreground mb-0">Detail Peserta</h2>
                    <p class="text-xs text-paragraph mt-0.5">Informasi profil lengkap dan nomor pertandingan atlet</p>
                </div>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-accent text-primary border border-primary/20">
                    <i class="fa fa-user"></i> {{ $peserta->status == "P" ? "Peserta / Atlet" : "Official" }}
                </span>
            </div>
        </div>

        <!-- 2 Columns: Foto & Biodata -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Foto Peserta -->
            <div class="lg:col-span-5">
                <div class="kempo-card h-full flex flex-col">
                    <div class="p-5 border-b border-input">
                        <h3 class="text-sm font-heading font-bold uppercase tracking-wider text-neutral-600 flex items-center gap-2 mb-0">
                            <i class="fa fa-image text-primary"></i> Foto Peserta
                        </h3>
                    </div>
                    <div class="p-6 flex-1 flex flex-col items-center justify-center bg-neutral-50/40">
                        @if(@$peserta && $peserta->foto)
                            <div class="relative group rounded-2xl overflow-hidden border border-input shadow-md bg-white p-2">
                                <img src="{{ url($peserta->foto) }}" alt="Foto Peserta" class="max-h-96 w-auto object-cover rounded-xl" id="preview">
                            </div>
                        @else
                            <div class="w-48 h-64 rounded-2xl border-2 border-dashed border-input flex flex-col items-center justify-center text-neutral-400">
                                <i class="fa fa-user-circle text-5xl mb-2"></i>
                                <span class="text-xs font-medium">Foto Tidak Tersedia</span>
                            </div>
                        @endif
                        <h4 class="font-heading font-bold text-foreground text-base mt-4 text-center">{{ $peserta->nama }}</h4>
                        <p class="text-xs text-paragraph mb-0 text-center">{{ $peserta->kabupaten->name ?? 'Tanpa Kontingen' }}</p>
                    </div>
                </div>
            </div>

            <!-- Biodata Table -->
            <div class="lg:col-span-7">
                <div class="kempo-card overflow-hidden">
                    <div class="p-5 border-b border-input">
                        <h3 class="text-sm font-heading font-bold uppercase tracking-wider text-neutral-600 flex items-center gap-2 mb-0">
                            <i class="fa fa-id-card-o text-primary"></i> Biodata Lengkap
                        </h3>
                    </div>
                    <div class="kempo-table-wrapper">
                        <table class="kempo-table">
                            <tbody>
                                <tr>
                                    <th class="w-44 text-neutral-500 font-semibold text-xs bg-neutral-50/70">Nama Lengkap</th>
                                    <td class="font-bold text-foreground">{{ $peserta->nama }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Jenis Kelamin</th>
                                    <td class="text-foreground">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ strtoupper(substr($peserta->jenis_kelamin, 0, 1)) == 'L' ? 'bg-blue-50 text-btn-4 border border-blue-200' : 'bg-pink-50 text-pink-700 border border-pink-200' }}">
                                            {{ strtoupper(substr($peserta->jenis_kelamin, 0, 1)) == 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Golongan Darah</th>
                                    <td class="text-foreground font-semibold">{{ $peserta->golongan_darah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">NIK</th>
                                    <td class="text-foreground font-semibold tabular-nums">{{ $peserta->nik }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Berat Badan</th>
                                    <td class="text-foreground font-semibold tabular-nums">{{ $peserta->berat_badan }} Kg</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Tingkat / Sabuk</th>
                                    <td>
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-bold rounded-md bg-neutral-100 text-neutral-800 border border-input">
                                            {{ $peserta->tingkatan_sabuk }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Status</th>
                                    <td class="text-foreground font-medium">{{ $peserta->status == "P" ? "Peserta / Atlet" : "Official" }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Tempat, Tgl Lahir</th>
                                    <td class="text-foreground">{{ $peserta->tempat }}, {{ $peserta->tanggal_lahir }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Alamat Rumah</th>
                                    <td class="text-foreground text-xs leading-relaxed">
                                        {{ $peserta->alamat }}
                                        @if(isset($peserta->kabupaten))
                                            <br><span class="text-neutral-500">{{ $peserta->kabupaten->name ?? "" }}, {{ $peserta->kecamatan->name ?? "" }}, {{ $peserta->kelurahan->name ?? "" }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Telepon / WhatsApp</th>
                                    <td class="text-foreground tabular-nums">{{ $peserta->telepon ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Keterangan Khusus</th>
                                    <td class="text-foreground text-xs">{{ $peserta->keterangan ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Nomor dan Kelas Pertandingan -->
        <div class="kempo-card">
            <div class="p-5 border-b border-input">
                <h3 class="text-sm font-heading font-bold uppercase tracking-wider text-neutral-600 flex items-center gap-2 mb-0">
                    <i class="fa fa-trophy text-primary"></i> Nomor dan Kelas Pertandingan yang Diikuti
                </h3>
            </div>
            <div class="kempo-table-wrapper">
                <table class="kempo-table">
                    <thead>
                        <tr>
                            <th class="w-24 text-center tabular-nums">No Urut</th>
                            <th>Nama Peserta Komposisi</th>
                            <th class="w-36">Tingkat</th>
                            <th>Urutan Komposisi yang Dimainkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $kategori_id = 0;
                        @endphp
                        @forelse ($peserta->PertandinganPeserta as $a)
                            @if($kategori_id != $a->list_kategori->kategori_id)
                                @php
                                    $kategori_id = $a->list_kategori->kategori_id;
                                @endphp
                                <tr class="bg-neutral-100/90 hover:bg-neutral-100 border-t border-b border-input">
                                    <td colspan="4" class="py-2.5 px-4 font-heading font-bold text-xs uppercase tracking-wide text-foreground">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-primary inline-block"></span>
                                            <span>Kategori: {{ $a->list_kategori->kategori->nama ?? "" }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            <tr class="bg-accent/40 hover:bg-accent/60 border-b border-input">
                                <td colspan="4" class="py-2 px-4 text-xs font-semibold text-foreground">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <span><strong>Tanding:</strong> {{ $a->list_kategori->pertandingan->nama ?? "" }}</span>
                                        <span class="text-neutral-500 tabular-nums">Nomor Peserta: <strong class="text-primary">{{ $a->urutan }}</strong></span>
                                    </div>
                                </td>
                            </tr>
                            @php
                                if($a->UrutanKomposisiPeserta) {
                                    $uk_list = explode(",", $a->UrutanKomposisiPeserta->urutan_komposisi);
                                } else {
                                    $uk_list = [];
                                }
                            @endphp
                            @if(count($uk_list) > 0)
                                @foreach ($uk_list as $index => $b)
                                    @if($b != "")
                                        <tr>
                                            <td class="text-center tabular-nums font-semibold text-neutral-500">
                                                {{ $index == 0 ? $a->urutan : '' }}
                                            </td>
                                            <td class="font-medium text-foreground">
                                                {{ $index == 0 ? ($a->UrutanKomposisiPeserta->nama ?? '-') : '' }}
                                            </td>
                                            <td>
                                                @if($index == 0 && !empty($a->UrutanKomposisiPeserta->tingkat))
                                                    <span class="inline-block px-2 py-0.5 text-xs font-bold rounded bg-neutral-100 text-neutral-700">
                                                        {{ $a->UrutanKomposisiPeserta->tingkat }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="font-medium text-foreground">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg bg-neutral-100 text-neutral-800 border border-input">
                                                    <i class="fa fa-check-circle text-primary text-xs"></i> {{ $b }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center tabular-nums font-semibold text-neutral-500">{{ $a->urutan }}</td>
                                    <td class="font-medium text-foreground">{{ $peserta->nama }}</td>
                                    <td>
                                        <span class="inline-block px-2 py-0.5 text-xs font-bold rounded bg-neutral-100 text-neutral-700">
                                            {{ $peserta->tingkatan_sabuk }}
                                        </span>
                                    </td>
                                    <td class="text-xs italic text-neutral-400">Komposisi belum ditentukan</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-paragraph">
                                    Peserta belum terdaftar di nomor pertandingan manapun.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
