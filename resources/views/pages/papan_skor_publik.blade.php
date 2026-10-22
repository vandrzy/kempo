<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Papan Skor Live - {{ $kegiatan->nama_kegiatan ?? $kegiatan->nama }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" />
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

        <!-- Filter Dropdown 3 Kolom Kompak -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
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

            <!-- Peserta -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-0.5">Pilih Peserta</label>
                <select id="selectPeserta" class="w-full px-2 py-1 text-xs font-semibold bg-slate-50 border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors" disabled>
                    <option value="">-- Pilih Peserta --</option>
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
            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black shadow-sm flex-shrink-0">
                <i class="fas fa-user-ninja text-base"></i>
            </div>
            <div class="min-w-0">
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Peserta Yang Tampil</div>
                <div id="bannerNamaPeserta" class="text-sm sm:text-base md:text-lg font-black text-slate-800 truncate tracking-tight">
                    -
                </div>
            </div>
        </div>

        <!-- Informasi Kontingen -->
        <div class="flex items-center gap-2 flex-shrink-0">
            <div class="text-right">
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Kontingen / Daerah</div>
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
    <main class="flex-1 flex flex-col justify-between gap-2 min-h-0 overflow-hidden">

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

        <!-- BARIS 2: WAKTU & GENTEN (DENDA) -->
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

            <!-- GENTEN -->
            <div class="col-span-5 sm:col-span-4 md:col-span-3 bg-white rounded-xl border border-rose-200 shadow-sm flex flex-col overflow-hidden">
                <div class="py-1 px-2 text-center bg-rose-50 border-b border-rose-200 flex-shrink-0">
                    <span class="text-[11px] sm:text-xs font-black tracking-wider text-rose-800 uppercase">
                        <i class="fas fa-exclamation-triangle mr-1 text-rose-600"></i> GENTEN
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

        <!-- BARIS 3: NILAI & TOTAL NILAI -->
        <div class="grid grid-cols-12 gap-2 sm:gap-2.5 flex-1 min-h-0">
            <div class="col-span-1 sm:col-span-2 md:col-span-3"></div>

            <!-- NILAI -->
            <div class="col-span-5 sm:col-span-4 md:col-span-3 bg-white rounded-xl border border-amber-300 shadow-sm flex flex-col overflow-hidden">
                <div class="py-1 px-2 text-center bg-amber-50 border-b border-amber-200 flex-shrink-0">
                    <span class="text-[11px] sm:text-xs font-black tracking-wider text-amber-800 uppercase">JUMLAH NILAI WASIT</span>
                </div>
                <div class="flex-1 flex items-center justify-center p-1 min-h-0">
                    <div id="displayNilai" class="score-font font-black text-amber-600 text-3xl sm:text-4xl md:text-5xl lg:text-6xl">
                        0
                    </div>
                </div>
            </div>

            <!-- TOTAL NILAI -->
            <div class="col-span-5 sm:col-span-4 md:col-span-3 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-xl border-2 border-blue-600 shadow-md flex flex-col overflow-hidden">
                <div class="py-1 px-2 text-center bg-white/15 border-b border-white/20 flex-shrink-0">
                    <span class="text-[11px] sm:text-xs font-black tracking-wider text-blue-100 uppercase">TOTAL NILAI AKHIR</span>
                </div>
                <div class="flex-1 flex items-center justify-center p-1 min-h-0">
                    <div id="displayTotalNilai" class="score-font font-black text-white text-3xl sm:text-4xl md:text-5xl lg:text-6xl drop-shadow">
                        0
                    </div>
                </div>
            </div>

            <div class="col-span-1 sm:col-span-2 md:col-span-3"></div>
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
        
        // Banner Elemen Peserta & Kontingen
        const bannerNamaPeserta = document.getElementById('bannerNamaPeserta');
        const textKontingen = document.getElementById('textKontingen');
        const labelPesertaAktif = document.getElementById('labelPesertaAktif');
        const labelKontingenAktif = document.getElementById('labelKontingenAktif');
        
        const saveStatus = document.getElementById('saveStatus');
        const btnRefreshDb = document.getElementById('btnRefreshDb');
        const refreshIcon = document.getElementById('refreshIcon');

        const wasitDisplays = [
            document.getElementById('wasit_1'),
            document.getElementById('wasit_2'),
            document.getElementById('wasit_3'),
            document.getElementById('wasit_4'),
            document.getElementById('wasit_5'),
        ];
        const displayWaktu = document.getElementById('displayWaktu');
        const displayGenten = document.getElementById('displayGenten');
        const displayNilai = document.getElementById('displayNilai');
        const displayTotalNilai = document.getElementById('displayTotalNilai');

        let currentKategori = null;
        let currentTanding = null;
        let currentPesertaGroup = null;

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

        // 3. Tanding Berubah
        function onTandingChange() {
            if (!currentKategori) return;
            const tandingId = parseInt(selectTanding.value);
            currentTanding = currentKategori.tandings.find(t => t.id === tandingId) || null;

            selectPeserta.innerHTML = '<option value="">-- Pilih Peserta --</option>';
            selectPeserta.disabled = !currentTanding;

            if (currentTanding && currentTanding.peserta_groups.length > 0) {
                currentTanding.peserta_groups.forEach(pg => {
                    const opt = document.createElement('option');
                    opt.value = pg.row_index;
                    const labelKontingen = (pg.kontingen && pg.kontingen !== '-') ? pg.kontingen : 'Kontingen';
                    opt.textContent = `${labelKontingen} - ${pg.nama_gabungan}`;
                    selectPeserta.appendChild(opt);
                });
                selectPeserta.value = currentTanding.peserta_groups[0].row_index;
                fetchFreshScoreFromDatabase(() => {
                    onPesertaChange(false);
                });
            } else {
                resetScoreboard();
            }
        }

        // 4. Peserta Berubah
        function onPesertaChange(shouldFetchDb = true) {
            if (!currentTanding) return;
            const rowIndex = parseInt(selectPeserta.value);
            currentPesertaGroup = currentTanding.peserta_groups.find(pg => pg.row_index === rowIndex) || null;

            if (currentPesertaGroup) {
                const namaPeserta = currentPesertaGroup.nama_gabungan || '-';
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

        // 5. MENGAMBIL DATA TERBARU DARI DATABASE (PULL/FETCH)
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

        // 6. RENDER SCORE DARI DATABASE (READ-ONLY DISPLAY)
        function renderScoreFromDatabaseState() {
            if (!currentTanding || !currentPesertaGroup) return;

            const scoresheet = currentTanding.scoresheet_data || {};
            const rows = scoresheet.rows || [];
            const pesertaList = scoresheet.peserta || [];
            const pesertaDbList = currentTanding.peserta_db || [];

            // 1. Prioritaskan pencarian langsung dari tabel database (peserta_db)
            let matchedDb = null;
            if (pesertaDbList.length > 0) {
                if (currentPesertaGroup.kabupaten_id) {
                    matchedDb = pesertaDbList.find(p => String(p.kabupaten_id) === String(currentPesertaGroup.kabupaten_id));
                }
                if (!matchedDb && currentPesertaGroup.id) {
                    matchedDb = pesertaDbList.find(p => p.id === currentPesertaGroup.id || (p.pp_ids && p.pp_ids.includes(currentPesertaGroup.id)));
                }
                if (!matchedDb && currentPesertaGroup.kontingen) {
                    const kName = currentPesertaGroup.kontingen.trim().toLowerCase();
                    matchedDb = pesertaDbList.find(p => p.kontingen && p.kontingen.trim().toLowerCase() === kName);
                }
                if (!matchedDb) {
                    matchedDb = pesertaDbList[currentPesertaGroup.row_index] || null;
                }
            }

            // 2. Cari dari data JSON scoresheet.peserta
            let matchedPesertaData = null;
            if (pesertaList.length > 0) {
                if (currentPesertaGroup.kontingen) {
                    const kName = currentPesertaGroup.kontingen.trim().toLowerCase();
                    matchedPesertaData = pesertaList.find(p => p.kontingen && p.kontingen.trim().toLowerCase() === kName);
                }
                if (!matchedPesertaData) {
                    matchedPesertaData = pesertaList[currentPesertaGroup.row_index] || null;
                }
            }

            // 3. Fallback ke scoresheet.rows
            const rowData = rows[currentPesertaGroup.row_index] || null;

            let scores = [0, 0, 0, 0, 0];
            let waktu = '00:00';
            let genten = 0;
            let explicitTotal = null;

            if (matchedDb) {
                scores = [
                    parseFloat(matchedDb.nilai_1) || 0,
                    parseFloat(matchedDb.nilai_2) || 0,
                    parseFloat(matchedDb.nilai_3) || 0,
                    parseFloat(matchedDb.nilai_4) || 0,
                    parseFloat(matchedDb.nilai_5) || 0,
                ];
                waktu = matchedDb.waktu || '00:00';
                genten = parseFloat(matchedDb.genten ?? matchedDb.minus) || 0;
                if (matchedDb.total_nilai !== null && matchedDb.total_nilai !== undefined) {
                    explicitTotal = parseFloat(matchedDb.total_nilai);
                }
            } else if (matchedPesertaData) {
                scores = [
                    parseFloat(matchedPesertaData.juri_1) || 0,
                    parseFloat(matchedPesertaData.juri_2) || 0,
                    parseFloat(matchedPesertaData.juri_3) || 0,
                    parseFloat(matchedPesertaData.juri_4) || 0,
                    parseFloat(matchedPesertaData.juri_5) || 0,
                ];
                waktu = matchedPesertaData.waktu || '00:00';
                genten = parseFloat(matchedPesertaData.hukuman ?? matchedPesertaData.genten) || 0;
                if (matchedPesertaData.total !== null && matchedPesertaData.total !== undefined) {
                    explicitTotal = parseFloat(matchedPesertaData.total);
                }
            } else if (rowData) {
                scores = (rowData.scores || rowData.wasit || [0, 0, 0, 0, 0]).map(v => parseFloat(v) || 0);
                waktu = rowData.waktu || '00:00';
                genten = parseFloat(rowData.genten !== undefined ? rowData.genten : (rowData.pengurangan !== undefined ? rowData.pengurangan : (rowData.denda || 0))) || 0;
                if (rowData.total_akhir !== null && rowData.total_akhir !== undefined) {
                    explicitTotal = parseFloat(rowData.total_akhir);
                }
            } else if (currentPesertaGroup.scores) {
                scores = currentPesertaGroup.scores;
                waktu = currentPesertaGroup.waktu || '00:00';
                genten = parseFloat(currentPesertaGroup.genten ?? currentPesertaGroup.minus) || 0;
                if (currentPesertaGroup.total_nilai !== null && currentPesertaGroup.total_nilai !== undefined) {
                    explicitTotal = parseFloat(currentPesertaGroup.total_nilai);
                }
            }

            while (scores.length < 5) scores.push(0);

            // Update UI Wasit 1 - 5
            wasitDisplays.forEach((el, idx) => {
                const val = scores[idx] !== undefined && scores[idx] !== null ? scores[idx] : 0;
                el.textContent = val;
            });

            // Update Waktu & Genten
            displayWaktu.textContent = waktu || '00:00';
            displayGenten.textContent = genten !== null && genten !== undefined ? genten : 0;

            // Hitung Nilai (Jumlah seluruh nilai Wasit 1-5) & Total Nilai Akhir (Nilai - Genten)
            const sumNilai = scores.reduce((sum, s) => sum + (parseFloat(s) || 0), 0);
            const totalNilai = explicitTotal !== null ? explicitTotal : Math.max(0, sumNilai - genten);

            displayNilai.textContent = Number.isInteger(sumNilai) ? sumNilai : sumNilai.toFixed(1);
            displayTotalNilai.textContent = Number.isInteger(totalNilai) ? totalNilai : totalNilai.toFixed(1);
        }

        function resetScoreboard() {
            bannerNamaPeserta.textContent = '-';
            textKontingen.textContent = '-';
            labelPesertaAktif.textContent = '-';
            labelKontingenAktif.textContent = '-';
            wasitDisplays.forEach(el => el.textContent = '0');
            displayWaktu.textContent = '00:00';
            displayGenten.textContent = '0';
            displayNilai.textContent = '0';
            displayTotalNilai.textContent = '0';
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
        selectPeserta.addEventListener('change', () => onPesertaChange(true));

        btnRefreshDb.addEventListener('click', () => {
            fetchFreshScoreFromDatabase(() => {
                renderScoreFromDatabaseState();
            });
        });

        // Auto-fetch data dari database setiap 3 detik secara otomatis (Live Screen Share Sync)
        setInterval(() => {
            if (currentTanding && currentPesertaGroup) {
                fetchFreshScoreFromDatabase(() => {
                    renderScoreFromDatabaseState();
                });
            }
        }, 3000);

        document.addEventListener('DOMContentLoaded', initKategoriDropdown);
    </script>
</body>
</html>
