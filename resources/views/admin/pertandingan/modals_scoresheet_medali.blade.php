@php
    $isRandori = stripos($pertandingan->pertandingan->nama ?? '', 'randori') !== false ||
                 stripos($pertandingan->kategori->nama ?? '', 'randori') !== false;
    $scoresheet = $pertandingan->data_scoresheet ?? [];
    $juara = $pertandingan->data_juara ?? [];
@endphp

<!-- ========================================== -->
<!-- MODAL 1: FORM SCORESHEET & CETAK PDF      -->
<!-- ========================================== -->
<div id="modalScoresheetPdf" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(0, 0, 0, 0.7); align-items: center; justify-content: center; padding: 1.5rem; overflow-y: auto;">
    <div style="position: relative; width: 100%; max-width: 1000px; margin: auto; max-height: 90vh; background: #ffffff; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); display: flex; flex-direction: column; overflow: hidden;" class="dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50" style="flex-shrink: 0;">
            <div>
                <div class="flex items-center gap-2">
                    <h5 class="text-lg font-bold text-slate-800 dark:text-white mb-0 flex items-center">
                        <i class="ni ni-paper-diploma text-blue-600 mr-2"></i> Form Scoresheet Pertandingan &amp; Cetak PDF
                    </h5>
                    @if($isRandori)
                        <span class="px-2.5 py-0.5 text-xs font-bold text-red-700 bg-red-100 dark:bg-red-900/40 dark:text-red-300 rounded-full">Kategori Randori</span>
                    @else
                        <span class="px-2.5 py-0.5 text-xs font-bold text-blue-700 bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300 rounded-full">Kategori Embu</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mb-0 mt-1">
                    {{ $pertandingan->kegiatan->nama }} &bull; {{ $pertandingan->pertandingan->nama }} (Kelas: {{ $pertandingan->pertandingan->kelas }})
                </p>
            </div>
            <button type="button" onclick="closeScoresheetModal()" class="text-slate-400 hover:text-slate-700 dark:hover:text-white text-3xl font-bold p-1 leading-none cursor-pointer">&times;</button>
        </div>

        <!-- Modal Body Form -->
        <form action="{{ route('pertandingan.simpan-pdf', $pertandingan->id) }}" method="POST" style="display: flex; flex-direction: column; flex: 1 1 auto; overflow: hidden; margin-bottom: 0;">
            @csrf
            <input type="hidden" name="kegiatan_id" value="{{ $pertandingan->kegiatan->id }}">

            <div class="p-6 overflow-y-auto space-y-6" style="flex: 1 1 auto; max-height: calc(90vh - 140px);">
                
                <!-- Info Acara / Tatami / Wasit -->
                <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-700">
                    <h6 class="text-xs font-bold uppercase text-slate-700 dark:text-white/80 mb-3 flex items-center gap-1">
                        <i class="ni ni-badge text-blue-500"></i> Informasi Pelaksanaan &amp; Wasit
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Nomor / Nama Tatami</label>
                            <input type="text" name="tatami" value="{{ $scoresheet['tatami'] ?? 'Tatami 1' }}" placeholder="Contoh: Tatami 1" class="w-full text-sm rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white p-2 focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Tanggal Pertandingan</label>
                            <input type="date" name="tanggal" value="{{ $scoresheet['tanggal'] ?? date('Y-m-d') }}" class="w-full text-sm rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white p-2 focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Wasit Kepala / Pengawas</label>
                            <input type="text" name="wasit_kepala" value="{{ $scoresheet['wasit_kepala'] ?? '' }}" placeholder="Nama Wasit Kepala" class="w-full text-sm rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white p-2 focus:outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Wasit / Juri 1 - 5 -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                        <div>
                            <label class="block text-[11px] font-medium text-slate-500 mb-1">Wasit / Juri 1</label>
                            <input type="text" name="wasit_1" value="{{ $scoresheet['wasit_1'] ?? '' }}" placeholder="Nama Juri 1" class="w-full text-xs rounded border border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white p-1.5 focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-slate-500 mb-1">Wasit / Juri 2</label>
                            <input type="text" name="wasit_2" value="{{ $scoresheet['wasit_2'] ?? '' }}" placeholder="Nama Juri 2" class="w-full text-xs rounded border border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white p-1.5 focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-slate-500 mb-1">Wasit / Juri 3</label>
                            <input type="text" name="wasit_3" value="{{ $scoresheet['wasit_3'] ?? '' }}" placeholder="Nama Juri 3" class="w-full text-xs rounded border border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white p-1.5 focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-slate-500 mb-1">Wasit / Juri 4</label>
                            <input type="text" name="wasit_4" value="{{ $scoresheet['wasit_4'] ?? '' }}" placeholder="Nama Juri 4" class="w-full text-xs rounded border border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white p-1.5 focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-slate-500 mb-1">Wasit / Juri 5</label>
                            <input type="text" name="wasit_5" value="{{ $scoresheet['wasit_5'] ?? '' }}" placeholder="Nama Juri 5" class="w-full text-xs rounded border border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white p-1.5 focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                @if(!$isRandori)
                <!-- ============================== -->
                <!-- FORM KHUSUS EMBU (PENILAIAN)  -->
                <!-- ============================== -->
                <div>
                    <h6 class="text-xs font-bold uppercase text-slate-700 dark:text-white/80 mb-2 flex items-center justify-between">
                        <span><i class="ni ni-bullet-list-67 text-blue-500"></i> Tabel Penilaian Peserta / Pasangan Embu</span>
                        <span class="text-[11px] font-normal lowercase text-slate-500">Nilai total dihitung dari jumlah nilai juri dikurangi hukuman</span>
                    </h6>

                    <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-xl">
                        <table class="w-full text-xs text-left text-slate-600 dark:text-white/80 border-collapse">
                            <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-white text-[11px] uppercase">
                                <tr>
                                    <th class="p-2 text-center" style="width: 50px;">Undian</th>
                                    <th class="p-2" style="width: 140px;">Kontingen</th>
                                    <th class="p-2">Peserta / Pasangan</th>
                                    <th class="p-2 text-center" style="width: 55px;">J-1</th>
                                    <th class="p-2 text-center" style="width: 55px;">J-2</th>
                                    <th class="p-2 text-center" style="width: 55px;">J-3</th>
                                    <th class="p-2 text-center" style="width: 55px;">J-4</th>
                                    <th class="p-2 text-center" style="width: 55px;">J-5</th>
                                    <th class="p-2 text-center text-red-600" style="width: 60px;">Minus</th>
                                    <th class="p-2 text-center font-bold" style="width: 70px;">Total</th>
                                    <th class="p-2 text-center" style="width: 80px;">Peringkat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @php
                                    $savedPeserta = $scoresheet['peserta'] ?? [];
                                    $indexRow = 0;
                                @endphp

                                @if(!empty($savedPeserta))
                                    @foreach($savedPeserta as $row)
                                        <tr class="embu-row hover:bg-slate-50/60 dark:hover:bg-slate-800/30">
                                            <td class="p-2 text-center">
                                                <input type="text" name="peserta[{{ $indexRow }}][undian]" value="{{ $row['undian'] ?? ($indexRow + 1) }}" class="w-10 text-center font-bold text-xs p-1 border rounded dark:bg-slate-800 dark:border-slate-600">
                                            </td>
                                            <td class="p-2 font-bold">
                                                <input type="text" name="peserta[{{ $indexRow }}][kontingen]" value="{{ $row['kontingen'] ?? '' }}" class="w-full text-xs p-1 border rounded dark:bg-slate-800 dark:border-slate-600">
                                            </td>
                                            <td class="p-2">
                                                <input type="text" name="peserta[{{ $indexRow }}][peserta]" value="{{ $row['peserta'] ?? '' }}" class="w-full text-xs p-1 border rounded dark:bg-slate-800 dark:border-slate-600">
                                            </td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][juri_1]" value="{{ $row['juri_1'] ?? '' }}" class="score-input w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][juri_2]" value="{{ $row['juri_2'] ?? '' }}" class="score-input w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][juri_3]" value="{{ $row['juri_3'] ?? '' }}" class="score-input w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][juri_4]" value="{{ $row['juri_4'] ?? '' }}" class="score-input w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][juri_5]" value="{{ $row['juri_5'] ?? '' }}" class="score-input w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][hukuman]" value="{{ $row['hukuman'] ?? 0 }}" class="hukuman-input w-full text-center text-xs p-1 border rounded text-red-600 dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="text" name="peserta[{{ $indexRow }}][total]" value="{{ $row['total'] ?? '' }}" class="total-input w-full text-center font-bold text-xs p-1 bg-slate-100 dark:bg-slate-700 border rounded" readonly></td>
                                            <td class="p-1"><input type="text" name="peserta[{{ $indexRow }}][peringkat]" value="{{ $row['peringkat'] ?? '' }}" placeholder="1 / 2 / 3" class="w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                        </tr>
                                        @php $indexRow++; @endphp
                                    @endforeach
                                @else
                                    {{-- Pre-fill otomatis dari kontingen yang bertanding --}}
                                    @foreach($kabupaten as $kabId => $items)
                                        @php
                                            $kName = optional($items->first()->Peserta->Kabupaten)->name ?? 'Tanpa Kabupaten';
                                            $pNames = $items->pluck('Peserta.nama')->implode(', ');
                                        @endphp
                                        <tr class="embu-row hover:bg-slate-50/60 dark:hover:bg-slate-800/30">
                                            <td class="p-2 text-center">
                                                <input type="text" name="peserta[{{ $indexRow }}][undian]" value="{{ $indexRow + 1 }}" class="w-10 text-center font-bold text-xs p-1 border rounded dark:bg-slate-800 dark:border-slate-600">
                                            </td>
                                            <td class="p-2 font-bold">
                                                <input type="text" name="peserta[{{ $indexRow }}][kontingen]" value="{{ $kName }}" class="w-full text-xs p-1 border rounded dark:bg-slate-800 dark:border-slate-600">
                                            </td>
                                            <td class="p-2">
                                                <input type="text" name="peserta[{{ $indexRow }}][peserta]" value="{{ $pNames }}" class="w-full text-xs p-1 border rounded dark:bg-slate-800 dark:border-slate-600">
                                            </td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][juri_1]" class="score-input w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][juri_2]" class="score-input w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][juri_3]" class="score-input w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][juri_4]" class="score-input w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][juri_5]" class="score-input w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="number" step="any" name="peserta[{{ $indexRow }}][hukuman]" value="0" class="hukuman-input w-full text-center text-xs p-1 border rounded text-red-600 dark:bg-slate-800"></td>
                                            <td class="p-1"><input type="text" name="peserta[{{ $indexRow }}][total]" class="total-input w-full text-center font-bold text-xs p-1 bg-slate-100 dark:bg-slate-700 border rounded" readonly></td>
                                            <td class="p-1"><input type="text" name="peserta[{{ $indexRow }}][peringkat]" placeholder="1 / 2 / 3" class="w-full text-center text-xs p-1 border rounded dark:bg-slate-800"></td>
                                        </tr>
                                        @php $indexRow++; @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <!-- ============================== -->
                <!-- FORM KHUSUS RANDORI (BAGAN)    -->
                <!-- ============================== -->
                <div>
                    <h6 class="text-xs font-bold uppercase text-slate-700 dark:text-white/80 mb-2 flex items-center justify-between">
                        <span><i class="ni ni-sound-wave text-red-500"></i> Tabel Rekap Partai Pertandingan Randori</span>
                        <span class="text-[11px] font-normal lowercase text-slate-500">Isi nomor partai, atlet sudut merah vs putih, perolehan skor, dan pemenang</span>
                    </h6>

                    <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-xl">
                        <table class="w-full text-xs text-left text-slate-600 dark:text-white/80 border-collapse">
                            <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-white text-[11px] uppercase">
                                <tr>
                                    <th class="p-2 text-center" style="width: 45px;">Partai</th>
                                    <th class="p-2 text-center" style="width: 90px;">Babak</th>
                                    <th class="p-2 bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-300" style="width: 25%;">Sudut Merah (Kontingen &amp; Nama)</th>
                                    <th class="p-2 text-center" style="width: 50px;">Skor M</th>
                                    <th class="p-2 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200" style="width: 25%;">Sudut Putih (Kontingen &amp; Nama)</th>
                                    <th class="p-2 text-center" style="width: 50px;">Skor P</th>
                                    <th class="p-2 text-center" style="width: 110px;">Pemenang</th>
                                    <th class="p-2" style="width: 120px;">Cara Menang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @php
                                    $savedPartai = $scoresheet['partai'] ?? [];
                                    $totalRows = max(count($savedPartai), 5);
                                @endphp

                                @for($i = 0; $i < $totalRows; $i++)
                                    @php
                                        $pRow = $savedPartai[$i] ?? [];
                                        $defaultBabak = ($i == $totalRows - 1) ? 'Final' : (($i >= $totalRows - 3) ? 'Semifinal' : 'Penyisihan');
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30">
                                        <td class="p-2 text-center">
                                            <input type="text" name="partai[{{ $i }}][nomor]" value="{{ $pRow['nomor'] ?? ($i + 1) }}" class="w-10 text-center font-bold text-xs p-1 border rounded dark:bg-slate-800">
                                        </td>
                                        <td class="p-2 text-center">
                                            <select name="partai[{{ $i }}][babak]" class="text-xs p-1 border rounded dark:bg-slate-800">
                                                <option value="Penyisihan" {{ ($pRow['babak'] ?? $defaultBabak) == 'Penyisihan' ? 'selected' : '' }}>Penyisihan</option>
                                                <option value="Perempat Final" {{ ($pRow['babak'] ?? '') == 'Perempat Final' ? 'selected' : '' }}>Perempat Final</option>
                                                <option value="Semifinal" {{ ($pRow['babak'] ?? $defaultBabak) == 'Semifinal' ? 'selected' : '' }}>Semifinal</option>
                                                <option value="Final" {{ ($pRow['babak'] ?? $defaultBabak) == 'Final' ? 'selected' : '' }}>Final</option>
                                            </select>
                                        </td>
                                        <td class="p-2 bg-red-50/40 dark:bg-red-950/10">
                                            <input type="text" name="partai[{{ $i }}][merah_kontingen]" value="{{ $pRow['merah_kontingen'] ?? '' }}" placeholder="Kontingen" class="w-full text-xs p-1 border rounded mb-1 dark:bg-slate-800">
                                            <input type="text" name="partai[{{ $i }}][merah_nama]" value="{{ $pRow['merah_nama'] ?? '' }}" placeholder="Nama Atlet Merah" class="w-full text-xs p-1 border rounded dark:bg-slate-800">
                                        </td>
                                        <td class="p-1 text-center">
                                            <input type="number" name="partai[{{ $i }}][merah_skor]" value="{{ $pRow['merah_skor'] ?? '' }}" placeholder="0" class="w-full text-center font-bold text-xs p-1 border rounded text-red-600 dark:bg-slate-800">
                                        </td>
                                        <td class="p-2 bg-slate-50/40 dark:bg-slate-800/20">
                                            <input type="text" name="partai[{{ $i }}][putih_kontingen]" value="{{ $pRow['putih_kontingen'] ?? '' }}" placeholder="Kontingen" class="w-full text-xs p-1 border rounded mb-1 dark:bg-slate-800">
                                            <input type="text" name="partai[{{ $i }}][putih_nama]" value="{{ $pRow['putih_nama'] ?? '' }}" placeholder="Nama Atlet Putih" class="w-full text-xs p-1 border rounded dark:bg-slate-800">
                                        </td>
                                        <td class="p-1 text-center">
                                            <input type="number" name="partai[{{ $i }}][putih_skor]" value="{{ $pRow['putih_skor'] ?? '' }}" placeholder="0" class="w-full text-center font-bold text-xs p-1 border rounded dark:bg-slate-800">
                                        </td>
                                        <td class="p-2 text-center">
                                            <select name="partai[{{ $i }}][pemenang]" class="w-full text-xs p-1 border rounded dark:bg-slate-800 font-semibold text-emerald-600">
                                                <option value="">- Pilih Pemenang -</option>
                                                <option value="Sudut Merah" {{ ($pRow['pemenang'] ?? '') == 'Sudut Merah' ? 'selected' : '' }}>Sudut Merah</option>
                                                <option value="Sudut Putih" {{ ($pRow['pemenang'] ?? '') == 'Sudut Putih' ? 'selected' : '' }}>Sudut Putih</option>
                                            </select>
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="partai[{{ $i }}][cara_menang]" value="{{ $pRow['cara_menang'] ?? '' }}" placeholder="Ippon / Waza-ari / Angka" class="w-full text-xs p-1 border rounded dark:bg-slate-800">
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>

            <!-- Modal Footer -->
            <div class="flex flex-wrap items-center justify-between p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50" style="flex-shrink: 0;">
                <a href="{{ route('pertandingan.cetak-pdf', $pertandingan->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white uppercase bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-md transition-all">
                    <i class="fa fa-print"></i> Cetak PDF Sekarang
                </a>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeScoresheetModal()" class="px-4 py-2 text-xs font-bold text-slate-600 uppercase bg-slate-200 hover:bg-slate-300 rounded-lg transition-all cursor-pointer">
                        Tutup
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white uppercase bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-all cursor-pointer">
                        <i class="fa fa-save mr-1"></i> Simpan Isian
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>


