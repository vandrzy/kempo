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

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        /* Click-to-Swap Mode Styles */
        .edit-mode-active .bracket-slot,
        .edit-mode-active .bracket-slot-b1 {
            cursor: pointer !important;
            transition: all 0.15s ease-in-out;
            outline: 2px dashed #94a3b8;
            outline-offset: -2px;
            border-radius: 8px;
        }

        .edit-mode-active .bracket-slot:hover,
        .edit-mode-active .bracket-slot-b1:hover {
            outline-color: #f59e0b !important;
            background-color: #fefce8 !important;
            transform: scale(1.02);
            z-index: 15;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2) !important;
        }

        .edit-mode-active .bracket-slot.slot-selected,
        .edit-mode-active .bracket-slot-b1.slot-selected {
            background-color: #fef3c7 !important;
            outline: 2.5px solid #d97706 !important;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.35) !important;
            transform: scale(1.03);
            z-index: 25;
        }

        .edit-mode-active .bracket-slot.slot-locked,
        .edit-mode-active .bracket-slot-b1.slot-locked {
            cursor: not-allowed !important;
            opacity: 0.55 !important;
            outline: 1.5px solid #e2e8f0 !important;
        }

        .edit-mode-active .bracket-slot.slot-locked:hover,
        .edit-mode-active .bracket-slot-b1.slot-locked:hover {
            transform: none !important;
            background-color: transparent !important;
            outline-color: #e2e8f0 !important;
            box-shadow: none !important;
        }

        .edit-mode-active .bracket-slot.slot-locked .edit-mode-lock-badge,
        .edit-mode-active .bracket-slot-b1.slot-locked .edit-mode-lock-badge {
            display: inline-flex !important;
        }

        #bracketSvgCanvas {
            pointer-events: none;
            overflow: visible;
        }

        #bracketSvgCanvas path {
            fill: none !important;
        }

        .connector-line {
            fill: none !important;
            stroke: #94a3b8;
            stroke-width: 2px;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: stroke 0.2s ease, stroke-width 0.2s ease;
        }

        .connector-line.active {
            stroke: #10b981 !important;
            stroke-width: 2.5px !important;
            filter: drop-shadow(0 1px 3px rgba(16, 185, 129, 0.4));
        }

        .connector-line.final-champion-line.active {
            stroke: #f59e0b !important;
            stroke-width: 3px !important;
            filter: drop-shadow(0 2px 4px rgba(245, 158, 11, 0.5));
        }

        .connector-line.highlighted {
            stroke: #059669 !important;
            stroke-width: 3.5px !important;
            filter: drop-shadow(0 2px 6px rgba(5, 150, 105, 0.6));
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
            @page {
                size: landscape A4;
                margin: 5mm;
            }
            html, body {
                background: white !important;
                color: #0f172a !important;
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .bg-dot-pattern {
                background-image: none !important;
            }
            #bracketMain {
                overflow: visible !important;
                display: block !important;
                padding: 0 !important;
                margin: 0 !important;
                min-height: 0 !important;
                background: none !important;
            }
            #bracketScrollArea {
                display: block !important;
                width: 100% !important;
                overflow: visible !important;
            }
            #bracketWrapper {
                display: flex !important;
                flex-direction: row !important;
                align-items: stretch !important;
                justify-content: space-between !important;
                width: 100% !important;
                max-width: 100% !important;
                gap: 8px !important;
                transform: none !important;
                overflow: visible !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .round-column, .champion-column {
                width: auto !important;
                flex: 1 1 0% !important;
                min-width: 0 !important;
                padding: 0 !important;
            }
            .match-card {
                box-shadow: none !important;
                border: 1px solid #94a3b8 !important;
                break-inside: avoid;
                page-break-inside: avoid;
                border-radius: 6px !important;
                margin-bottom: 4px !important;
            }
            .match-card .px-3\.5 {
                padding-left: 5px !important;
                padding-right: 5px !important;
            }
            .match-card .py-2, .match-card .py-2\.5 {
                padding-top: 2px !important;
                padding-bottom: 2px !important;
            }
            .match-card p {
                font-size: 9.5px !important;
            }
            #bracketSvgCanvas {
                display: block !important;
                position: absolute !important;
                inset: 0 !important;
                width: 100% !important;
                height: 100% !important;
                pointer-events: none !important;
                z-index: 0 !important;
                overflow: visible !important;
            }
            #bracketSvgCanvas path {
                fill: none !important;
            }
            .connector-line {
                stroke: #475569 !important;
                stroke-width: 1.5px !important;
                fill: none !important;
            }
            .connector-line.active {
                stroke: #059669 !important;
                stroke-width: 2px !important;
            }
            .connector-line.final-champion-line.active {
                stroke: #d97706 !important;
                stroke-width: 2px !important;
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
                    <button type="button" onclick="fitBracketToScreen()" class="px-2.5 h-8 rounded-lg bg-white hover:bg-slate-200 text-slate-800 text-xs font-bold transition-colors cursor-pointer border border-slate-200 inline-flex items-center gap-1.5 shadow-2xs" title="Sesuaikan Tampilan dengan Lebar Layar">
                        <i class="fas fa-compress-arrows-alt text-slate-600"></i> Fit Layar
                    </button>
                    <button type="button" onclick="resetZoom()" class="px-2.5 h-8 rounded-lg hover:bg-white text-slate-600 hover:text-slate-900 text-xs font-bold transition-colors cursor-pointer" title="Reset Zoom ke 100%">
                        100%
                    </button>
                </div>

                <!-- Fullscreen Toggle -->
                <button type="button" onclick="toggleFullscreen()" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors border border-slate-200 cursor-pointer" title="Toggle Fullscreen">
                    <i class="fas fa-expand text-sm"></i>
                </button>

                <!-- Edit Bagan Toggle Button -->
                <button type="button" id="btnToggleEditBagan" onclick="toggleEditBaganMode()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white text-xs font-bold tracking-wide shadow-xs hover:shadow transition-all cursor-pointer" title="Aktifkan Mode Tukar Posisi Peserta">
                    <i class="fas fa-random"></i> <span id="textToggleEditBagan">Mode Edit Bagan</span>
                </button>

                <!-- Print Button -->
                <button type="button" onclick="printBagan()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-xs font-bold tracking-wide shadow-sm hover:shadow transition-all cursor-pointer">
                    <i class="fas fa-print"></i> Cetak Bagan
                </button>
            </div>

        </div>
    </header>

    <!-- ======================================================== -->
    <!-- STICKY ALERT BANNER MODE EDIT BAGAN                      -->
    <!-- ======================================================== -->
    <div id="bannerEditMode" class="hidden no-print sticky top-[61px] z-40 bg-gradient-to-r from-amber-500 via-amber-600 to-amber-500 text-white shadow-md border-b border-amber-600 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-white/20 text-white font-black text-xs shrink-0">
                    <i class="fas fa-info animate-pulse"></i>
                </span>
                <div class="min-w-0">
                    <p class="font-bold mb-0">Mode Edit Bagan Aktif (Click-to-Swap)</p>
                    <p class="text-[11px] text-amber-100 mb-0 truncate" id="editModeBannerInstruction">
                        Klik salah satu slot peserta yang ingin ditukar, lalu klik slot tujuan untuk menukar posisinya.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span id="badgeSelectedSlot" class="hidden px-2.5 py-1 bg-black/25 text-amber-100 rounded-lg text-[11px] font-bold border border-white/20"></span>
                <button type="button" id="btnCancelSlot" onclick="cancelSelectedSlot()" class="hidden px-2.5 py-1 bg-white hover:bg-amber-50 text-amber-900 rounded-lg text-xs font-bold transition-colors cursor-pointer shadow-xs">
                    <i class="fas fa-times"></i> Batal Pilih
                </button>
                <button type="button" onclick="toggleEditBaganMode(false)" class="px-2.5 py-1 bg-amber-700/60 hover:bg-amber-800 text-white rounded-lg text-xs font-bold transition-colors cursor-pointer border border-white/10">
                    Keluar Mode Edit
                </button>
            </div>
        </div>
    </div>

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
    <main id="bracketMain" class="flex-1 overflow-x-auto overflow-y-auto bg-dot-pattern p-4 sm:p-6 lg:p-8 min-h-[calc(100vh-68px)]">
        
        <div id="bracketScrollArea" class="w-max min-w-full flex items-start justify-start py-2">
            <div id="bracketWrapper" class="relative inline-flex items-stretch gap-6 sm:gap-8 lg:gap-10 py-4 mx-auto origin-top-left" style="transform: scale(1);">
                
                <!-- SVG Canvas untuk garis penghubung antar partai -->
                <svg id="bracketSvgCanvas" fill="none" class="absolute inset-0 pointer-events-none z-0 w-full h-full" style="fill: none;"></svg>

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
                        <div class="round-column flex flex-col justify-around z-10 w-[260px] sm:w-[280px] shrink-0" data-babak="{{ $b }}">
                            
                            <!-- Header Babak -->
                            <div class="mb-5 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider border {{ $badgeColor }}">
                                    {{ $namaBabak }}
                                </span>
                                <p class="text-[11px] font-semibold text-slate-500 mt-1 mb-0">
                                    {{ $roundMatches->count() }} Partai Duel
                                </p>
                            </div>

                            <!-- Daftar Partai Duel di Babak Ini -->
                            <div class="flex-1 flex flex-col {{ ($b == 1 && $roundMatches->count() == 1) ? 'justify-start gap-4 pt-2' : 'justify-around gap-6 sm:gap-8' }}">
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
                                    $isPlayedThisMatch = (floatval($m->skor_kiri) > 0 || floatval($m->skor_kanan) > 0 || ($pemenangId && strtoupper($m->keterangan_menang ?? '') !== 'BYE'));
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
                                    <div class="competitor-row px-3.5 py-2.5 flex items-center justify-between border-b border-slate-100 transition-all bracket-slot bracket-slot-b1 {{ $isPlayedThisMatch ? 'slot-locked' : '' }} {{ $kiriMenang ? 'bg-emerald-50/70 font-bold' : ($isComplete && !$kiriMenang ? 'opacity-40' : '') }}"
                                         data-match-id="{{ $m->id }}"
                                         data-babak="{{ $b }}"
                                         data-corner="merah"
                                         data-partai="{{ $m->nomor_partai }}"
                                         data-athlete-id="{{ $kiriId }}"
                                         data-athlete-name="{{ $kiri ? ($kiri->Peserta->nama ?? 'Atlet') : 'BYE / Kosong' }}"
                                         data-is-played="{{ $isPlayedThisMatch ? '1' : '0' }}"
                                         onclick="handleSlotClick(this, event)"
                                         onmouseenter="highlightAthlete('{{ $kiriId }}')"
                                         onmouseleave="unhighlightAthlete()">
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-rose-600 shrink-0 shadow-2xs"></span>
                                            <div class="min-w-0">
                                                @if($kiri)
                                                    <p class="text-xs font-semibold text-slate-900 truncate mb-0 {{ $kiriMenang ? 'text-emerald-950 font-bold' : '' }}">
                                                        {{ $kiri->Peserta->nama ?? 'Atlet' }}
                                                        @if($isPlayedThisMatch)
                                                            <span class="edit-mode-lock-badge hidden text-slate-400 text-[10px] ml-1.5" title="Partai sudah dimainkan, tidak dapat ditukar"><i class="fas fa-lock"></i></span>
                                                        @endif
                                                    </p>
                                                    <p class="text-[10px] text-slate-500 truncate mb-0 font-medium">
                                                        {{ $kiri->Peserta->kabupaten->name ?? ($kiri->Peserta->kontigen ?? ($kiriId ? 'Tanpa Kontingen' : 'Belum Ada')) }}
                                                    </p>
                                                @else
                                                    <p class="text-xs font-medium text-slate-400 italic mb-0">
                                                        {{ $m->getWaitingPlaceholderText('kiri') }}
                                                        @if($b == 1)
                                                            <span class="text-amber-600 font-bold text-[10px] ml-1">(Slot BYE)</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-[10px] text-slate-400 mb-0">Belum Ada</p>
                                                @endif
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
                                    </div>                                     <!-- Competitor White (Sudut Putih) -->
                                    <div class="competitor-row px-3.5 py-2.5 flex items-center justify-between transition-all bracket-slot bracket-slot-b1 {{ $isPlayedThisMatch ? 'slot-locked' : '' }} {{ $kananMenang ? 'bg-emerald-50/70 font-bold' : ($isComplete && !$kananMenang ? 'opacity-40' : '') }}"
                                         data-match-id="{{ $m->id }}"
                                         data-babak="{{ $b }}"
                                         data-corner="putih"
                                         data-partai="{{ $m->nomor_partai }}"
                                         data-athlete-id="{{ $kananId }}"
                                         data-athlete-name="{{ $kanan ? ($kanan->Peserta->nama ?? 'Atlet') : 'BYE / Kosong' }}"
                                         data-is-played="{{ $isPlayedThisMatch ? '1' : '0' }}"
                                         onclick="handleSlotClick(this, event)"
                                         onmouseenter="highlightAthlete('{{ $kananId }}')"
                                         onmouseleave="unhighlightAthlete()">
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shrink-0 shadow-2xs"></span>
                                            <div class="min-w-0">
                                                @if($kanan)
                                                    <p class="text-xs font-semibold text-slate-900 truncate mb-0 {{ $kananMenang ? 'text-emerald-950 font-bold' : '' }}">
                                                        {{ $kanan->Peserta->nama ?? 'Atlet' }}
                                                        @if($isPlayedThisMatch)
                                                            <span class="edit-mode-lock-badge hidden text-slate-400 text-[10px] ml-1.5" title="Partai sudah dimainkan, tidak dapat ditukar"><i class="fas fa-lock"></i></span>
                                                        @endif
                                                    </p>
                                                    <p class="text-[10px] text-slate-500 truncate mb-0 font-medium">
                                                        {{ $kanan->Peserta->kabupaten->name ?? ($kanan->Peserta->kontigen ?? 'Tanpa Kontingen') }}
                                                    </p>
                                                @elseif($isBye)
                                                    <p class="text-xs font-medium text-slate-400 italic mb-0">
                                                        Belum Ada Lawan (BYE)
                                                        @if($b == 1)
                                                            <span class="text-amber-600 font-bold text-[10px] ml-1">(Slot BYE)</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-[10px] text-slate-400 mb-0">Belum Ada</p>
                                                @else
                                                    <p class="text-xs font-medium text-slate-400 italic mb-0">{{ $m->getWaitingPlaceholderText('kanan') }}</p>
                                                    <p class="text-[10px] text-slate-400 mb-0">Belum Ada</p>
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
                <div class="champion-column flex flex-col justify-center z-10 w-[250px] sm:w-[270px] shrink-0">
                    
                    <div class="mb-5 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-500 text-white shadow-md">
                            <i class="fas fa-trophy"></i> PODIUM JUARA
                        </span>
                        <p class="text-[11px] font-semibold text-slate-500 mt-1 mb-0">
                            Hasil Akhir Turnamen Randori
                        </p>
                    </div>

                    <div class="space-y-4">
                        
                        <!-- JUARA 1 (EMAS) -->
                        <div id="podiumJuara1Card" class="p-4 rounded-2xl bg-gradient-to-br from-amber-50 via-yellow-100/60 to-amber-100 border-2 border-amber-400 shadow-md relative overflow-hidden">
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
        const totalBabakGlobal = {{ $totalBabak ?? 1 }};

        function zoomBracket(delta) {
            currentZoom = Math.min(1.8, Math.max(0.35, Math.round((currentZoom + delta) * 100) / 100));
            applyZoom();
        }

        function resetZoom() {
            currentZoom = 1.0;
            applyZoom();
        }

        function fitBracketToScreen() {
            const main = document.getElementById('bracketMain');
            const wrapper = document.getElementById('bracketWrapper');
            if (!main || !wrapper) return;

            // Reset scale sejenak untuk mengukur lebar asli (unscaled)
            wrapper.style.transform = 'scale(1)';
            const naturalWidth = wrapper.scrollWidth || wrapper.offsetWidth;
            const availableWidth = main.clientWidth - 48; // padding aman

            if (naturalWidth > 0 && availableWidth > 0) {
                let scale = availableWidth / naturalWidth;
                // Batasi scale antara 0.40 dan 1.0 (agar tidak terlalu besar di layar 4K, pas di laptop)
                scale = Math.min(1.0, Math.max(0.40, Math.floor(scale * 100) / 100));
                currentZoom = scale;
            } else {
                currentZoom = 1.0;
            }
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
            requestAnimationFrame(drawBracketConnectors);
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

            const w = wrapper.offsetWidth;
            const h = wrapper.offsetHeight;
            if (w <= 0 || h <= 0) return;

            svg.setAttribute('viewBox', `0 0 ${w} ${h}`);
            svg.setAttribute('fill', 'none');

            const wrapRect = wrapper.getBoundingClientRect();
            const zoom = currentZoom || 1.0;

            function createPathElement(d, isActive, isFinal = false, winnerId = null) {
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('d', d);
                path.setAttribute('fill', 'none');

                let strokeColor = '#94a3b8';
                let strokeWidth = '2';

                if (isFinal && isActive) {
                    strokeColor = '#f59e0b';
                    strokeWidth = '3';
                } else if (isActive) {
                    strokeColor = '#10b981';
                    strokeWidth = '2.5';
                }

                path.setAttribute('stroke', strokeColor);
                path.setAttribute('stroke-width', strokeWidth);
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');

                let className = 'connector-line';
                if (isActive) {
                    className += ' active';
                    if (isFinal) className += ' final-champion-line';
                }
                path.setAttribute('class', className);

                if (winnerId && winnerId !== 'null') {
                    path.setAttribute('data-line-athlete', winnerId);
                }

                svg.appendChild(path);
                return path;
            }

            // 1. Hubungkan setiap Match Card ke Babak Berikutnya
            const matchCards = document.querySelectorAll('.match-card');
            matchCards.forEach(card => {
                const babak = parseInt(card.getAttribute('data-babak'));
                const partai = parseInt(card.getAttribute('data-partai'));
                const winnerId = card.getAttribute('data-winner-id');

                const nextBabak = babak + 1;
                let nextPartai = Math.ceil(partai / 2);
                let isOdd = (partai % 2 !== 0);

                // Cek kasus khusus 6 peserta: Babak 1 ada 2 partai dan Babak 2 ada 2 partai
                const b1Cards = document.querySelectorAll('.match-card[data-babak="1"]');
                const b2Cards = document.querySelectorAll('.match-card[data-babak="2"]');
                if (babak === 1 && b1Cards.length === 2 && b2Cards.length === 2) {
                    nextPartai = partai; // Babak 1 Partai 1 -> Semifinal 1, Babak 1 Partai 2 -> Semifinal 2
                    isOdd = true;        // Masuk ke Sudut Merah / Kiri (bagian atas kartu)
                }

                const nextCard = document.querySelector(`.match-card[data-babak="${nextBabak}"][data-partai="${nextPartai}"]`);
                if (!nextCard) return;

                const fromRect = card.getBoundingClientRect();
                const toRect = nextCard.getBoundingClientRect();

                const startX = Math.round((fromRect.right - wrapRect.left) / zoom);
                const startY = Math.round((fromRect.top + fromRect.height / 2 - wrapRect.top) / zoom);

                // Partai ganjil -> Slot Merah (bagian atas, ~35% tinggi kartu)
                // Partai genap  -> Slot Putih (bagian bawah, ~75% tinggi kartu)
                const targetYOffset = isOdd ? (toRect.height * 0.35) : (toRect.height * 0.75);

                const endX = Math.round((toRect.left - wrapRect.left) / zoom);
                const endY = Math.round((toRect.top + targetYOffset - wrapRect.top) / zoom);

                const midX = Math.round(startX + (endX - startX) * 0.5);

                const d = `M ${startX} ${startY} H ${midX} V ${endY} H ${endX}`;
                const isPathActive = Boolean(winnerId && winnerId !== 'null');

                createPathElement(d, isPathActive, false, winnerId);
            });

            // 2. Hubungkan Partai FINAL ke PODIUM JUARA 1 (EMAS)
            const finalCard = document.querySelector(`.match-card[data-babak="${totalBabakGlobal}"][data-partai="1"]`);
            const podiumCard = document.getElementById('podiumJuara1Card');
            if (finalCard && podiumCard) {
                const fromRect = finalCard.getBoundingClientRect();
                const toRect = podiumCard.getBoundingClientRect();

                const startX = Math.round((fromRect.right - wrapRect.left) / zoom);
                const startY = Math.round((fromRect.top + fromRect.height / 2 - wrapRect.top) / zoom);
                const endX = Math.round((toRect.left - wrapRect.left) / zoom);
                const endY = Math.round((toRect.top + toRect.height / 2 - wrapRect.top) / zoom);
                const midX = Math.round(startX + (endX - startX) * 0.45);

                const dFinal = `M ${startX} ${startY} H ${midX} V ${endY} H ${endX}`;
                const winnerId = finalCard.getAttribute('data-winner-id');
                const isFinalActive = Boolean(winnerId && winnerId !== 'null');

                createPathElement(dFinal, isFinalActive, true, winnerId);
            }
        }

        function printBagan() {
            drawBracketConnectors();
            setTimeout(() => {
                window.print();
            }, 80);
        }

        window.addEventListener('beforeprint', () => {
            drawBracketConnectors();
        });

        window.addEventListener('afterprint', () => {
            fitBracketToScreen();
            drawBracketConnectors();
        });

        window.addEventListener('DOMContentLoaded', () => {
            fitBracketToScreen();
            setTimeout(drawBracketConnectors, 150);
        });

        window.addEventListener('load', () => {
            drawBracketConnectors();
        });

        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(() => {
                drawBracketConnectors();
            });
        }

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                fitBracketToScreen();
                drawBracketConnectors();
            }, 120);
        });

        // ========================================================
        // FASE 3: LOGIKA CLICK-TO-SWAP EDIT BAGAN MANUAL
        // ========================================================
        let isEditBaganMode = false;
        let selectedSlot = null;
        const listKategoriId = {{ $pertandingan->id }};

        function toggleEditBaganMode(forceState) {
            if (typeof forceState === 'boolean') {
                isEditBaganMode = forceState;
            } else {
                isEditBaganMode = !isEditBaganMode;
            }

            const banner = document.getElementById('bannerEditMode');
            const btn = document.getElementById('btnToggleEditBagan');
            const textBtn = document.getElementById('textToggleEditBagan');

            if (isEditBaganMode) {
                document.body.classList.add('edit-mode-active');
                if (banner) banner.classList.remove('hidden');
                if (btn) {
                    btn.classList.remove('bg-amber-500', 'hover:bg-amber-600');
                    btn.classList.add('bg-slate-800', 'hover:bg-slate-900', 'ring-2', 'ring-amber-400');
                }
                if (textBtn) textBtn.innerText = 'Keluar Mode Edit';
                
                document.querySelectorAll('.edit-mode-lock-badge').forEach(el => el.classList.remove('hidden'));
            } else {
                cancelSelectedSlot();
                document.body.classList.remove('edit-mode-active');
                if (banner) banner.classList.add('hidden');
                if (btn) {
                    btn.classList.add('bg-amber-500', 'hover:bg-amber-600');
                    btn.classList.remove('bg-slate-800', 'hover:bg-slate-900', 'ring-2', 'ring-amber-400');
                }
                if (textBtn) textBtn.innerText = 'Mode Edit Bagan';
                
                document.querySelectorAll('.edit-mode-lock-badge').forEach(el => el.classList.add('hidden'));
            }
        }

        function handleSlotClick(el, event) {
            if (!isEditBaganMode) return;
            
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            const isPlayed = el.getAttribute('data-is-played') === '1';
            if (isPlayed) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Partai Terkunci',
                    text: 'Partai ini sudah berlangsung atau selesai, posisinya tidak dapat diubah.',
                    confirmButtonColor: '#d97706'
                });
                return;
            }

            const matchId = parseInt(el.getAttribute('data-match-id'));
            const babak = parseInt(el.getAttribute('data-babak'));
            const corner = el.getAttribute('data-corner');
            const partai = el.getAttribute('data-partai');
            const athleteName = el.getAttribute('data-athlete-name') || 'BYE / Kosong';
            const athleteId = el.getAttribute('data-athlete-id');

            // Mencegah memilih slot kosong di babak lanjutan yang menunggu pemenang partai sebelumnya
            if ((!athleteId || athleteId === 'null') && babak > 1) {
                Swal.fire({
                    icon: 'info',
                    title: 'Slot Menunggu Pemenang',
                    text: 'Slot ini dialokasikan untuk pemenang partai duel babak sebelumnya dan akan terisi secara otomatis.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            // Klik pada slot yang sama -> Batalkan pilihan
            if (selectedSlot && selectedSlot.matchId === matchId && selectedSlot.corner === corner) {
                cancelSelectedSlot();
                return;
            }

            // KLIK PERTAMA: MEMILIH SOURCE
            if (!selectedSlot) {
                selectedSlot = { matchId, corner, partai, athleteName, athleteId, element: el };
                el.classList.add('slot-selected');
                
                const badge = document.createElement('span');
                badge.className = 'slot-badge-selected ml-2 inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black bg-amber-600 text-white shrink-0 animate-bounce';
                badge.innerHTML = '<i class="fas fa-check mr-0.5"></i> DIPILIH';
                el.appendChild(badge);

                const badgeBanner = document.getElementById('badgeSelectedSlot');
                if (badgeBanner) {
                    badgeBanner.innerText = `Asal: Partai #${partai} [${corner.toUpperCase()}] - ${athleteName}`;
                    badgeBanner.classList.remove('hidden');
                }
                const btnCancel = document.getElementById('btnCancelSlot');
                if (btnCancel) btnCancel.classList.remove('hidden');

                const instr = document.getElementById('editModeBannerInstruction');
                if (instr) instr.innerText = 'Sekarang klik slot tujuan (atlet lain atau slot BYE) di bagan untuk menukar posisinya.';
                return;
            }

            // KLIK KEDUA: MEMILIH TARGET
            const targetSlot = { matchId, corner, partai, athleteName, athleteId, element: el };
            const cornerLabel = (c) => (c === 'merah' ? 'Merah' : 'Putih');

            Swal.fire({
                title: 'Konfirmasi Tukar Posisi Bagan',
                html: `
                    <div class="text-left text-sm p-3.5 bg-slate-50 rounded-2xl border border-slate-200 shadow-inner">
                        <div class="flex items-start gap-2.5 mb-3">
                            <span class="w-3.5 h-3.5 rounded-full ${selectedSlot.corner === 'merah' ? 'bg-rose-600' : 'bg-blue-600'} mt-1 shrink-0"></span>
                            <div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Slot Asal (Partai #${selectedSlot.partai} - Sudut ${cornerLabel(selectedSlot.corner)}):</div>
                                <div class="font-extrabold text-base text-slate-900">${selectedSlot.athleteName}</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-center my-2 text-amber-500">
                            <div class="h-px bg-slate-200 flex-1"></div>
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-black flex items-center gap-1.5 border border-amber-300">
                                <i class="fas fa-arrows-alt-v"></i> DITUKAR DENGAN
                            </span>
                            <div class="h-px bg-slate-200 flex-1"></div>
                        </div>

                        <div class="flex items-start gap-2.5 mt-3">
                            <span class="w-3.5 h-3.5 rounded-full ${targetSlot.corner === 'merah' ? 'bg-rose-600' : 'bg-blue-600'} mt-1 shrink-0"></span>
                            <div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Slot Tujuan (Partai #${targetSlot.partai} - Sudut ${cornerLabel(targetSlot.corner)}):</div>
                                <div class="font-extrabold text-base text-slate-900">${targetSlot.athleteName}</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-3 text-center mb-0">
                        <i class="fas fa-shield-alt text-emerald-600 mr-1"></i> Status kemajuan babak berikutnya (BYE / partai normal) akan otomatis diselaraskan oleh sistem.
                    </p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-check mr-1.5"></i> Ya, Tukar Posisi',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                focusConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    executeSwap(selectedSlot, targetSlot);
                } else {
                    cancelSelectedSlot();
                }
            });
        }

        function cancelSelectedSlot() {
            if (selectedSlot && selectedSlot.element) {
                selectedSlot.element.classList.remove('slot-selected');
                const badge = selectedSlot.element.querySelector('.slot-badge-selected');
                if (badge) badge.remove();
            }
            selectedSlot = null;
            const badgeBanner = document.getElementById('badgeSelectedSlot');
            if (badgeBanner) badgeBanner.classList.add('hidden');
            const btnCancel = document.getElementById('btnCancelSlot');
            if (btnCancel) btnCancel.classList.add('hidden');
            const instr = document.getElementById('editModeBannerInstruction');
            if (instr) instr.innerText = 'Klik salah satu slot peserta yang ingin ditukar, lalu klik slot tujuan untuk menukar posisinya.';
        }

        function executeSwap(source, target) {
            Swal.fire({
                title: 'Menyimpan Perubahan Bagan...',
                text: 'Sedang memproses penukaran posisi dan menyelaraskan bagan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('/api/randori/swap-bracket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({
                    id_kelas: listKategoriId,
                    source: {
                        match_id: source.matchId,
                        corner: source.corner
                    },
                    target: {
                        match_id: target.matchId,
                        corner: target.corner
                    }
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, ok: res.ok, data })))
            .then(result => {
                if (!result.ok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menukar Bagan',
                        text: result.data.message || 'Terjadi kesalahan pada server saat menukar bagan.',
                        confirmButtonColor: '#e11d48'
                    });
                    cancelSelectedSlot();
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Ditukar!',
                    text: result.data.message || 'Posisi bagan berhasil diperbarui.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan',
                    text: 'Gagal terhubung ke server: ' + err.message,
                    confirmButtonColor: '#e11d48'
                });
                cancelSelectedSlot();
            });
        }
    </script>
</body>
</html>
