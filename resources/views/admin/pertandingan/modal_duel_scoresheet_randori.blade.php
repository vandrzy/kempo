<!-- ======================================================== -->
<!-- MODAL SCORESHEET DUEL RANDORI (FASE 3 KEMPO)            -->
<!-- ======================================================== -->
<div id="duelScoresheetModal" style="display: none;" class="fixed inset-0 z-[999999] items-center justify-center p-3 sm:p-5 md:p-6 bg-black/60 backdrop-blur-xs overflow-y-auto transition-all duration-200">
    <div class="relative w-full max-w-[850px] my-auto max-h-[92vh] bg-white rounded-3xl border border-[#ebebeb] shadow-2xl flex flex-col overflow-hidden font-body animate-in fade-in zoom-in-95 duration-150">
        
        <!-- Header Modal -->
        <div class="px-6 py-5 sm:px-8 sm:py-6 shrink-0 flex items-start justify-between border-b border-[#ebebeb] bg-gradient-to-r from-red-50/50 via-white to-blue-50/50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-500 to-blue-600 text-white flex items-center justify-center shrink-0 text-xl shadow-md">
                    <i class="fas fa-fist-raised"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h3 class="font-heading font-bold text-xl md:text-2xl text-foreground mb-0 tracking-tight">
                            Scoresheet Duel Randori
                        </h3>
                        <span id="modalDuelBabakBadge" class="px-3 py-0.5 text-xs font-bold text-primary bg-primary/10 rounded-full border border-primary/20">
                            Babak 1
                        </span>
                        <span id="modalDuelPartaiBadge" class="px-3 py-0.5 text-xs font-bold text-neutral-700 bg-neutral-100 rounded-full border border-neutral-200 tabular-nums">
                            Partai #1
                        </span>
                    </div>
                    <p class="text-xs md:text-sm text-paragraph mb-0 mt-1 font-body">
                        {{ $pertandingan->kegiatan->nama ?? '' }} &bull; {{ $pertandingan->pertandingan->nama ?? '' }} (Kelas: {{ $pertandingan->pertandingan->kelas ?? '-' }})
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeDuelScoresheetModal()" class="w-9 h-9 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-500 hover:text-foreground flex items-center justify-center transition-colors border border-input/60 cursor-pointer">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Body Form Modal -->
        <form id="formDuelScoresheet" class="p-6 sm:p-8 overflow-y-auto space-y-6">
            @csrf
            <input type="hidden" name="peserta_terpilih_id" id="modalDuelPesertaTerpilihId">

            <!-- Grid Pertandingan Duel: Sudut Merah vs Sudut Putih -->
            <div class="grid grid-cols-1 md:grid-cols-11 gap-4 items-center">
                
                <!-- SUDUT MERAH (AKA) -->
                <div class="md:col-span-5 rounded-2xl border-2 border-red-200 bg-red-50/30 p-5 flex flex-col justify-between shadow-xs">
                    <div class="flex items-center justify-between border-b border-red-100 pb-3 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-red-600 text-white shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                            Sudut Merah
                        </span>
                        <span class="text-xs font-bold text-red-600 uppercase">AKA</span>
                    </div>
                    <div class="mb-4">
                        <h4 id="modalDuelKiriNama" class="font-heading font-bold text-lg text-foreground mb-1">
                            -
                        </h4>
                        <p id="modalDuelKiriKontingen" class="text-xs font-semibold text-paragraph mb-0 flex items-center gap-1.5">
                            <i class="fas fa-shield-alt text-red-500"></i> Kontingen: -
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-3.5 border border-red-100 shadow-2xs">
                        <label class="block text-xs font-bold text-neutral-600 uppercase tracking-wider mb-2">
                            Total Skor Sudut Merah
                        </label>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="adjustDuelScore('skor_kiri', -1)" class="w-10 h-10 rounded-lg bg-neutral-100 hover:bg-red-100 text-neutral-700 hover:text-red-700 font-black text-lg transition-colors flex items-center justify-center cursor-pointer">
                                -
                            </button>
                            <input type="number" step="0.5" min="0" max="99" name="skor_kiri" id="modalDuelSkorKiri" class="flex-1 text-center font-heading font-black text-2xl text-red-600 bg-neutral-50 border border-input rounded-lg h-11 focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-200 tabular-nums outline-hidden" value="0">
                            <button type="button" onclick="adjustDuelScore('skor_kiri', 1)" class="w-10 h-10 rounded-lg bg-red-600 hover:bg-red-700 text-white font-black text-lg transition-colors flex items-center justify-center cursor-pointer shadow-xs">
                                +
                            </button>
                        </div>
                    </div>
                </div>

                <!-- VS BADGE -->
                <div class="md:col-span-1 flex flex-col items-center justify-center my-2 md:my-0">
                    <div class="w-11 h-11 rounded-full bg-neutral-900 text-white font-black text-sm flex items-center justify-center shadow-md border-2 border-white">
                        VS
                    </div>
                </div>

                <!-- SUDUT PUTIH (SHIRO) -->
                <div class="md:col-span-5 rounded-2xl border-2 border-blue-200 bg-blue-50/30 p-5 flex flex-col justify-between shadow-xs">
                    <div class="flex items-center justify-between border-b border-blue-100 pb-3 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-blue-600 text-white shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                            Sudut Putih
                        </span>
                        <span class="text-xs font-bold text-blue-600 uppercase">SHIRO</span>
                    </div>
                    <div class="mb-4">
                        <h4 id="modalDuelKananNama" class="font-heading font-bold text-lg text-foreground mb-1">
                            -
                        </h4>
                        <p id="modalDuelKananKontingen" class="text-xs font-semibold text-paragraph mb-0 flex items-center gap-1.5">
                            <i class="fas fa-shield-alt text-blue-500"></i> Kontingen: -
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-3.5 border border-blue-100 shadow-2xs">
                        <label class="block text-xs font-bold text-neutral-600 uppercase tracking-wider mb-2">
                            Total Skor Sudut Putih
                        </label>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="adjustDuelScore('skor_kanan', -1)" class="w-10 h-10 rounded-lg bg-neutral-100 hover:bg-blue-100 text-neutral-700 hover:text-blue-700 font-black text-lg transition-colors flex items-center justify-center cursor-pointer">
                                -
                            </button>
                            <input type="number" step="0.5" min="0" max="99" name="skor_kanan" id="modalDuelSkorKanan" class="flex-1 text-center font-heading font-black text-2xl text-blue-600 bg-neutral-50 border border-input rounded-lg h-11 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 tabular-nums outline-hidden" value="0">
                            <button type="button" onclick="adjustDuelScore('skor_kanan', 1)" class="w-10 h-10 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-black text-lg transition-colors flex items-center justify-center cursor-pointer shadow-xs">
                                +
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Keputusan Pemenang & Keterangan Kemenangan -->
            <div class="rounded-2xl bg-neutral-50 border border-input p-5 space-y-4">
                <h5 class="text-xs font-bold uppercase tracking-wider text-neutral-600 mb-0 flex items-center gap-2">
                    <i class="fas fa-gavel text-primary"></i> Keputusan Wasit &amp; Penentuan Pemenang
                </h5>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Pilihan Pemenang -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1.5">
                            Pemenang Pertandingan:
                        </label>
                        <select name="pemenang_id" id="modalDuelPemenangSelect" class="w-full h-10 rounded-lg border border-input bg-white px-3 text-xs font-medium text-foreground focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden">
                            <option value="">-- Otomatis (Skor Tertinggi) --</option>
                            <option id="optPemenangKiri" value="">Sudut Merah</option>
                            <option id="optPemenangKanan" value="">Sudut Putih</option>
                        </select>
                        <p class="text-[11px] text-paragraph mt-1 mb-0">
                            *Jika skor seri, wasit wajib memilih pemenang (Hantei).
                        </p>
                    </div>

                    <!-- Jenis Kemenangan -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1.5">
                            Keterangan / Jenis Kemenangan:
                        </label>
                        <select name="keterangan_menang" id="modalDuelKeteranganSelect" class="w-full h-10 rounded-lg border border-input bg-white px-3 text-xs font-medium text-foreground focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden">
                            <option value="POIN">POIN - Keunggulan Skor / Angka</option>
                            <option value="IPPON">IPPON - Kemenangan Mutlak Ippon</option>
                            <option value="WAZA-ARI">WAZA-ARI - Keunggulan Waza-ari</option>
                            <option value="HANTEI">HANTEI - Keputusan Dewan Wasit (Seri)</option>
                            <option value="HANSOKU">HANSOKU - Diskualifikasi Pelanggaran Lawan</option>
                            <option value="KIKEN">KIKEN - Lawan Mengundurkan Diri / Cedera</option>
                        </select>
                        <p class="text-[11px] text-paragraph mt-1 mb-0">
                            Sesuai regulasi pertandingan Shorinji Kempo.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pesan Notifikasi Form -->
            <div id="duelModalAlert" style="display: none;" class="p-3.5 rounded-xl text-xs font-medium border"></div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-input">
                <button type="button" onclick="closeDuelScoresheetModal()" class="px-5 py-2.5 rounded-xl border border-input hover:bg-neutral-100 text-xs font-bold text-neutral-700 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="btnSubmitDuelScoresheet" class="btn-kempo-1 inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-bold tracking-wide shadow-md hover:shadow-lg transition-all cursor-pointer">
                    <i class="fas fa-check-circle"></i> Simpan Skor &amp; Majukan Pemenang
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openDuelScoresheetModal(data) {
        document.getElementById('modalDuelPesertaTerpilihId').value = data.id || '';
        document.getElementById('modalDuelBabakBadge').innerText = data.nama_babak || ('Babak ' + (data.babak || 1));
        document.getElementById('modalDuelPartaiBadge').innerText = 'Partai #' + (data.nomor_partai || 1);

        // Kiri
        document.getElementById('modalDuelKiriNama').innerText = data.kiri_nama || '-';
        document.getElementById('modalDuelKiriKontingen').innerHTML = '<i class="fas fa-shield-alt text-red-500"></i> Kontingen: ' + (data.kiri_kontingen || '-');
        document.getElementById('modalDuelSkorKiri').value = (data.skor_kiri !== null && data.skor_kiri !== undefined && data.skor_kiri !== '') ? data.skor_kiri : 0;

        // Kanan
        document.getElementById('modalDuelKananNama').innerText = data.kanan_nama || '-';
        document.getElementById('modalDuelKananKontingen').innerHTML = '<i class="fas fa-shield-alt text-blue-500"></i> Kontingen: ' + (data.kanan_kontingen || '-');
        document.getElementById('modalDuelSkorKanan').value = (data.skor_kanan !== null && data.skor_kanan !== undefined && data.skor_kanan !== '') ? data.skor_kanan : 0;

        // Dropdown Pemenang
        const optKiri = document.getElementById('optPemenangKiri');
        const optKanan = document.getElementById('optPemenangKanan');
        optKiri.value = data.kiri_id || '';
        optKiri.innerText = 'Sudut Merah (' + (data.kiri_nama || 'Merah') + ')';
        optKanan.value = data.kanan_id || '';
        optKanan.innerText = 'Sudut Putih (' + (data.kanan_nama || 'Putih') + ')';

        const pemenangSelect = document.getElementById('modalDuelPemenangSelect');
        // Selalu otomatis (jangan gunakan pemenang sebelumnya saat edit skor)
        pemenangSelect.value = '';

        const ketSelect = document.getElementById('modalDuelKeteranganSelect');
        if (data.keterangan_menang && data.keterangan_menang !== 'BYE') {
            ketSelect.value = data.keterangan_menang;
        } else {
            ketSelect.value = 'POIN';
        }

        const alertBox = document.getElementById('duelModalAlert');
        alertBox.style.display = 'none';

        const modal = document.getElementById('duelScoresheetModal');
        modal.style.display = 'flex';
        document.body.classList.add('overflow-hidden');
    }

    function closeDuelScoresheetModal() {
        const modal = document.getElementById('duelScoresheetModal');
        modal.style.display = 'none';
        document.body.classList.remove('overflow-hidden');
    }

    function adjustDuelScore(fieldId, delta) {
        const input = document.getElementById('modalDuel' + (fieldId === 'skor_kiri' ? 'SkorKiri' : 'SkorKanan'));
        if (input) {
            let val = parseFloat(input.value) || 0;
            val = Math.max(0, val + delta);
            input.value = val;

            // Selalu kembalikan pilihan pemenang ke Otomatis ketika ada perubahan skor
            const pemenangSelect = document.getElementById('modalDuelPemenangSelect');
            if (pemenangSelect) {
                pemenangSelect.value = '';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Listener input skor agar dropdown pemenang selalu otomatis saat angka diketik
        const skorKiriInput = document.getElementById('modalDuelSkorKiri');
        const skorKananInput = document.getElementById('modalDuelSkorKanan');
        const pemenangSelect = document.getElementById('modalDuelPemenangSelect');

        function resetPemenangToOtomatis() {
            if (pemenangSelect) pemenangSelect.value = '';
        }

        if (skorKiriInput) {
            skorKiriInput.addEventListener('input', resetPemenangToOtomatis);
            skorKiriInput.addEventListener('change', resetPemenangToOtomatis);
        }
        if (skorKananInput) {
            skorKananInput.addEventListener('input', resetPemenangToOtomatis);
            skorKananInput.addEventListener('change', resetPemenangToOtomatis);
        }

        const form = document.getElementById('formDuelScoresheet');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitDuelScoresheet');
            const alertBox = document.getElementById('duelModalAlert');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
            alertBox.style.display = 'none';

            const formData = new FormData(form);

            fetch("{{ route('pertandingan.duel-scoresheet', $pertandingan->id) }}", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-2';
                    alertBox.innerHTML = '<i class="fas fa-check-circle text-base"></i> ' + data.message;
                    alertBox.style.display = 'flex';

                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-red-50 text-red-800 border border-red-200 flex items-center gap-2';
                    alertBox.innerHTML = '<i class="fas fa-exclamation-circle text-base"></i> ' + (data.message || 'Gagal menyimpan skor duel.');
                    alertBox.style.display = 'flex';
                }
            })
            .catch(err => {
                console.error(err);
                btn.disabled = false;
                btn.innerHTML = originalText;
                alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-red-50 text-red-800 border border-red-200 flex items-center gap-2';
                alertBox.innerHTML = '<i class="fas fa-exclamation-circle text-base"></i> Terjadi kesalahan koneksi atau server.';
                alertBox.style.display = 'flex';
            });
        });
    });
</script>
