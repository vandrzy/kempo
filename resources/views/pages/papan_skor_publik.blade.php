@php
    $pertandinganId = 0;
    if (isset($pertandingan) && $pertandingan) {
        $pertandinganId = $pertandingan->id;
    } elseif (!empty($structuredData)) {
        foreach ($structuredData as $kat) {
            if (!empty($kat['tandings'])) {
                $pertandinganId = $kat['tandings'][0]['pertandingan_id'] ?? ($kat['tandings'][0]['id'] ?? 0);
                break;
            }
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Papan Skor Live - {{ $kegiatan->nama_kegiatan ?? $kegiatan->nama }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-removebg.png') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
            height: 100vh;
            max-height: 100vh;
            overflow: hidden !important;
            user-select: none;
            font-family: 'Open Sans', sans-serif;
        }
        .score-font {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="m-0 bg-slate-100 text-slate-800 h-screen max-h-screen overflow-hidden flex flex-col p-2 sm:p-3 justify-between">

    <!-- ============================================== -->
    <!-- 1. HEADER & FILTER KONTROL (TEMA TERANG ARGON) -->
    <!-- ============================================== -->
    <header class="bg-white rounded-xl px-3 py-2 border border-slate-200/90 shadow-sm flex-shrink-0 mb-1.5">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200/80 pb-1.5 mb-1.5">
            <!-- Title Kegiatan -->
            <div class="flex items-center gap-2 min-w-0">
                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 rounded-md">Live Scoreboard</span>
                <h1 class="text-xs sm:text-sm md:text-base font-extrabold text-slate-800 truncate">
                    {{ $kegiatan->nama_kegiatan ?? $kegiatan->nama }}
                </h1>
            </div>

            <!-- Controls (Live Sync Status, Sinkronkan DB, Fullscreen) -->
            <div class="flex items-center gap-2 flex-shrink-0">
                <span id="saveStatus" class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Sync
                </span>
                <button type="button" id="btnRefreshDb" title="Ambil Data Terbaru dari Database (Tarik Nilai Terbaru)"
                    class="px-2.5 py-1 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 active:scale-95 border border-slate-300 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer">
                    <i class="fas fa-sync-alt text-blue-600" id="refreshIcon"></i>
                    <span class="hidden sm:inline">Sinkronkan Database</span>
                </button>
                <button type="button" onclick="toggleFullscreen()" title="Layar Penuh (Share Screen)"
                    class="px-2.5 py-1 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 active:scale-95 border border-slate-300 rounded-lg transition-all flex items-center gap-1 cursor-pointer">
                    <i class="fas fa-expand text-slate-600"></i>
                </button>
            </div>
        </div>

        <!-- Filter Dropdown Dinamis (3 Kolom untuk Randori, 4 Kolom untuk Embu) -->
        <div id="filterDropdownGrid" class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
            <!-- Kategori -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-0.5">Kategori</label>
                <select id="selectKategori" class="w-full px-2 py-1 text-xs font-semibold bg-slate-50 border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors">
                    <option value="">-- Pilih Kategori --</option>
                </select>
            </div>

            <!-- Tanding -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-0.5">Tanding</label>
                <select id="selectTanding" class="w-full px-2 py-1 text-xs font-semibold bg-slate-50 border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors" disabled>
                    <option value="">-- Pilih Tanding --</option>
                </select>
            </div>

            <!-- Peserta / Babak & Partai (Randori) -->
            <div>
                <label id="labelDropdown3" class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-0.5 flex items-center">
                    <i class="fas fa-user-ninja mr-1 text-blue-500"></i> Pilih Peserta
                </label>
                <select id="selectPeserta" class="w-full px-2 py-1 text-xs font-semibold bg-slate-50 border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors" disabled>
                    <option value="">-- Pilih Peserta --</option>
                </select>
            </div>

            <!-- Tampilan Babak (Khusus Kategori Embu) -->
            <div id="containerFilterBabak" style="display: none;">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-blue-600 mb-0.5 flex items-center gap-1">
                    <i class="fas fa-layer-group text-blue-500"></i> Tampilan Babak
                </label>
                <select id="filter_babak_scoreboard" class="w-full px-2 py-1 text-xs font-bold bg-blue-50/70 border border-blue-300 rounded-lg text-blue-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors cursor-pointer">
                    <option value="1">Babak 1</option>
                    <option value="2">Babak 2</option>
                </select>
            </div>
        </div>
    </header>

    <!-- ============================================== -->
    <!-- 2. BANNER PESERTA & KONTINGEN (MUNCUL MENCOLOK)-->
    <!-- ============================================== -->
    <section class="bg-white rounded-xl px-4 py-2 border border-slate-200/90 shadow-sm flex-shrink-0 mb-1.5 flex flex-wrap items-center justify-between gap-3">
        <!-- Informasi Peserta Tampil -->
        <div class="flex items-center gap-3 min-w-0">
            <div id="bannerIconPeserta" class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black shadow-sm flex-shrink-0">
                <i class="fas fa-user-ninja text-base"></i>
            </div>
            <div class="min-w-0">
                <div id="bannerLabelPeserta" class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Peserta Yang Tampil</div>
                <div id="bannerNamaPeserta" class="text-sm sm:text-base md:text-lg font-black text-slate-800 truncate tracking-tight">
                    -
                </div>
            </div>
        </div>

        <!-- Informasi Kontingen -->
        <div class="flex items-center gap-2 flex-shrink-0">
            <div class="text-right">
                <div id="bannerLabelKontingen" class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Kontingen / Daerah</div>
                <div id="bannerNamaKontingen" class="inline-flex items-center gap-1.5 px-3 py-0.5 bg-blue-50 border border-blue-200 text-blue-700 font-extrabold text-xs sm:text-sm rounded-lg truncate">
                    <i class="fas fa-map-marker-alt text-blue-500 text-xs"></i>
                    <span id="textKontingen">-</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================== -->
    <!-- 3. PAPAN SKOR DISPLAY UTAMA (READ-ONLY)       -->
    <!-- ============================================== -->
    <main id="scoreboard-content" class="flex-1 flex flex-col justify-between gap-2 min-h-0 overflow-hidden">

        <!-- ========================================== -->
        <!-- VIEW 1: KHUSUS KATEGORI EMBU               -->
        <!-- ========================================== -->
        <div id="embuScoreboardView" class="flex-1 flex flex-col justify-between gap-2 min-h-0 overflow-hidden">
            <!-- BARIS 1: 5 WASIT (WASIT 1 - WASIT 5) -->
            <div class="grid grid-cols-5 gap-2 sm:gap-2.5 flex-1 min-h-0">
                @for ($i = 1; $i <= 5; $i++)
                <div class="bg-white rounded-xl border border-slate-200/90 shadow-sm flex flex-col overflow-hidden">
                    <div class="py-1 px-2 text-center bg-slate-50 border-b border-slate-200 flex-shrink-0">
                        <span class="text-[11px] sm:text-xs font-black tracking-wider text-slate-600 uppercase">WASIT {{ $i }}</span>
                    </div>
                    <div class="flex-1 flex items-center justify-center p-1 min-h-0">
                        <div id="wasit_{{ $i }}" class="score-font font-black text-slate-800 text-3xl sm:text-4xl md:text-5xl lg:text-6xl drop-shadow-sm">
                            0
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <!-- BARIS 2: WAKTU & DENDA -->
            <div class="grid grid-cols-12 gap-2 sm:gap-2.5 flex-1 min-h-0">
                <div class="col-span-1 sm:col-span-2 md:col-span-3"></div>

                <!-- WAKTU -->
                <div class="col-span-5 sm:col-span-4 md:col-span-3 bg-white rounded-xl border border-cyan-200 shadow-sm flex flex-col overflow-hidden">
                    <div class="py-1 px-2 text-center bg-cyan-50 border-b border-cyan-200 flex-shrink-0">
                        <span class="text-[11px] sm:text-xs font-black tracking-wider text-cyan-800 uppercase">
                            <i class="fas fa-stopwatch mr-1 text-cyan-600"></i> WAKTU
                        </span>
                    </div>
                    <div class="flex-1 flex items-center justify-center p-1 min-h-0">
                        <div id="displayWaktu" class="score-font font-black text-cyan-600 text-3xl sm:text-4xl md:text-5xl lg:text-6xl tracking-wider">
                            00:00
                        </div>
                    </div>
                </div>

                <!-- DENDA -->
                <div class="col-span-5 sm:col-span-4 md:col-span-3 bg-white rounded-xl border border-rose-200 shadow-sm flex flex-col overflow-hidden">
                    <div class="py-1 px-2 text-center bg-rose-50 border-b border-rose-200 flex-shrink-0">
                        <span class="text-[11px] sm:text-xs font-black tracking-wider text-rose-800 uppercase">
                            <i class="fas fa-exclamation-triangle mr-1 text-rose-600"></i> DENDA
                        </span>
                    </div>
                    <div class="flex-1 flex items-center justify-center p-1 min-h-0">
                        <div id="displayGenten" class="score-font font-black text-rose-600 text-3xl sm:text-4xl md:text-5xl lg:text-6xl">
                            0
                        </div>
                    </div>
                </div>

                <div class="col-span-1 sm:col-span-2 md:col-span-3"></div>
            </div>

            <!-- BARIS 3: TOTAL NILAI PER BABAK & TOTAL AKHIR (KHUSUS BABAK 2) -->
            <div id="rowTotalScores" class="grid grid-cols-12 gap-2 sm:gap-2.5 flex-1 min-h-0">
                <!-- Left Spacer -->
                <div id="spacerLeftTotal" class="col-span-1 sm:col-span-2 md:col-span-3"></div>

                <!-- CARD TOTAL NILAI (BABAK 1 / BABAK 2 / TOTAL AKHIR RANDORI) -->
                <div id="cardTotalNilai" class="col-span-10 sm:col-span-8 md:col-span-6 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-xl border-2 border-blue-600 shadow-md flex flex-col overflow-hidden transition-all duration-300">
                    <div class="py-1.5 px-3 text-center bg-white/15 border-b border-white/20 flex-shrink-0 flex items-center justify-center gap-1.5">
                        <i class="fas fa-trophy text-amber-300 text-xs"></i>
                        <span id="labelTotalNilai" class="text-[11px] sm:text-xs font-black tracking-wider text-blue-100 uppercase">TOTAL NILAI BABAK 1</span>
                    </div>
                    <div class="flex-1 flex items-center justify-center p-1 min-h-0">
                        <div id="displayTotalNilai" class="score-font font-black text-white text-3xl sm:text-4xl md:text-5xl lg:text-6xl drop-shadow">
                            0
                        </div>
                    </div>
                </div>

                <!-- CARD TOTAL AKHIR (HANYA MUNCUL DI BABAK 2 EMBU) -->
                <div id="cardTotalAkhir" style="display: none;" class="col-span-6 sm:col-span-5 md:col-span-4 bg-gradient-to-br from-amber-500 via-orange-600 to-amber-700 text-white rounded-xl border-2 border-amber-400 shadow-lg flex flex-col overflow-hidden transition-all duration-300">
                    <div class="py-1.5 px-3 text-center bg-white/20 border-b border-white/25 flex-shrink-0 flex items-center justify-center gap-1.5">
                        <i class="fas fa-medal text-amber-200 text-xs"></i>
                        <span class="text-[11px] sm:text-xs font-black tracking-wider text-amber-50 uppercase">TOTAL AKHIR</span>
                    </div>
                    <div class="flex-1 flex items-center justify-center p-1 min-h-0">
                        <div id="displayTotalAkhir" class="score-font font-black text-white text-3xl sm:text-4xl md:text-5xl lg:text-6xl drop-shadow">
                            0
                        </div>
                    </div>
                </div>

                <!-- Right Spacer -->
                <div id="spacerRightTotal" class="col-span-1 sm:col-span-2 md:col-span-3"></div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- VIEW 2: KHUSUS KATEGORI RANDORI (DUEL)    -->
        <!-- ========================================== -->
        <div id="randoriScoreboardView" style="display: none;" class="flex-1 flex flex-col justify-between gap-2 min-h-0 overflow-hidden">
            <!-- STATUS BAR ATAS PARTAI DUEL (SIMPLE & CLEAN) -->
            <div class="bg-white rounded-xl px-4 py-1.5 border border-slate-200/90 shadow-sm flex items-center justify-between flex-shrink-0 gap-2">
                <div class="flex items-center gap-2">
                    <span id="randoriBadgeBabak" class="px-2.5 py-0.5 bg-slate-800 text-white font-extrabold text-xs rounded-md uppercase tracking-wider">
                        BABAK 1 - PARTAI #1
                    </span>
                </div>
                <div id="randoriAlertHasil" class="text-xs sm:text-sm font-bold text-slate-700 truncate">
                    <!-- Dinamis: Pemenang / BYE secara simpel -->
                </div>
            </div>

            <!-- ARENA DUEL: SUDUT MERAH (AKA) VS SUDUT PUTIH (SHIRO) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-3 flex-1 min-h-0 relative">
                
                <!-- 1. SUDUT MERAH (AKA) -->
                <div id="cardSudutMerah" class="bg-white rounded-xl border-2 border-red-500 shadow-sm flex flex-col overflow-hidden relative">
                    <!-- Header Sudut Merah -->
                    <div class="bg-red-600 text-white px-3.5 py-1.5 flex items-center justify-between shadow-sm flex-shrink-0">
                        <span class="font-extrabold text-xs sm:text-sm uppercase tracking-wider">
                            SUDUT MERAH (AKA)
                        </span>
                    </div>

                    <!-- Profil Atlet Merah -->
                    <div class="py-2 px-3 text-center flex-shrink-0 border-b border-slate-100 bg-red-50/25">
                        <h2 id="randoriNamaKiri" class="text-base sm:text-xl md:text-2xl font-black text-slate-800 truncate tracking-tight">
                            -
                        </h2>
                        <div class="mt-0.5 flex items-center justify-center">
                            <span id="randoriKontingenKiri" class="inline-flex items-center gap-1.5 text-slate-600 font-semibold text-xs sm:text-sm truncate">
                                <i class="fas fa-map-marker-alt text-red-500 text-xs"></i>
                                <span>-</span>
                            </span>
                        </div>
                    </div>

                    <!-- Display Skor Raksasa Merah -->
                    <div class="flex-1 flex items-center justify-center p-2 min-h-0 bg-white">
                        <div id="randoriSkorKiri" class="score-font font-black text-red-600 text-6xl sm:text-7xl md:text-8xl lg:text-9xl select-none">
                            0
                        </div>
                    </div>
                </div>

                <!-- BADGE VS TENGAH -->
                <div class="hidden md:flex absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-slate-800 text-white border-2 border-white shadow-md items-center justify-center font-bold text-xs pointer-events-none tracking-wider">
                    VS
                </div>

                <!-- 2. SUDUT PUTIH (SHIRO) -->
                <div id="cardSudutBiru" class="bg-white rounded-xl border-2 border-blue-500 shadow-sm flex flex-col overflow-hidden relative">
                    <!-- Header Sudut Putih -->
                    <div class="bg-blue-600 text-white px-3.5 py-1.5 flex items-center justify-between shadow-sm flex-shrink-0">
                        <span class="font-extrabold text-xs sm:text-sm uppercase tracking-wider">
                            SUDUT PUTIH (SHIRO)
                        </span>
                    </div>

                    <!-- Profil Atlet Putih -->
                    <div class="py-2 px-3 text-center flex-shrink-0 border-b border-slate-100 bg-blue-50/25">
                        <h2 id="randoriNamaKanan" class="text-base sm:text-xl md:text-2xl font-black text-slate-800 truncate tracking-tight">
                            -
                        </h2>
                        <div class="mt-0.5 flex items-center justify-center">
                            <span id="randoriKontingenKanan" class="inline-flex items-center gap-1.5 text-slate-600 font-semibold text-xs sm:text-sm truncate">
                                <i class="fas fa-map-marker-alt text-blue-500 text-xs"></i>
                                <span>-</span>
                            </span>
                        </div>
                    </div>

                    <!-- Display Skor Raksasa Biru -->
                    <div class="flex-1 flex items-center justify-center p-2 min-h-0 bg-white">
                        <div id="randoriSkorKanan" class="score-font font-black text-blue-600 text-6xl sm:text-7xl md:text-8xl lg:text-9xl select-none">
                            0
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <!-- ============================================== -->
    <!-- 4. FOOTER INFO STATUS SATU BARIS               -->
    <!-- ============================================== -->
    <footer class="pt-1 flex items-center justify-between text-[11px] text-slate-500 flex-shrink-0">
        <div>
            <span class="font-bold text-slate-600">Peserta:</span>
            <span id="labelPesertaAktif" class="ml-1 text-slate-800 font-bold">-</span>
            <span class="mx-1 text-slate-300">|</span>
            <span class="font-bold text-slate-600">Kontingen:</span>
            <span id="labelKontingenAktif" class="ml-1 text-blue-600 font-bold">-</span>
        </div>
        <div class="text-[11px] text-slate-400 hidden sm:block">
            Shorinji Kempo Scoreboard &bull; Share Screen View
        </div>
    </footer>

    <!-- ============================================== -->
    <!-- 5. JAVASCRIPT DISPLAY-ONLY (PULL/FETCH DARI DB) -->
    <!-- ============================================== -->
    <script>
        const structuredData = @json($structuredData);

        const selectKategori = document.getElementById('selectKategori');
        const selectTanding = document.getElementById('selectTanding');
        const selectPeserta = document.getElementById('selectPeserta');
        const labelDropdown3 = document.getElementById('labelDropdown3');
        const filterBabakSelect = document.getElementById('filter_babak_scoreboard');
        const containerFilterBabak = document.getElementById('containerFilterBabak');
        const filterDropdownGrid = document.getElementById('filterDropdownGrid');
        
        // Container Views
        const embuScoreboardView = document.getElementById('embuScoreboardView');
        const randoriScoreboardView = document.getElementById('randoriScoreboardView');

        // Banner Elemen Peserta & Kontingen
        const bannerIconPeserta = document.getElementById('bannerIconPeserta');
        const bannerLabelPeserta = document.getElementById('bannerLabelPeserta');
        const bannerNamaPeserta = document.getElementById('bannerNamaPeserta');
        const bannerLabelKontingen = document.getElementById('bannerLabelKontingen');
        const textKontingen = document.getElementById('textKontingen');
        const labelPesertaAktif = document.getElementById('labelPesertaAktif');
        const labelKontingenAktif = document.getElementById('labelKontingenAktif');
        
        const saveStatus = document.getElementById('saveStatus');
        const btnRefreshDb = document.getElementById('btnRefreshDb');
        const refreshIcon = document.getElementById('refreshIcon');

        // Elemen Embu
        const wasitDisplays = [
            document.getElementById('wasit_1'),
            document.getElementById('wasit_2'),
            document.getElementById('wasit_3'),
            document.getElementById('wasit_4'),
            document.getElementById('wasit_5'),
        ];
        const displayWaktu = document.getElementById('displayWaktu');
        const displayDenda = document.getElementById('displayDenda') || document.getElementById('displayGenten');
        const cardTotalNilai = document.getElementById('cardTotalNilai');
        const cardTotalAkhir = document.getElementById('cardTotalAkhir');
        const labelTotalNilai = document.getElementById('labelTotalNilai');
        const displayTotalNilai = document.getElementById('displayTotalNilai');
        const displayTotalAkhir = document.getElementById('displayTotalAkhir');
        const spacerLeftTotal = document.getElementById('spacerLeftTotal');
        const spacerRightTotal = document.getElementById('spacerRightTotal');

        // Elemen Randori
        const randoriBadgeBabak = document.getElementById('randoriBadgeBabak');
        const randoriBadgeStatus = document.getElementById('randoriBadgeStatus');
        const randoriAlertHasil = document.getElementById('randoriAlertHasil');

        const cardSudutMerah = document.getElementById('cardSudutMerah');
        const randoriNamaKiri = document.getElementById('randoriNamaKiri');
        const randoriKontingenKiri = document.getElementById('randoriKontingenKiri');
        const randoriSkorKiri = document.getElementById('randoriSkorKiri');
        const randoriKetKiri = document.getElementById('randoriKetKiri');

        const cardSudutBiru = document.getElementById('cardSudutBiru');
        const randoriNamaKanan = document.getElementById('randoriNamaKanan');
        const randoriKontingenKanan = document.getElementById('randoriKontingenKanan');
        const randoriSkorKanan = document.getElementById('randoriSkorKanan');
        const randoriKetKanan = document.getElementById('randoriKetKanan');

        let currentKategori = null;
        let currentTanding = null;
        let currentPesertaGroup = null;
        let currentPartaiDuel = null;

        // 1. Inisialisasi Dropdown Kategori
        function initKategoriDropdown() {
            structuredData.forEach(kat => {
                const opt = document.createElement('option');
                opt.value = kat.id;
                opt.textContent = kat.nama_kategori;
                selectKategori.appendChild(opt);
            });

            if (structuredData.length > 0) {
                selectKategori.value = structuredData[0].id;
                onKategoriChange();
            }
        }

        // 2. Kategori Berubah
        function onKategoriChange() {
            const katId = parseInt(selectKategori.value);
            currentKategori = structuredData.find(k => k.id === katId) || null;

            selectTanding.innerHTML = '<option value="">-- Pilih Tanding --</option>';
            selectPeserta.innerHTML = '<option value="">-- Pilih Peserta --</option>';
            selectTanding.disabled = !currentKategori;
            selectPeserta.disabled = true;

            if (currentKategori && currentKategori.tandings.length > 0) {
                currentKategori.tandings.forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t.id;
                    opt.textContent = `${t.nama_tanding} (${t.jenis.toUpperCase()})`;
                    selectTanding.appendChild(opt);
                });
                selectTanding.value = currentKategori.tandings[0].id;
                onTandingChange();
            } else {
                resetScoreboard();
            }
        }

        // 3. Tanding Berubah (Toggle Randori vs Embu)
        function onTandingChange() {
            if (!currentKategori) return;
            const tandingId = parseInt(selectTanding.value);
            currentTanding = currentKategori.tandings.find(t => t.id === tandingId) || null;

            const isRandori = currentTanding && currentTanding.jenis === 'randori';
            const isEmbu = currentTanding && currentTanding.jenis === 'embu';

            if (isRandori) {
                if (containerFilterBabak) containerFilterBabak.style.display = 'none';
                if (filterDropdownGrid) filterDropdownGrid.className = 'grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs';
                if (labelDropdown3) labelDropdown3.innerHTML = '<i class="fas fa-sitemap mr-1 text-red-500"></i> Pilih Babak & Partai Duel';
                if (bannerIconPeserta) {
                    bannerIconPeserta.className = 'w-9 h-9 rounded-xl bg-red-600 text-white flex items-center justify-center font-black shadow-sm flex-shrink-0';
                    bannerIconPeserta.innerHTML = '<i class="fas fa-fist-raised text-base"></i>';
                }
                if (bannerLabelPeserta) bannerLabelPeserta.textContent = 'Partai Duel Randori';
                if (bannerLabelKontingen) bannerLabelKontingen.textContent = 'Babak / Partai';

                if (embuScoreboardView) embuScoreboardView.style.display = 'none';
                if (randoriScoreboardView) randoriScoreboardView.style.display = 'flex';
            } else {
                if (containerFilterBabak) containerFilterBabak.style.display = 'block';
                if (filterDropdownGrid) filterDropdownGrid.className = 'grid grid-cols-1 sm:grid-cols-4 gap-2 text-xs';
                if (labelDropdown3) labelDropdown3.innerHTML = '<i class="fas fa-user-ninja mr-1 text-blue-500"></i> Pilih Peserta';
                if (bannerIconPeserta) {
                    bannerIconPeserta.className = 'w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black shadow-sm flex-shrink-0';
                    bannerIconPeserta.innerHTML = '<i class="fas fa-user-ninja text-base"></i>';
                }
                if (bannerLabelPeserta) bannerLabelPeserta.textContent = 'Peserta Yang Tampil';
                if (bannerLabelKontingen) bannerLabelKontingen.textContent = 'Kontingen / Daerah';

                if (embuScoreboardView) embuScoreboardView.style.display = 'flex';
                if (randoriScoreboardView) randoriScoreboardView.style.display = 'none';
                if (filterBabakSelect) filterBabakSelect.value = '1';
            }

            if (typeof resetSmartPollingHash === 'function') {
                resetSmartPollingHash();
            }

            selectPeserta.innerHTML = isRandori ? '<option value="">-- Pilih Partai Duel --</option>' : '<option value="">-- Pilih Peserta --</option>';
            selectPeserta.disabled = !currentTanding;

            if (isRandori) {
                const partaiList = currentTanding.partai_duel || [];
                if (partaiList.length > 0) {
                    partaiList.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = p.label;
                        selectPeserta.appendChild(opt);
                    });
                    selectPeserta.value = partaiList[0].id;
                    fetchFreshScoreFromDatabase(() => {
                        onPartaiDuelChange(false);
                    });
                } else {
                    resetScoreboard();
                }
            } else {
                const pGroups = currentTanding.peserta_groups || [];
                if (pGroups.length > 0) {
                    pGroups.forEach(pg => {
                        const opt = document.createElement('option');
                        opt.value = pg.row_index;
                        const labelKontingen = (pg.kontingen && pg.kontingen !== '-') ? pg.kontingen : 'Kontingen';
                        opt.textContent = `${labelKontingen} - ${pg.nama_gabungan || pg.nama}`;
                        selectPeserta.appendChild(opt);
                    });
                    selectPeserta.value = pGroups[0].row_index;
                    fetchFreshScoreFromDatabase(() => {
                        onPesertaChange(false);
                    });
                } else {
                    resetScoreboard();
                }
            }
        }

        // 4. Peserta Berubah (Khusus Embu)
        function onPesertaChange(shouldFetchDb = true) {
            if (!currentTanding) return;
            const rowIndex = parseInt(selectPeserta.value);
            currentPesertaGroup = (currentTanding.peserta_groups || []).find(pg => pg.row_index === rowIndex) || null;

            if (currentPesertaGroup) {
                const namaPeserta = currentPesertaGroup.nama_gabungan || currentPesertaGroup.nama || '-';
                const kontingen = currentPesertaGroup.kontingen || '-';

                // Tampilkan nama peserta & kontingen secara menonjol di banner
                bannerNamaPeserta.textContent = namaPeserta;
                textKontingen.textContent = kontingen;

                // Tampilkan juga di footer
                labelPesertaAktif.textContent = namaPeserta;
                labelKontingenAktif.textContent = kontingen;

                if (shouldFetchDb) {
                    fetchFreshScoreFromDatabase(() => {
                        renderScoreFromDatabaseState();
                    });
                } else {
                    renderScoreFromDatabaseState();
                }
            } else {
                resetScoreboard();
            }
        }

        // 5. Partai Duel Berubah (Khusus Randori)
        function onPartaiDuelChange(shouldFetchDb = true) {
            if (!currentTanding) return;
            const partaiId = parseInt(selectPeserta.value);
            const partaiList = currentTanding.partai_duel || [];
            currentPartaiDuel = partaiList.find(p => p.id === partaiId) || null;

            if (currentPartaiDuel) {
                if (shouldFetchDb) {
                    fetchFreshScoreFromDatabase(() => {
                        renderRandoriScore();
                    });
                } else {
                    renderRandoriScore();
                }
            } else {
                resetScoreboard();
            }
        }

        // 6. MENGAMBIL DATA TERBARU DARI DATABASE (PULL/FETCH)
        function fetchFreshScoreFromDatabase(callback) {
            if (!currentTanding) return;

            if (refreshIcon) refreshIcon.classList.add('fa-spin');

            fetch(`/kegiatan/papan-skor/${currentTanding.id}/data`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (refreshIcon) refreshIcon.classList.remove('fa-spin');
                if (data.status === 'success') {
                    currentTanding.scoresheet_data = data.scoresheet_data || {};
                    if (data.peserta && Array.isArray(data.peserta)) {
                        currentTanding.peserta_db = data.peserta;
                        currentTanding.peserta_groups = data.peserta;
                        if (currentPesertaGroup) {
                            currentPesertaGroup = currentTanding.peserta_groups.find(pg => pg.row_index === currentPesertaGroup.row_index) || currentPesertaGroup;
                        }
                    }
                    if (data.partai_duel && Array.isArray(data.partai_duel)) {
                        currentTanding.partai_duel = data.partai_duel;
                        if (currentPartaiDuel) {
                            currentPartaiDuel = currentTanding.partai_duel.find(p => p.id === currentPartaiDuel.id) || currentPartaiDuel;
                        }
                    }
                    saveStatus.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Sync';
                    saveStatus.className = 'inline-flex items-center gap-1.5 text-[11px] font-bold px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200';
                    if (typeof callback === 'function') callback();
                }
            })
            .catch(() => {
                if (refreshIcon) refreshIcon.classList.remove('fa-spin');
                saveStatus.innerHTML = '<span class="w-2 h-2 rounded-full bg-amber-500"></span> Offline';
                saveStatus.className = 'inline-flex items-center gap-1.5 text-[11px] font-bold px-2 py-0.5 rounded-lg bg-amber-50 text-amber-700 border border-amber-200';
                if (typeof callback === 'function') callback();
            });
        }

        // 7. RENDER SKOR RANDORI (DUEL MERAH VS BIRU)
        function renderRandoriScore() {
            if (!currentTanding || !currentPartaiDuel) {
                resetScoreboard();
                return;
            }

            const p = currentPartaiDuel;

            // 1. Update Banner Atas
            if (bannerNamaPeserta) {
                bannerNamaPeserta.innerHTML = `<span class="text-rose-600 font-black">${p.kiri_nama}</span> <span class="text-slate-400 font-bold px-1.5">VS</span> <span class="text-blue-600 font-black">${p.kanan_nama}</span>`;
            }
            if (textKontingen) {
                textKontingen.textContent = `${p.nama_babak} - Partai #${p.nomor_partai}`;
            }

            // 2. Update Footer
            if (labelPesertaAktif) {
                labelPesertaAktif.textContent = `${p.kiri_nama} vs ${p.kanan_nama}`;
            }
            if (labelKontingenAktif) {
                labelKontingenAktif.textContent = `${p.kiri_kontingen} vs ${p.kanan_kontingen}`;
            }

            // 3. Header Partai & Status
            if (randoriBadgeBabak) {
                randoriBadgeBabak.textContent = `${p.nama_babak.toUpperCase()} - PARTAI #${p.nomor_partai}`;
            }

            const isMenangKiri = (p.pemenang_id && p.kiri_id && p.pemenang_id == p.kiri_id);
            const isMenangKanan = (p.pemenang_id && p.kanan_id && p.pemenang_id == p.kanan_id);

            if (randoriAlertHasil) {
                if (p.is_bye) {
                    randoriAlertHasil.innerHTML = `<span class="text-slate-500 font-normal">Lolos otomatis (BYE):</span> <span class="text-slate-800 font-bold">${p.kiri_nama}</span>`;
                } else if (p.pemenang_id) {
                    const pemenangNama = isMenangKiri ? p.kiri_nama : (isMenangKanan ? p.kanan_nama : 'Pemenang');
                    const pemenangSudut = isMenangKiri ? 'Sudut Merah' : (isMenangKanan ? 'Sudut Putih' : '');
                    randoriAlertHasil.innerHTML = `<span class="text-slate-500 font-normal">Pemenang:</span> <span class="text-slate-900 font-black">${pemenangNama}</span> <span class="text-slate-400 font-normal">|</span> <span class="text-slate-700 font-semibold">${pemenangSudut}</span>`;
                } else {
                    randoriAlertHasil.innerHTML = '';
                }
            }

            // 4. Update Sudut Kiri (Merah)
            if (randoriNamaKiri) randoriNamaKiri.textContent = p.kiri_nama || 'Atlet Merah';
            if (randoriKontingenKiri) {
                randoriKontingenKiri.innerHTML = `<i class="fas fa-map-marker-alt text-red-500 text-xs"></i> <span>${p.kiri_kontingen || '-'}</span>`;
            }
            if (randoriSkorKiri) {
                randoriSkorKiri.textContent = p.skor_kiri !== null && p.skor_kiri !== undefined ? p.skor_kiri : 0;
            }

            // 5. Update Sudut Kanan (Putih)
            if (randoriNamaKanan) randoriNamaKanan.textContent = p.kanan_nama || 'Atlet Putih';
            if (randoriKontingenKanan) {
                randoriKontingenKanan.innerHTML = `<i class="fas fa-map-marker-alt text-blue-500 text-xs"></i> <span>${p.kanan_kontingen || '-'}</span>`;
            }
            if (randoriSkorKanan) {
                randoriSkorKanan.textContent = p.skor_kanan !== null && p.skor_kanan !== undefined ? p.skor_kanan : 0;
            }
        }

        // 8. RENDER SKOR EMBU DARI DATABASE (READ-ONLY DISPLAY)
        function renderScoreFromDatabaseState() {
            if (!currentTanding || !currentPesertaGroup) return;

            const isEmbu = currentTanding && currentTanding.jenis === 'embu';
            const activeBabak = (isEmbu && filterBabakSelect && filterBabakSelect.value === '2') ? 2 : 1;

            let matchedGroup = currentPesertaGroup;
            if (currentTanding.peserta_db && Array.isArray(currentTanding.peserta_db)) {
                const found = currentTanding.peserta_db.find(p => p.row_index === currentPesertaGroup.row_index);
                if (found) matchedGroup = found;
            }

            const babakData = activeBabak === 2 ? matchedGroup.babak_2 : matchedGroup.babak_1;

            let scores = [0, 0, 0, 0, 0];
            let waktu = '00:00';
            let genten = 0;

            if (babakData) {
                scores = babakData.scores || [
                    parseFloat(babakData.nilai_1) || 0,
                    parseFloat(babakData.nilai_2) || 0,
                    parseFloat(babakData.nilai_3) || 0,
                    parseFloat(babakData.nilai_4) || 0,
                    parseFloat(babakData.nilai_5) || 0,
                ];
                waktu = babakData.waktu || '00:00';
                genten = parseFloat(babakData.genten ?? babakData.minus) || 0;
            } else if (activeBabak === 1) {
                if (matchedGroup.scores) {
                    scores = matchedGroup.scores;
                    waktu = matchedGroup.waktu || '00:00';
                    genten = parseFloat(matchedGroup.genten ?? matchedGroup.minus) || 0;
                } else {
                    const scoresheet = currentTanding.scoresheet_data || {};
                    const rows = scoresheet.rows || [];
                    const rowData = rows[currentPesertaGroup.row_index] || null;
                    if (rowData) {
                        scores = (rowData.scores || rowData.wasit || [0, 0, 0, 0, 0]).map(v => parseFloat(v) || 0);
                        waktu = rowData.waktu || '00:00';
                        genten = parseFloat(rowData.genten !== undefined ? rowData.genten : (rowData.pengurangan !== undefined ? rowData.pengurangan : (rowData.denda || 0))) || 0;
                    }
                }
            }

            while (scores.length < 5) scores.push(0);

            const parsedScores = scores.slice(0, 5).map(s => parseFloat(s) || 0);

            // Update UI Wasit 1 - 5
            for (let i = 1; i <= 5; i++) {
                const el = document.getElementById('wasit_' + i);
                if (el) {
                    el.textContent = parsedScores[i - 1];
                }
            }

            // Update Waktu & Denda
            const elWaktu = document.getElementById('displayWaktu');
            const elDenda = document.getElementById('displayDenda') || document.getElementById('displayGenten');
            if (elWaktu) elWaktu.textContent = waktu || '00:00';
            if (elDenda) elDenda.textContent = genten !== null && genten !== undefined ? genten : 0;

            // Hitung skor: Hilangkan nilai terbesar dan terkecil dari wasit yang aktif (> 0)
            const activeScores = parsedScores.filter(s => s > 0).sort((a, b) => a - b);
            let sumMiddle = 0;

            if (activeScores.length >= 3) {
                const middleScores = activeScores.slice(1, activeScores.length - 1);
                sumMiddle = middleScores.reduce((acc, curr) => acc + curr, 0);
            } else if (activeScores.length > 0) {
                sumMiddle = 0;
            }

            const dendaVal = parseFloat(genten) || 0;
            const totalNilaiBabak = Math.max(0, sumMiddle - dendaVal);

            function formatScoreDisplay(val) {
                if (val === null || val === undefined || isNaN(val)) return '0';
                if (Number.isInteger(val)) return val.toString();
                const fixed = val.toFixed(2);
                return parseFloat(fixed).toString();
            }

            const elTotalNilai = document.getElementById('displayTotalNilai');
            if (elTotalNilai) {
                elTotalNilai.textContent = formatScoreDisplay(totalNilaiBabak);
            }

            // Card styling & Card Total Akhir (Khusus Babak 2 Embu)
            if (isEmbu && activeBabak === 2) {
                if (labelTotalNilai) labelTotalNilai.textContent = 'TOTAL NILAI BABAK 2';

                let totalBabak1 = 0;
                if (matchedGroup.babak_1) {
                    if (matchedGroup.babak_1.total_nilai !== null && matchedGroup.babak_1.total_nilai !== undefined) {
                        totalBabak1 = parseFloat(matchedGroup.babak_1.total_nilai) || 0;
                    } else if (matchedGroup.babak_1.scores) {
                        const b1Scores = (matchedGroup.babak_1.scores || []).map(s => parseFloat(s) || 0).filter(s => s > 0).sort((a, b) => a - b);
                        let b1Sum = 0;
                        if (b1Scores.length >= 3) {
                            b1Sum = b1Scores.slice(1, b1Scores.length - 1).reduce((acc, curr) => acc + curr, 0);
                        }
                        totalBabak1 = Math.max(0, b1Sum - (parseFloat(matchedGroup.babak_1.genten) || 0));
                    }
                } else if (matchedGroup.total_babak_1 !== null && matchedGroup.total_babak_1 !== undefined) {
                    totalBabak1 = parseFloat(matchedGroup.total_babak_1) || 0;
                }

                const totalBabak2 = totalNilaiBabak;
                const totalAkhir = (totalBabak1 + totalBabak2) / 2;

                if (displayTotalAkhir) {
                    displayTotalAkhir.textContent = formatScoreDisplay(totalAkhir);
                }

                if (cardTotalAkhir) cardTotalAkhir.style.display = 'flex';
                if (cardTotalNilai) {
                    cardTotalNilai.className = 'col-span-6 sm:col-span-5 md:col-span-4 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-xl border-2 border-blue-600 shadow-md flex flex-col overflow-hidden transition-all duration-300';
                }
                if (cardTotalAkhir) {
                    cardTotalAkhir.className = 'col-span-6 sm:col-span-5 md:col-span-4 bg-gradient-to-br from-amber-500 via-orange-600 to-amber-700 text-white rounded-xl border-2 border-amber-400 shadow-lg flex flex-col overflow-hidden transition-all duration-300';
                }
                if (spacerLeftTotal) spacerLeftTotal.className = 'hidden sm:block sm:col-span-1 md:col-span-2';
                if (spacerRightTotal) spacerRightTotal.className = 'hidden sm:block sm:col-span-1 md:col-span-2';
            } else {
                if (labelTotalNilai) {
                    labelTotalNilai.textContent = isEmbu ? 'TOTAL NILAI BABAK 1' : 'TOTAL NILAI AKHIR';
                }
                if (cardTotalAkhir) {
                    cardTotalAkhir.style.display = 'none';
                }
                if (cardTotalNilai) {
                    cardTotalNilai.className = 'col-span-10 sm:col-span-8 md:col-span-6 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-xl border-2 border-blue-600 shadow-md flex flex-col overflow-hidden transition-all duration-300';
                }
                if (spacerLeftTotal) spacerLeftTotal.className = 'col-span-1 sm:col-span-2 md:col-span-3';
                if (spacerRightTotal) spacerRightTotal.className = 'col-span-1 sm:col-span-2 md:col-span-3';
            }
        }

        function resetScoreboard() {
            if (bannerNamaPeserta) bannerNamaPeserta.textContent = '-';
            if (textKontingen) textKontingen.textContent = '-';
            if (labelPesertaAktif) labelPesertaAktif.textContent = '-';
            if (labelKontingenAktif) labelKontingenAktif.textContent = '-';
            for (let i = 1; i <= 5; i++) {
                const el = document.getElementById('wasit_' + i);
                if (el) el.textContent = '0';
            }
            const dw = document.getElementById('displayWaktu');
            if (dw) dw.textContent = '00:00';
            const dd = document.getElementById('displayDenda') || document.getElementById('displayGenten');
            if (dd) dd.textContent = '0';
            const dtn = document.getElementById('displayTotalNilai');
            if (dtn) dtn.textContent = '0';
            const dta = document.getElementById('displayTotalAkhir');
            if (dta) dta.textContent = '0';

            // Reset Randori
            if (randoriNamaKiri) randoriNamaKiri.textContent = '-';
            if (randoriKontingenKiri) randoriKontingenKiri.innerHTML = '<span>-</span>';
            if (randoriSkorKiri) randoriSkorKiri.textContent = '0';
            if (randoriKetKiri) randoriKetKiri.textContent = '-';

            if (randoriNamaKanan) randoriNamaKanan.textContent = '-';
            if (randoriKontingenKanan) randoriKontingenKanan.innerHTML = '<span>-</span>';
            if (randoriSkorKanan) randoriSkorKanan.textContent = '0';
            if (randoriKetKanan) randoriKetKanan.textContent = '-';

            if (randoriAlertHasil) randoriAlertHasil.innerHTML = '';
        }

        // Fullscreen Toggle
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            } else {
                document.exitFullscreen();
            }
        }

        // Event Listeners
        selectKategori.addEventListener('change', onKategoriChange);
        selectTanding.addEventListener('change', onTandingChange);
        selectPeserta.addEventListener('change', () => {
            if (currentTanding && currentTanding.jenis === 'randori') {
                onPartaiDuelChange(true);
            } else {
                onPesertaChange(true);
            }
        });
        if (filterBabakSelect) {
            filterBabakSelect.addEventListener('change', () => {
                renderScoreFromDatabaseState();
            });
        }

        btnRefreshDb.addEventListener('click', () => {
            fetchFreshScoreFromDatabase(() => {
                if (currentTanding && currentTanding.jenis === 'randori') {
                    renderRandoriScore();
                } else {
                    renderScoreFromDatabaseState();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', initKategoriDropdown);
    </script>

    <!-- ============================================== -->
    <!-- 6. SMART POLLING (CHECKSUM + PARTIAL DOM)      -->
    <!-- ============================================== -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const defaultPertandinganId = {{ $pertandinganId ?: 1 }};
        const pageUrl = window.location.href;
        const containerId = 'scoreboard-content';

        let currentHash = null;
        let isUpdating = false;
        const POLL_INTERVAL = 2500; // Interval pengecekan: 2.5 detik

        window.resetSmartPollingHash = function () {
            currentHash = null;
        };

        async function updateScoreboardDOM() {
            if (isUpdating) return;
            isUpdating = true;

            try {
                // Tarik dan render nilai terbaru dari database ke elemen papan skor
                if (typeof fetchFreshScoreFromDatabase === 'function') {
                    fetchFreshScoreFromDatabase(() => {
                        if (typeof currentTanding !== 'undefined' && currentTanding && currentTanding.jenis === 'randori') {
                            if (typeof renderRandoriScore === 'function') {
                                renderRandoriScore();
                            }
                        } else {
                            if (typeof renderScoreFromDatabaseState === 'function') {
                                renderScoreFromDatabaseState();
                            }
                        }
                    });
                }
            } catch (err) {
                console.warn('[SmartPolling] Gagal memperbarui tampilan:', err);
            } finally {
                isUpdating = false;
            }
        }

        async function checkForUpdates() {
            // Hanya lakukan request jika tab browser sedang aktif dilihat
            if (!document.hidden) {
                try {
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 5000);

                    // Gunakan ID tanding aktif jika dipilih, fallback ke defaultPertandinganId
                    const activeTandingId = (typeof currentTanding !== 'undefined' && currentTanding && (currentTanding.pertandingan_id || currentTanding.id))
                        ? (currentTanding.pertandingan_id || currentTanding.id)
                        : defaultPertandinganId;
                    const urlToFetch = "{{ url('/scoreboard') }}/" + activeTandingId + "/check-update";

                    const response = await fetch(urlToFetch, {
                        signal: controller.signal,
                        headers: { 'Accept': 'application/json' },
                        cache: 'no-store'
                    });

                    clearTimeout(timeoutId);

                    if (response.ok) {
                        const result = await response.json();

                        if (result && result.hash) {
                            if (currentHash === null) {
                                // Simpan hash awal saat halaman pertama kali dibuka
                                currentHash = result.hash;
                            } else if (currentHash !== result.hash) {
                                // Hash berubah! Perbarui hash dan muat ulang bagian skor
                                currentHash = result.hash;
                                await updateScoreboardDOM();
                            }
                        }
                    }
                } catch (err) {
                    // Abaikan error jaringan sesaat, lanjutkan pada siklus berikutnya
                }
            }

            // Gunakan recursive setTimeout agar tidak terjadi penumpukan request
            setTimeout(checkForUpdates, POLL_INTERVAL);
        }

        // Jalankan hanya jika elemen #scoreboard-content ditemukan di halaman
        if (document.getElementById(containerId)) {
            checkForUpdates();
        }
    });
    </script>
</body>
</html>