<!-- ========================================== -->
<!-- MODAL 2: TENTUKAN PERAIH MEDALI           -->
<!-- ========================================== -->
<div id="modalSetMedali" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(0, 0, 0, 0.7); align-items: center; justify-content: center; padding: 1.5rem; overflow-y: auto;">
    <div style="position: relative; width: 100%; max-width: 650px; margin: auto; max-height: 90vh; background: #ffffff; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); display: flex; flex-direction: column; overflow: hidden;" class="dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-5 border-b border-slate-200 dark:border-slate-800 bg-amber-50/50 dark:bg-amber-950/20" style="flex-shrink: 0;">
            <div>
                <h5 class="text-lg font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                    <i class="ni ni-trophy text-amber-500"></i> Tentukan Peraih Medali
                </h5>
                <p class="text-xs text-slate-500 mb-0 mt-1">
                    {{ $pertandingan->pertandingan->nama }} (Kelas: {{ $pertandingan->pertandingan->kelas }}) &bull; {{ $isRandori ? 'Randori' : 'Embu' }}
                </p>
            </div>
            <button type="button" onclick="closeMedaliModal()" class="text-slate-400 hover:text-slate-700 dark:hover:text-white text-3xl font-bold p-1 leading-none cursor-pointer">&times;</button>
        </div>

        <!-- Form Penentuan Medali -->
        <form action="{{ route('pertandingan.set-medali', $pertandingan->id) }}" method="POST" style="display: flex; flex-direction: column; flex: 1 1 auto; overflow: hidden; margin-bottom: 0;">
            @csrf
            <input type="hidden" name="kegiatan_id" value="{{ $pertandingan->kegiatan->id }}">

            <div class="p-6 space-y-4 overflow-y-auto" style="flex: 1 1 auto; max-height: calc(90vh - 140px);">
                <p class="text-xs text-slate-600 dark:text-slate-300 mb-4">
                    Pilih kontingen yang berhasil meraih medali pada nomor pertandingan ini. Data yang tersimpan akan langsung terakumulasi pada <strong>Klasemen Perolehan Medali Kegiatan</strong>.
                </p>

                <!-- Medali Emas -->
                <div class="p-3 bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/40 rounded-xl">
                    <label class="block text-xs font-bold text-amber-800 dark:text-amber-300 uppercase mb-1 flex items-center gap-1">
                        <span>🥇</span> Medali Emas (Juara 1)
                    </label>
                    <select name="emas_kabupaten_id" class="w-full text-sm rounded-lg border border-amber-300 dark:border-amber-700/60 dark:bg-slate-800 dark:text-white p-2.5 focus:outline-none focus:border-amber-500">
                        <option value="">-- Pilih Kontingen Pemenang Emas --</option>
                        @foreach($kabupaten as $kabId => $items)
                            @php
                                $kId = optional($items->first()->Peserta->Kabupaten)->id;
                                $kName = optional($items->first()->Peserta->Kabupaten)->name;
                                $pNames = $items->pluck('Peserta.nama')->implode(', ');
                            @endphp
                            @if($kName)
                                <option value="{{ $kId }}" {{ ($juara['emas']['kabupaten_id'] ?? null) == $kId ? 'selected' : '' }}>
                                    {{ $kName }} &bull; ({{ $pNames }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Medali Perak -->
                <div class="p-3 bg-slate-50 dark:bg-slate-800/40 border border-slate-300 dark:border-slate-700 rounded-xl">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase mb-1 flex items-center gap-1">
                        <span>🥈</span> Medali Perak (Juara 2)
                    </label>
                    <select name="perak_kabupaten_id" class="w-full text-sm rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white p-2.5 focus:outline-none focus:border-slate-500">
                        <option value="">-- Pilih Kontingen Pemenang Perak --</option>
                        @foreach($kabupaten as $kabId => $items)
                            @php
                                $kId = optional($items->first()->Peserta->Kabupaten)->id;
                                $kName = optional($items->first()->Peserta->Kabupaten)->name;
                                $pNames = $items->pluck('Peserta.nama')->implode(', ');
                            @endphp
                            @if($kName)
                                <option value="{{ $kId }}" {{ ($juara['perak']['kabupaten_id'] ?? null) == $kId ? 'selected' : '' }}>
                                    {{ $kName }} &bull; ({{ $pNames }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Medali Perunggu 1 -->
                <div class="p-3 bg-orange-50/60 dark:bg-orange-950/20 border border-orange-200 dark:border-orange-800/40 rounded-xl">
                    <label class="block text-xs font-bold text-orange-800 dark:text-orange-300 uppercase mb-1 flex items-center gap-1">
                        <span>🥉</span> Medali Perunggu {{ $isRandori ? '1 (Juara 3 Bersama)' : '(Juara 3)' }}
                    </label>
                    <select name="perunggu_kabupaten_id" class="w-full text-sm rounded-lg border border-orange-300 dark:border-orange-700/60 dark:bg-slate-800 dark:text-white p-2.5 focus:outline-none focus:border-orange-500">
                        <option value="">-- Pilih Kontingen Pemenang Perunggu --</option>
                        @foreach($kabupaten as $kabId => $items)
                            @php
                                $kId = optional($items->first()->Peserta->Kabupaten)->id;
                                $kName = optional($items->first()->Peserta->Kabupaten)->name;
                                $pNames = $items->pluck('Peserta.nama')->implode(', ');
                            @endphp
                            @if($kName)
                                <option value="{{ $kId }}" {{ ($juara['perunggu']['kabupaten_id'] ?? null) == $kId ? 'selected' : '' }}>
                                    {{ $kName }} &bull; ({{ $pNames }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                @if($isRandori)
                <!-- Medali Perunggu 2 (Khusus Randori Juara 3 Bersama) -->
                <div class="p-3 bg-orange-50/60 dark:bg-orange-950/20 border border-orange-200 dark:border-orange-800/40 rounded-xl">
                    <label class="block text-xs font-bold text-orange-800 dark:text-orange-300 uppercase mb-1 flex items-center gap-1">
                        <span>🥉</span> Medali Perunggu 2 (Juara 3 Bersama)
                    </label>
                    <select name="perunggu_2_kabupaten_id" class="w-full text-sm rounded-lg border border-orange-300 dark:border-orange-700/60 dark:bg-slate-800 dark:text-white p-2.5 focus:outline-none focus:border-orange-500">
                        <option value="">-- Pilih Kontingen Pemenang Perunggu Kedua (Opsional) --</option>
                        @foreach($kabupaten as $kabId => $items)
                            @php
                                $kId = optional($items->first()->Peserta->Kabupaten)->id;
                                $kName = optional($items->first()->Peserta->Kabupaten)->name;
                                $pNames = $items->pluck('Peserta.nama')->implode(', ');
                            @endphp
                            @if($kName)
                                <option value="{{ $kId }}" {{ ($juara['perunggu_2']['kabupaten_id'] ?? null) == $kId ? 'selected' : '' }}>
                                    {{ $kName }} &bull; ({{ $pNames }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                @endif

            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-2 p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50" style="flex-shrink: 0;">
                <button type="button" onclick="closeMedaliModal()" class="px-4 py-2 text-xs font-bold text-slate-600 uppercase bg-slate-200 hover:bg-slate-300 rounded-lg transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white uppercase bg-amber-500 hover:bg-amber-600 rounded-lg shadow-md transition-all cursor-pointer">
                    <i class="fa fa-medal mr-1"></i> Simpan Medali
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPT JS MODAL & OTOMATISASI NILAI       -->
<!-- ========================================== -->
<script>
    window.openScoresheetModal = function() {
        var modal = document.getElementById('modalScoresheetPdf');
        if (modal) {
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.style.display = 'flex';
            if (typeof calculateAllEmbuTotals === 'function') {
                calculateAllEmbuTotals();
            }
        }
    };

    window.closeScoresheetModal = function() {
        var modal = document.getElementById('modalScoresheetPdf');
        if (modal) {
            modal.style.display = 'none';
        }
    };

    window.openMedaliModal = function() {
        var modal = document.getElementById('modalSetMedali');
        if (modal) {
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.style.display = 'flex';
        }
    };

    window.closeMedaliModal = function() {
        var modal = document.getElementById('modalSetMedali');
        if (modal) {
            modal.style.display = 'none';
        }
    };

    // Auto-calculate Total Nilai Embu
    function calculateEmbuRow(row) {
        var sum = 0;
        var count = 0;
        var scoreInputs = row.querySelectorAll('.score-input');
        scoreInputs.forEach(function(input) {
            var val = parseFloat(input.value);
            if (!isNaN(val)) {
                sum += val;
                count++;
            }
        });

        var hukumanInput = row.querySelector('.hukuman-input');
        var hukuman = parseFloat(hukumanInput ? hukumanInput.value : 0) || 0;

        var totalInput = row.querySelector('.total-input');
        if (totalInput) {
            if (count > 0) {
                var total = Math.max(0, sum - hukuman);
                totalInput.value = Number.isInteger(total) ? total : total.toFixed(2);
            } else {
                totalInput.value = '';
            }
        }
    }

    function calculateAllEmbuTotals() {
        document.querySelectorAll('.embu-row').forEach(function(row) {
            calculateEmbuRow(row);
        });
    }

    // Attach listeners
    (function() {
        function initModalEvents() {
            // Embu inputs
            document.querySelectorAll('.embu-row').forEach(function(row) {
                row.querySelectorAll('.score-input, .hukuman-input').forEach(function(input) {
                    input.addEventListener('input', function() {
                        calculateEmbuRow(row);
                    });
                });
            });

            // Close on click backdrop
            var modal1 = document.getElementById('modalScoresheetPdf');
            if (modal1) {
                modal1.addEventListener('click', function(e) {
                    if (e.target === modal1) {
                        window.closeScoresheetModal();
                    }
                });
            }

            var modal2 = document.getElementById('modalSetMedali');
            if (modal2) {
                modal2.addEventListener('click', function(e) {
                    if (e.target === modal2) {
                        window.closeMedaliModal();
                    }
                });
            }

            // Close on ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    window.closeScoresheetModal();
                    window.closeMedaliModal();
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initModalEvents);
        } else {
            initModalEvents();
        }
    })();
</script>
