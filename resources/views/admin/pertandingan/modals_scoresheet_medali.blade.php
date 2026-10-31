@php
    $isRandori = stripos($pertandingan->pertandingan->nama ?? '', 'randori') !== false ||
                 stripos($pertandingan->kategori->nama ?? '', 'randori') !== false;
    $scoresheet = $pertandingan->data_scoresheet ?? [];
    $juara = $pertandingan->data_juara ?? [];
@endphp

<style>
    /* Hilangkan panah spinner bawaan browser pada input angka modal scoresheet */
    #scoresheetModal input[type="number"]::-webkit-outer-spin-button,
    #scoresheetModal input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    #scoresheetModal input[type="number"] {
        -moz-appearance: textfield;
    }
</style>

<!-- ============================================== -->
<!-- 1. MODAL SCORE SHEET PERTANDINGAN (KEMPO UI)   -->
<!-- ============================================== -->
<div id="scoresheetModal" style="display: none;" class="fixed inset-0 z-[999999] items-center justify-center p-3 sm:p-5 md:p-6 bg-black/60 backdrop-blur-xs overflow-y-auto transition-all duration-200">
    <div class="relative w-full max-w-[1360px] my-auto max-h-[92vh] bg-white rounded-3xl border border-[#ebebeb] shadow-2xl flex flex-col overflow-hidden font-body">
        
        <!-- Header Modal -->
        <div class="px-6 py-5 sm:px-8 sm:py-6 shrink-0 flex items-start justify-between border-b border-[#ebebeb] bg-white">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#fff0eb] text-[#f2470c] flex items-center justify-center shrink-0 text-xl border border-[#f2470c]/25 shadow-2xs">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h3 class="font-heading font-bold text-xl md:text-2xl text-[#000000] mb-0 tracking-tight">
                            Score Sheet Pertandingan
                        </h3>
                        @if($isRandori)
                            <span class="px-3 py-0.5 text-xs font-bold text-[#e54b4f] bg-[#fff0eb] rounded-full border border-[#e54b4f]/25">
                                Kategori Randori
                            </span>
                        @else
                            <span class="px-3 py-0.5 text-xs font-bold text-[#3276e4] bg-[#3276e4]/10 rounded-full border border-[#3276e4]/25">
                                Kategori Embu
                            </span>
                        @endif
                    </div>
                    <p class="text-xs md:text-sm text-neutral-500 mb-0 mt-1 font-body">
                        {{ $pertandingan->kegiatan->nama ?? '' }} &bull; {{ $pertandingan->pertandingan->nama ?? '' }} (Kelas: {{ $pertandingan->pertandingan->kelas ?? '-' }}) &bull; Penilaian Berdasarkan Kontingen
                    </p>
                    <div id="scoresheetFilterBadge" style="display: none;" class="mt-2.5 flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 text-xs font-bold bg-[#fff0eb] text-[#f2470c] rounded-full border border-[#f2470c]/30">
                            <i class="fas fa-filter text-[#f2470c]"></i> Kontingen: <strong id="scoresheetFilterText" class="font-bold">-</strong>
                        </span>
                        <button type="button" onclick="clearScoresheetFilter()" class="text-xs text-[#f2470c] hover:underline font-bold cursor-pointer p-0 ml-1 bg-transparent border-0">
                            <i class="fas fa-undo mr-1"></i> Tampilkan Semua Kontingen
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <!-- Dropdown Tampilan Nilai (Khusus Kategori Embu) -->
                <div id="containerBabakPenilaian" class="{{ $isRandori ? 'hidden' : 'flex' }} items-center gap-2 bg-[#fff0eb] border border-[#f2470c]/30 px-3.5 py-1.5 rounded-2xl shadow-2xs">
                    <label for="selectBabakPenilaian" class="text-xs font-bold text-neutral-700 font-heading whitespace-nowrap flex items-center gap-1.5">
                        <i class="fas fa-layer-group text-[#f2470c]"></i> Pilih Tampilan Nilai:
                    </label>
                    <select id="selectBabakPenilaian" name="babak_penilaian" onchange="onBabakPenilaianChange(this.value)" class="text-xs font-bold text-[#f2470c] bg-white border border-[#f2470c]/30 rounded-xl px-2.5 py-1 focus:border-[#f2470c] focus:outline-none focus:ring-2 focus:ring-[#f2470c]/20 transition-all cursor-pointer shadow-2xs">
                        <option value="1">Nilai Babak 1</option>
                        <option value="2">Nilai Babak 2</option>
                        <option value="akhir">Nilai Akhir (Rekapitulasi)</option>
                    </select>
                </div>

                <button type="button" onclick="closeScoresheetModal()" class="w-9 h-9 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-500 hover:text-[#000000] flex items-center justify-center transition-colors cursor-pointer border-0">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- Form Scoresheet -->
        <form id="scoresheetForm" onsubmit="submitScoresheet(event)" class="flex flex-col flex-1 overflow-hidden mb-0">
            @csrf
            <input type="hidden" id="scoresheet_pertandingan_id" value="{{ $pertandingan->id }}">
            <input type="hidden" id="scoresheet_babak" name="babak" value="1">
            <input type="hidden" name="kegiatan_id" value="{{ $pertandingan->kegiatan->id ?? '' }}">
            
            <!-- Container Tabel dengan Background Soft & Padding Lega -->
            <div class="p-4 sm:p-6 md:p-8 flex-1 min-h-0 flex flex-col overflow-hidden bg-[#fdfdfd]">
                
                <!-- Wrapper Scrollable Tabel dengan Header Solid Opaque -->
                <div class="overflow-x-auto overflow-y-auto max-h-[calc(90vh-230px)] rounded-2xl border border-[#ebebeb] bg-white shadow-2xs kempo-scrollbar">
                    <table class="w-full border-separate border-spacing-0 text-left text-sm font-body">
                        <!-- THEAD BABAK 1 & BABAK 2 (12 Kolom Form Input) -->
                        <thead id="scoresheetTheadBabak">
                            <tr class="uppercase text-xs font-bold text-neutral-600 font-heading">
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; width: 45px; padding: 12px 10px; text-align: center;">No</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; min-width: 170px; padding: 12px 14px;">Kontingen</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; min-width: 200px; padding: 12px 14px;">Nama Atlet / Anggota</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; width: 95px; padding: 12px 8px; text-align: center;">Waktu</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; width: 85px; padding: 12px 6px; text-align: center;">Wasit 1</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; width: 85px; padding: 12px 6px; text-align: center;">Wasit 2</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; width: 85px; padding: 12px 6px; text-align: center;">Wasit 3</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; width: 85px; padding: 12px 6px; text-align: center;">Wasit 4</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; width: 85px; padding: 12px 6px; text-align: center;">Wasit 5</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fff0eb !important; border-bottom: 2px solid #fd822b !important; border-right: 1px solid #ebebeb !important; color: #f2470c !important; width: 95px; padding: 12px 8px; text-align: center; font-weight: 800;">Nilai</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fee2e2 !important; border-bottom: 2px solid #e54b4f !important; border-right: 1px solid #ebebeb !important; color: #e54b4f !important; width: 85px; padding: 12px 6px; text-align: center; font-weight: 800;">Denda</th>
                                <th id="thTotalNilaiLabel" style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fff0eb !important; border-bottom: 2px solid #f2470c !important; color: #f2470c !important; width: 140px; padding: 12px 8px; text-align: center; font-weight: 800;">{{ $isRandori ? 'Total Nilai Akhir' : 'Total Nilai Babak 1' }}</th>
                            </tr>
                        </thead>
                        <!-- THEAD NILAI AKHIR (REKAPITULASI 7 KOLOM) -->
                        <thead id="scoresheetTheadAkhir" style="display: none;">
                            <tr class="uppercase text-xs font-bold text-neutral-600 font-heading">
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; width: 50px; padding: 14px 10px; text-align: center;">No</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; min-width: 170px; padding: 14px 14px;">Kontingen</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fafafa !important; border-bottom: 2px solid #ebebeb !important; border-right: 1px solid #ebebeb !important; min-width: 200px; padding: 14px 14px;">Nama Atlet / Anggota</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fff0eb !important; border-bottom: 2px solid #fd822b !important; border-right: 1px solid #ebebeb !important; color: #f2470c !important; width: 130px; padding: 14px 10px; text-align: center; font-weight: 800;">Nilai Babak 1</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fff0eb !important; border-bottom: 2px solid #fd822b !important; border-right: 1px solid #ebebeb !important; color: #f2470c !important; width: 130px; padding: 14px 10px; text-align: center; font-weight: 800;">Nilai Babak 2</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fff0eb !important; border-bottom: 2px solid #f2470c !important; border-right: 1px solid #ebebeb !important; color: #f2470c !important; width: 140px; padding: 14px 10px; text-align: center; font-weight: 800;">Nilai Akhir</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 20 !important; background-color: #fef3c7 !important; border-bottom: 2px solid #f59e0b !important; color: #b45309 !important; width: 170px; padding: 14px 10px; text-align: center; font-weight: 800;">Juara</th>
                            </tr>
                        </thead>
                        <tbody id="scoresheetBody" class="bg-white">
                            <tr>
                                <td colspan="12" class="text-center py-10 text-neutral-400 font-medium" style="padding: 2.5rem 1rem;">Memuat data kontingen &amp; peserta...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="px-6 py-4 sm:px-8 sm:py-5 shrink-0 flex flex-wrap items-center justify-between gap-3 border-t border-[#ebebeb] bg-white">
                <div>
                    <a href="{{ route('pertandingan.cetak-pdf', $pertandingan->id) }}" id="btnCetakPdfScoresheet" target="_blank" class="btn-kempo-4 inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold uppercase shadow-2xs hover:opacity-90 transition-all cursor-pointer">
                        <i class="fas fa-print mr-1"></i> Cetak PDF Sekarang
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    <span id="infoModeRekap" style="display: none;" class="text-xs text-neutral-500 font-medium italic items-center gap-1.5">
                        <i class="fas fa-info-circle text-[#f2470c]"></i> Mode Rekapitulasi (Nilai terakumulasi otomatis dari Babak 1 &amp; 2)
                    </span>
                    <button type="button" onclick="closeScoresheetModal()" class="btn-kempo-2 px-5 py-2.5 text-xs font-bold uppercase transition cursor-pointer border-0">
                        Batal
                    </button>
                    <button type="submit" id="btnSimpanScoresheet" class="btn-kempo-1 px-6 py-2.5 text-xs font-bold uppercase shadow-md hover:opacity-95 transition cursor-pointer border-0 inline-flex items-center gap-1.5">
                        <i class="fas fa-save"></i> Simpan Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- 2. MODAL PERAIH MEDALI (KEMPO UI)              -->
