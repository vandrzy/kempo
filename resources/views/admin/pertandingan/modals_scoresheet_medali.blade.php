@php
    $isRandori = stripos($pertandingan->pertandingan->nama ?? '', 'randori') !== false ||
                 stripos($pertandingan->kategori->nama ?? '', 'randori') !== false;
    $scoresheet = $pertandingan->data_scoresheet ?? [];
    $juara = $pertandingan->data_juara ?? [];
@endphp

<!-- ============================================== -->
<!-- 1. MODAL SCORE SHEET PERTANDINGAN (MAX-W-7XL)  -->
<!-- ============================================== -->
<div id="scoresheetModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(0, 0, 0, 0.75); align-items: center; justify-content: center; padding: 2.5rem 1.5rem; overflow-y: auto;">
    <div style="position: relative; width: 100%; max-width: 1320px; margin: auto; max-height: 92vh; background: #ffffff; border-radius: 1.25rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); display: flex; flex-direction: column; overflow: hidden;" class="dark:bg-slate-850 border border-slate-200 dark:border-slate-700">
        
        <!-- Header Modal (Padding Lega) -->
        <div style="padding: 1.5rem 2.25rem; flex-shrink: 0;" class="flex justify-between items-center border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/70">
            <div>
                <div class="flex items-center gap-2.5">
                    <h3 class="text-xl md:text-2xl font-black text-slate-800 dark:text-white flex items-center mb-0">
                        <i class="ni ni-paper-diploma text-blue-600 mr-2.5"></i> Score Sheet Pertandingan
                    </h3>
                    @if($isRandori)
                        <span class="px-3 py-1 text-xs font-black text-red-700 bg-red-100 dark:bg-red-900/40 dark:text-red-300 rounded-full">Kategori Randori</span>
                    @else
                        <span class="px-3 py-1 text-xs font-black text-blue-700 bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300 rounded-full">Kategori Embu</span>
                    @endif
                </div>
                <p class="text-xs md:text-sm text-slate-500 mb-0 mt-1.5 font-medium">
                    {{ $pertandingan->kegiatan->nama ?? '' }} &bull; {{ $pertandingan->pertandingan->nama ?? '' }} (Kelas: {{ $pertandingan->pertandingan->kelas ?? '-' }}) &bull; Penilaian Berdasarkan Kontingen
                </p>
            </div>
            <button type="button" onclick="closeScoresheetModal()" class="text-slate-400 hover:text-slate-700 dark:hover:text-white text-3xl font-bold leading-none cursor-pointer border-0 bg-transparent p-1">&times;</button>
        </div>
        
        <!-- Form Scoresheet -->
        <form id="scoresheetForm" onsubmit="submitScoresheet(event)" class="flex flex-col flex-1 overflow-hidden mb-0">
            @csrf
            <input type="hidden" id="scoresheet_pertandingan_id" value="{{ $pertandingan->id }}">
            <input type="hidden" name="kegiatan_id" value="{{ $pertandingan->kegiatan->id ?? '' }}">
            
            <!-- Container Tabel dengan Padding Lega -->
            <div style="padding: 1.5rem 2.25rem; flex: 1 1 auto; min-height: 0; display: flex; flex-direction: column;">
                
                <!-- Wrapper Scrollable Tabel dengan Header Solid Opaque (Tidak Tembus) -->
                <div style="overflow-x: auto; overflow-y: auto; max-height: calc(90vh - 230px); border: 1.5px solid #cbd5e1; border-radius: 0.75rem; background-color: #ffffff;" class="dark:bg-slate-800 dark:border-slate-700 shadow-xs">
                    <table style="width: 100%; border-collapse: separate !important; border-spacing: 0 !important; text-align: left; background-color: #ffffff;" class="dark:bg-slate-800 text-sm">
                        <thead>
                            <tr class="uppercase text-xs font-bold text-slate-700 dark:text-white">
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #f1f5f9 !important; background-clip: padding-box !important; border-bottom: 2px solid #94a3b8 !important; border-right: 1px solid #cbd5e1 !important; width: 45px; padding: 14px 10px; text-align: center;">No</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #f1f5f9 !important; background-clip: padding-box !important; border-bottom: 2px solid #94a3b8 !important; border-right: 1px solid #cbd5e1 !important; min-width: 170px; padding: 14px 14px;">Kontingen</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #f1f5f9 !important; background-clip: padding-box !important; border-bottom: 2px solid #94a3b8 !important; border-right: 1px solid #cbd5e1 !important; min-width: 200px; padding: 14px 14px;">Nama Atlet / Anggota</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #f1f5f9 !important; background-clip: padding-box !important; border-bottom: 2px solid #94a3b8 !important; border-right: 1px solid #cbd5e1 !important; width: 95px; padding: 14px 10px; text-align: center;">Waktu</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #f8fafc !important; background-clip: padding-box !important; border-bottom: 2px solid #94a3b8 !important; border-right: 1px solid #cbd5e1 !important; width: 85px; padding: 14px 8px; text-align: center;">Wasit 1</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #f8fafc !important; background-clip: padding-box !important; border-bottom: 2px solid #94a3b8 !important; border-right: 1px solid #cbd5e1 !important; width: 85px; padding: 14px 8px; text-align: center;">Wasit 2</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #f8fafc !important; background-clip: padding-box !important; border-bottom: 2px solid #94a3b8 !important; border-right: 1px solid #cbd5e1 !important; width: 85px; padding: 14px 8px; text-align: center;">Wasit 3</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #f8fafc !important; background-clip: padding-box !important; border-bottom: 2px solid #94a3b8 !important; border-right: 1px solid #cbd5e1 !important; width: 85px; padding: 14px 8px; text-align: center;">Wasit 4</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #f8fafc !important; background-clip: padding-box !important; border-bottom: 2px solid #94a3b8 !important; border-right: 1px solid #cbd5e1 !important; width: 85px; padding: 14px 8px; text-align: center;">Wasit 5</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #dbeafe !important; background-clip: padding-box !important; border-bottom: 2px solid #60a5fa !important; border-right: 1px solid #cbd5e1 !important; color: #1e40af !important; width: 95px; padding: 14px 10px; text-align: center; font-weight: 900;">Nilai</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #fee2e2 !important; background-clip: padding-box !important; border-bottom: 2px solid #f87171 !important; border-right: 1px solid #cbd5e1 !important; color: #dc2626 !important; width: 85px; padding: 14px 8px; text-align: center;">Genten</th>
                                <th style="position: sticky !important; top: 0 !important; z-index: 50 !important; background-color: #d1fae5 !important; background-clip: padding-box !important; border-bottom: 2px solid #34d399 !important; color: #065f46 !important; width: 130px; padding: 14px 10px; text-align: center; font-weight: 900;">Total Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody id="scoresheetBody" class="bg-white dark:bg-slate-800">
                            <tr>
                                <td colspan="12" class="text-center py-10 text-slate-400 font-semibold" style="padding: 2.5rem 1rem;">Memuat data kontingen &amp; peserta...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Modal (Padding Lega) -->
            <div style="padding: 1.25rem 2.25rem; flex-shrink: 0;" class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/70">
                <div>
                    <a href="{{ route('pertandingan.cetak-pdf', $pertandingan->id) }}" target="_blank" style="background-color: #059669 !important; color: #ffffff !important;" class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold text-white uppercase rounded-lg shadow-md hover:opacity-90 transition-all cursor-pointer">
                        <i class="fa fa-print mr-1"></i> Cetak PDF Sekarang
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeScoresheetModal()" style="background-color: #e2e8f0 !important; color: #334155 !important;" class="px-5 py-2.5 text-xs font-bold text-slate-700 uppercase rounded-lg transition cursor-pointer border-0 hover:bg-slate-300">
                        Batal
                    </button>
                    <button type="submit" id="btnSimpanScoresheet" style="background-color: #2563eb !important; color: #ffffff !important;" class="px-6 py-2.5 text-xs font-bold text-white uppercase rounded-lg shadow-md hover:opacity-90 transition cursor-pointer border-0">
                        <i class="fa fa-save mr-1.5"></i> Simpan Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- 2. MODAL PERAIH MEDALI (MAX-W-2XL, PADDING LEGA)-->
