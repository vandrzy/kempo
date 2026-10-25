@extends('tamplate')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- ============================================== -->
    <!-- 1. HEADER & DROPDOWN PILIH KEGIATAN            -->
    <!-- ============================================== -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-5 sm:p-6 rounded-3xl bg-white border border-[#ebebeb] shadow-xs">
        
        <!-- Left: Judul & Subtitle -->
        <div class="flex items-start gap-3.5 sm:gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#fff0eb] text-[#f2470c] flex items-center justify-center shrink-0 border border-[#f2470c]/25 shadow-2xs text-xl">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#fff0eb] text-[#f2470c] border border-[#f2470c]/25">
                        <i class="fa-solid fa-sparkles text-[10px]"></i> Dashboard Utama
                    </span>
                    @if($kegiatanAktif)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Kegiatan Aktif
                        </span>
                    @endif
                </div>
                <h1 class="font-heading font-bold text-xl sm:text-2xl text-[#000000] mb-0 tracking-tight leading-tight">
                    Dashboard Kejuaraan
                </h1>
                <p class="text-xs sm:text-sm text-neutral-500 mb-0 font-body mt-1">
                    Selamat datang, <strong class="text-neutral-800 font-semibold">{{ Auth::user()->name }}</strong>. Berikut rangkuman statistik dan klasemen perolehan medali.
                </p>
            </div>
        </div>

        <!-- Right: Dropdown Pilih Kegiatan (Default: Data Paling Awal) -->
        <div class="shrink-0 w-full lg:w-auto">
            <form method="GET" action="{{ url('dashboard') }}" class="flex flex-col sm:flex-row sm:items-center gap-2.5">
                <div class="flex items-center gap-2">
                    <label for="kegiatan_id" class="text-xs font-bold text-neutral-600 uppercase tracking-wider font-heading shrink-0 flex items-center gap-1.5 mb-0">
                        <i class="fa-solid fa-calendar-check text-[#f2470c]"></i>
                        <span>Kegiatan:</span>
                    </label>
                </div>
                <div class="relative min-w-full sm:min-w-[300px] lg:min-w-[340px]">
                    <select name="kegiatan_id" id="kegiatan_id" onchange="this.form.submit()" 
                        class="w-full pl-3.5 pr-8 py-2.5 rounded-2xl bg-[#fdfdfd] border border-[#ebebeb] text-xs sm:text-sm font-semibold text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 shadow-2xs cursor-pointer transition-all">
                        @forelse($kegiatans as $kg)
                            <option value="{{ $kg->id }}" {{ $selectedKegiatanId == $kg->id ? 'selected' : '' }}>
                                {{ $kg->nama }}
                            </option>
                        @empty
                            <option value="">-- Belum ada kegiatan --</option>
                        @endforelse
                    </select>
                </div>
            </form>
        </div>

    </div>

    <!-- ============================================== -->
    <!-- 2. STAT CARDS GRID (PESERTA, OFFICIAL, MEDALI) -->
    <!-- ============================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Card 1: Jumlah Peserta -->
        <div class="p-5 sm:p-6 rounded-3xl bg-white border border-[#ebebeb] shadow-xs hover:border-[#f2470c]/40 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider font-heading">
                    Jumlah Peserta
                </span>
                <div class="w-10 h-10 rounded-2xl bg-[#fff0eb] text-[#f2470c] flex items-center justify-center border border-[#f2470c]/20 shadow-2xs">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-heading font-extrabold text-3xl sm:text-4xl text-[#000000] tabular-nums">
                    {{ number_format($jumlahPeserta) }}
                </span>
                <span class="text-xs font-semibold text-neutral-400 font-body">Atlet</span>
            </div>
            <p class="text-[11px] text-neutral-400 mt-3 mb-0 flex items-center gap-1.5 font-body">
                <i class="fa-solid fa-circle-check text-emerald-500 text-[11px]"></i>
                <span>Terdaftar pada kegiatan ini</span>
            </p>
        </div>

        <!-- Card 2: Jumlah Official -->
        <div class="p-5 sm:p-6 rounded-3xl bg-white border border-[#ebebeb] shadow-xs hover:border-[#fd822b]/40 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider font-heading">
                    Jumlah Official
                </span>
                <div class="w-10 h-10 rounded-2xl bg-[#fff0eb] text-[#fd822b] flex items-center justify-center border border-[#fd822b]/20 shadow-2xs">
                    <i class="fa-solid fa-id-badge text-sm"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-heading font-extrabold text-3xl sm:text-4xl text-[#000000] tabular-nums">
                    {{ number_format($jumlahOfficial) }}
                </span>
                <span class="text-xs font-semibold text-neutral-400 font-body">Orang</span>
            </div>
            <p class="text-[11px] text-neutral-400 mt-3 mb-0 flex items-center gap-1.5 font-body">
                <i class="fa-solid fa-user-tie text-blue-500 text-[11px]"></i>
                <span>Pelatih &amp; manajer kontingen</span>
            </p>
        </div>

        <!-- Card 3: Total Medali Diperoleh -->
        <div class="p-5 sm:p-6 rounded-3xl bg-white border border-[#ebebeb] shadow-xs hover:border-amber-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider font-heading">
                    Total Medali Keluar
                </span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200 shadow-2xs">
                    <i class="fa-solid fa-trophy text-sm"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-heading font-extrabold text-3xl sm:text-4xl text-[#000000] tabular-nums">
                    {{ number_format($totalSeluruhMedali) }}
                </span>
                <span class="text-xs font-semibold text-neutral-400 font-body">Medali</span>
            </div>
            <div class="flex items-center gap-1.5 mt-3 flex-wrap">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 tabular-nums">
                    🥇 {{ $totalMedaliEmas }}
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 tabular-nums">
                    🥈 {{ $totalMedaliPerak }}
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold bg-orange-50 text-orange-800 border border-orange-200 tabular-nums">
                    🥉 {{ $totalMedaliPerunggu }}
                </span>
            </div>
        </div>

        <!-- Card 4: Total Nomor Pertandingan -->
        <div class="p-5 sm:p-6 rounded-3xl bg-white border border-[#ebebeb] shadow-xs hover:border-blue-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider font-heading">
                    Nomor Pertandingan
                </span>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#3276e4] flex items-center justify-center border border-blue-200 shadow-2xs">
                    <i class="fa-solid fa-sitemap text-sm"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-heading font-extrabold text-3xl sm:text-4xl text-[#000000] tabular-nums">
                    {{ number_format($totalKategori) }}
                </span>
                <span class="text-xs font-semibold text-neutral-400 font-body">Kelas</span>
            </div>
            <p class="text-[11px] text-neutral-400 mt-3 mb-0 flex items-center gap-1.5 font-body">
                <i class="fa-solid fa-layer-group text-neutral-400 text-[11px]"></i>
                <span>Kategori Embu &amp; Randori</span>
            </p>
        </div>

    </div>

    <!-- ============================================== -->
    <!-- 3. TABEL KLASEMEN PEROLEHAN MEDALI KONTINGEN   -->
    <!-- ============================================== -->
    <div class="bg-white rounded-3xl border border-[#ebebeb] shadow-xs overflow-hidden">
        
        <!-- Header Card Tabel -->
        <div class="p-5 sm:p-6 border-b border-[#ebebeb] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-[#fff0eb] text-[#f2470c] flex items-center justify-center shrink-0 border border-[#f2470c]/25 shadow-2xs">
                    <i class="fa-solid fa-ranking-star"></i>
                </div>
                <div>
                    <h2 class="font-heading font-bold text-base sm:text-lg text-[#000000] mb-0 tracking-tight">
                        Klasemen Perolehan Medali per Kontingen
                    </h2>
                    <p class="text-xs text-neutral-500 mb-0 font-body mt-0.5">
                        Peringkat ditentukan berdasarkan jumlah Emas, Perak, Perunggu, lalu Total Medali.
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#fff0eb] text-[#f2470c] border border-[#f2470c]/20">
                    <i class="fa-solid fa-flag text-[11px]"></i> {{ count($klasemen) }} Kontingen
                </span>
            </div>
        </div>

        <!-- Tabel Klasemen Medali -->
        <div class="overflow-x-auto kempo-scrollbar">
            <table class="w-full border-collapse text-left font-body text-sm">
                <thead>
                    <tr class="bg-[#fafafa] border-b border-[#ebebeb] text-[11px] font-bold text-neutral-600 uppercase tracking-wider font-heading">
                        <th class="py-3.5 px-4 text-center w-24">Peringkat</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Kontingen / Daerah</th>
                        <th class="py-3.5 px-4 text-center w-28 text-amber-800 bg-amber-50/50">
                            🥇 Emas
                        </th>
                        <th class="py-3.5 px-4 text-center w-28 text-slate-700 bg-slate-50/70">
                            🥈 Perak
                        </th>
                        <th class="py-3.5 px-4 text-center w-28 text-orange-900 bg-orange-50/40">
                            🥉 Perunggu
                        </th>
                        <th class="py-3.5 px-4 text-center w-32 text-[#f2470c] bg-[#fff0eb]/50 font-extrabold">
                            Total Medali
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#ebebeb]">
                    @forelse($klasemen as $index => $item)
                        <tr class="hover:bg-[#fff0eb]/20 transition-colors {{ $index < 3 && $item['total'] > 0 ? 'bg-neutral-50/30' : '' }}">
                            <td class="py-3 px-4 text-center">
                                @if($index === 0 && $item['total'] > 0)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-100 text-amber-900 font-heading font-extrabold text-xs border border-amber-300 shadow-2xs">
                                        1
                                    </span>
                                @elseif($index === 1 && $item['total'] > 0)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-200 text-slate-800 font-heading font-extrabold text-xs border border-slate-300 shadow-2xs">
                                        2
                                    </span>
                                @elseif($index === 2 && $item['total'] > 0)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-orange-100 text-orange-900 font-heading font-extrabold text-xs border border-orange-300 shadow-2xs">
                                        3
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-neutral-100 text-neutral-500 font-semibold text-xs tabular-nums">
                                        {{ $index + 1 }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-heading font-bold text-sm text-[#000000]">
                                    {{ $item['nama'] }}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center bg-amber-50/20">
                                <span class="inline-block px-3 py-1 rounded-xl text-xs font-bold tabular-nums {{ $item['emas'] > 0 ? 'bg-amber-100 text-amber-900 border border-amber-200 shadow-2xs font-extrabold' : 'text-neutral-400' }}">
                                    {{ $item['emas'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center bg-slate-50/30">
                                <span class="inline-block px-3 py-1 rounded-xl text-xs font-bold tabular-nums {{ $item['perak'] > 0 ? 'bg-slate-200 text-slate-800 border border-slate-300 shadow-2xs font-extrabold' : 'text-neutral-400' }}">
                                    {{ $item['perak'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center bg-orange-50/20">
                                <span class="inline-block px-3 py-1 rounded-xl text-xs font-bold tabular-nums {{ $item['perunggu'] > 0 ? 'bg-orange-100 text-orange-900 border border-orange-200 shadow-2xs font-extrabold' : 'text-neutral-400' }}">
                                    {{ $item['perunggu'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center bg-[#fff0eb]/30">
                                <span class="inline-block px-3.5 py-1 rounded-xl text-sm font-extrabold tabular-nums {{ $item['total'] > 0 ? 'bg-[#fff0eb] text-[#f2470c] border border-[#f2470c]/30 shadow-2xs' : 'text-neutral-400' }}">
                                    {{ $item['total'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-4 text-center text-neutral-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-solid fa-trophy text-3xl text-neutral-300"></i>
                                    <span class="text-sm font-medium">Belum ada kontingen atau perolehan medali yang dicatat pada kegiatan ini.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
