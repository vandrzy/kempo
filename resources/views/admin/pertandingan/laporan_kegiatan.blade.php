@extends('tamplate')
@section('title', 'Laporan Rekapitulasi Kegiatan')

@section('content')
<div class="space-y-6">
    <!-- Card Filter & Header -->
    <div class="kempo-card">
        <div class="p-6 border-b border-input">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-heading font-bold text-foreground flex items-center gap-2 mb-1">
                        <i class="ni ni-trophy text-primary"></i> Rekapitulasi &amp; Klasemen Medali
                    </h2>
                    <p class="text-sm text-paragraph mb-0">
                        Laporan resmi perolehan medali kontingen dan daftar juara per kategori pertandingan
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Dropdown Filter Kegiatan -->
                    <div class="flex items-center gap-2">
                        <label for="filter_kegiatan" class="text-xs font-bold uppercase tracking-wider text-neutral-600">Kegiatan:</label>
                        <select id="filter_kegiatan" onchange="if(this.value) window.location.href='{{ url('laporan-kegiatan') }}/' + this.value" class="text-xs font-medium border border-input rounded-xl bg-white text-foreground py-2 px-3 focus:outline-none focus:border-primary shadow-xs">
                            @foreach($semua_kegiatan as $kg)
                                <option value="{{ $kg->id }}" {{ (isset($kegiatan_aktif) && $kegiatan_aktif->id == $kg->id) ? 'selected' : '' }}>
                                    {{ $kg->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(isset($kegiatan_aktif) && $kegiatan_aktif)
                    <!-- Cetak Buku PDF Laporan Button -->
                    <a href="{{ route('laporan.kegiatan.pdf', $kegiatan_aktif->id) }}" target="_blank" class="btn-kempo-4 inline-flex items-center gap-2 px-4 py-2 text-xs font-bold uppercase tracking-wider shadow-sm hover:opacity-90 transition-all cursor-pointer" title="Cetak Buku Laporan Hasil Pertandingan (PDF)">
                        <i class="fa fa-file-pdf-o"></i>
                        <span>Cetak PDF Buku Laporan</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary Counter Cards -->
        @if($kegiatan_aktif)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6 bg-neutral-50/50">
            <div class="p-4 bg-white rounded-xl border border-input flex items-center justify-between shadow-2xs">
                <div>
                    <span class="text-xs uppercase font-bold text-neutral-500 tracking-wider">Total Kategori</span>
                    <h3 class="text-xl font-heading font-bold text-foreground mt-1 mb-0 tabular-nums">{{ $total_kategori }} <span class="text-xs font-normal text-paragraph">Kategori</span></h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-btn-4">
                    <i class="ni ni-calendar-grid-58 text-lg"></i>
                </div>
            </div>

            <div class="p-4 bg-white rounded-xl border border-input flex items-center justify-between shadow-2xs">
                <div>
                    <span class="text-xs uppercase font-bold text-amber-600 tracking-wider">Medali Emas</span>
                    <h3 class="text-xl font-heading font-bold text-amber-600 mt-1 mb-0 tabular-nums">🥇 {{ $total_medali_emas }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                    <i class="ni ni-trophy text-lg"></i>
                </div>
            </div>

            <div class="p-4 bg-white rounded-xl border border-input flex items-center justify-between shadow-2xs">
                <div>
                    <span class="text-xs uppercase font-bold text-slate-500 tracking-wider">Medali Perak</span>
                    <h3 class="text-xl font-heading font-bold text-slate-700 mt-1 mb-0 tabular-nums">🥈 {{ $total_medali_perak }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                    <i class="ni ni-trophy text-lg"></i>
                </div>
            </div>

            <div class="p-4 bg-white rounded-xl border border-input flex items-center justify-between shadow-2xs">
                <div>
                    <span class="text-xs uppercase font-bold text-btn-3 tracking-wider">Medali Perunggu</span>
                    <h3 class="text-xl font-heading font-bold text-btn-3 mt-1 mb-0 tabular-nums">🥉 {{ $total_medali_perunggu }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-btn-3">
                    <i class="ni ni-trophy text-lg"></i>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if($kegiatan_aktif)
    <!-- TABEL 1: Rekapitulasi Perolehan Medali per Kontingen (Klasemen) -->
    <div class="kempo-card">
        <div class="p-6 border-b border-input flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="text-lg font-heading font-bold text-foreground flex items-center gap-2 mb-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary inline-block"></span>
                    Tabel 1: Klasemen Perolehan Medali per Kontingen
                </h3>
                <p class="text-xs text-paragraph mb-0">
                    Peringkat diurutkan berdasarkan: Emas terbanyak &gt; Perak &gt; Perunggu &gt; Total Medali
                </p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-foreground bg-accent rounded-full border border-primary/20">
                    <i class="fa fa-shield text-primary"></i> {{ count($klasemen) }} Kontingen
                </span>
            </div>
        </div>

        <div class="kempo-table-wrapper">
            <table class="kempo-table">
                <thead>
                    <tr>
                        <th class="w-20 text-center tabular-nums">Peringkat</th>
                        <th>Kontingen / Kabupaten</th>
                        <th class="w-28 text-center text-amber-700">🥇 Emas</th>
                        <th class="w-28 text-center text-slate-600">🥈 Perak</th>
                        <th class="w-28 text-center text-orange-600">🥉 Perunggu</th>
                        <th class="w-32 text-center text-btn-4">Total Medali</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($klasemen as $index => $row)
                        <tr class="{{ $index < 3 ? 'bg-neutral-50/50 font-semibold' : '' }}">
                            <td class="text-center align-middle tabular-nums">
                                @if($index == 0)
                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 9999px; background: linear-gradient(135deg, #ffd375, #d97706); color: #000000 !important; font-size: 13px; font-weight: 800; box-shadow: 0 2px 4px rgba(217, 119, 6, 0.3);" title="Juara 1 (Emas)">
                                        1
                                    </span>
                                @elseif($index == 1)
                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 9999px; background: linear-gradient(135deg, #cbd5e1, #64748b); color: #ffffff !important; font-size: 13px; font-weight: 800; box-shadow: 0 2px 4px rgba(100, 116, 139, 0.3);" title="Juara 2 (Perak)">
                                        2
                                    </span>
                                @elseif($index == 2)
                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 9999px; background: linear-gradient(135deg, #fd822b, #ea580c); color: #ffffff !important; font-size: 13px; font-weight: 800; box-shadow: 0 2px 4px rgba(234, 88, 12, 0.3);" title="Juara 3 (Perunggu)">
                                        3
                                    </span>
                                @else
                                    <span class="text-sm font-semibold text-neutral-500 tabular-nums">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="font-bold text-foreground">{{ $row['nama'] }}</span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="inline-block px-3 py-1 text-xs font-bold rounded-lg tabular-nums {{ $row['emas'] > 0 ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'text-neutral-400' }}">
                                    {{ $row['emas'] }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="inline-block px-3 py-1 text-xs font-bold rounded-lg tabular-nums {{ $row['perak'] > 0 ? 'bg-slate-100 text-slate-800 border border-slate-200' : 'text-neutral-400' }}">
                                    {{ $row['perak'] }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="inline-block px-3 py-1 text-xs font-bold rounded-lg tabular-nums {{ $row['perunggu'] > 0 ? 'bg-orange-100 text-orange-900 border border-orange-200' : 'text-neutral-400' }}">
                                    {{ $row['perunggu'] }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="inline-block px-3 py-1 text-sm font-extrabold rounded-lg bg-blue-50 text-btn-4 border border-blue-200 tabular-nums">
                                    {{ $row['total'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-paragraph">
                                Belum ada data kontingen pada kegiatan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @php
        $juaraEmbu = $daftar_juara_embu ?? array_values(array_filter($daftar_juara ?? [], fn($i) => ($i['jenis'] ?? '') === 'Embu'));
        $juaraRandori = $daftar_juara_randori ?? array_values(array_filter($daftar_juara ?? [], fn($i) => ($i['jenis'] ?? '') === 'Randori'));
    @endphp

    <!-- TABEL 2: Daftar Juara Kategori Embu -->
    <div class="kempo-card">
        <div class="p-6 border-b border-input flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="text-lg font-heading font-bold text-foreground flex items-center gap-2 mb-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-btn-4 inline-block"></span>
                    Tabel 2: Daftar Juara Kategori Embu
                </h3>
                <p class="text-xs text-paragraph mb-0">
                    Daftar peraih Medali Emas, Perak, dan Perunggu untuk seluruh nomor pertandingan Kategori Embu
                </p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-btn-4 bg-blue-50 rounded-full border border-blue-200">
                    <i class="fa fa-star text-btn-4"></i> {{ count($juaraEmbu) }} Nomor Pertandingan
                </span>
            </div>
        </div>

        <div class="kempo-table-wrapper">
            <table class="kempo-table">
                <thead>
                    <tr>
                        <th class="w-14 text-center tabular-nums">No</th>
                        <th class="w-3/12">Nomor &amp; Kategori</th>
                        <th class="w-3/12 text-amber-700">🥇 Emas (Juara 1)</th>
                        <th class="w-2/12 text-slate-600">🥈 Perak (Juara 2)</th>
                        <th class="w-2/12 text-orange-600">🥉 Perunggu (Juara 3)</th>
                        <th class="w-24 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($juaraEmbu as $index => $item)
                        <tr>
                            <td class="text-center align-middle font-bold text-neutral-500 tabular-nums">
                                {{ $index + 1 }}
                            </td>
                            <td class="align-middle">
                                <div class="font-bold text-foreground">
                                    {{ $item['pertandingan_nama'] }}
                                </div>
                                <div class="text-xs text-neutral-500 mt-0.5">
                                    {{ $item['kategori_nama'] }} &bull; Kelas: {{ $item['kelas'] }}
                                </div>
                            </td>
                            <td class="align-middle">
                                @if(!empty($item['emas']['kabupaten']))
                                    <div class="font-bold text-amber-800">
                                        {{ $item['emas']['kabupaten'] }}
                                    </div>
                                    @if(!empty($item['emas']['peserta']))
                                        <div class="text-xs text-neutral-500 mt-0.5">{{ $item['emas']['peserta'] }}</div>
                                    @endif
                                @else
                                    <span class="text-xs italic text-neutral-400">- Belum Ditentukan -</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if(!empty($item['perak']['kabupaten']))
                                    <div class="font-bold text-slate-700">
                                        {{ $item['perak']['kabupaten'] }}
                                    </div>
                                    @if(!empty($item['perak']['peserta']))
                                        <div class="text-xs text-neutral-500 mt-0.5">{{ $item['perak']['peserta'] }}</div>
                                    @endif
                                @else
                                    <span class="text-xs italic text-neutral-400">- Belum Ditentukan -</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if(!empty($item['perunggu']['kabupaten']))
                                    <div class="font-bold text-orange-800">
                                        {{ $item['perunggu']['kabupaten'] }}
                                    </div>
                                    @if(!empty($item['perunggu']['peserta']))
                                        <div class="text-xs text-neutral-500 mt-0.5">{{ $item['perunggu']['peserta'] }}</div>
                                    @endif
                                @else
                                    <span class="text-xs italic text-neutral-400">- Belum Ditentukan -</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ url('pertandingan/' . $item['list_kategori_id'] . '?kegiatan_id=' . $kegiatan_aktif->id) }}" class="btn-kempo-4 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all" title="Buka detail pertandingan">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-paragraph">
                                Belum ada kategori Embu yang terdaftar pada kegiatan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL 3: Daftar Juara Kategori Randori -->
    <div class="kempo-card">
        <div class="p-6 border-b border-input flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="text-lg font-heading font-bold text-foreground flex items-center gap-2 mb-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-destructive inline-block"></span>
                    Tabel 3: Daftar Juara Kategori Randori
                </h3>
                <p class="text-xs text-paragraph mb-0">
                    Daftar peraih Medali Emas, Perak, dan Perunggu untuk seluruh nomor pertandingan Kategori Randori
                </p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-destructive bg-red-50 rounded-full border border-red-200">
                    <i class="fa fa-shield text-destructive"></i> {{ count($juaraRandori) }} Nomor Pertandingan
                </span>
            </div>
        </div>

        <div class="kempo-table-wrapper">
            <table class="kempo-table">
                <thead>
                    <tr>
                        <th class="w-14 text-center tabular-nums">No</th>
                        <th class="w-3/12">Nomor &amp; Kategori</th>
                        <th class="w-3/12 text-amber-700">🥇 Emas (Juara 1)</th>
                        <th class="w-2/12 text-slate-600">🥈 Perak (Juara 2)</th>
                        <th class="w-2/12 text-orange-600">🥉 Perunggu (Juara 3)</th>
                        <th class="w-24 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($juaraRandori as $index => $item)
                        <tr>
                            <td class="text-center align-middle font-bold text-neutral-500 tabular-nums">
                                {{ $index + 1 }}
                            </td>
                            <td class="align-middle">
                                <div class="font-bold text-foreground">
                                    {{ $item['pertandingan_nama'] }}
                                </div>
                                <div class="text-xs text-neutral-500 mt-0.5">
                                    {{ $item['kategori_nama'] }} &bull; Kelas: {{ $item['kelas'] }}
                                </div>
                            </td>
                            <td class="align-middle">
                                @if(!empty($item['emas']['kabupaten']))
                                    <div class="font-bold text-amber-800">
                                        {{ $item['emas']['kabupaten'] }}
                                    </div>
                                    @if(!empty($item['emas']['peserta']))
                                        <div class="text-xs text-neutral-500 mt-0.5">{{ $item['emas']['peserta'] }}</div>
                                    @endif
                                @else
                                    <span class="text-xs italic text-neutral-400">- Belum Ditentukan -</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if(!empty($item['perak']['kabupaten']))
                                    <div class="font-bold text-slate-700">
                                        {{ $item['perak']['kabupaten'] }}
                                    </div>
                                    @if(!empty($item['perak']['peserta']))
                                        <div class="text-xs text-neutral-500 mt-0.5">{{ $item['perak']['peserta'] }}</div>
                                    @endif
                                @else
                                    <span class="text-xs italic text-neutral-400">- Belum Ditentukan -</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if(!empty($item['perunggu']['kabupaten']))
                                    <div class="font-bold text-orange-800">
                                        {{ $item['perunggu']['kabupaten'] }}
                                    </div>
                                    @if(!empty($item['perunggu']['peserta']))
                                        <div class="text-xs text-neutral-500 mt-0.5">{{ $item['perunggu']['peserta'] }}</div>
                                    @endif
                                @endif
                                @if(!empty($item['perunggu_2']['kabupaten']))
                                    <div class="mt-1 pt-1 border-t border-input font-bold text-orange-800">
                                        {{ $item['perunggu_2']['kabupaten'] }} <span class="text-[10px] text-btn-3 font-normal">(Bersama)</span>
                                    </div>
                                    @if(!empty($item['perunggu_2']['peserta']))
                                        <div class="text-xs text-neutral-500 mt-0.5">{{ $item['perunggu_2']['peserta'] }}</div>
                                    @endif
                                @endif
                                @if(empty($item['perunggu']['kabupaten']) && empty($item['perunggu_2']['kabupaten']))
                                    <span class="text-xs italic text-neutral-400">- Belum Ditentukan -</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ url('pertandingan/' . $item['list_kategori_id'] . '?kegiatan_id=' . $kegiatan_aktif->id) }}" class="btn-kempo-4 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all" title="Buka detail pertandingan">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-paragraph">
                                Belum ada kategori Randori yang terdaftar pada kegiatan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="kempo-card p-12 text-center">
        <i class="ni ni-calendar-grid-58 text-5xl text-neutral-300 mb-3 block"></i>
        <h4 class="text-lg font-heading font-bold text-foreground">Belum Ada Kegiatan Dipilih</h4>
        <p class="text-sm text-paragraph mt-1">Silakan pilih kegiatan terlebih dahulu untuk melihat rekapitulasi dan klasemen medali.</p>
    </div>
    @endif
</div>
@endsection