<!-- ============================================== -->
<div id="medaliModal" style="display: none;" class="fixed inset-0 z-[999999] items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto transition-all duration-200">
    <div class="relative w-full max-w-[650px] my-auto max-h-[90vh] bg-white rounded-3xl border border-[#ebebeb] shadow-2xl flex flex-col overflow-hidden font-body">
        
        <!-- Header Modal -->
        <div class="px-6 py-5 sm:px-7 sm:py-6 shrink-0 flex items-start justify-between border-b border-[#ebebeb] bg-white">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#fff0eb] text-[#fd822b] flex items-center justify-center shrink-0 text-xl border border-[#ffd375]/40 shadow-2xs">
                    <i class="fas fa-trophy"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-xl md:text-2xl text-[#000000] mb-0 tracking-tight">
                        Peraih Medali
                    </h3>
                    <p class="text-xs md:text-sm text-neutral-500 mb-0 mt-1 font-body">
                        {{ $pertandingan->pertandingan->nama ?? '' }} (Kelas: {{ $pertandingan->pertandingan->kelas ?? '-' }}) &bull; Penilaian Berdasarkan Kontingen
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeMedaliModal()" class="w-9 h-9 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-500 hover:text-[#000000] flex items-center justify-center transition-colors cursor-pointer border-0">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Form Konten Medali (Cards Emas, Perak, Perunggu) -->
        <div id="medaliForm" class="p-6 sm:p-7 flex-1 overflow-y-auto bg-[#fdfdfd] space-y-4 max-h-[calc(90vh-170px)] kempo-scrollbar">
            <input type="hidden" id="medali_pertandingan_id" value="{{ $pertandingan->id }}">
            
            <!-- Emas -->
            <div class="p-4 sm:p-5 rounded-2xl border border-amber-200 bg-amber-50/40 shadow-2xs">
                <label class="block text-xs font-bold mb-2 text-amber-800 font-heading flex items-center gap-2 uppercase tracking-wider">
                    <span class="text-lg">🥇</span> Medali Emas (Juara 1)
                </label>
                <select id="medali_emas" disabled class="w-full border border-amber-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-[#000000] font-semibold cursor-not-allowed">
                    <option value="">-- Belum Ditentukan --</option>
                </select>
            </div>

            <!-- Perak -->
            <div class="p-4 sm:p-5 rounded-2xl border border-slate-200 bg-slate-50/70 shadow-2xs">
                <label class="block text-xs font-bold mb-2 text-slate-700 font-heading flex items-center gap-2 uppercase tracking-wider">
                    <span class="text-lg">🥈</span> Medali Perak (Juara 2)
                </label>
                <select id="medali_perak" disabled class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-[#000000] font-semibold cursor-not-allowed">
                    <option value="">-- Belum Ditentukan --</option>
                </select>
            </div>

            <!-- Perunggu -->
            <div class="p-4 sm:p-5 rounded-2xl border border-orange-200 bg-[#fff0eb]/70 shadow-2xs">
                <label class="block text-xs font-bold mb-2 text-[#f2470c] font-heading flex items-center gap-2 uppercase tracking-wider">
                    <span class="text-lg">🥉</span> Medali Perunggu (Juara 3)
                </label>
                <select id="medali_perunggu1" disabled class="w-full border border-orange-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-[#000000] font-semibold cursor-not-allowed">
                    <option value="">-- Belum Ditentukan --</option>
                </select>
            </div>
        </div>

        <!-- Footer Modal Medali -->
        <div class="px-6 py-4 sm:px-7 sm:py-5 shrink-0 flex justify-end border-t border-[#ebebeb] bg-white">
            <button type="button" onclick="closeMedaliModal()" class="btn-kempo-2 px-6 py-2.5 text-xs font-bold uppercase transition cursor-pointer border-0">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- 3. JAVASCRIPT LOGIKA PENILAIAN PER KONTINGEN   -->
<!-- ============================================== -->
<script>
    const DEFAULT_PERTANDINGAN_ID = {{ $pertandingan->id }};
    window.currentScoresheetFilterKabId = null;
    window.currentScoresheetFilterKabName = null;
    window.currentScoresheetBabak = 1;
    window.currentScoresheetIsRandori = {{ $isRandori ? 'true' : 'false' }};

    window.openScoresheetModal = function(id, filterKabupatenId = null, filterKabupatenName = null) {
        const targetId = id || DEFAULT_PERTANDINGAN_ID;
        window.currentScoresheetFilterKabId = filterKabupatenId;
        window.currentScoresheetFilterKabName = filterKabupatenName;
        window.currentScoresheetBabak = 1;

        const modal = document.getElementById('scoresheetModal');
        if (modal) {
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.style.display = 'flex';
        }

        document.getElementById('scoresheet_pertandingan_id').value = targetId;
        const babakInput = document.getElementById('scoresheet_babak');
        if (babakInput) babakInput.value = '1';
        const babakSelect = document.getElementById('selectBabakPenilaian');
        if (babakSelect) babakSelect.value = '1';

        // Update PDF Link & Filter Badge
        updateScoresheetPdfLink(targetId, filterKabupatenId);
        updateScoresheetFilterBadge(filterKabupatenName);

        // Load data nilai untuk babak 1
        loadScoresheetData(targetId, 1);
    };

    window.onBabakPenilaianChange = function(babak) {
        window.currentScoresheetBabak = babak;
        const babakInput = document.getElementById('scoresheet_babak');
        if (babakInput) babakInput.value = babak;

        const targetId = document.getElementById('scoresheet_pertandingan_id').value || DEFAULT_PERTANDINGAN_ID;
        loadScoresheetData(targetId, babak);
    };

    window.loadScoresheetData = function(targetId, babak = 1) {
        const isAkhir = (babak === 'akhir');
        const theadBabak = document.getElementById('scoresheetTheadBabak');
        const theadAkhir = document.getElementById('scoresheetTheadAkhir');
        const btnSimpan = document.getElementById('btnSimpanScoresheet');
        const infoRekap = document.getElementById('infoModeRekap');
        const colCount = isAkhir ? 7 : 12;

        if (theadBabak) theadBabak.style.display = isAkhir ? 'none' : 'table-header-group';
        if (theadAkhir) theadAkhir.style.display = isAkhir ? 'table-header-group' : 'none';
        if (btnSimpan) btnSimpan.style.display = isAkhir ? 'none' : 'inline-flex';
        if (infoRekap) infoRekap.style.display = isAkhir ? 'inline-flex' : 'none';

        const tbody = document.getElementById('scoresheetBody');
        const labelMemuat = isAkhir ? 'Nilai Akhir (Rekapitulasi)' : 'Babak ' + babak;
        tbody.innerHTML = `<tr><td colspan="${colCount}" class="text-center py-10 text-neutral-400 font-medium" style="padding: 2.5rem 1rem;"><i class="fas fa-spinner fa-spin mr-2 text-[#f2470c]"></i> Memuat data kontingen &amp; peserta (${labelMemuat})...</td></tr>`;

        // Update dynamic table header label jika pada thead babak
        const thTotal = document.getElementById('thTotalNilaiLabel');
        if (thTotal && !isAkhir) {
            if (window.currentScoresheetIsRandori) {
                thTotal.textContent = 'Total Nilai Akhir';
            } else {
                thTotal.textContent = (babak === 2 || babak === '2') ? 'Total Nilai Babak 2' : 'Total Nilai Babak 1';
            }
        }

        fetch(`/pertandingan/${targetId}/peserta?babak=${babak}`)
            .then(res => res.json())
            .then(res => {
                if (typeof res.is_randori !== 'undefined') {
                    window.currentScoresheetIsRandori = res.is_randori;
                    const containerBabak = document.getElementById('containerBabakPenilaian');
                    if (containerBabak) {
                        if (res.is_randori) {
                            containerBabak.classList.add('hidden');
                            containerBabak.classList.remove('flex');
                        } else {
                            containerBabak.classList.remove('hidden');
                            containerBabak.classList.add('flex');
                        }
                    }
                    if (thTotal && !isAkhir) {
                        if (res.is_randori) {
                            thTotal.textContent = 'Total Nilai Akhir';
                        } else {
                            thTotal.textContent = (babak === 2 || babak === '2') ? 'Total Nilai Babak 2' : 'Total Nilai Babak 1';
                        }
                    }
                }

                const data = res.peserta || [];
                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="${colCount}" class="text-center py-10 text-neutral-400 font-medium" style="padding: 2.5rem 1rem;">Belum ada peserta di nomor pertandingan ini.</td></tr>`;
                    return;
                }
                tbody.innerHTML = '';
                let visibleCount = 0;

                data.forEach((item, index) => {
                    const namaPeserta = item.nama || '-';
                    const kontingenName = item.kontingen || '-';

                    const matchesFilter = !window.currentScoresheetFilterKabId ||
                        String(item.kabupaten_id) === String(window.currentScoresheetFilterKabId) ||
                        (window.currentScoresheetFilterKabName && kontingenName.toLowerCase().includes(window.currentScoresheetFilterKabName.toLowerCase()));

                    if (matchesFilter) {
                        visibleCount++;
                    }

                    const tr = document.createElement('tr');
                    tr.className = 'scoresheet-row hover:bg-[#fff0eb]/30 transition-colors border-b border-[#ebebeb]';
                    tr.dataset.rowId = item.id;
                    tr.dataset.kabupatenId = item.kabupaten_id || '';
                    tr.dataset.kontingen = kontingenName;

                    if (!matchesFilter) {
                        tr.style.display = 'none';
                    }

                    if (isAkhir) {
                        // TAMPILAN TABEL NILAI AKHIR (REKAPITULASI 7 KOLOM)
                        const b1 = (item.total_babak_1 !== null && item.total_babak_1 !== undefined) ? item.total_babak_1 : '-';
                        const b2 = (item.total_babak_2 !== null && item.total_babak_2 !== undefined) ? item.total_babak_2 : '-';
                        const na = (item.nilai_akhir !== null && item.nilai_akhir !== undefined) ? item.nilai_akhir : '-';

                        let juaraBadge = '<span class="text-neutral-400 font-semibold">-</span>';
                        const medaliVal = (item.medali || item.medali_auto || '').toLowerCase();
                        const tingkat = item.juara_tingkat;

                        if (medaliVal === 'emas' || tingkat === 1) {
                            juaraBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300 shadow-2xs">🥇 Juara 1 (Emas)</span>';
                        } else if (medaliVal === 'perak' || tingkat === 2) {
                            juaraBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-slate-100 text-slate-800 border border-slate-300 shadow-2xs">🥈 Juara 2 (Perak)</span>';
                        } else if (medaliVal === 'perunggu' || tingkat === 3) {
                            juaraBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-orange-100 text-[#f2470c] border border-orange-300 shadow-2xs">🥉 Juara 3 (Perunggu)</span>';
                        } else if (tingkat && tingkat > 3) {
                            juaraBadge = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-neutral-100 text-neutral-600">Peringkat ${tingkat}</span>`;
                        }

                        tr.innerHTML = `
                            <td style="padding: 12px 10px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center;" class="font-bold text-xs text-neutral-400 tabular-nums row-number-cell">${index + 1}</td>
                            <td style="padding: 12px 14px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb;">
                                <div class="text-sm font-heading font-bold text-[#f2470c]">${kontingenName}</div>
                            </td>
                            <td style="padding: 12px 14px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb;" class="font-medium text-xs text-neutral-800">
                                ${namaPeserta}
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center; background-color: #fff0eb;" class="font-heading font-bold text-neutral-800 text-sm tabular-nums">
                                ${b1}
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center; background-color: #fff0eb;" class="font-heading font-bold text-neutral-800 text-sm tabular-nums">
                                ${b2}
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center; background-color: #fff0eb;" class="font-heading font-black text-[#f2470c] text-base tabular-nums">
                                ${na}
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid #ebebeb; text-align: center; background-color: #fefce8;">
                                ${juaraBadge}
                            </td>
                        `;
                    } else {
                        // TAMPILAN TABEL FORM INPUT WASIT BABAK 1 / BABAK 2 (12 KOLOM)
                        const waktu = (item.waktu && item.waktu !== '00:00') ? item.waktu : '';
                        const n1 = (item.nilai_1 !== null && item.nilai_1 !== undefined && item.nilai_1 !== '') ? item.nilai_1 : '';
                        const n2 = (item.nilai_2 !== null && item.nilai_2 !== undefined && item.nilai_2 !== '') ? item.nilai_2 : '';
                        const n3 = (item.nilai_3 !== null && item.nilai_3 !== undefined && item.nilai_3 !== '') ? item.nilai_3 : '';
                        const n4 = (item.nilai_4 !== null && item.nilai_4 !== undefined && item.nilai_4 !== '') ? item.nilai_4 : '';
                        const n5 = (item.nilai_5 !== null && item.nilai_5 !== undefined && item.nilai_5 !== '') ? item.nilai_5 : '';

                        const hasAnyJudgeScore = [n1, n2, n3, n4, n5].some(v => v !== '');
                        const genten = (item.minus !== null && item.minus !== undefined && item.minus !== '' && (hasAnyJudgeScore || item.minus != 0)) ? item.minus : '';
                        const ppIdsString = item.pp_ids ? item.pp_ids.join(',') : item.id;

                        tr.innerHTML = `
                            <td style="padding: 10px 8px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center;" class="font-bold text-xs text-neutral-400 tabular-nums row-number-cell">${index + 1}</td>
                            <td style="padding: 10px 14px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb;">
                                <div class="text-sm font-heading font-bold text-[#f2470c]">${kontingenName}</div>
                                <input type="hidden" name="scores[${item.id}][pp_ids]" value="${ppIdsString}">
                                <input type="hidden" name="scores[${item.id}][kontingen]" value="${kontingenName}">
                                <input type="hidden" name="scores[${item.id}][nama]" value="${namaPeserta}">
                            </td>
                            <td style="padding: 10px 14px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb;" class="font-medium text-xs text-neutral-800">
                                ${namaPeserta}
                            </td>
                            <td style="padding: 8px 6px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center;">
                                <input type="text" placeholder="00:00" name="scores[${item.id}][waktu]" value="${waktu}" style="width: 80px; padding: 6px 6px; border: 1px solid #ebebeb; border-radius: 10px; text-align: center; font-size: 0.8125rem; font-family: var(--font-body);" class="font-semibold tabular-nums text-neutral-800 bg-white focus:border-[#f2470c] focus:outline-none focus:ring-2 focus:ring-[#f2470c]/20 transition-all">
                            </td>
                            <td style="padding: 8px 4px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center;">
                                <input type="number" step="0.01" min="0" max="100" placeholder="0" title="Wasit 1 (0 - 100)" name="scores[${item.id}][nilai_1]" value="${n1}" oninput="validateScoreInput(this); recalcScoresheet();" onwheel="this.blur();" style="width: 76px; padding: 6px 4px; border: 1px solid #ebebeb; border-radius: 10px; text-align: center; font-size: 0.8125rem; font-family: var(--font-body);" class="score-input n1 font-bold tabular-nums text-[#000000] bg-white focus:border-[#f2470c] focus:outline-none focus:ring-2 focus:ring-[#f2470c]/20 transition-all">
                            </td>
                            <td style="padding: 8px 4px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center;">
                                <input type="number" step="0.01" min="0" max="100" placeholder="0" title="Wasit 2 (0 - 100)" name="scores[${item.id}][nilai_2]" value="${n2}" oninput="validateScoreInput(this); recalcScoresheet();" onwheel="this.blur();" style="width: 76px; padding: 6px 4px; border: 1px solid #ebebeb; border-radius: 10px; text-align: center; font-size: 0.8125rem; font-family: var(--font-body);" class="score-input n2 font-bold tabular-nums text-[#000000] bg-white focus:border-[#f2470c] focus:outline-none focus:ring-2 focus:ring-[#f2470c]/20 transition-all">
                            </td>
                            <td style="padding: 8px 4px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center;">
                                <input type="number" step="0.01" min="0" max="100" placeholder="0" title="Wasit 3 (0 - 100)" name="scores[${item.id}][nilai_3]" value="${n3}" oninput="validateScoreInput(this); recalcScoresheet();" onwheel="this.blur();" style="width: 76px; padding: 6px 4px; border: 1px solid #ebebeb; border-radius: 10px; text-align: center; font-size: 0.8125rem; font-family: var(--font-body);" class="score-input n3 font-bold tabular-nums text-[#000000] bg-white focus:border-[#f2470c] focus:outline-none focus:ring-2 focus:ring-[#f2470c]/20 transition-all">
                            </td>
                            <td style="padding: 8px 4px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center;">
                                <input type="number" step="0.01" min="0" max="100" placeholder="0" title="Wasit 4 (0 - 100)" name="scores[${item.id}][nilai_4]" value="${n4}" oninput="validateScoreInput(this); recalcScoresheet();" onwheel="this.blur();" style="width: 76px; padding: 6px 4px; border: 1px solid #ebebeb; border-radius: 10px; text-align: center; font-size: 0.8125rem; font-family: var(--font-body);" class="score-input n4 font-bold tabular-nums text-[#000000] bg-white focus:border-[#f2470c] focus:outline-none focus:ring-2 focus:ring-[#f2470c]/20 transition-all">
                            </td>
                            <td style="padding: 8px 4px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center;">
                                <input type="number" step="0.01" min="0" max="100" placeholder="0" title="Wasit 5 (0 - 100)" name="scores[${item.id}][nilai_5]" value="${n5}" oninput="validateScoreInput(this); recalcScoresheet();" onwheel="this.blur();" style="width: 76px; padding: 6px 4px; border: 1px solid #ebebeb; border-radius: 10px; text-align: center; font-size: 0.8125rem; font-family: var(--font-body);" class="score-input n5 font-bold tabular-nums text-[#000000] bg-white focus:border-[#f2470c] focus:outline-none focus:ring-2 focus:ring-[#f2470c]/20 transition-all">
                            </td>
                            <td style="padding: 10px 8px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center; background-color: #fff0eb;" class="font-heading font-bold text-[#f2470c] text-sm tabular-nums nilai-sum">-</td>
                            <td style="padding: 8px 4px; border-bottom: 1px solid #ebebeb; border-right: 1px solid #ebebeb; text-align: center;">
                                <input type="number" step="0.01" min="0" max="100" placeholder="0" title="Denda / Minus (0 - 100)" name="scores[${item.id}][minus]" value="${genten}" oninput="validateScoreInput(this); recalcScoresheet();" onwheel="this.blur();" style="width: 76px; padding: 6px 4px; border: 1px solid #fca5a5; border-radius: 10px; text-align: center; font-size: 0.8125rem; font-family: var(--font-body);" class="score-input genten font-bold tabular-nums text-[#e54b4f] bg-white focus:border-[#e54b4f] focus:outline-none focus:ring-2 focus:ring-[#e54b4f]/20 transition-all">
                            </td>
                            <td style="padding: 10px 8px; border-bottom: 1px solid #ebebeb; text-align: center; background-color: #fff0eb;" class="font-heading font-extrabold text-[#f2470c] text-base tabular-nums total-akhir-cell">-</td>
                        `;
                    }
                    tbody.appendChild(tr);
                });

                if (window.currentScoresheetFilterKabId && visibleCount === 0) {
                    const emptyMsg = document.createElement('tr');
                    emptyMsg.id = 'scoresheetEmptyFilterRow';
                    emptyMsg.innerHTML = `<td colspan="${colCount}" class="text-center py-6 text-amber-700 font-semibold" style="padding: 2rem 1rem;">
                        Data untuk kontingen ini tidak ditemukan di pertandingan ini. 
                        <button type="button" onclick="clearScoresheetFilter()" class="underline font-bold ml-1 text-[#f2470c] hover:underline bg-transparent border-0 cursor-pointer">Tampilkan Semua</button>
                    </td>`;
                    tbody.appendChild(emptyMsg);
                }

                if (!isAkhir) {
                    recalcScoresheet();
                }
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = `<tr><td colspan="${colCount}" class="text-center py-10 text-rose-500 font-semibold" style="padding: 2.5rem 1rem;">Gagal memuat data peserta.</td></tr>`;
            });
    };

    window.updateScoresheetPdfLink = function(targetId, filterKabupatenId) {
        const btnPdf = document.getElementById('btnCetakPdfScoresheet');
        if (btnPdf) {
            let baseUrl = `/pertandingan/${targetId}/cetak-pdf`;
            if (filterKabupatenId) {
                baseUrl += `?kabupaten_id=${filterKabupatenId}`;
            }
            btnPdf.href = baseUrl;
        }
    };

    window.updateScoresheetFilterBadge = function(filterKabupatenName) {
        const badge = document.getElementById('scoresheetFilterBadge');
        const text = document.getElementById('scoresheetFilterText');
        if (badge && text) {
            if (window.currentScoresheetFilterKabId) {
                text.textContent = filterKabupatenName || 'Khusus Kontingen';
                badge.style.display = 'inline-flex';
            } else {
                badge.style.display = 'none';
            }
        }
    };

    window.clearScoresheetFilter = function() {
        window.currentScoresheetFilterKabId = null;
        window.currentScoresheetFilterKabName = null;

        const targetId = document.getElementById('scoresheet_pertandingan_id').value || DEFAULT_PERTANDINGAN_ID;
        updateScoresheetPdfLink(targetId, null);
        updateScoresheetFilterBadge(null);

        const emptyMsg = document.getElementById('scoresheetEmptyFilterRow');
        if (emptyMsg) {
            emptyMsg.remove();
        }

        document.querySelectorAll('.scoresheet-row').forEach(row => {
            row.style.display = '';
        });
    };

    window.closeScoresheetModal = function() {
        const modal = document.getElementById('scoresheetModal');
        if (modal) {
            modal.style.display = 'none';
        }
    };

    window.validateScoreInput = function(input) {
        if (!input || input.value === '') return;

        // Cegah tanda minus / karakter negatif
        if (input.value.includes('-')) {
            input.value = input.value.replace(/-/g, '');
        }

        let val = parseFloat(input.value);
        if (!isNaN(val)) {
            if (val < 0) {
                input.value = 0;
            } else if (val > 100) {
                input.value = 100;
            }
        }
    };

    // Hilangkan fungsi merubah nilai input angka melalui scroll mouse wheel
    document.addEventListener('wheel', function(e) {
        if (e.target && (e.target.matches('#scoresheetModal input[type="number"]') || e.target.classList.contains('score-input'))) {
            e.target.blur();
        }
    }, { passive: true });

    window.recalcScoresheet = function() {
        const rows = document.querySelectorAll('.scoresheet-row');

        rows.forEach(row => {
            const v1 = row.querySelector('.n1').value;
            const v2 = row.querySelector('.n2').value;
            const v3 = row.querySelector('.n3').value;
            const v4 = row.querySelector('.n4').value;
            const v5 = row.querySelector('.n5').value;
            const gentenInput = row.querySelector('.genten').value;
            const gentenVal = (gentenInput !== '' && !isNaN(parseFloat(gentenInput))) ? parseFloat(gentenInput) : 0;

            const hasAnyInput = [v1, v2, v3, v4, v5, gentenInput].some(v => v !== '');
            if (!hasAnyInput) {
                row.querySelector('.nilai-sum').textContent = '-';
                row.querySelector('.total-akhir-cell').textContent = '-';
                return;
            }

            const n1 = (v1 !== '' && !isNaN(parseFloat(v1))) ? parseFloat(v1) : null;
            const n2 = (v2 !== '' && !isNaN(parseFloat(v2))) ? parseFloat(v2) : null;
            const n3 = (v3 !== '' && !isNaN(parseFloat(v3))) ? parseFloat(v3) : null;
            const n4 = (v4 !== '' && !isNaN(parseFloat(v4))) ? parseFloat(v4) : null;
            const n5 = (v5 !== '' && !isNaN(parseFloat(v5))) ? parseFloat(v5) : null;

            // Rumus: Hilangkan nilai terbesar dan terkecil dari wasit yang aktif (> 0), jumlahkan nilai tengah, lalu dikurangi denda
            const activeScores = [n1, n2, n3, n4, n5].filter(v => v !== null && v > 0).sort((a, b) => a - b);
            let jumlahNilai = 0;
            if (activeScores.length >= 3) {
                const middle = activeScores.slice(1, activeScores.length - 1);
                jumlahNilai = middle.reduce((acc, val) => acc + val, 0);
            }
            const totalNilaiAkhir = Math.max(0, jumlahNilai - gentenVal);

            const hasJudgesInput = activeScores.length > 0;
            row.querySelector('.nilai-sum').textContent = hasJudgesInput
                ? (Number.isInteger(jumlahNilai) ? jumlahNilai : jumlahNilai.toFixed(2))
                : '-';
            row.querySelector('.total-akhir-cell').textContent = (hasJudgesInput || gentenVal > 0)
                ? (Number.isInteger(totalNilaiAkhir) ? totalNilaiAkhir : totalNilaiAkhir.toFixed(2))
                : '-';
        });
    };

    window.submitScoresheet = function(event) {
        event.preventDefault();
        const id = document.getElementById('scoresheet_pertandingan_id').value || DEFAULT_PERTANDINGAN_ID;
        const form = document.getElementById('scoresheetForm');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('btnSimpanScoresheet');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Menyimpan...';

        fetch(`/pertandingan/${id}/scoresheet`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-1.5"></i> Simpan Nilai';

            if (data.status === 'success') {
                const successMsg = data.message || 'Nilai scoresheet kontingen dan peraih medali berhasil disimpan!';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: successMsg,
                        timer: 1800,
                        showConfirmButton: false
                    });
                } else {
                    alert(successMsg);
                }
                closeScoresheetModal();
            } else {
                const msg = data.message || 'Gagal menyimpan nilai.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: msg
                    });
                } else {
                    alert(msg);
                }
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-1.5"></i> Simpan Nilai';
            console.error(err);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Terjadi kesalahan saat memproses data.'
                });
            } else {
                alert('Terjadi kesalahan saat menyimpan nilai.');
            }
        });
    };

    window.openMedaliModal = function(id) {
        const targetId = id || DEFAULT_PERTANDINGAN_ID;
        const modal = document.getElementById('medaliModal');
        if (modal) {
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.style.display = 'flex';
        }

        document.getElementById('medali_pertandingan_id').value = targetId;
        const selects = ['medali_emas', 'medali_perak', 'medali_perunggu1'];
        selects.forEach(s => {
            document.getElementById(s).innerHTML = '<option value="">Memuat data...</option>';
        });

        fetch(`/pertandingan/${targetId}/peserta`)
            .then(res => res.json())
            .then(res => {
                const pert = res.pertandingan || {};
                const data = res.peserta || [];

                let optionsHtml = '<option value="">-- Belum Ditentukan --</option>';

                // Opsi penilaian langsung berdasarkan kontingen
                data.forEach(item => {
                    const label = `${item.kontingen} (${item.nama})`;
                    const val = item.kontingen;
                    optionsHtml += `<option value="${val}">${label}</option>`;
                });

                selects.forEach(s => {
                    document.getElementById(s).innerHTML = optionsHtml;
                });

                // Urutkan kontingen berdasarkan nilai_akhir / total_nilai tertinggi untuk pengisian otomatis
                const scoredPeserta = data
                    .filter(item => (item.nilai_akhir !== null && item.nilai_akhir !== undefined) || (item.total_nilai !== null && item.total_nilai !== undefined))
                    .sort((a, b) => {
                        const scoreA = (a.nilai_akhir !== null && a.nilai_akhir !== undefined) ? parseFloat(a.nilai_akhir) : parseFloat(a.total_nilai || 0);
                        const scoreB = (b.nilai_akhir !== null && b.nilai_akhir !== undefined) ? parseFloat(b.nilai_akhir) : parseFloat(b.total_nilai || 0);
                        return scoreB - scoreA;
                    });

                const autoCandidates = [];
                scoredPeserta.forEach(item => {
                    const val = item.kontingen;
                    if (val && !autoCandidates.includes(val)) {
                        autoCandidates.push(val);
                    }
                });

                // Isi otomatis berdasarkan data_juara, medali peserta, atau nilai akhir tertinggi
                const dj = pert.data_juara || {};
                const djEmas = (dj.emas && dj.emas.kabupaten_nama) || '';
                const djPerak = (dj.perak && dj.perak.kabupaten_nama) || '';
                const djPerunggu1 = (dj.perunggu && dj.perunggu.kabupaten_nama) || '';

                const medaliEmasKontingen = (data.find(it => it.medali && it.medali.toLowerCase() === 'emas') || {}).kontingen;
                const medaliPerakKontingen = (data.find(it => it.medali && it.medali.toLowerCase() === 'perak') || {}).kontingen;
                const medaliPerunggu1Kontingen = (data.find(it => it.medali && it.medali.toLowerCase() === 'perunggu') || {}).kontingen;

                document.getElementById('medali_emas').value = djEmas || medaliEmasKontingen || autoCandidates[0] || pert.emas || '';
                document.getElementById('medali_perak').value = djPerak || medaliPerakKontingen || autoCandidates[1] || pert.perak || '';
                document.getElementById('medali_perunggu1').value = djPerunggu1 || medaliPerunggu1Kontingen || autoCandidates[2] || pert.perunggu1 || pert.perunggu || '';
            })
            .catch(err => {
                console.error(err);
                selects.forEach(s => {
                    document.getElementById(s).innerHTML = '<option value="">Gagal memuat</option>';
                });
            });
    };

    window.closeMedaliModal = function() {
        const modal = document.getElementById('medaliModal');
        if (modal) {
            modal.style.display = 'none';
        }
    };

    // Close modal on click backdrop & ESC
    document.addEventListener('DOMContentLoaded', function() {
        ['scoresheetModal', 'medaliModal'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function(e) {
                    if (e.target === el) {
                        el.style.display = 'none';
                    }
                });
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeScoresheetModal();
                closeMedaliModal();
            }
        });
    });
</script>
