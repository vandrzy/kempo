<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bagan Pertandingan - {{ $pertandingan->pertandingan->nama ?? 'Randori' }} ({{ $pertandingan->pertandingan->kelas ?? '-' }})</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (CDN standalone untuk stand-alone view ini) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            red: '#e11d48',
                            blue: '#2563eb',
                            gold: '#d97706',
                            dark: '#0f172a'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        /* Dot Pattern Background */
        .bg-dot-pattern {
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Bracket Match Card Styles */
        .match-card {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .match-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.12), 0 4px 8px -4px rgba(0, 0, 0, 0.06);
        }

        .competitor-row {
            transition: all 0.15s ease;
        }

        /* Hover Path Highlight */
        .athlete-highlight {
            background-color: #ecfdf5 !important;
            border-color: #10b981 !important;
            box-shadow: 0 0 0 2px #10b981 !important;
            transform: scale(1.02);
            z-index: 10;
        }

        .connector-line {
            fill: none;
            stroke: #cbd5e1;
            stroke-width: 2px;
            transition: all 0.25s ease;
        }

        .connector-line.active {
            stroke: #10b981;
            stroke-width: 3px;
            filter: drop-shadow(0 2px 4px rgba(16, 185, 129, 0.25));
        }

        .connector-line.highlighted {
            stroke: #059669;
            stroke-width: 4px;
            filter: drop-shadow(0 2px 6px rgba(5, 150, 105, 0.4));
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            height: 10px;
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Print Settings */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .bg-dot-pattern {
                background-image: none !important;
            }
            #bracketWrapper {
                transform: none !important;
                overflow: visible !important;
                width: 100% !important;
            }
            .match-card {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                break-inside: avoid;
            }
            @page {
                size: landscape A4;
                margin: 8mm;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-800">

    @php
        $check = optional($pertandingan)->pertandingan;
        $isRandori = ($check && $check->bagan == 1) ||
                     stripos($check->nama ?? '', 'randori') !== false ||
                     stripos(optional($pertandingan->kategori)->nama ?? '', 'randori') !== false;

        $totalPeserta = $totalPeserta ?? ($pesertaList ? $pesertaList->count() : 0);
        $slotCount = $slotCount ?? pow(2, ceil(log(max(2, $totalPeserta), 2)));
        $totalBabak = $totalBabak ?? (int) log($slotCount, 2);

        $matchesByBabak = $matches ? $matches->groupBy('babak') : collect();
        $juara = optional($pertandingan)->data_juara ?? [];
    @endphp

    <!-- ======================================================== -->
    <!-- TOP NAVBAR & ACTION TOOLBAR (NO-PRINT)                   -->
    <!-- ======================================================== -->
    <header class="no-print sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-col md:flex-row items-center justify-between gap-3">
            
            <!-- Left Info -->
            <div class="flex items-center gap-3 w-full md:w-auto">
                <a href="{{ url('/pertandingan/'.$pertandingan->id.'?kegiatan_id='.(request('kegiatan_id') ?? optional($pertandingan->kegiatan)->id)) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 transition-colors border border-slate-200 shrink-0" title="Kembali ke Manajemen Pertandingan">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200">
                            <i class="fas fa-sitemap"></i> Bagan Randori
                        </span>
                        <h1 class="font-heading font-black text-base sm:text-lg text-slate-900 mb-0 tracking-tight">
                            {{ $pertandingan->pertandingan->nama ?? 'Bagan Pertandingan' }}
                        </h1>
                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200">
                            Kelas: {{ $pertandingan->pertandingan->kelas ?? '-' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mb-0 font-medium truncate max-w-xl">
                        {{ $pertandingan->kegiatan->nama ?? 'Kejuaraan Shorinji Kempo' }} &bull; {{ $totalPeserta }} Peserta Terdaftar &bull; {{ $totalBabak }} Babak Eliminasi
                    </p>
                </div>
            </div>

            <!-- Right Actions Toolbar -->
            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <!-- Zoom Controls -->
                <div class="inline-flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200">
                    <button type="button" onclick="zoomBracket(-0.1)" class="w-8 h-8 rounded-lg hover:bg-white text-slate-700 flex items-center justify-center transition-colors text-xs font-bold cursor-pointer" title="Zoom Out (-)">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span id="zoomText" class="px-2 text-xs font-bold tabular-nums text-slate-600 select-none">100%</span>
                    <button type="button" onclick="zoomBracket(0.1)" class="w-8 h-8 rounded-lg hover:bg-white text-slate-700 flex items-center justify-center transition-colors text-xs font-bold cursor-pointer" title="Zoom In (+)">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" onclick="resetZoom()" class="px-2.5 h-8 rounded-lg hover:bg-white text-slate-600 hover:text-slate-900 text-xs font-bold transition-colors cursor-pointer" title="Reset Zoom">
                        Reset
                    </button>
                </div>

                <!-- Fullscreen Toggle -->
                <button type="button" onclick="toggleFullscreen()" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors border border-slate-200 cursor-pointer" title="Toggle Fullscreen">
                    <i class="fas fa-expand text-sm"></i>
                </button>

                <!-- Print Button -->
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-xs font-bold tracking-wide shadow-sm hover:shadow transition-all cursor-pointer">
                    <i class="fas fa-print"></i> Cetak Bagan
                </button>
            </div>

        </div>
    </header>

    <!-- ======================================================== -->
    <!-- PRINT HEADER (HANYA TAMPIL SAAT CETAK / PDF)             -->
    <!-- ======================================================== -->
    <div class="hidden print:block p-6 text-center border-b border-slate-300 mb-4 bg-white">
        <h2 class="font-heading font-black text-2xl uppercase tracking-wider text-slate-900 mb-1">
            BAGAN PERTANDINGAN SHORINJI KEMPO
        </h2>
        <h3 class="font-heading font-bold text-lg text-rose-600 uppercase mb-1">
            {{ $pertandingan->pertandingan->nama ?? '-' }} (KELAS: {{ $pertandingan->pertandingan->kelas ?? '-' }})
        </h3>
        <p class="text-xs text-slate-600 font-semibold mb-0">
            {{ $pertandingan->kegiatan->nama ?? 'Kejuaraan Shorinji Kempo' }} &bull; Total {{ $totalPeserta }} Peserta &bull; Sistem Gugur Tunggal (*Single Elimination*)
        </p>
    </div>

    <!-- ======================================================== -->
    <!-- MAIN INTERACTIVE BRACKET TREE CANVAS                     -->
    <!-- ======================================================== -->
    <main class="flex-1 overflow-x-auto overflow-y-auto bg-dot-pattern p-4 sm:p-8 flex justify-center items-start min-h-[calc(100vh-68px)]">
        
        <div id="bracketWrapper" class="relative inline-flex items-stretch gap-12 sm:gap-16 lg:gap-20 py-4 transition-transform duration-150 origin-top-left" style="transform: scale(1);">
            
            <!-- SVG Canvas untuk garis penghubung antar partai -->
            <svg id="bracketSvgCanvas" class="absolute inset-0 pointer-events-none z-0 w-full h-full"></svg>

            @if($isRandori && $matches && $matches->isNotEmpty())
                
                @for ($b = 1; $b <= $totalBabak; $b++)
                    @php
                        $roundMatches = $matchesByBabak->get($b, collect());
                        $namaBabak = 'Babak ' . $b;
                        $badgeColor = 'bg-slate-100 text-slate-800 border-slate-300';

                        if ($b == $totalBabak) {
                            $namaBabak = 'FINAL';
                            $badgeColor = 'bg-amber-100 text-amber-800 border-amber-300 shadow-xs';
                        } elseif ($totalBabak >= 2 && $b == $totalBabak - 1) {
                            $namaBabak = 'SEMIFINAL';
                            $badgeColor = 'bg-purple-100 text-purple-800 border-purple-300';
                        } elseif ($totalBabak >= 3 && $b == $totalBabak - 2) {
                            $namaBabak = 'PEREMPATFINAL';
                            $badgeColor = 'bg-blue-100 text-blue-800 border-blue-300';
                        } elseif ($totalBabak >= 4 && $b == $totalBabak - 3) {
                            $namaBabak = '16 BESAR';
                            $badgeColor = 'bg-slate-100 text-slate-700 border-slate-200';
                        }

                        $expectedCount = (int) ($slotCount / pow(2, $b));
                    @endphp

                    <!-- KOLOM BABAK {{ $b }} -->
                    <div class="round-column flex flex-col justify-around z-10 w-[290px] sm:w-[320px] shrink-0" data-babak="{{ $b }}">
                        
                        <!-- Header Babak -->
                        <div class="mb-6 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider border {{ $badgeColor }}">
                                {{ $namaBabak }}
                            </span>
                            <p class="text-[11px] font-semibold text-slate-500 mt-1 mb-0">
                                {{ $roundMatches->count() }} Partai Duel
                            </p>
                        </div>

                        <!-- Daftar Partai Duel di Babak Ini -->
                        <div class="flex-1 flex flex-col justify-around gap-8 sm:gap-10">
                            @foreach ($roundMatches as $m)
                                @php
                                    $kiri = $m->PertandinganPesertaPertama;
                                    $kanan = $m->PertandinganPesertaKedua;
                                    $pemenangId = $m->pemenang_id;
                                    $isBye = ($kanan === null && $m->keterangan_menang === 'BYE');
                                    
                                    $kiriId = $m->pertandingan_peserta_kiri_id;
                                    $kananId = $m->pertandingan_peserta_kanan_id;

                                    $kiriMenang = ($pemenangId && $pemenangId == $kiriId);
                                    $kananMenang = ($pemenangId && $pemenangId == $kananId);

                                    $isComplete = ($pemenangId !== null);
                                    $isReady = ($kiriId && $kananId);
                                @endphp

                                <!-- Match Card -->
                                <div id="match-node-{{ $m->id }}" 
                                     class="match-card bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden"
                                     data-match-id="{{ $m->id }}"
                                     data-babak="{{ $b }}"
                                     data-partai="{{ $m->nomor_partai }}"
                                     data-winner-id="{{ $pemenangId }}"
                                     data-kiri-id="{{ $kiriId }}"
                                     data-kanan-id="{{ $kananId }}">
                                    
                                    <!-- Card Header: Partai # & Status -->
                                    <div class="px-3.5 py-2 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                        <span class="text-xs font-heading font-black text-slate-700 tracking-tight tabular-nums">
                                            Partai #{{ $m->nomor_partai }}
                                        </span>
                                        @if($isBye)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fas fa-check-circle"></i> BYE
                                            </span>
                                        @elseif($isComplete)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                <i class="fas fa-trophy text-amber-500"></i> {{ $m->keterangan_menang ?? 'SELESAI' }}
                                            </span>
                                        @elseif($isReady)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fas fa-bolt"></i> Siap Bertanding
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                                <i class="fas fa-clock"></i> Menunggu
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Competitor Red (Sudut Merah / Aka) -->
                                    <div class="competitor-row px-3.5 py-2.5 flex items-center justify-between border-b border-slate-100 transition-all cursor-pointer {{ $kiriMenang ? 'bg-emerald-50/70 font-bold' : ($isComplete && !$kiriMenang ? 'opacity-40' : '') }}"
                                         data-athlete-id="{{ $kiriId }}"
                                         onmouseenter="highlightAthlete('{{ $kiriId }}')"
                                         onmouseleave="unhighlightAthlete()">
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-rose-600 shrink-0 shadow-2xs"></span>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-slate-900 truncate mb-0 {{ $kiriMenang ? 'text-emerald-950 font-bold' : '' }}">
                                                    {{ $kiri->Peserta->nama ?? ($kiriId ? 'Atlet' : 'Menunggu Pemenang B'.($b-1)) }}
                                                </p>
                                                <p class="text-[10px] text-slate-500 truncate mb-0 font-medium">
                                                    {{ $kiri->Peserta->kabupaten->name ?? ($kiri->Peserta->kontigen ?? ($kiriId ? '-' : 'Babak Sebelumnya')) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0 pl-2">
                                            <span class="text-xs font-black tabular-nums {{ $kiriMenang ? 'text-emerald-700' : 'text-slate-600' }}">
                                                {{ $m->skor_kiri !== null ? $m->skor_kiri : '-' }}
                                            </span>
                                            @if($kiriMenang)
                                                <span class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[9px] font-bold">
                                                    ✓
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Competitor White/Blue (Sudut Putih/Biru / Shiro) -->
                                    <div class="competitor-row px-3.5 py-2.5 flex items-center justify-between transition-all cursor-pointer {{ $kananMenang ? 'bg-emerald-50/70 font-bold' : ($isComplete && !$kananMenang ? 'opacity-40' : '') }}"
                                         data-athlete-id="{{ $kananId }}"
                                         onmouseenter="highlightAthlete('{{ $kananId }}')"
                                         onmouseleave="unhighlightAthlete()">
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shrink-0 shadow-2xs"></span>
                                            <div class="min-w-0">
                                                @if($kanan)
                                                    <p class="text-xs font-semibold text-slate-900 truncate mb-0 {{ $kananMenang ? 'text-emerald-950 font-bold' : '' }}">
                                                        {{ $kanan->Peserta->nama ?? 'Atlet' }}
                                                    </p>
                                                    <p class="text-[10px] text-slate-500 truncate mb-0 font-medium">
                                                        {{ $kanan->Peserta->kabupaten->name ?? ($kanan->Peserta->kontigen ?? '-') }}
                                                    </p>
                                                @elseif($isBye)
                                                    <p class="text-xs font-medium text-slate-400 italic mb-0">BYE (Slot Kosong)</p>
                                                    <p class="text-[10px] text-slate-400 mb-0">-</p>
                                                @else
                                                    <p class="text-xs font-medium text-slate-400 italic mb-0">Menunggu Pemenang B{{ $b - 1 }}</p>
                                                    <p class="text-[10px] text-slate-400 mb-0">Babak Sebelumnya</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0 pl-2">
                                            <span class="text-xs font-black tabular-nums {{ $kananMenang ? 'text-emerald-700' : 'text-slate-600' }}">
                                                {{ $m->skor_kanan !== null ? $m->skor_kanan : '-' }}
                                            </span>
                                            @if($kananMenang)
                                                <span class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[9px] font-bold">
                                                    ✓
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                    </div>
                @endfor

                <!-- ==================================================== -->
                <!-- KOLOM PODIUM JUARA (CHAMPIONS SHOWCASE)               -->
                <!-- ==================================================== -->
                <div class="champion-column flex flex-col justify-center z-10 w-[280px] sm:w-[310px] shrink-0">
                    
                    <div class="mb-6 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-500 text-white shadow-md">
                            <i class="fas fa-trophy"></i> PODIUM JUARA
                        </span>
                        <p class="text-[11px] font-semibold text-slate-500 mt-1 mb-0">
                            Hasil Akhir Turnamen Randori
                        </p>
                    </div>

                    <div class="space-y-4">
                        
                        <!-- JUARA 1 (EMAS) -->
                        <div class="p-4 rounded-2xl bg-gradient-to-br from-amber-50 via-yellow-100/60 to-amber-100 border-2 border-amber-400 shadow-md relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 text-amber-200/50 text-7xl font-black pointer-events-none">
                                1
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500 text-white shadow-2xs">
                                    <i class="fas fa-medal"></i> MEDALI EMAS
                                </span>
                                <i class="fas fa-crown text-amber-500 text-lg"></i>
                            </div>
                            <h4 class="font-heading font-black text-base text-amber-950 mb-0.5 truncate">
                                {{ $juara['emas']['peserta_nama'] ?? 'Belum Ditentukan' }}
                            </h4>
                            <p class="text-xs font-semibold text-amber-800 mb-0 flex items-center gap-1">
                                <i class="fas fa-shield-alt text-amber-600"></i> {{ $juara['emas']['kabupaten_nama'] ?? 'Kontingen' }}
                            </p>
                        </div>

                        <!-- JUARA 2 (PERAK) -->
                        <div class="p-3.5 rounded-2xl bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200/80 border-2 border-slate-300 shadow-sm relative overflow-hidden">
                            <div class="absolute -right-3 -bottom-3 text-slate-300/40 text-6xl font-black pointer-events-none">
                                2
                            </div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-500 text-white shadow-2xs">
                                    <i class="fas fa-medal"></i> MEDALI PERAK
                                </span>
                            </div>
                            <h4 class="font-heading font-black text-sm text-slate-900 mb-0.5 truncate">
                                {{ $juara['perak']['peserta_nama'] ?? 'Belum Ditentukan' }}
                            </h4>
                            <p class="text-[11px] font-semibold text-slate-600 mb-0 flex items-center gap-1">
                                <i class="fas fa-shield-alt text-slate-500"></i> {{ $juara['perak']['kabupaten_nama'] ?? 'Kontingen' }}
                            </p>
                        </div>

                        <!-- JUARA 3 BERSAMA (PERUNGGU BERSAMA) -->
                        <div class="p-3.5 rounded-2xl bg-gradient-to-br from-orange-50 via-amber-50 to-orange-100/70 border-2 border-orange-300 shadow-sm relative overflow-hidden">
                            <div class="absolute -right-3 -bottom-3 text-orange-200/40 text-6xl font-black pointer-events-none">
                                3
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-700 text-white shadow-2xs">
                                    <i class="fas fa-medal"></i> PERUNGGU BERSAMA
                                </span>
                            </div>

                            <!-- Perunggu 1 -->
                            <div class="mb-2">
                                <h4 class="font-heading font-bold text-xs text-amber-950 mb-0.5 truncate">
                                    1. {{ $juara['perunggu']['peserta_nama'] ?? 'Semifinalis 1' }}
                                </h4>
                                <p class="text-[10px] font-semibold text-amber-800 mb-0 flex items-center gap-1">
                                    <i class="fas fa-shield-alt text-amber-600"></i> {{ $juara['perunggu']['kabupaten_nama'] ?? '-' }}
                                </p>
                            </div>

                            <div class="border-t border-amber-200/60 my-1.5"></div>

                            <!-- Perunggu 2 -->
                            <div>
                                <h4 class="font-heading font-bold text-xs text-amber-950 mb-0.5 truncate">
                                    2. {{ $juara['perunggu_2']['peserta_nama'] ?? 'Semifinalis 2' }}
                                </h4>
                                <p class="text-[10px] font-semibold text-amber-800 mb-0 flex items-center gap-1">
                                    <i class="fas fa-shield-alt text-amber-600"></i> {{ $juara['perunggu_2']['kabupaten_nama'] ?? '-' }}
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            @else
                <!-- Fallback jika belum diundi atau bukan Randori -->
                <div class="p-12 text-center bg-white rounded-3xl border border-slate-200 shadow-sm max-w-md mx-auto my-auto">
                    <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4 border border-rose-100">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-1">
                        Bagan Pertandingan Belum Tersedia
                    </h3>
                    <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                        Nomor tanding ini belum diundi bagannya atau merupakan kategori Embu (penilaian juri). Silakan lakukan pengundian bagan terlebih dahulu pada halaman manajemen tanding.
                    </p>
                    <a href="{{ url('/pertandingan/'.$pertandingan->id.'?kegiatan_id='.(request('kegiatan_id') ?? optional($pertandingan->kegiatan)->id)) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-colors">
                        <i class="fas fa-arrow-left"></i> Kembali ke Manajemen Tanding
                    </a>
                </div>
            @endif

        </div>

    </main>

    <!-- ======================================================== -->
    <!-- INTERACTIVE SCRIPT (SVG CONNECTOR & ZOOM & HOVER)        -->
    <!-- ======================================================== -->
    <script>
        let currentZoom = 1.0;

        function zoomBracket(delta) {
            currentZoom = Math.min(1.8, Math.max(0.4, currentZoom + delta));
            applyZoom();
        }

        function resetZoom() {
            currentZoom = 1.0;
            applyZoom();
        }

        function applyZoom() {
            const wrapper = document.getElementById('bracketWrapper');
            const text = document.getElementById('zoomText');
            if (wrapper) {
                wrapper.style.transform = `scale(${currentZoom})`;
            }
            if (text) {
                text.innerText = Math.round(currentZoom * 100) + '%';
            }
            setTimeout(drawBracketConnectors, 150);
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.error(err));
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Highlight semua node atlet saat di-hover
        function highlightAthlete(athleteId) {
            if (!athleteId || athleteId === 'null') return;
            const rows = document.querySelectorAll(`[data-athlete-id="${athleteId}"]`);
            rows.forEach(r => r.classList.add('athlete-highlight'));

            const lines = document.querySelectorAll(`[data-line-athlete="${athleteId}"]`);
            lines.forEach(l => l.classList.add('highlighted'));
        }

        function unhighlightAthlete() {
            const rows = document.querySelectorAll('.athlete-highlight');
            rows.forEach(r => r.classList.remove('athlete-highlight'));

            const lines = document.querySelectorAll('.connector-line.highlighted');
            lines.forEach(l => l.classList.remove('highlighted'));
        }

        // Gambar Garis Konektor Antar Babak di SVG Canvas
        function drawBracketConnectors() {
            const svg = document.getElementById('bracketSvgCanvas');
            const wrapper = document.getElementById('bracketWrapper');
            if (!svg || !wrapper) return;

            // Bersihkan path lama
            svg.innerHTML = '';

            const wrapperRect = wrapper.getBoundingClientRect();
            svg.setAttribute('viewBox', `0 0 ${wrapper.offsetWidth} ${wrapper.offsetHeight}`);

            const matchCards = document.querySelectorAll('.match-card');
            
            matchCards.forEach(card => {
                const babak = parseInt(card.getAttribute('data-babak'));
                const partai = parseInt(card.getAttribute('data-partai'));
                const winnerId = card.getAttribute('data-winner-id');

                // Cari partai tujuan di babak berikutnya (babak + 1, ceil(partai / 2))
                const nextBabak = babak + 1;
                const nextPartai = Math.ceil(partai / 2);

                const nextCard = document.querySelector(`.match-card[data-babak="${nextBabak}"][data-partai="${nextPartai}"]`);
                if (!nextCard) return;

                // Titik Awal: Sisi Kanan Tengah dari Match Card saat ini
                const fromRect = card.getBoundingClientRect();
                const toRect = nextCard.getBoundingClientRect();

                // Konversi koordinat relatif terhadap wrapper (dengan mengabaikan scale saat ini)
                const startX = (fromRect.right - wrapperRect.left) / currentZoom;
                const startY = (fromRect.top + fromRect.height / 2 - wrapperRect.top) / currentZoom;

                // Titik Tujuan: Sisi Kiri dari Match Card berikutnya
                // Jika partai ganjil -> sudut kiri (merah, sepertiga atas)
                // Jika partai genap -> sudut kanan (biru, sepertiga bawah)
                const isOdd = (partai % 2 !== 0);
                const targetYOffset = isOdd ? (toRect.height * 0.35) : (toRect.height * 0.75);

                const endX = (toRect.left - wrapperRect.left) / currentZoom;
                const endY = (toRect.top + targetYOffset - wrapperRect.top) / currentZoom;

                const midX = startX + (endX - startX) * 0.5;

                // Buat Orthogonal Path (garis lurus siku-siku turnamen)
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                const d = `M ${startX} ${startY} H ${midX} V ${endY} H ${endX}`;
                path.setAttribute('d', d);

                const isPathActive = (winnerId && winnerId !== 'null');
                path.setAttribute('class', `connector-line ${isPathActive ? 'active' : ''}`);

                if (winnerId && winnerId !== 'null') {
                    path.setAttribute('data-line-athlete', winnerId);
                }

                svg.appendChild(path);
            });
        }

        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(drawBracketConnectors, 200);
        });

        window.addEventListener('resize', () => {
            setTimeout(drawBracketConnectors, 150);
        });
    </script>
</body>
</html>