<!-- ============================================== -->
<div id="medaliModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(0, 0, 0, 0.75); align-items: center; justify-content: center; padding: 2.5rem 1.5rem; overflow-y: auto;">
    <div style="position: relative; width: 100%; max-width: 700px; margin: auto; max-height: 90vh; background: #ffffff; border-radius: 1.25rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); display: flex; flex-direction: column; overflow: hidden;" class="dark:bg-slate-850 border border-slate-200 dark:border-slate-700">
        
        <!-- Header Modal (Padding Lega) -->
        <div style="padding: 1.5rem 2rem; flex-shrink: 0;" class="flex justify-between items-center border-b border-slate-200 dark:border-slate-700 bg-amber-50/70 dark:bg-amber-950/20">
            <div>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 dark:text-white flex items-center gap-2 mb-0">
                    <i class="ni ni-trophy text-amber-500"></i> Peraih Medali
                </h3>
                <p class="text-xs md:text-sm text-slate-500 mb-0 mt-1.5 font-medium">
                    {{ $pertandingan->pertandingan->nama ?? '' }} (Kelas: {{ $pertandingan->pertandingan->kelas ?? '-' }}) &bull; Penilaian Berdasarkan Kontingen
                </p>
            </div>
            <button type="button" onclick="closeMedaliModal()" class="text-slate-400 hover:text-slate-700 dark:hover:text-white text-3xl font-bold leading-none cursor-pointer border-0 bg-transparent p-1">&times;</button>
        </div>

        <!-- Form Konten Medali (Padding Lega & Spasi Antar Card) -->
        <div id="medaliForm" style="padding: 2rem; flex: 1 1 auto; max-height: calc(90vh - 170px); overflow-y: auto;">
            <input type="hidden" id="medali_pertandingan_id" value="{{ $pertandingan->id }}">
            
            <!-- Emas -->
            <div style="padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; border: 1.5px solid #fde68a;" class="bg-amber-50/70 dark:bg-amber-950/20 rounded-xl shadow-xs">
                <label class="block text-sm font-black mb-2 text-yellow-700 dark:text-yellow-400 flex items-center gap-2">
                    <span class="text-xl">🥇</span> Medali Emas (Juara 1)
                </label>
                <select id="medali_emas" disabled class="w-full border border-amber-300 rounded-lg px-3.5 py-2.5 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-white font-bold cursor-not-allowed">
                    <option value="">-- Belum Ditentukan --</option>
                </select>
            </div>

            <!-- Perak -->
            <div style="padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; border: 1.5px solid #cbd5e1;" class="bg-slate-50 dark:bg-slate-800/40 rounded-xl shadow-xs">
                <label class="block text-sm font-black mb-2 text-slate-700 dark:text-slate-200 flex items-center gap-2">
                    <span class="text-xl">🥈</span> Medali Perak (Juara 2)
                </label>
                <select id="medali_perak" disabled class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-white font-bold cursor-not-allowed">
                    <option value="">-- Belum Ditentukan --</option>
                </select>
            </div>

            <!-- Perunggu -->
            <div style="padding: 1.25rem 1.5rem; border: 1.5px solid #fed7aa;" class="bg-orange-50/70 dark:bg-orange-950/20 rounded-xl shadow-xs">
                <label class="block text-sm font-black mb-2 text-amber-700 dark:text-amber-400 flex items-center gap-2">
                    <span class="text-xl">🥉</span> Medali Perunggu (Juara 3)
                </label>
                <select id="medali_perunggu1" disabled class="w-full border border-orange-300 rounded-lg px-3.5 py-2.5 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-white font-bold cursor-not-allowed">
                    <option value="">-- Belum Ditentukan --</option>
                </select>
            </div>
        </div>

        <!-- Footer Modal Medali (Padding Lega) -->
        <div style="padding: 1.25rem 2rem; flex-shrink: 0;" class="flex justify-end border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/70">
            <button type="button" onclick="closeMedaliModal()" style="background-color: #e2e8f0 !important; color: #334155 !important;" class="px-6 py-2.5 text-xs font-bold text-slate-700 uppercase rounded-lg transition cursor-pointer border-0 hover:bg-slate-300">
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

    window.openScoresheetModal = function(id) {
        const targetId = id || DEFAULT_PERTANDINGAN_ID;
        const modal = document.getElementById('scoresheetModal');
        if (modal) {
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.style.display = 'flex';
        }

        document.getElementById('scoresheet_pertandingan_id').value = targetId;
        const tbody = document.getElementById('scoresheetBody');
        tbody.innerHTML = '<tr><td colspan="12" class="text-center py-10 text-slate-500 font-semibold" style="padding: 2.5rem 1rem;">Memuat data kontingen &amp; peserta...</td></tr>';

        fetch(`/pertandingan/${targetId}/peserta`)
            .then(res => res.json())
            .then(res => {
                const data = res.peserta || [];
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="12" class="text-center py-10 text-slate-400 font-semibold" style="padding: 2.5rem 1rem;">Belum ada peserta di nomor pertandingan ini.</td></tr>';
                    return;
                }
                tbody.innerHTML = '';
                data.forEach((item, index) => {
                    const namaPeserta = item.nama || '-';
                    const kontingenName = item.kontingen || '-';
                    const waktu = item.waktu ?? '';
                    const n1 = item.nilai_1 ?? '';
                    const n2 = item.nilai_2 ?? '';
                    const n3 = item.nilai_3 ?? '';
                    const n4 = item.nilai_4 ?? '';
                    const n5 = item.nilai_5 ?? '';
                    const genten = item.minus ?? 0;
                    const ppIdsString = item.pp_ids ? item.pp_ids.join(',') : item.id;

                    const tr = document.createElement('tr');
                    tr.className = 'scoresheet-row hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors';
                    tr.dataset.rowId = item.id;
                    tr.innerHTML = `
                        <td style="padding: 12px 10px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; text-align: center;" class="font-bold text-xs text-slate-500">${index + 1}</td>
                        <td style="padding: 12px 14px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9;" class="text-xs font-black text-blue-700 dark:text-blue-300">
                            <div class="text-sm font-extrabold text-blue-800 dark:text-blue-300">${kontingenName}</div>
                            <input type="hidden" name="scores[${item.id}][pp_ids]" value="${ppIdsString}">
                            <input type="hidden" name="scores[${item.id}][kontingen]" value="${kontingenName}">
                            <input type="hidden" name="scores[${item.id}][nama]" value="${namaPeserta}">
                        </td>
                        <td style="padding: 12px 14px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9;" class="font-medium text-xs text-slate-700 dark:text-slate-200">
                            ${namaPeserta}
                        </td>
                        <td style="padding: 12px 10px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; text-align: center;">
                            <input type="text" placeholder="00:00" name="scores[${item.id}][waktu]" value="${waktu}" style="width: 82px; padding: 7px 8px; border: 1.5px solid #cbd5e1; border-radius: 8px; text-align: center; font-size: 0.85rem;" class="font-semibold dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        </td>
                        <td style="padding: 12px 6px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; text-align: center;">
                            <input type="number" step="0.01" placeholder="Wasit 1" title="Wasit 1" name="scores[${item.id}][nilai_1]" value="${n1}" oninput="recalcScoresheet()" style="width: 78px; padding: 7px 6px; border: 1.5px solid #cbd5e1; border-radius: 8px; text-align: center; font-size: 0.85rem;" class="score-input n1 font-bold dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        </td>
                        <td style="padding: 12px 6px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; text-align: center;">
                            <input type="number" step="0.01" placeholder="Wasit 2" title="Wasit 2" name="scores[${item.id}][nilai_2]" value="${n2}" oninput="recalcScoresheet()" style="width: 78px; padding: 7px 6px; border: 1.5px solid #cbd5e1; border-radius: 8px; text-align: center; font-size: 0.85rem;" class="score-input n2 font-bold dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        </td>
                        <td style="padding: 12px 6px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; text-align: center;">
                            <input type="number" step="0.01" placeholder="Wasit 3" title="Wasit 3" name="scores[${item.id}][nilai_3]" value="${n3}" oninput="recalcScoresheet()" style="width: 78px; padding: 7px 6px; border: 1.5px solid #cbd5e1; border-radius: 8px; text-align: center; font-size: 0.85rem;" class="score-input n3 font-bold dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        </td>
                        <td style="padding: 12px 6px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; text-align: center;">
                            <input type="number" step="0.01" placeholder="Wasit 4" title="Wasit 4" name="scores[${item.id}][nilai_4]" value="${n4}" oninput="recalcScoresheet()" style="width: 78px; padding: 7px 6px; border: 1.5px solid #cbd5e1; border-radius: 8px; text-align: center; font-size: 0.85rem;" class="score-input n4 font-bold dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        </td>
                        <td style="padding: 12px 6px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; text-align: center;">
                            <input type="number" step="0.01" placeholder="Wasit 5" title="Wasit 5" name="scores[${item.id}][nilai_5]" value="${n5}" oninput="recalcScoresheet()" style="width: 78px; padding: 7px 6px; border: 1.5px solid #cbd5e1; border-radius: 8px; text-align: center; font-size: 0.85rem;" class="score-input n5 font-bold dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        </td>
                        <td style="padding: 12px 10px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; text-align: center; background-color: #eff6ff;" class="font-black text-blue-700 dark:text-blue-300 dark:bg-slate-700/60 text-sm nilai-sum">0</td>
                        <td style="padding: 12px 6px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; text-align: center;">
                            <input type="number" step="0.01" placeholder="0" title="Genten (Minus)" name="scores[${item.id}][minus]" value="${genten}" oninput="recalcScoresheet()" style="width: 78px; padding: 7px 6px; border: 1.5px solid #fca5a5; border-radius: 8px; text-align: center; font-size: 0.85rem;" class="score-input genten font-bold text-red-600 dark:bg-slate-800 dark:border-red-500">
                        </td>
                        <td style="padding: 12px 10px; border-bottom: 1px solid #e2e8f0; text-align: center; background-color: #ecfdf5;" class="font-black text-emerald-700 dark:text-emerald-300 dark:bg-slate-700/60 text-sm total-akhir-cell">0</td>
                    `;
                    tbody.appendChild(tr);
                });
                recalcScoresheet();
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="12" class="text-center py-10 text-red-500 font-semibold" style="padding: 2.5rem 1rem;">Gagal memuat data peserta.</td></tr>';
            });
    };

    window.closeScoresheetModal = function() {
        const modal = document.getElementById('scoresheetModal');
        if (modal) {
            modal.style.display = 'none';
        }
    };

    window.recalcScoresheet = function() {
        const rows = document.querySelectorAll('.scoresheet-row');

        rows.forEach(row => {
            const v1 = row.querySelector('.n1').value;
            const v2 = row.querySelector('.n2').value;
            const v3 = row.querySelector('.n3').value;
            const v4 = row.querySelector('.n4').value;
            const v5 = row.querySelector('.n5').value;
            const gentenVal = parseFloat(row.querySelector('.genten').value) || 0;

            const hasAnyInput = [v1, v2, v3, v4, v5].some(v => v !== '');
            if (!hasAnyInput) {
                row.querySelector('.nilai-sum').textContent = '-';
                row.querySelector('.total-akhir-cell').textContent = '-';
                return;
            }

            const n1 = parseFloat(v1) || 0;
            const n2 = parseFloat(v2) || 0;
            const n3 = parseFloat(v3) || 0;
            const n4 = parseFloat(v4) || 0;
            const n5 = parseFloat(v5) || 0;

            // Rumus: Jumlahkan ke-5 nilai wasit lalu dikurangi genten
            const jumlahNilai = n1 + n2 + n3 + n4 + n5;
            const totalNilaiAkhir = jumlahNilai - gentenVal;

            row.querySelector('.nilai-sum').textContent = Number.isInteger(jumlahNilai) ? jumlahNilai : jumlahNilai.toFixed(2);
            row.querySelector('.total-akhir-cell').textContent = Number.isInteger(totalNilaiAkhir) ? totalNilaiAkhir : totalNilaiAkhir.toFixed(2);
        });
    };

    window.submitScoresheet = function(event) {
        event.preventDefault();
        const id = document.getElementById('scoresheet_pertandingan_id').value || DEFAULT_PERTANDINGAN_ID;
        const form = document.getElementById('scoresheetForm');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('btnSimpanScoresheet');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1.5"></i> Menyimpan...';

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
            submitBtn.innerHTML = '<i class="fa fa-save mr-1.5"></i> Simpan Nilai';

            if (data.status === 'success') {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Nilai scoresheet kontingen dan peraih medali berhasil disimpan!',
                        timer: 1800,
                        showConfirmButton: false
                    });
                } else {
                    alert('Nilai scoresheet kontingen dan peraih medali berhasil disimpan!');
                }
                closeScoresheetModal();
            } else {
                alert('Gagal menyimpan nilai.');
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa fa-save mr-1.5"></i> Simpan Nilai';
            console.error(err);
            alert('Terjadi kesalahan saat menyimpan nilai.');
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

                // Urutkan kontingen berdasarkan total_nilai tertinggi untuk pengisian otomatis
                const scoredPeserta = data
                    .filter(item => item.total_nilai !== null && item.total_nilai !== undefined)
                    .sort((a, b) => parseFloat(b.total_nilai) - parseFloat(a.total_nilai));

                const autoCandidates = [];
                scoredPeserta.forEach(item => {
                    const val = item.kontingen;
                    if (val && !autoCandidates.includes(val)) {
                        autoCandidates.push(val);
                    }
                });

                // Isi otomatis berdasarkan nilai akhir tertinggi
                document.getElementById('medali_emas').value = autoCandidates[0] || pert.emas || '';
                document.getElementById('medali_perak').value = autoCandidates[1] || pert.perak || '';
                document.getElementById('medali_perunggu1').value = autoCandidates[2] || pert.perunggu1 || pert.perunggu || '';
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
