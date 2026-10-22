@extends('tamplate')
@section('title', 'Laporan Rekapitulasi Kegiatan')

@section('content')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <!-- Card Filter & Header -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-4 border-b border-solid border-slate-100 dark:border-white/10">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h4 class="mb-1 font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="ni ni-trophy text-amber-500"></i> Rekapitulasi & Klasemen Medali Kegiatan
                        </h4>
                        <p class="mb-0 text-sm text-slate-500 dark:text-slate-400">
                            Laporan resmi perolehan medali kontingen dan daftar juara per kategori pertandingan.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Dropdown Filter Kegiatan -->
                        <div class="flex items-center gap-2">
                            <label for="filter_kegiatan" class="text-xs font-bold uppercase text-slate-600 dark:text-white/70">Kegiatan:</label>
                            <select id="filter_kegiatan" onchange="if(this.value) window.location.href='{{ url('laporan-kegiatan') }}/' + this.value" class="text-sm rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white py-2 px-3 focus:outline-none focus:border-blue-500">
                                @foreach($semua_kegiatan as $kg)
                                    <option value="{{ $kg->id }}" {{ (isset($kegiatan_aktif) && $kegiatan_aktif->id == $kg->id) ? 'selected' : '' }}>
                                        {{ $kg->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Print Button -->
                        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white uppercase transition-all bg-emerald-600 rounded-lg shadow-md hover:bg-emerald-700 active:opacity-85">
                            <i class="fa fa-print"></i> Cetak Laporan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Counter Cards -->
            @if($kegiatan_aktif)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 pt-4 border-b border-slate-100 dark:border-white/10">
                <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-xs uppercase font-bold text-slate-500">Total Kategori</span>
                        <h4 class="text-xl font-bold text-slate-800 dark:text-white mb-0">{{ $total_kategori }} Kategori</h4>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <i class="ni ni-calendar-grid-58 text-lg"></i>
                    </div>
                </div>

                <div class="p-4 bg-amber-50 dark:bg-amber-950/20 rounded-xl border border-amber-200 dark:border-amber-800/40 flex items-center justify-between">
                    <div>
                        <span class="text-xs uppercase font-bold text-amber-700 dark:text-amber-400">Medali Emas</span>
                        <h4 class="text-xl font-bold text-amber-600 dark:text-amber-300 mb-0">🥇 {{ $total_medali_emas }}</h4>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600">
                        <i class="ni ni-trophy text-lg"></i>
                    </div>
                </div>

                <div class="p-4 bg-slate-100 dark:bg-slate-800 rounded-xl border border-slate-300 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-xs uppercase font-bold text-slate-600 dark:text-slate-300">Medali Perak</span>
                        <h4 class="text-xl font-bold text-slate-700 dark:text-slate-200 mb-0">🥈 {{ $total_medali_perak }}</h4>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-600">
                        <i class="ni ni-trophy text-lg"></i>
                    </div>
                </div>

                <div class="p-4 bg-orange-50 dark:bg-orange-950/20 rounded-xl border border-orange-200 dark:border-orange-800/40 flex items-center justify-between">
                    <div>
                        <span class="text-xs uppercase font-bold text-orange-700 dark:text-orange-400">Medali Perunggu</span>
                        <h4 class="text-xl font-bold text-orange-600 dark:text-orange-300 mb-0">🥉 {{ $total_medali_perunggu }}</h4>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/40 flex items-center justify-center text-orange-600">
                        <i class="ni ni-trophy text-lg"></i>
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if($kegiatan_aktif)
        <!-- TABEL 1: Rekapitulasi Perolehan Medali per Kontingen (Klasemen) -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-2 border-b border-solid border-slate-100 dark:border-white/10 flex items-center justify-between">
                <div>
                    <h5 class="font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span>
                        Tabel 1: Klasemen Perolehan Medali per Kontingen (Kabupaten / Kota)
                    </h5>
                    <p class="text-xs text-slate-500 mb-0 mt-1">
                        Peringkat diurutkan berdasarkan: Emas terbanyak &gt; Perak &gt; Perunggu &gt; Total Medali.
                    </p>
                </div>
            </div>

            <div class="flex-auto p-4 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse text-slate-600 dark:text-white/80">
                    <thead class="align-bottom bg-slate-50 dark:bg-slate-800/60">
                        <tr>
                            <th class="px-4 py-3 font-bold text-center uppercase align-middle text-xs text-slate-700 dark:text-white/80 border-b border-slate-200 dark:border-white/10" style="width: 60px;">Peringkat</th>
                            <th class="px-4 py-3 font-bold text-left uppercase align-middle text-xs text-slate-700 dark:text-white/80 border-b border-slate-200 dark:border-white/10">Kontingen / Kabupaten</th>
                            <th class="px-4 py-3 font-bold text-center uppercase align-middle text-xs text-amber-700 dark:text-amber-400 border-b border-slate-200 dark:border-white/10" style="width: 100px;">🥇 Emas</th>
                            <th class="px-4 py-3 font-bold text-center uppercase align-middle text-xs text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-white/10" style="width: 100px;">🥈 Perak</th>
                            <th class="px-4 py-3 font-bold text-center uppercase align-middle text-xs text-orange-600 dark:text-orange-400 border-b border-slate-200 dark:border-white/10" style="width: 100px;">🥉 Perunggu</th>
                            <th class="px-4 py-3 font-bold text-center uppercase align-middle text-xs text-blue-700 dark:text-blue-400 border-b border-slate-200 dark:border-white/10" style="width: 110px;">Total Medali</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @forelse($klasemen as $index => $row)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors {{ $index < 3 ? 'font-semibold' : '' }}">
                                <td class="p-3 text-center align-middle">
                                    @if($index == 0)
                                        <span class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-white bg-amber-500 rounded-full shadow-sm">1</span>
                                    @elseif($index == 1)
                                        <span class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-white bg-slate-400 rounded-full shadow-sm">2</span>
                                    @elseif($index == 2)
                                        <span class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-white bg-orange-500 rounded-full shadow-sm">3</span>
                                    @else
                                        <span class="text-sm text-slate-500">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="p-3 text-left align-middle">
                                    <span class="text-sm font-bold text-slate-800 dark:text-white">{{ $row['nama'] }}</span>
                                </td>
                                <td class="p-3 text-center align-middle">
                                    <span class="inline-block px-3 py-1 text-sm font-bold rounded-lg {{ $row['emas'] > 0 ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'text-slate-400' }}">
                                        {{ $row['emas'] }}
                                    </span>
                                </td>
                                <td class="p-3 text-center align-middle">
                                    <span class="inline-block px-3 py-1 text-sm font-bold rounded-lg {{ $row['perak'] > 0 ? 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200' : 'text-slate-400' }}">
                                        {{ $row['perak'] }}
                                    </span>
                                </td>
                                <td class="p-3 text-center align-middle">
                                    <span class="inline-block px-3 py-1 text-sm font-bold rounded-lg {{ $row['perunggu'] > 0 ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300' : 'text-slate-400' }}">
                                        {{ $row['perunggu'] }}
                                    </span>
                                </td>
                                <td class="p-3 text-center align-middle">
                                    <span class="inline-block px-3 py-1 text-base font-extrabold text-blue-700 bg-blue-50 dark:bg-blue-900/40 dark:text-blue-300 rounded-lg">
                                        {{ $row['total'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-400">
                                    Belum ada data kontingen pada kegiatan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABEL 2: Daftar Juara per Kategori (Embu & Randori) -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-2 border-b border-solid border-slate-100 dark:border-white/10 flex items-center justify-between">
                <div>
                    <h5 class="font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-600 inline-block"></span>
                        Tabel 2: Daftar Juara per Kategori (Embu &amp; Randori)
                    </h5>
                    <p class="text-xs text-slate-500 mb-0 mt-1">
                        Daftar peraih Medali Emas, Perak, dan Perunggu untuk seluruh nomor pertandingan di event ini.
                    </p>
                </div>
            </div>

            <div class="flex-auto p-4 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse text-slate-600 dark:text-white/80">
                    <thead class="align-bottom bg-slate-50 dark:bg-slate-800/60">
                        <tr>
                            <th class="px-3 py-3 font-bold text-center uppercase text-xs border-b border-slate-200 dark:border-white/10" style="width: 40px;">No</th>
                            <th class="px-4 py-3 font-bold text-left uppercase text-xs border-b border-slate-200 dark:border-white/10" style="width: 25%;">Nama Kategori &amp; Nomor</th>
                            <th class="px-3 py-3 font-bold text-center uppercase text-xs border-b border-slate-200 dark:border-white/10" style="width: 80px;">Jenis</th>
                            <th class="px-4 py-3 font-bold text-left uppercase text-xs text-amber-700 dark:text-amber-400 border-b border-slate-200 dark:border-white/10" style="width: 22%;">🥇 Emas (Juara 1)</th>
                            <th class="px-4 py-3 font-bold text-left uppercase text-xs text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-white/10" style="width: 22%;">🥈 Perak (Juara 2)</th>
                            <th class="px-4 py-3 font-bold text-left uppercase text-xs text-orange-600 dark:text-orange-400 border-b border-slate-200 dark:border-white/10" style="width: 22%;">🥉 Perunggu (Juara 3)</th>
                            <th class="px-3 py-3 font-bold text-center uppercase text-xs border-b border-slate-200 dark:border-white/10" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @forelse($daftar_juara as $index => $item)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-3 text-center align-middle text-sm font-bold text-slate-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="p-3 text-left align-middle">
                                    <div class="font-bold text-sm text-slate-800 dark:text-white">
                                        {{ $item['pertandingan_nama'] }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $item['kategori_nama'] }} &bull; Kelas: {{ $item['kelas'] }}
                                    </div>
                                </td>
                                <td class="p-3 text-center align-middle">
                                    @if($item['jenis'] == 'Randori')
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold text-red-700 bg-red-100 dark:bg-red-900/40 dark:text-red-300 rounded-md">
                                            Randori
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold text-blue-700 bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300 rounded-md">
                                            Embu
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 text-left align-middle">
                                    @if(!empty($item['emas']['kabupaten']))
                                        <div class="font-bold text-sm text-amber-700 dark:text-amber-300">
                                            {{ $item['emas']['kabupaten'] }}
                                        </div>
                                        @if(!empty($item['emas']['peserta']))
                                            <div class="text-xs text-slate-500">{{ $item['emas']['peserta'] }}</div>
                                        @endif
                                    @else
                                        <span class="text-xs italic text-slate-400">- Belum Ditentukan -</span>
                                    @endif
                                </td>
                                <td class="p-3 text-left align-middle">
                                    @if(!empty($item['perak']['kabupaten']))
                                        <div class="font-bold text-sm text-slate-700 dark:text-slate-200">
                                            {{ $item['perak']['kabupaten'] }}
                                        </div>
                                        @if(!empty($item['perak']['peserta']))
                                            <div class="text-xs text-slate-500">{{ $item['perak']['peserta'] }}</div>
                                        @endif
                                    @else
                                        <span class="text-xs italic text-slate-400">- Belum Ditentukan -</span>
                                    @endif
                                </td>
                                <td class="p-3 text-left align-middle">
                                    @if(!empty($item['perunggu']['kabupaten']))
                                        <div class="font-bold text-sm text-orange-700 dark:text-orange-300">
                                            {{ $item['perunggu']['kabupaten'] }}
                                        </div>
                                        @if(!empty($item['perunggu']['peserta']))
                                            <div class="text-xs text-slate-500">{{ $item['perunggu']['peserta'] }}</div>
                                        @endif
                                    @else
                                        <span class="text-xs italic text-slate-400">- Belum Ditentukan -</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center align-middle">
                                    <a href="{{ url('pertandingan/' . $item['list_kategori_id'] . '?kegiatan_id=' . $kegiatan_aktif->id) }}" class="inline-block px-3 py-1.5 text-xs font-bold text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-colors" title="Buka detail pertandingan">
                                        Kelola
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-slate-400">
                                    Belum ada kategori yang terdaftar pada kegiatan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="p-8 text-center bg-white rounded-2xl shadow-xl dark:bg-slate-850">
            <i class="ni ni-calendar-grid-58 text-5xl text-slate-300 mb-3"></i>
            <h5 class="text-lg font-bold text-slate-700 dark:text-white">Belum Ada Kegiatan Dipilih</h5>
            <p class="text-sm text-slate-400">Silakan pilih atau tambahkan kegiatan terlebih dahulu untuk melihat rekapitulasi.</p>
        </div>
        @endif

    </div>
</div>
@endsection
