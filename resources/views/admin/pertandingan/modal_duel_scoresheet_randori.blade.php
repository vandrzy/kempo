<!-- ======================================================== -->
<!-- MODAL SCORESHEET DUEL RANDORI (SISTEM PENILAIAN TEKNIK)  -->
<!-- ======================================================== -->
<div id="duelScoresheetModal" style="display: none;" class="fixed inset-0 z-[999999] flex items-center justify-center p-3 sm:p-5 md:p-6 bg-black/60 backdrop-blur-xs overflow-hidden transition-all duration-200">
    <form id="formDuelScoresheet" class="relative w-full max-w-[960px] h-[92vh] max-h-[92vh] bg-white rounded-3xl border border-[#ebebeb] shadow-2xl flex flex-col overflow-hidden font-body animate-in fade-in zoom-in-95 duration-150">
        @csrf
        <input type="hidden" name="peserta_terpilih_id" id="modalDuelPesertaTerpilihId">

        <!-- ================= 1. HEADER (FIXED DI ATAS) ================= -->
        <div class="px-6 py-4 sm:px-8 shrink-0 flex items-center justify-between border-b border-[#ebebeb] bg-gradient-to-r from-red-50/70 via-white to-blue-50/70 z-10">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-red-500 via-amber-500 to-blue-600 text-white flex items-center justify-center shrink-0 text-lg shadow-md">
                    <i class="fas fa-fist-raised"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h3 class="font-heading font-bold text-lg md:text-xl text-foreground mb-0 tracking-tight">
                            Scoresheet Duel Randori
                        </h3>
                        <span id="modalDuelBabakBadge" class="px-2.5 py-0.5 text-xs font-bold text-primary bg-primary/10 rounded-full border border-primary/20">
                            Babak 1
                        </span>
                        <span id="modalDuelPartaiBadge" class="px-2.5 py-0.5 text-xs font-bold text-neutral-700 bg-neutral-100 rounded-full border border-neutral-200 tabular-nums">
                            Partai #1
                        </span>
                    </div>
                    <p class="text-xs text-paragraph mb-0 mt-0.5 font-body">
                        {{ $pertandingan->kegiatan->nama ?? '' }} &bull; {{ $pertandingan->pertandingan->nama ?? '' }} (Kelas: {{ $pertandingan->pertandingan->kelas ?? '-' }})
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeDuelScoresheetModal()" class="w-9 h-9 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-500 hover:text-foreground flex items-center justify-center transition-colors border border-input/60 cursor-pointer">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- ================= 2. BODY SCROLLABLE (KONTEN MODAL) ================= -->
        <div class="flex-1 overflow-y-auto p-5 sm:p-7 space-y-6 overscroll-contain">
            
            <!-- Grid Pertandingan Duel: Sudut Merah vs Sudut Putih -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                
                <!-- ================= SUDUT MERAH (AKA) ================= -->
                <div class="rounded-2xl border-2 border-red-200 bg-red-50/20 p-5 flex flex-col shadow-xs">
                    
                    <!-- Header Sudut Merah -->
                    <div class="flex items-center justify-between border-b border-red-100 pb-3 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-red-600 text-white shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                            Sudut Merah
                        </span>
                        <span class="text-xs font-bold text-red-600 uppercase tracking-wider">AKA</span>
                    </div>

                    <!-- Atlet Info Merah -->
                    <div class="mb-4">
                        <h4 id="modalDuelKiriNama" class="font-heading font-bold text-base text-foreground mb-1">
                            -
                        </h4>
                        <p id="modalDuelKiriKontingen" class="text-xs font-semibold text-paragraph mb-0 flex items-center gap-1.5">
                            <i class="fas fa-shield-alt text-red-500"></i> Kontingen: -
                        </p>
                    </div>

                    <!-- Total Skor Real-Time Banner Merah -->
                    <div class="flex items-center justify-between p-3.5 bg-gradient-to-r from-red-600 to-red-500 text-white rounded-2xl shadow-sm mb-4">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-red-100 block">Total Skor Sudut Merah</span>
                            <span class="text-[10px] text-red-200 italic">*Hasil akumulasi otomatis</span>
                        </div>
                        <div class="flex items-baseline gap-1.5">
                            <span id="preview_skor_merah" class="font-heading font-black text-3xl tabular-nums tracking-tight">0</span>
                            <span class="text-xs font-bold text-red-100 uppercase">Poin</span>
                        </div>
                    </div>

                    <!-- Daftar Input Teknik Merah -->
                    <div class="space-y-2.5">
                        <div class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider mb-1 flex items-center justify-between">
                            <span>Input Teknik Peserta</span>
                            <span>Frekuensi (Kali)</span>
                        </div>

                        <!-- 1. Mujoken Kachi (x15) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-red-100 hover:border-red-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Mujoken Kachi</span>
                                <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100">+15 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('merah_mujoken_kachi', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-red-100 text-neutral-700 hover:text-red-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="merah_mujoken_kachi" id="merah_mujoken_kachi" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('merah_mujoken_kachi', 1)" class="w-8 h-8 rounded-lg bg-red-600 hover:bg-red-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                        <!-- 2. Ippon (x10) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-red-100 hover:border-red-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Ippon</span>
                                <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100">+10 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('merah_ippon', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-red-100 text-neutral-700 hover:text-red-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="merah_ippon" id="merah_ippon" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('merah_ippon', 1)" class="w-8 h-8 rounded-lg bg-red-600 hover:bg-red-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                        <!-- 3. Waza Ari (x5) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-red-100 hover:border-red-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Waza Ari</span>
                                <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100">+5 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('merah_waza_ari', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-red-100 text-neutral-700 hover:text-red-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="merah_waza_ari" id="merah_waza_ari" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('merah_waza_ari', 1)" class="w-8 h-8 rounded-lg bg-red-600 hover:bg-red-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                        <!-- 4. Hasil Batsu 5 (x5) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-red-100 hover:border-red-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Hasil Batsu 5</span>
                                <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100">+5 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('merah_batsu_5', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-red-100 text-neutral-700 hover:text-red-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="merah_batsu_5" id="merah_batsu_5" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('merah_batsu_5', 1)" class="w-8 h-8 rounded-lg bg-red-600 hover:bg-red-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                        <!-- 5. Hasil Batsu 10 (x10) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-red-100 hover:border-red-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Hasil Batsu 10</span>
                                <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100">+10 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('merah_batsu_10', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-red-100 text-neutral-700 hover:text-red-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="merah_batsu_10" id="merah_batsu_10" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('merah_batsu_10', 1)" class="w-8 h-8 rounded-lg bg-red-600 hover:bg-red-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                        <!-- 6. Yusei Kachi (x5) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-red-100 hover:border-red-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Yusei Kachi</span>
                                <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100">+5 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('merah_yusei_kachi', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-red-100 text-neutral-700 hover:text-red-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="merah_yusei_kachi" id="merah_yusei_kachi" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('merah_yusei_kachi', 1)" class="w-8 h-8 rounded-lg bg-red-600 hover:bg-red-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ================= SUDUT PUTIH (SHIRO) ================= -->
                <div class="rounded-2xl border-2 border-blue-200 bg-blue-50/20 p-5 flex flex-col shadow-xs">
                    
                    <!-- Header Sudut Putih -->
                    <div class="flex items-center justify-between border-b border-blue-100 pb-3 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-blue-600 text-white shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                            Sudut Putih
                        </span>
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">SHIRO</span>
                    </div>

                    <!-- Atlet Info Putih -->
                    <div class="mb-4">
                        <h4 id="modalDuelKananNama" class="font-heading font-bold text-base text-foreground mb-1">
                            -
                        </h4>
                        <p id="modalDuelKananKontingen" class="text-xs font-semibold text-paragraph mb-0 flex items-center gap-1.5">
                            <i class="fas fa-shield-alt text-blue-500"></i> Kontingen: -
                        </p>
                    </div>

                    <!-- Total Skor Real-Time Banner Putih -->
                    <div class="flex items-center justify-between p-3.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-2xl shadow-sm mb-4">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-100 block">Total Skor Sudut Putih</span>
                            <span class="text-[10px] text-blue-200 italic">*Hasil akumulasi otomatis</span>
                        </div>
                        <div class="flex items-baseline gap-1.5">
                            <span id="preview_skor_putih" class="font-heading font-black text-3xl tabular-nums tracking-tight">0</span>
                            <span class="text-xs font-bold text-blue-100 uppercase">Poin</span>
                        </div>
                    </div>

                    <!-- Daftar Input Teknik Putih -->
                    <div class="space-y-2.5">
                        <div class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider mb-1 flex items-center justify-between">
                            <span>Input Teknik Peserta</span>
                            <span>Frekuensi (Kali)</span>
                        </div>

                        <!-- 1. Mujoken Kachi (x15) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-blue-100 hover:border-blue-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Mujoken Kachi</span>
                                <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">+15 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('putih_mujoken_kachi', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-blue-100 text-neutral-700 hover:text-blue-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="putih_mujoken_kachi" id="putih_mujoken_kachi" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('putih_mujoken_kachi', 1)" class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                        <!-- 2. Ippon (x10) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-blue-100 hover:border-blue-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Ippon</span>
                                <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">+10 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('putih_ippon', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-blue-100 text-neutral-700 hover:text-blue-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="putih_ippon" id="putih_ippon" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('putih_ippon', 1)" class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                        <!-- 3. Waza Ari (x5) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-blue-100 hover:border-blue-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Waza Ari</span>
                                <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">+5 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('putih_waza_ari', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-blue-100 text-neutral-700 hover:text-blue-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="putih_waza_ari" id="putih_waza_ari" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('putih_waza_ari', 1)" class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                        <!-- 4. Hasil Batsu 5 (x5) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-blue-100 hover:border-blue-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Hasil Batsu 5</span>
                                <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">+5 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('putih_batsu_5', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-blue-100 text-neutral-700 hover:text-blue-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="putih_batsu_5" id="putih_batsu_5" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('putih_batsu_5', 1)" class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                        <!-- 5. Hasil Batsu 10 (x10) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-blue-100 hover:border-blue-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Hasil Batsu 10</span>
                                <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">+10 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('putih_batsu_10', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-blue-100 text-neutral-700 hover:text-blue-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="putih_batsu_10" id="putih_batsu_10" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('putih_batsu_10', 1)" class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                        <!-- 6. Yusei Kachi (x5) -->
                        <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-blue-100 hover:border-blue-300 transition-colors shadow-2xs">
                            <div>
                                <span class="text-xs font-bold text-foreground block">Yusei Kachi</span>
                                <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">+5 Poin</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="adjustTeknik('putih_yusei_kachi', -1)" class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-blue-100 text-neutral-700 hover:text-blue-700 font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none">-</button>
                                <input type="number" min="0" max="99" name="putih_yusei_kachi" id="putih_yusei_kachi" value="0" class="input-teknik-randori w-12 h-8 text-center font-heading font-bold text-sm text-foreground bg-neutral-50 border border-input rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-200 tabular-nums outline-hidden">
                                <button type="button" onclick="adjustTeknik('putih_yusei_kachi', 1)" class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-black text-sm flex items-center justify-center transition-colors cursor-pointer select-none shadow-2xs">+</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Keputusan Pemenang Pertandingan -->
            <div class="rounded-2xl bg-neutral-50 border border-input p-5 space-y-3">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h5 class="text-xs font-bold uppercase tracking-wider text-neutral-700 mb-0 flex items-center gap-2">
                        <i class="fas fa-gavel text-primary"></i> Penentuan Pemenang Pertandingan
                    </h5>
                    <span id="badgeStatusKemenangan" class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-neutral-100 text-neutral-600 border border-neutral-200">
                        Otomatis (Skor Tertinggi)
                    </span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1.5">
                        Pemenang Pertandingan:
                    </label>
                    <select name="pemenang_id" id="modalDuelPemenangSelect" class="w-full h-10 rounded-lg border border-input bg-white px-3 text-xs font-semibold text-foreground focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden">
                        <option value="">-- Otomatis (Skor Tertinggi) --</option>
                        <option id="optPemenangKiri" value="">Sudut Merah</option>
                        <option id="optPemenangKanan" value="">Sudut Putih</option>
                    </select>
                    <p class="text-[11px] text-paragraph mt-1.5 mb-0">
                        *Jika skor seri/imbang, pilihan diarahkan ke <strong>Otomatis</strong>. Wasit wajib memilih pemenang (HANTEI) sebelum memajukan pemenang.
                    </p>
                </div>
            </div>

            <!-- Pesan Notifikasi Alert -->
            <div id="duelModalAlert" style="display: none;" class="p-3.5 rounded-xl text-xs font-medium border"></div>

        </div>

        <!-- ================= 3. FOOTER (SELALU FIXED DI BAWAH) ================= -->
        <div class="px-6 py-4 sm:px-8 shrink-0 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-[#ebebeb] bg-white z-10 shadow-[0_-4px_16px_rgba(0,0,0,0.04)]">
            <!-- Indikator Sinkronisasi Otomatis Real-Time -->
            <div id="autoSaveIndicator" class="flex items-center gap-2 text-xs font-semibold text-neutral-500">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span class="text-neutral-600">Data tersinkron otomatis ke database</span>
            </div>

            <!-- Tombol Aksi (Fixed) -->
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <button type="button" onclick="closeDuelScoresheetModal()" class="px-5 py-2.5 rounded-xl border border-input hover:bg-neutral-100 text-xs font-bold text-neutral-700 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="btnSubmitDuelScoresheet" class="btn-kempo-1 inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-bold tracking-wide shadow-md hover:shadow-lg transition-all cursor-pointer">
                    <i class="fas fa-check-circle"></i> Simpan Skor &amp; Majukan Pemenang
                </button>
            </div>
        </div>

    </form>
</div>

<script>
    // Bobot Nilai Poin Teknik Randori Shorinji Kempo
    const RANDORI_WEIGHTS = {
        mujoken_kachi: 15,
        ippon: 10,
        waza_ari: 5,
        batsu_5: 5,
        batsu_10: 10,
        yusei_kachi: 5
    };

    let autoSaveTimer = null;
    let isAutoSaving = false;
    let hasQueuedSave = false;

    /**
     * Hitung total skor randori secara real-time dan arahkan pemenang ke otomatis jika seri
     */
    function hitungSkorRandori() {
        let skorMerah = 0;
        let skorPutih = 0;

        for (const [teknik, poin] of Object.entries(RANDORI_WEIGHTS)) {
            const inputMerah = document.querySelector(`[name="merah_${teknik}"]`);
            const inputPutih = document.querySelector(`[name="putih_${teknik}"]`);

            const jmlMerah = Math.max(0, parseInt(inputMerah ? inputMerah.value : 0) || 0);
            const jmlPutih = Math.max(0, parseInt(inputPutih ? inputPutih.value : 0) || 0);

            skorMerah += jmlMerah * poin;
            skorPutih += jmlPutih * poin;
        }

        // Tampilkan preview total skor
        const displayMerah = document.getElementById('preview_skor_merah');
        const displayPutih = document.getElementById('preview_skor_putih');
        if (displayMerah) displayMerah.innerText = skorMerah;
        if (displayPutih) displayPutih.innerText = skorPutih;

        // Otomatis tentukan pemenang berdasarkan skor
        const selectPemenang = document.getElementById('modalDuelPemenangSelect');
        const statusBadge = document.getElementById('badgeStatusKemenangan');
        const optKiri = document.getElementById('optPemenangKiri');
        const optKanan = document.getElementById('optPemenangKanan');

        if (selectPemenang && optKiri && optKanan) {
            if (skorMerah > skorPutih) {
                selectPemenang.value = optKiri.value;
                if (statusBadge) {
                    statusBadge.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-red-50 text-red-700 border border-red-200';
                    statusBadge.innerHTML = '<i class="fas fa-trophy mr-1"></i> Merah Unggul (' + skorMerah + ' vs ' + skorPutih + ')';
                }
            } else if (skorPutih > skorMerah) {
                selectPemenang.value = optKanan.value;
                if (statusBadge) {
                    statusBadge.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200';
                    statusBadge.innerHTML = '<i class="fas fa-trophy mr-1"></i> Putih Unggul (' + skorPutih + ' vs ' + skorMerah + ')';
                }
            } else {
                // Skor Imbang / Seri: Diarahkan ke otomatis (sesuai permintaan user)
                selectPemenang.value = '';
                if (statusBadge) {
                    statusBadge.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-neutral-100 text-neutral-600 border border-neutral-200';
                    statusBadge.innerHTML = '<i class="fas fa-balance-scale mr-1"></i> Skor Seri (' + skorMerah + ' - ' + skorPutih + ') &bull; Otomatis';
                }
            }
        }
    }

    /**
     * Tambah / Kurang counter teknik via tombol stepper & langsung picu auto-save
     */
    function adjustTeknik(fieldName, delta) {
        const input = document.querySelector(`[name="${fieldName}"]`);
        if (input) {
            let val = parseInt(input.value) || 0;
            val = Math.max(0, val + delta);
            input.value = val;
            hitungSkorRandori();
            triggerAutoSaveToDatabase();
        }
    }

    /**
     * Kirim data langsung ke database saat frekuensi teknik diubah
     */
    function triggerAutoSaveToDatabase() {
        const indicator = document.getElementById('autoSaveIndicator');
        if (indicator) {
            indicator.innerHTML = '<i class="fas fa-spinner fa-spin text-amber-500"></i> <span class="text-amber-700 font-semibold">Menyimpan otomatis ke database...</span>';
        }

        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            execAutoSaveRequest();
        }, 300);
    }

    function execAutoSaveRequest() {
        const form = document.getElementById('formDuelScoresheet');
        const matchId = document.getElementById('modalDuelPesertaTerpilihId')?.value;
        if (!form || !matchId) return;

        if (isAutoSaving) {
            hasQueuedSave = true;
            return;
        }

        isAutoSaving = true;
        const formData = new FormData(form);
        formData.append('is_autosave', '1');

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
            isAutoSaving = false;
            const indicator = document.getElementById('autoSaveIndicator');
            if (data.status === 'success') {
                if (indicator) {
                    const now = new Date();
                    const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    indicator.innerHTML = '<i class="fas fa-check-circle text-emerald-500"></i> <span class="text-emerald-700 font-semibold">Tersimpan otomatis di database (' + timeStr + ')</span>';
                }
            } else {
                if (indicator) {
                    indicator.innerHTML = '<i class="fas fa-exclamation-circle text-red-500"></i> <span class="text-red-600 font-semibold">' + (data.message || 'Gagal sinkron') + '</span>';
                }
            }

            if (hasQueuedSave) {
                hasQueuedSave = false;
                triggerAutoSaveToDatabase();
            }
        })
        .catch(err => {
            isAutoSaving = false;
            console.error('Auto-save error:', err);
            const indicator = document.getElementById('autoSaveIndicator');
            if (indicator) {
                indicator.innerHTML = '<i class="fas fa-wifi text-amber-500"></i> <span class="text-amber-700 font-semibold">Menunggu koneksi server...</span>';
            }
        });
    }

    /**
     * Buka modal scoresheet duel randori dan muat data partai
     */
    function openDuelScoresheetModal(data) {
        document.getElementById('modalDuelPesertaTerpilihId').value = data.id || '';
        document.getElementById('modalDuelBabakBadge').innerText = data.nama_babak || ('Babak ' + (data.babak || 1));
        document.getElementById('modalDuelPartaiBadge').innerText = 'Partai #' + (data.nomor_partai || 1);

        // Kiri (Sudut Merah)
        document.getElementById('modalDuelKiriNama').innerText = data.kiri_nama || '-';
        document.getElementById('modalDuelKiriKontingen').innerHTML = '<i class="fas fa-shield-alt text-red-500"></i> Kontingen: ' + (data.kiri_kontingen || '-');

        // Kanan (Sudut Putih)
        document.getElementById('modalDuelKananNama').innerText = data.kanan_nama || '-';
        document.getElementById('modalDuelKananKontingen').innerHTML = '<i class="fas fa-shield-alt text-blue-500"></i> Kontingen: ' + (data.kanan_kontingen || '-');

        // Dropdown Pemenang Options
        const optKiri = document.getElementById('optPemenangKiri');
        const optKanan = document.getElementById('optPemenangKanan');
        optKiri.value = data.kiri_id || '';
        optKiri.innerText = 'Sudut Merah (' + (data.kiri_nama || 'Merah') + ')';
        optKanan.value = data.kanan_id || '';
        optKanan.innerText = 'Sudut Putih (' + (data.kanan_nama || 'Putih') + ')';

        // Load nilai teknik tersimpan
        for (const teknik of Object.keys(RANDORI_WEIGHTS)) {
            const inputMerah = document.querySelector(`[name="merah_${teknik}"]`);
            const inputPutih = document.querySelector(`[name="putih_${teknik}"]`);

            if (inputMerah) {
                inputMerah.value = (data[`merah_${teknik}`] !== undefined && data[`merah_${teknik}`] !== null) ? data[`merah_${teknik}`] : 0;
            }
            if (inputPutih) {
                inputPutih.value = (data[`putih_${teknik}`] !== undefined && data[`putih_${teknik}`] !== null) ? data[`putih_${teknik}`] : 0;
            }
        }

        // Kalkulasi skor awal dan render preview
        hitungSkorRandori();

        // Jika ada pemenang sebelumnya yang tersimpan, muat jika bukan seri atau jika sudah disahkan
        const pemenangSelect = document.getElementById('modalDuelPemenangSelect');
        if (pemenangSelect && data.pemenang_id) {
            pemenangSelect.value = data.pemenang_id;
        }

        const alertBox = document.getElementById('duelModalAlert');
        alertBox.style.display = 'none';

        const indicator = document.getElementById('autoSaveIndicator');
        if (indicator) {
            indicator.innerHTML = '<i class="fas fa-check-circle text-emerald-500"></i> <span class="text-neutral-600">Data tersinkron otomatis ke database</span>';
        }

        const modal = document.getElementById('duelScoresheetModal');
        modal.style.display = 'flex';
        document.body.classList.add('overflow-hidden');
    }

    function closeDuelScoresheetModal() {
        const modal = document.getElementById('duelScoresheetModal');
        modal.style.display = 'none';
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Pasang event listener ke semua input teknik untuk kalkulasi & auto-save real-time
        document.querySelectorAll('.input-teknik-randori').forEach(function(input) {
            input.addEventListener('input', function() {
                hitungSkorRandori();
                triggerAutoSaveToDatabase();
            });
            input.addEventListener('change', function() {
                hitungSkorRandori();
                triggerAutoSaveToDatabase();
            });
        });

        // Auto-save jika wasit memilih pemenang di dropdown (misal pilihan Hantei saat seri)
        const pemenangSelect = document.getElementById('modalDuelPemenangSelect');
        if (pemenangSelect) {
            pemenangSelect.addEventListener('change', function() {
                triggerAutoSaveToDatabase();
            });
        }

        // Form Submission (Simpan & Majukan Pemenang)
        const form = document.getElementById('formDuelScoresheet');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            clearTimeout(autoSaveTimer);

            const btn = document.getElementById('btnSubmitDuelScoresheet');
            const alertBox = document.getElementById('duelModalAlert');
            const selectPemenang = document.getElementById('modalDuelPemenangSelect');
            const originalText = btn.innerHTML;

            // Validasi jika skor seri dan pemenang belum dipilih wasit
            const displayMerah = document.getElementById('preview_skor_merah');
            const displayPutih = document.getElementById('preview_skor_putih');
            const skorMerah = parseInt(displayMerah?.innerText) || 0;
            const skorPutih = parseInt(displayPutih?.innerText) || 0;

            if (skorMerah === skorPutih && (!selectPemenang || !selectPemenang.value)) {
                alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-2';
                alertBox.innerHTML = '<i class="fas fa-exclamation-triangle text-base text-amber-600"></i> Skor kedua atlet seri/imbang! Silakan pilih pemenang pertandingan (Sudut Merah atau Sudut Putih) berdasarkan keputusan wasit (HANTEI) sebelum memajukan pemenang.';
                alertBox.style.display = 'flex';
                selectPemenang?.focus();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
            alertBox.style.display = 'none';

            const formData = new FormData(form);
            formData.append('is_final_advance', '1');

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
                    }, 800);
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
