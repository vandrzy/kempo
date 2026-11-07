@extends('tamplate')
@section('title', 'Pertandingan')
@section('content')
    @php
        $current_kegiatan_id = $pertandingan->kegiatan_id ?? ($pertandingan->kegiatan->id ?? ($kegiatan->id ?? ''));
        $current_kegiatan_nama = $pertandingan->kegiatan->nama ?? ($kegiatan->nama ?? 'Kegiatan');
        $isRandori = stripos($pertandingan->pertandingan->nama ?? '', 'randori') !== false ||
                     stripos($pertandingan->kategori->nama ?? '', 'randori') !== false ||
                     ($pertandingan->pertandingan->bagan == 1);
    @endphp
    <style>
        .table-edit-mode-active .table-slot,
        .table-edit-mode-active .table-slot-b1 {
            cursor: pointer !important;
            transition: all 0.15s ease-in-out;
        }
        .table-edit-mode-active .table-slot:hover,
        .table-edit-mode-active .table-slot-b1:hover {
            background-color: #fefce8 !important;
            box-shadow: inset 0 0 0 2px #f59e0b !important;
        }
        .table-edit-mode-active .table-slot.table-slot-selected,
        .table-edit-mode-active .table-slot-b1.table-slot-selected {
            background-color: #fef3c7 !important;
            box-shadow: inset 0 0 0 3px #d97706 !important;
        }
        .table-edit-mode-active .table-slot.table-slot-locked,
        .table-edit-mode-active .table-slot-b1.table-slot-locked {
            cursor: not-allowed !important;
            opacity: 0.55 !important;
        }
        .table-edit-mode-active .table-slot.table-slot-locked:hover,
        .table-edit-mode-active .table-slot-b1.table-slot-locked:hover {
            box-shadow: none !important;
            background-color: inherit !important;
        }
        .table-edit-mode-active .table-slot.table-slot-locked .table-lock-badge,
        .table-edit-mode-active .table-slot-b1.table-slot-locked .table-lock-badge {
            display: inline-flex !important;
        }
    </style>
    <div class="space-y-6">
        <!-- Top Bar Header Card -->
        <div class="kempo-card p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        @if(isset($_GET['kabupaten_id']))
                            <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$current_kegiatan_id) }}" class="btn-kempo-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        @else
                            <a href="{{ url('kegiatan/'.$current_kegiatan_id) }}" class="btn-kempo-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        @endif
                        <h2 class="text-xl font-heading font-bold text-foreground mb-0">
                            {{ $current_kegiatan_nama }} &bull; {{ $pertandingan->pertandingan->nama ?? 'Pertandingan' }}
                        </h2>
                    </div>
                    <p class="text-xs text-paragraph mt-1.5 mb-0">
                        Manajemen undian, urutan bagan, score sheet, dan penentuan hasil pertandingan
                    </p>
                </div>

                <!-- Action Button Group -->
                @if(!isset($_GET['kabupaten_id']))
                    <div class="flex flex-wrap items-center gap-2">
                        @if(!$isRandori)
                            <button type="button" onclick="openScoresheetModal({{ $pertandingan->id }})" class="btn-kempo-4 inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold uppercase tracking-wider shadow-xs hover:opacity-90 transition-all cursor-pointer">
                                <i class="fa fa-file-text-o"></i> Score Sheet &amp; PDF
                            </button>
                        @endif
                        <button type="button" onclick="openMedaliModal({{ $pertandingan->id }})" class="btn-kempo-5 inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold uppercase tracking-wider shadow-xs hover:opacity-90 transition-all cursor-pointer">
                            <i class="fa fa-trophy"></i> Peraih Medali
                        </button>
                        <form action="{{ route('pertandingan.reset-medali', $pertandingan->id) }}" method="post" class="inline-block mb-0" onsubmit="return confirm('Reset semua peraih medali pada kategori ini? Nomor tanding, bagan, dan nilai pertandingan tidak akan berubah.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold uppercase tracking-wider text-amber-800 bg-amber-100 hover:bg-amber-200 rounded-2xl shadow-xs transition-all cursor-pointer">
                                <i class="fa fa-undo"></i> Reset Peraih Medali
                            </button>
                        </form>
                        @if($peserta_ready == 0)
                            @if($isRandori)
                                <a href="{{ url('pertandingans/'.$pertandingan->id.'/print') }}?kegiatan_id={{ $current_kegiatan_id }}" target="_blank" class="btn-kempo-4 inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold uppercase tracking-wider shadow-xs hover:opacity-90 transition-all cursor-pointer">
                                    <i class="fa fa-print"></i> Print Nomor Tanding
                                </a>
                            @else
                                <a href="{{ url('pertandingan/'.$pertandingan->id.'/cetak-pdf') }}" target="_blank" class="btn-kempo-4 inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold uppercase tracking-wider shadow-xs hover:opacity-90 transition-all cursor-pointer">
                                    <i class="fa fa-print"></i> Print Nomor Tanding
                                </a>
                            @endif
                            <form action="{{ url('pertandingans/tanding-delete/') }}" method="post" class="delete-form inline-block mb-0">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="list_kategori_id" value="{{ $pertandingan->id }}">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold uppercase tracking-wider text-white bg-destructive hover:bg-red-700 rounded-2xl shadow-xs transition-all cursor-pointer">
                                    <i class="fa fa-trash"></i> {{ $isRandori ? 'Reset Bagan Undian' : 'Hapus Nomor Tanding' }}
                                </button>
                            </form>
                        @else
                            <form action="{{ url('pertandingans/tanding/'.$pertandingan->id) }}" method="post" class="tanding-form inline-block mb-0">
                                @csrf
                                <input type="hidden" name="pertandingan_id" value="{{ $pertandingan->pertandingan->id }}">
                                <button type="submit" class="btn-kempo-1 inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold uppercase tracking-wider shadow-xs hover:opacity-95 transition-all cursor-pointer">
                                    <i class="fa fa-sitemap"></i> {{ $isRandori ? 'Undi Bagan Otomatis' : 'Acak Nomor Tanding' }}
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if(isset($_GET['kabupaten_id']))
            <!-- ============================================== -->
            <!-- VIEW DETAIL KABUPATEN TANDING                  -->
            <!-- ============================================== -->
            <div class="kempo-card">
                <div class="p-6 border-b border-input flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-heading font-bold text-foreground mb-0">
                            Peserta dari Kontingen: {{ $kabupaten_first->name ?? "Bye" }}
                        </h3>
                        <p class="text-xs text-paragraph mt-0.5 mb-0">Daftar atlet terdaftar pada nomor ini</p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-neutral-100 text-neutral-700 tabular-nums">
                        {{ $peserta->count() }} Atlet
                    </span>
                </div>

                <div class="kempo-table-wrapper">
                    <table class="kempo-table">
                        <thead>
                            <tr>
                                <th rowspan="2" class="w-16 text-center tabular-nums">No</th>
                                <th rowspan="2">Nama Atlet</th>
                                <th colspan="2" class="text-center">Tingkat Sabuk</th>
                                <th rowspan="2" class="text-center">Kelas</th>
                                <th rowspan="2" class="text-center">Berat Badan</th>
                            </tr>
                            <tr>
                                <th class="text-center w-24">KYU</th>
                                <th class="text-center w-24">DAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peserta as $a)
                                <tr>
                                    <td class="text-center tabular-nums font-semibold text-neutral-500">{{ $loop->iteration }}</td>
                                    <td class="font-bold text-foreground">{{ $a->Peserta->nama }}</td>
                                    <td class="text-center font-bold text-primary">
                                        {{ strtoupper(substr($a->Peserta->tingkatan_sabuk , 0, 1)) == "K" ? "✓" : '-' }}
                                    </td>
                                    <td class="text-center font-bold text-btn-4">
                                        {{ strtoupper(substr($a->Peserta->tingkatan_sabuk , 0, 1)) == "D" ? '✓' : '-' }}
                                    </td>
                                    <td class="text-center font-medium text-foreground">
                                        {{ $a->Peserta->PertandinganPesertaUtama->list_kategori->pertandingan->kelas ?? '-' }}
                                    </td>
                                    <td class="text-center font-semibold tabular-nums text-foreground">
                                        {{ $a->Peserta->berat_badan }} Kg
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-paragraph">Tidak ada peserta dari kontingen ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Urutan Komposisi -->
            <div class="kempo-card">
                <div class="p-6 border-b border-input">
                    <h3 class="text-base font-heading font-bold text-foreground mb-0">Urutan Komposisi yang Dimainkan</h3>
                </div>
                <div class="kempo-table-wrapper">
                    <table class="kempo-table">
                        <thead>
                            <tr>
                                <th class="w-24 text-center tabular-nums">No. Urut</th>
                                <th>Urutan Komposisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pesertas->count() == 0)
                                @for ($i = 0; $i < 6; $i++)
                                    <tr>
                                        <td class="text-center tabular-nums font-semibold text-neutral-400">{{ $i + 1 }}</td>
                                        <td class="text-xs italic text-neutral-400">-</td>
                                    </tr>
                                @endfor
                            @else
                                @foreach ($pesertas as $peserta_item)
                                    @php
                                        $kategoriList = $peserta_item->PertandinganPeserta->pluck('list_kategori.kategori')->unique()->values();
                                        $pertandingan_item = $peserta_item->PertandinganPeserta->pluck('list_kategori.pertandingan')->unique()->values();
                                        $kategori_id = 0;
                                        $urutan = 0;
                                        $peserta_list = $loop->iteration;
                                    @endphp
                                    @foreach ($peserta_item->PertandinganPeserta as $item)
                                        @if($peserta_list == 1)
                                            @php
                                                if($item->list_kategori->kategori->id != $kategori_id) {
                                                    $kategori_id = $item->list_kategori->kategori->id;
                                                }
                                            @endphp
                                            @if (@$item->UrutanKomposisiPeserta->urutan_komposisi)
                                                @php
                                                    $kelas = explode(",", $item->UrutanKomposisiPeserta->urutan_komposisi);
                                                    $value_kelas = [];
                                                    for($i = 0; $i < 6; $i++) {
                                                        if(isset($kelas[$i])) {
                                                            $value_kelas[] = $kelas[$i];
                                                        } else {
                                                            $value_kelas[] = "";
                                                        }
                                                    }
                                                @endphp
                                                @foreach ($value_kelas as $index => $a)
                                                    <tr>
                                                        <td class="text-center tabular-nums font-bold text-primary">{{ $index + 1 }}</td>
                                                        <td class="font-medium text-foreground">{{ $a ?: '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endif
                                        @php
                                            $urutan = $urutan + 1;
                                        @endphp
                                    @endforeach
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <!-- ============================================== -->
            <!-- VIEW LIST KONTINGEN & UNDIAN                   -->
            <!-- ============================================== -->
            @if(!$kolom1->isEmpty() || ($isRandori && ($is_diacak || !$peserta_lawan->isEmpty())))

                @php
                    $totalPesertaKategori = \App\Models\PertandinganPeserta::where('list_kategori_id', $pertandingan->id)->count();
                    $slotCountKategori = pow(2, ceil(log(max(2, $totalPesertaKategori), 2)));
                    $totalBabakKategori = (int) log($slotCountKategori, 2);
                    $currentSelectedBabak = request('babak') ?? request('filter_babak') ?? 'all';
                @endphp

                @if($isRandori && ($is_diacak || !$peserta_lawan->isEmpty()))
                    <!-- Tabel Pasangan Duel Randori -->
                    <div class="kempo-card">
                        <div class="p-6 border-b border-input flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-heading font-bold text-foreground mb-0">Bagan Pertandingan &amp; Pasangan Lawan Randori</h3>
                                <p class="text-xs text-paragraph mt-0.5 mb-0">Kelola skor partai duel, penentuan pemenang (Waza-ari/Ippon/Hantei), dan auto-advance babak</p>
                            </div>
                            <div class="flex items-center flex-wrap gap-2.5">
                                <!-- DROPDOWN FILTER PILIHAN BABAK -->
                                <div class="flex items-center gap-2 bg-neutral-50/80 border border-input rounded-xl px-3 py-1 shadow-2xs">
                                    <label for="selectFilterBabak" class="text-xs font-bold text-neutral-600 whitespace-nowrap flex items-center gap-1.5 mb-0">
                                        <i class="fas fa-layer-group text-primary"></i> Pilih Babak:
                                    </label>
                                    <select id="selectFilterBabak" onchange="handleBabakFilterChange(this.value)" class="text-xs font-bold bg-transparent border-0 text-foreground focus:ring-0 cursor-pointer pr-6 py-1">
                                        <option value="all" {{ $currentSelectedBabak === 'all' ? 'selected' : '' }}>Semua Babak</option>
                                        @for($b = 1; $b <= max(1, $totalBabakKategori); $b++)
                                            @php
                                                $labelB = 'Babak ' . $b;
                                                if ($b == $totalBabakKategori) {
                                                    $labelB .= ' (Final)';
                                                } elseif ($totalBabakKategori >= 2 && $b == $totalBabakKategori - 1) {
                                                    $labelB .= ' (Semifinal)';
                                                } elseif ($totalBabakKategori >= 3 && $b == $totalBabakKategori - 2) {
                                                    $labelB .= ' (Perempatfinal)';
                                                }
                                            @endphp
                                            <option value="{{ $b }}" {{ (string)$currentSelectedBabak === (string)$b ? 'selected' : '' }}>
                                                {{ $labelB }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-primary/10 text-primary border border-primary/20">
                                    <i class="fas fa-sitemap"></i> Total {{ $peserta_lawan->total() ?? $peserta_lawan->count() }} Partai
                                </span>
                                <a href="{{ url('/pertandingan/'.$pertandingan->id.'?kegiatan_id='.$current_kegiatan_id.'&print=1') }}" target="_blank" class="btn-kempo-1 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold rounded-xl shadow-xs hover:shadow-sm transition-all">
                                    <i class="fas fa-network-wired"></i> Buka Bagan Interaktif (Tree)
                                </a>
                                <button type="button" id="btnToggleTableEdit" onclick="toggleTableEditBagan()" {{ ($hasSubmittedScores ?? false) ? 'disabled' : '' }} title="{{ ($hasSubmittedScores ?? false) ? 'Mode edit dinonaktifkan karena skor pertandingan sudah disubmit.' : 'Aktifkan mode edit bagan' }}" class="btn-kempo-outline inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold rounded-xl shadow-xs hover:shadow-sm transition-all {{ ($hasSubmittedScores ?? false) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                                    <i class="fas fa-random text-amber-500"></i> <span id="textToggleTableEdit">{{ ($hasSubmittedScores ?? false) ? 'Edit Bagan Terkunci' : 'Mode Edit Bagan' }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sticky/Top Banner Mode Edit Bagan Table -->
                        <div id="tableEditBaganBanner" class="hidden mb-4 p-3.5 bg-gradient-to-r from-amber-500 via-amber-600 to-amber-500 text-white rounded-2xl shadow-sm border border-amber-600 text-xs transition-all">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-white/20 text-white font-black text-xs shrink-0">
                                        <i class="fas fa-info animate-pulse"></i>
                                    </span>
                                    <div>
                                        <span class="font-bold">Mode Edit Bagan Aktif (Click-to-Swap):</span>
                                        <span id="tableEditInstruction" class="text-amber-100 ml-1">Klik baris peserta yang ingin ditukar, lalu klik slot tujuan pada babak yang sama.</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span id="badgeTableSelectedSlot" class="hidden px-2.5 py-1 bg-black/25 text-amber-100 rounded-lg text-[11px] font-bold border border-white/20"></span>
                                    <button type="button" id="btnCancelTableSlot" onclick="cancelTableSelectedSlot()" class="hidden px-2.5 py-1 bg-white hover:bg-amber-50 text-amber-900 rounded-lg text-xs font-bold transition-colors cursor-pointer shadow-xs">
                                        <i class="fas fa-times"></i> Batal Pilih
                                    </button>
                                    <button type="button" onclick="toggleTableEditBagan(false)" class="px-2.5 py-1 bg-amber-700/60 hover:bg-amber-800 text-white rounded-lg text-xs font-bold transition-colors cursor-pointer border border-white/10">
                                        Keluar Mode Edit
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="kempo-table-wrapper">
                            <table class="kempo-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="w-32 text-center">Babak &amp; Partai</th>
                                        <th rowspan="2" class="w-36">Sudut</th>
                                        @if($isRandori)
                                            <th rowspan="2" class="text-center w-28 tabular-nums">No Urutan</th>
                                        @endif
                                        <th rowspan="2">Kontingen</th>
                                        <th rowspan="2">Nama Atlet</th>
                                        <th colspan="2" class="text-center w-20">Sabuk</th>
                                        <th rowspan="2" class="text-center w-24">Skor</th>
                                        <th rowspan="2" class="text-center w-40">Status &amp; Pemenang</th>
                                        <th rowspan="2" class="text-center w-32">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center w-10">KYU</th>
                                        <th class="text-center w-10">DAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $babakSebelumnya = null;
                                    @endphp
                                    @forelse ($peserta_lawan as $a)
                                        @php
                                            $babakBaru = $babakSebelumnya !== (int) $a->babak;
                                            $isReady = $a->pertandingan_peserta_kiri_id && $a->pertandingan_peserta_kanan_id;
                                            $isBye = $a->keterangan_menang === 'BYE'
                                                && (bool) $a->pertandingan_peserta_kiri_id !== (bool) $a->pertandingan_peserta_kanan_id;
                                            $nomorUrutanMerah = (((int) $a->nomor_partai - 1) * 2) + 1;
                                            $nomorUrutanPutih = $nomorUrutanMerah + 1;
                                            
                                                $namaBabak = 'Babak ' . $a->babak;
                                            if ($a->babak == $totalBabakKategori) {
                                                $namaBabak = 'Final';
                                            } elseif ($totalBabakKategori >= 2 && $a->babak == $totalBabakKategori - 1) {
                                                $namaBabak = 'Semifinal';
                                            } elseif ($totalBabakKategori >= 3 && $a->babak == $totalBabakKategori - 2) {
                                                $namaBabak = 'Perempatfinal';
                                            }
                                            $babakSebelumnya = (int) $a->babak;

                                            $kiriMenang = ($a->pemenang_id && $a->pemenang_id == $a->pertandingan_peserta_kiri_id);
                                            $kananMenang = ($a->pemenang_id && $a->pemenang_id == $a->pertandingan_peserta_kanan_id);
                                            $isPlayedThisMatch = (floatval($a->skor_kiri) > 0 || floatval($a->skor_kanan) > 0 || ($a->pemenang_id && strtoupper($a->keterangan_menang ?? '') !== 'BYE'));
                                        @endphp

                                        @if($babakBaru)
                                            <tr class="bg-slate-100 border-y-2 border-slate-300">
                                                <th colspan="{{ $isRandori ? 11 : 10 }}" class="px-4 py-2 text-left text-xs font-black uppercase tracking-wider text-slate-700">
                                                    {{ $namaBabak }}
                                                </th>
                                            </tr>
                                        @endif

                                        <!-- Tim Merah (Sudut Merah) -->
                                        <tr class="bg-red-50/30 hover:bg-red-50/60 border-b border-input/60 duel-match-row table-slot table-slot-b1 {{ $isPlayedThisMatch ? 'table-slot-locked' : '' }}" 
                                            data-duel-babak="{{ $a->babak }}"
                                            data-match-id="{{ $a->id }}"
                                            data-corner="merah"
                                            data-partai="{{ $a->nomor_partai }}"
                                            data-athlete-id="{{ $a->pertandingan_peserta_kiri_id }}"
                                            data-athlete-name="{{ $a->PertandinganPesertaPertama ? ($a->PertandinganPesertaPertama->Peserta->nama ?? 'Atlet') : 'BYE / Kosong' }}"
                                            data-is-played="{{ $isPlayedThisMatch ? '1' : '0' }}"
                                            data-bye-slot="{{ $isBye && !$a->pertandingan_peserta_kiri_id ? '1' : '0' }}"
                                            onclick="handleTableSlotClick(this, event)">
                                            <!-- Babak & Partai (Rowspan 2) -->
                                            <td rowspan="2" class="text-center align-middle font-bold border-r border-input/60">
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider {{ $namaBabak == 'Final' ? 'bg-amber-100 text-amber-800 border border-amber-300' : ($namaBabak == 'Semifinal' ? 'bg-purple-100 text-purple-800 border border-purple-300' : 'bg-neutral-100 text-neutral-800 border border-neutral-200') }}">
                                                        {{ $namaBabak }}
                                                    </span>
                                                    <span class="text-xs font-heading font-black text-foreground tabular-nums">
                                                        Partai #{{ $a->nomor_partai }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Sudut -->
                                            <td class="font-bold text-xs uppercase tracking-wide text-destructive">
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="w-2.5 h-2.5 rounded-full bg-red-600"></span> [Merah]
                                                </span>
                                            </td>

                                            @if($isRandori)
                                                <td class="text-center font-bold tabular-nums text-foreground">
                                                    {{ $nomorUrutanMerah }}
                                                </td>
                                            @endif

                                            <!-- Kontingen -->
                                            <td class="font-semibold text-xs text-foreground">
                                                {{ $a->PertandinganPesertaPertama->Peserta->kabupaten->name ?? ($a->PertandinganPesertaPertama->Peserta->kontigen ?? ($a->pertandingan_peserta_kiri_id ? 'Tanpa Kontingen' : 'Belum Ada')) }}
                                            </td>

                                            <!-- Nama Atlet -->
                                            <td data-athlete-name-cell class="font-medium text-foreground">
                                                @if($a->PertandinganPesertaPertama)
                                                    <span class="font-bold text-sm {{ $kiriMenang ? 'text-emerald-700' : '' }}">
                                                        {{ $a->PertandinganPesertaPertama->Peserta->nama ?? 'Atlet' }}
                                                    </span>
                                                    @if($isPlayedThisMatch)
                                                        <span class="table-lock-badge hidden text-slate-400 text-xs ml-1.5" title="Partai sudah dimainkan / terkunci"><i class="fas fa-lock"></i></span>
                                                    @endif
                                                    @if($kiriMenang)
                                                        <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                            ✓ MENANG
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-neutral-400 italic text-xs">
                                                        {{ $isBye ? 'Slot BYE — klik untuk assign' : $a->getWaitingPlaceholderText('kiri') }}
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Sabuk KYU -->
                                            <td class="text-center font-bold text-primary text-xs">
                                                {{ strtoupper(substr($a->PertandinganPesertaPertama->Peserta->tingkatan_sabuk ?? '', 0, 1)) == "K" ? "✓" : '-' }}
                                            </td>

                                            <!-- Sabuk DAN -->
                                            <td class="text-center font-bold text-btn-4 text-xs">
                                                {{ strtoupper(substr($a->PertandinganPesertaPertama->Peserta->tingkatan_sabuk ?? '', 0, 1)) == "D" ? '✓' : '-' }}
                                            </td>

                                            <!-- Skor Merah -->
                                            <td class="text-center font-black text-base text-red-600 tabular-nums">
                                                {{ $a->skor_kiri !== null ? $a->skor_kiri : '-' }}
                                            </td>

                                            <!-- Status & Pemenang (Rowspan 2) -->
                                            <td rowspan="2" class="text-center align-middle border-x border-input/60">
                                                @if($isBye)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        <i class="fas fa-check-circle"></i> Lolos (BYE)
                                                    </span>
                                                @elseif($a->pemenang_id)
                                                    <div class="flex flex-col items-center gap-0.5">
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                            <i class="fas fa-trophy text-amber-500"></i> {{ $kiriMenang ? 'Merah Menang' : 'Putih Menang' }}
                                                        </span>
                                                        <span class="text-[10px] font-bold text-neutral-600 uppercase tracking-wider">
                                                            {{ $a->keterangan_menang ?? 'POIN' }} ({{ $a->skor_kiri }} - {{ $a->skor_kanan }})
                                                        </span>
                                                    </div>
                                                @elseif($isReady)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                        <i class="fas fa-hourglass-half"></i> Siap Bertanding
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium bg-neutral-100 text-neutral-500 border border-neutral-200">
                                                        <i class="fas fa-clock"></i> Menunggu Lawan
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Aksi (Rowspan 2) -->
                                            <td rowspan="2" class="text-center align-middle">
                                                @if($isBye)
                                                    <span class="text-xs text-neutral-400 italic">Selesai (BYE)</span>
                                                @elseif($isReady)
                                                    <button type="button" 
                                                        onclick="openDuelScoresheetModal({
                                                            id: {{ $a->id }},
                                                            babak: {{ $a->babak }},
                                                            nama_babak: '{{ $namaBabak }}',
                                                            nomor_partai: {{ $a->nomor_partai }},
                                                            kiri_id: {{ $a->pertandingan_peserta_kiri_id ?? 'null' }},
                                                            kiri_nama: '{{ addslashes($a->PertandinganPesertaPertama->Peserta->nama ?? 'Atlet Merah') }}',
                                                            kiri_kontingen: '{{ addslashes($a->PertandinganPesertaPertama->Peserta->kabupaten->name ?? ($a->PertandinganPesertaPertama->Peserta->kontigen ?? '-')) }}',
                                                            skor_kiri: {{ $a->skor_kiri !== null ? $a->skor_kiri : 'null' }},
                                                            kanan_id: {{ $a->pertandingan_peserta_kanan_id ?? 'null' }},
                                                            kanan_nama: '{{ addslashes($a->PertandinganPesertaKedua->Peserta->nama ?? 'Atlet Sudut Putih') }}',
                                                            kanan_kontingen: '{{ addslashes($a->PertandinganPesertaKedua->Peserta->kabupaten->name ?? ($a->PertandinganPesertaKedua->Peserta->kontigen ?? '-')) }}',
                                                            skor_kanan: {{ $a->skor_kanan !== null ? $a->skor_kanan : 'null' }},
                                                            pemenang_id: {{ $a->pemenang_id ?? 'null' }},
                                                            merah_mujoken_kachi: {{ (int) ($a->merah_mujoken_kachi ?? 0) }},
                                                            merah_ippon: {{ (int) ($a->merah_ippon ?? 0) }},
                                                            merah_waza_ari: {{ (int) ($a->merah_waza_ari ?? 0) }},
                                                            merah_batsu_5: {{ (int) ($a->merah_batsu_5 ?? 0) }},
                                                            merah_batsu_10: {{ (int) ($a->merah_batsu_10 ?? 0) }},
                                                            merah_yusei_kachi: {{ (int) ($a->merah_yusei_kachi ?? 0) }},
                                                            putih_mujoken_kachi: {{ (int) ($a->putih_mujoken_kachi ?? 0) }},
                                                            putih_ippon: {{ (int) ($a->putih_ippon ?? 0) }},
                                                            putih_waza_ari: {{ (int) ($a->putih_waza_ari ?? 0) }},
                                                            putih_batsu_5: {{ (int) ($a->putih_batsu_5 ?? 0) }},
                                                            putih_batsu_10: {{ (int) ($a->putih_batsu_10 ?? 0) }},
                                                            putih_yusei_kachi: {{ (int) ($a->putih_yusei_kachi ?? 0) }}
                                                        })"
                                                        class="{{ $a->pemenang_id ? 'btn-kempo-outline' : 'btn-kempo-1' }} inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold shadow-xs hover:shadow-sm transition-all cursor-pointer">
                                                        <i class="fas {{ $a->pemenang_id ? 'fa-edit' : 'fa-play' }}"></i>
                                                        {{ $a->pemenang_id ? 'Edit Skor' : 'Input Skor' }}
                                                    </button>
                                                @else
                                                    <button type="button" disabled class="px-2.5 py-1 text-[11px] font-bold text-neutral-400 bg-neutral-100 rounded-lg cursor-not-allowed">
                                                        Belum Siap
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Tim Sudut Putih -->
                                        <tr class="bg-blue-50/30 hover:bg-blue-50/60 border-b-2 border-input duel-match-row table-slot table-slot-b1 {{ $isPlayedThisMatch ? 'table-slot-locked' : '' }}" 
                                            data-duel-babak="{{ $a->babak }}"
                                            data-match-id="{{ $a->id }}"
                                            data-corner="putih"
                                            data-partai="{{ $a->nomor_partai }}"
                                            data-athlete-id="{{ $a->pertandingan_peserta_kanan_id }}"
                                            data-athlete-name="{{ $a->PertandinganPesertaKedua ? ($a->PertandinganPesertaKedua->Peserta->nama ?? 'Atlet') : 'BYE / Kosong' }}"
                                            data-is-played="{{ $isPlayedThisMatch ? '1' : '0' }}"
                                            data-bye-slot="{{ $isBye && !$a->pertandingan_peserta_kanan_id ? '1' : '0' }}"
                                            onclick="handleTableSlotClick(this, event)">
                                            <!-- Sudut -->
                                            <td class="font-bold text-xs uppercase tracking-wide text-neutral-700">
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="w-2.5 h-2.5 rounded-full bg-white border border-neutral-400"></span> [Putih]
                                                </span>
                                            </td>

                                            @if($isRandori)
                                                <td class="text-center font-bold tabular-nums text-foreground">
                                                    {{ $nomorUrutanPutih }}
                                                </td>
                                            @endif

                                            <!-- Kontingen -->
                                            <td class="font-semibold text-xs text-foreground">
                                                {{ $a->PertandinganPesertaKedua->Peserta->kabupaten->name ?? ($a->PertandinganPesertaKedua->Peserta->kontigen ?? ($a->pertandingan_peserta_kanan_id ? 'Tanpa Kontingen' : 'Belum Ada')) }}
                                            </td>

                                            <!-- Nama Atlet -->
                                            <td data-athlete-name-cell class="font-medium text-foreground">
                                                @if($a->PertandinganPesertaKedua)
                                                    <span class="font-bold text-sm {{ $kananMenang ? 'text-emerald-700' : '' }}">
                                                        {{ $a->PertandinganPesertaKedua->Peserta->nama ?? 'Atlet' }}
                                                    </span>
                                                    @if($isPlayedThisMatch)
                                                        <span class="table-lock-badge hidden text-slate-400 text-xs ml-1.5" title="Partai sudah dimainkan / terkunci"><i class="fas fa-lock"></i></span>
                                                    @endif
                                                    @if($kananMenang)
                                                        <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                            ✓ MENANG
                                                        </span>
                                                    @endif
                                                @elseif($isBye)
                                                    <span class="text-neutral-400 italic text-xs">Slot BYE — klik untuk assign</span>
                                                @else
                                                    <span class="text-neutral-400 italic text-xs">{{ $a->getWaitingPlaceholderText('kanan') }}</span>
                                                @endif
                                            </td>

                                            <!-- Sabuk KYU -->
                                            <td class="text-center font-bold text-primary text-xs">
                                                {{ strtoupper(substr($a->PertandinganPesertaKedua->Peserta->tingkatan_sabuk ?? '', 0, 1)) == "K" ? "✓" : '-' }}
                                            </td>

                                            <!-- Sabuk DAN -->
                                            <td class="text-center font-bold text-btn-4 text-xs">
                                                {{ strtoupper(substr($a->PertandinganPesertaKedua->Peserta->tingkatan_sabuk ?? '', 0, 1)) == "D" ? '✓' : '-' }}
                                            </td>

                                            <!-- Skor Sudut Putih -->
                                            <td class="text-center font-black text-base text-blue-600 tabular-nums">
                                                {{ $a->skor_kanan !== null ? $a->skor_kanan : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $isRandori ? 10 : 9 }}" class="py-8 text-center text-paragraph">Tidak ada partai duel pada babak ini atau belum ada bagan tanding yang diacak.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if(!$kolom1->isEmpty())
                <div class="kempo-card">
                    <div class="p-6 border-b border-input flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-heading font-bold text-foreground mb-0">Daftar Kontingen &amp; Undian Bagan</h3>
                            <p class="text-xs text-paragraph mt-0.5 mb-0">Hasil pengundian giliran dan peserta pertandingan</p>
                        </div>
                    </div>

                    <div class="kempo-table-wrapper">
                        <table class="kempo-table">
                            <thead>
                                <tr>
                                    <th>Kontingen / Kabupaten</th>
                                    @if($isRandori)
                                        <th class="text-center w-28 tabular-nums">Nomor Undian</th>
                                    @else
                                        <th class="text-center w-28 tabular-nums">Babak 1</th>
                                        <th class="text-center w-28 tabular-nums">Babak 2</th>
                                    @endif
                                    <th class="text-center w-36">Jumlah Peserta</th>
                                    <th class="text-center w-96">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $databye = [];
                                    foreach ($kolom2 as $item) {
                                        $databye[] = $item;
                                    }
                                    $list_byte = 0;
                                @endphp
                                @foreach ($kolom1 as $index_list => $item)
                                    @php
                                        $kegiatan_id_get = request('kegiatan_id') ?? ($_GET['kegiatan_id'] ?? ($current_kegiatan_id ?? optional($pertandingan)->kegiatan_id));
                                        $list_kategori = $item->list_kategori_id;
                                        if($item->kabupaten_id != null) {
                                            $total_peserta = \App\Models\Peserta::where('kabupaten_id', $item->kabupaten_id)
                                            ->whereHas('PertandinganPeserta', function ($pp) use ($list_kategori, $kegiatan_id_get) {
                                                $pp->where('list_kategori_id', $list_kategori)
                                                ->where('kegiatan_id', $kegiatan_id_get);
                                            })
                                            ->count();
                                        } else {
                                            $total_peserta = 0;
                                        }
                                    @endphp
                                    <tr id="tr_team_{{ $index_list }}">
                                        <td class="col-team-{{ $index_list }} font-bold text-foreground">
                                            @if (isset($item->kabupaten->name))
                                                <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$current_kegiatan_id.'&kabupaten_id='.$item->kabupaten->id) }}" class="text-primary hover:underline">
                                                    {{ $item->kabupaten->name }}
                                                </a>
                                            @else
                                                <span class="text-neutral-400 italic">Bye</span>
                                            @endif
                                        </td>
                                        <td class="col-team-{{ $index_list }} text-center font-bold tabular-nums text-foreground">
                                            @if($isRandori && !isset($_GET['kabupaten_id']))
                                                <form action="{{ route('pertandingan.update-undian', [$pertandingan->id, $item->id]) }}" method="post" class="inline-flex items-center justify-center gap-1">
                                                    @csrf
                                                    <input
                                                        type="number"
                                                        name="nomor_undian"
                                                        value="{{ $item->undian + 1 }}"
                                                        min="1"
                                                        max="{{ $kolom1->count() }}"
                                                        aria-label="Nomor undian untuk {{ $item->kabupaten->name ?? 'peserta' }}"
                                                        title="Mengubah nomor akan menukar posisi dengan peserta yang memakai nomor tujuan"
                                                        class="w-14 rounded bg-neutral-100 px-2 py-1 text-center text-neutral-800"
                                                        required
                                                    >
                                                    <button type="submit" class="rounded px-1.5 py-1 text-neutral-600 hover:bg-neutral-200" aria-label="Simpan nomor undian">
                                                        <i class="fa fa-save"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-block px-2.5 py-1 rounded bg-neutral-100 text-neutral-800">
                                                    {{ $item->undian + 1 }}
                                                </span>
                                            @endif
                                        </td>
                                        @if(!$isRandori)
                                            <td class="col-team-{{ $index_list }} text-center font-bold tabular-nums text-foreground">
                                                <span class="inline-block px-2.5 py-1 rounded bg-neutral-100 text-neutral-800">
                                                    @if(isset($item->kabupaten->KontigenPertandinganKedua->undian))
                                                        {{ $item->kabupaten->KontigenPertandinganKedua->undian + 1 }}
                                                    @else
                                                        {{ isset($databye[$list_byte]) ? ($databye[$list_byte]->undian + 1) : '-' }}
                                                    @endif
                                                </span>
                                            </td>
                                        @endif
                                        <td class="col-team-{{ $index_list }} text-center">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-neutral-100 text-neutral-700 tabular-nums">
                                                <i class="fa fa-users text-neutral-400"></i> {{ $total_peserta }} Peserta
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                                <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$current_kegiatan_id.'&kabupaten_id='.($item->kabupaten->id ?? null) ) }}" class="btn-kempo-4 inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold shadow-2xs hover:opacity-90 transition-all">
                                                    <i class="fa fa-eye"></i> Detail
                                                </a>
                                                <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$current_kegiatan_id.'&kabupaten_id='.($item->kabupaten->id ?? null).'&status=print&kontigen_id_1='.$item->id.'&kontigen_id_2='. (isset($item->kabupaten->KontigenPertandinganKedua->id) ? $item->kabupaten->KontigenPertandinganKedua->id : (isset($databye[$list_byte]) ? $databye[$list_byte]->id : '')) ) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-2xl bg-neutral-700 text-white hover:bg-neutral-800 transition-all shadow-2xs">
                                                    <i class="fa fa-print"></i> Nilai
                                                </a>
                                                @if(!$isRandori && isset($item->kabupaten))
                                                    <button type="button" onclick="openScoresheetModal({{ $pertandingan->id }}, {{ $item->kabupaten->id }}, '{{ addslashes($item->kabupaten->name) }}')" class="btn-kempo-4 inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold shadow-2xs hover:opacity-90 transition-all cursor-pointer">
                                                        <i class="fa fa-file-text-o"></i> Scoresheet
                                                    </button>
                                                @endif
                                                <button type="button" status="-" total_peserta="{{ $total_peserta }}" index="{{ $index_list }}" kabupaten_id="{{ $item->kabupaten->id ?? "-" }}" class="lihat_peserta btn-kempo-3 inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold shadow-2xs hover:opacity-90 transition-all cursor-pointer">
                                                    <i class="fa fa-list"></i> Peserta
                                                </button>
                                            </div>
                                        </td>
                                        @if(!isset($item->kabupaten->KontigenPertandinganKedua->undian))
                                            @php
                                                unset($databye[$list_byte]);
                                                $list_byte += 1;
                                            @endphp
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            @elseif($peserta_ready > 0)
                <div class="kempo-card">
                    <div class="p-6 border-b border-input flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-heading font-bold text-foreground mb-0">Daftar Kontingen Tersedia</h3>
                            <p class="text-xs text-paragraph mt-0.5 mb-0">Total peserta tersedia: {{ $peserta_ready }}</p>
                        </div>
                    </div>

                    <div class="kempo-table-wrapper">
                        <table class="kempo-table">
                            <thead>
                                <tr>
                                    <th>Kabupaten / Kontingen</th>
                                    @if($isRandori)
                                        <th class="w-28 text-center tabular-nums">Nomor Undian</th>
                                    @else
                                        <th class="w-28 text-center tabular-nums">Babak 1</th>
                                        <th class="w-28 text-center tabular-nums">Babak 2</th>
                                    @endif
                                    <th class="text-center w-80">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kabupaten as $kabId => $items)
                                    @php
                                        $kabupatenId = optional($items->first()->Peserta->Kabupaten)->id;
                                        $kabupatenName = optional($items->first()->Peserta->Kabupaten)->name;
                                        $kabupatenTotalPeserta = $items->count();
                                    @endphp
                                    @if ($kabupatenName)
                                        <tr id="tr_team_{{ $kabId }}">
                                            <td class="col-team-{{ $kabId }} font-bold text-foreground">
                                                <div class="flex items-center gap-2">
                                                    <span>{{ $kabupatenName }}</span>
                                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-neutral-100 text-neutral-600 tabular-nums">
                                                        {{ $kabupatenTotalPeserta }} Peserta
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="col-team-{{ $kabId }} text-center text-neutral-400">-</td>
                                            @if(!$isRandori)
                                                <td class="col-team-{{ $kabId }} text-center text-neutral-400">-</td>
                                            @endif
                                            <td class="text-center">
                                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                                    <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$current_kegiatan_id.'&kabupaten_id='.($kabupatenId ?? null) ) }}" class="btn-kempo-4 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold shadow-2xs hover:opacity-90 transition-all">
                                                        <i class="fa fa-eye"></i> Detail
                                                    </a>
                                                    @if(!$isRandori && $kabupatenId)
                                                        <button type="button" onclick="openScoresheetModal({{ $pertandingan->id }}, {{ $kabupatenId }}, '{{ addslashes($kabupatenName ?? '') }}')" class="btn-kempo-4 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold shadow-2xs hover:opacity-90 transition-all cursor-pointer">
                                                            <i class="fa fa-file-text-o"></i> Scoresheet
                                                        </button>
                                                    @endif
                                                    <button type="button" status="-" total_peserta="{{ $kabupatenTotalPeserta }}" index="{{ $kabId }}" kabupaten_id="{{ $kabupatenId ?? "-" }}" class="lihat_peserta btn-kempo-3 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold shadow-2xs hover:opacity-90 transition-all cursor-pointer">
                                                        <i class="fa fa-list"></i> Lihat Peserta
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                <!-- Tabel Peserta Lawan / Pertandingan Selesai Diacak -->
                <div class="kempo-card">
                    <div class="p-6 border-b border-input">
                        <h3 class="text-lg font-heading font-bold text-foreground mb-0">Bagan Pertandingan &amp; Pasangan Lawan</h3>
                        <p class="text-xs text-paragraph mt-0.5 mb-0">Pasangan peserta merah dan biru yang akan bertanding</p>
                    </div>

                    <div class="kempo-table-wrapper">
                        <table class="kempo-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="w-16 text-center tabular-nums">No</th>
                                    <th rowspan="2">Kontingen</th>
                                    <th rowspan="2">Nama Atlet</th>
                                    <th colspan="2" class="text-center">Tingkat Sabuk</th>
                                    <th rowspan="2" class="text-center">Kelas</th>
                                    <th rowspan="2" class="text-center">Berat Badan</th>
                                </tr>
                                <tr>
                                    <th class="text-center w-24">KYU</th>
                                    <th class="text-center w-24">DAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peserta_lawan as $a)
                                    <!-- Tim Merah (Sudut Merah) -->
                                    <tr class="bg-red-50/40 hover:bg-red-50/70 border-b border-input">
                                        <td class="text-center font-bold text-destructive tabular-nums">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-destructive text-xs">
                                                {{ $a->PertandinganPesertaPertama->urutan }}
                                            </span>
                                        </td>
                                        <td class="font-bold text-foreground">
                                            <span class="text-destructive font-bold text-xs uppercase mr-1">[Merah]</span>
                                            {{ $a->PertandinganPesertaPertama->Peserta->kabupaten->name ?? "" }}
                                        </td>
                                        <td class="font-medium text-foreground">{{ $a->PertandinganPesertaPertama->Peserta->nama }}</td>
                                        <td class="text-center font-bold text-primary">
                                            {{ strtoupper(substr($a->PertandinganPesertaPertama->Peserta->tingkatan_sabuk , 0, 1)) == "K" ? "✓" : '-' }}
                                        </td>
                                        <td class="text-center font-bold text-btn-4">
                                            {{ strtoupper(substr($a->PertandinganPesertaPertama->Peserta->tingkatan_sabuk , 0, 1)) == "D" ? '✓' : '-' }}
                                        </td>
                                        <td class="text-center font-medium text-foreground">
                                            {{ $a->PertandinganPesertaPertama->Peserta->PertandinganPesertaUtama->list_kategori->pertandingan->kelas ?? '-' }}
                                        </td>
                                        <td class="text-center font-semibold tabular-nums text-foreground">
                                            {{ $a->PertandinganPesertaPertama->Peserta->berat_badan }} Kg
                                        </td>
                                    </tr>

                                    <!-- Tim Putih (Sudut Putih) -->
                                    <tr class="bg-blue-50/40 hover:bg-blue-50/70 border-b-2 border-input">
                                        <td class="text-center font-bold text-btn-4 tabular-nums">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-btn-4 text-xs">
                                                {{ @$a->PertandinganPesertaKedua->urutan ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="font-bold text-foreground">
                                            <span class="text-neutral-700 font-bold text-xs uppercase mr-1">[Putih]</span>
                                            {{ $a->PertandinganPesertaKedua->Peserta->kabupaten->name ?? "" }}
                                        </td>
                                        <td class="font-medium text-foreground">{{ @$a->PertandinganPesertaKedua->Peserta->nama ?? "WO" }}</td>
                                        <td class="text-center font-bold text-primary">
                                            {{ strtoupper(substr(@$a->PertandinganPesertaKedua->Peserta->tingkatan_sabuk , 0, 1)) == "K" ? "✓" : '-' }}
                                        </td>
                                        <td class="text-center font-bold text-btn-4">
                                            {{ strtoupper(substr(@$a->PertandinganPesertaKedua->Peserta->tingkatan_sabuk , 0, 1)) == "D" ? '✓' : '-' }}
                                        </td>
                                        <td class="text-center font-medium text-foreground">
                                            {{ @$a->PertandinganPesertaKedua->Peserta->PertandinganPesertaUtama->list_kategori->pertandingan->kelas ?? '-' }}
                                        </td>
                                        <td class="text-center font-semibold tabular-nums text-foreground">
                                            {{ @$a->PertandinganPesertaKedua->Peserta->berat_badan ? $a->PertandinganPesertaKedua->Peserta->berat_badan . ' Kg' : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-paragraph">Belum ada bagan tanding yang diacak.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-input flex justify-center">
                        {{ $peserta_lawan->withQueryString()->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            @endif
        @endif
    </div>

    @include('admin.pertandingan.modals_scoresheet_medali')
    @include('admin.pertandingan.modal_duel_scoresheet_randori')
@endsection

@section('script')
    const select = document.getElementById('kabupaten_select');
    const form = document.getElementById('kabupaten_change');

    if (select && form) {
        select.addEventListener('change', function() {
            form.submit();
        });
    }

    $(document).on('click', '.lihat_peserta', function(e) {
        e.preventDefault();
        let $btn = $(this);
        let kabupaten_id = $btn.attr('kabupaten_id');
        let index_list = $btn.attr('index');
        let status = $btn.attr('status');

        if (status === "true") {
            $('.tr_team_all_' + index_list).hide();
            $btn.attr('status', 'false');
            return;
        }

        if (status === "false") {
            $('.tr_team_all_' + index_list).show();
            $btn.attr('status', 'true');
            return;
        }

        if (status === "-") {
            if (!kabupaten_id || kabupaten_id === '-' || kabupaten_id === '') {
                alert("Data kontingen belum tersedia atau merupakan Bye!");
                return;
            }

            let originalContent = $btn.html();
            $btn.html('<i class="fa fa-spinner fa-spin"></i> Memuat...').prop('disabled', true);

            let kegiatanId = (new URLSearchParams(window.location.search).get('kegiatan_id')) || "{{ $current_kegiatan_id }}";

            $.ajax({
                url: "{{ url('pertandingan/'.$pertandingan->id) }}",
                type: 'GET',
                data: {
                    kegiatan_id: kegiatanId,
                    kabupaten_id: kabupaten_id,
                    status: "true",
                },
                success: function (response) {
                    $btn.html(originalContent).prop('disabled', false);
                    $btn.attr('status', 'true');

                    let colCount = $btn.closest('table').find('thead tr:first th').length || 5;
                    let dataList = response.data || [];
                    let totalCount = response.total_data !== undefined ? response.total_data : dataList.length;

                    let html = `<tr class="tr_team_all_${index_list} bg-neutral-50/90 border-b border-input">
                        <td colspan="${colCount}" class="py-3 px-6">
                            <div class="p-4 rounded-xl bg-white border border-input shadow-2xs space-y-3">
                                <div class="flex items-center justify-between pb-2.5 border-b border-input">
                                    <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 flex items-center gap-2">
                                        <i class="fa fa-users text-primary"></i> Daftar Atlet Terdaftar (${totalCount})
                                    </span>
                                    <span class="text-[11px] text-neutral-400">Klik tombol peserta lagi untuk menutup</span>
                                </div>`;

                    if (dataList.length === 0) {
                        html += `<p class="text-xs text-neutral-500 italic py-2 mb-0">Tidak ada atlet yang terdaftar untuk kontingen ini.</p>`;
                    } else {
                        html += `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">`;
                        dataList.forEach(function(item, idx) {
                            let p = item.peserta || {};
                            let nama = p.nama || 'Nama atlet';
                            let sabuk = p.tingkatan_sabuk ? `<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-primary/10 text-primary">${p.tingkatan_sabuk}</span>` : '';
                            let bb = p.berat_badan ? `<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-neutral-100 text-neutral-700 tabular-nums">${p.berat_badan} kg</span>` : '';
                            let jk = p.jenis_kelamin ? `<span class="text-[11px] text-neutral-500">${p.jenis_kelamin}</span>` : '';

                            html += `
                                <div class="p-2.5 rounded-lg bg-neutral-50 hover:bg-neutral-100/80 border border-input/70 transition-colors flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-accent text-primary text-xs font-bold flex items-center justify-center tabular-nums shrink-0">
                                        ${idx + 1}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-foreground truncate mb-0.5">${nama}</p>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            ${sabuk}
                                            ${bb}
                                            ${jk}
                                        </div>
                                    </div>
                                </div>`;
                        });
                        html += `</div>`;
                    }

                    html += `
                            </div>
                        </td>
                    </tr>`;

                    $('#tr_team_' + index_list).after(html);
                },
                error: function (xhr) {
                    $btn.html(originalContent).prop('disabled', false);
                    console.error('Error fetching peserta:', xhr.responseText);
                    alert("Gagal mengambil data peserta kontingen. Silakan coba lagi.");
                }
            });
        }
    });

    window.handleBabakFilterChange = function(val) {
        const url = new URL(window.location.href);
        if (val === 'all' || !val) {
            url.searchParams.delete('babak');
            url.searchParams.delete('filter_babak');
        } else {
            url.searchParams.set('babak', val);
            url.searchParams.delete('filter_babak');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    };

    // ========================================================
    // FASE 3: CLICK-TO-SWAP EDIT BAGAN MANUAL PADA TABEL DUEL
    // ========================================================
    let isTableEditMode = false;
    let selectedTableSlot = null;
    const tableListKategoriId = {{ $pertandingan->id }};
    const tableBracketEditLocked = @json($hasSubmittedScores ?? false);

    window.toggleTableEditBagan = function(forceState) {
        if (tableBracketEditLocked && forceState !== false) return;

        if (typeof forceState === 'boolean') {
            isTableEditMode = forceState;
        } else {
            isTableEditMode = !isTableEditMode;
        }

        const banner = document.getElementById('tableEditBaganBanner');
        const btn = document.getElementById('btnToggleTableEdit');
        const textBtn = document.getElementById('textToggleTableEdit');

        if (isTableEditMode) {
            document.body.classList.add('table-edit-mode-active');
            if (banner) banner.classList.remove('hidden');
            if (btn) {
                btn.classList.add('bg-neutral-900', 'text-white', 'border-neutral-900');
            }
            if (textBtn) textBtn.innerText = 'Keluar Mode Edit';
        } else {
            cancelTableSelectedSlot();
            document.body.classList.remove('table-edit-mode-active');
            if (banner) banner.classList.add('hidden');
            if (btn) {
                btn.classList.remove('bg-neutral-900', 'text-white', 'border-neutral-900');
            }
            if (textBtn) textBtn.innerText = 'Mode Edit Bagan';
        }
    };

    window.handleTableSlotClick = function(el, event) {
        if (!isTableEditMode) return;

        // Jika mengklik tombol aksi di kolom aksi, abaikan agar tombol tetap berfungsi
        if (event.target.closest('button') || event.target.closest('a')) {
            return;
        }

        const isPlayed = el.getAttribute('data-is-played') === '1';
        if (isPlayed) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Partai Terkunci',
                    text: 'Partai ini sudah berlangsung atau selesai, posisinya tidak dapat diubah.',
                    confirmButtonColor: '#d97706'
                });
            } else {
                alert('Partai ini sudah berlangsung atau selesai, posisinya tidak dapat diubah.');
            }
            return;
        }

        const matchId = parseInt(el.getAttribute('data-match-id'));
        const babak = parseInt(el.getAttribute('data-duel-babak'));
        const corner = el.getAttribute('data-corner');
        const partai = el.getAttribute('data-partai');
        const athleteName = el.getAttribute('data-athlete-name') || 'BYE / Kosong';
        const athleteId = el.getAttribute('data-athlete-id');
        const isByeSlot = el.getAttribute('data-bye-slot') === '1';

        // Empty slots in later rounds are reserved for winners of earlier matches.
        if ((!athleteId || athleteId === 'null') && babak > 1 && !isByeSlot) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Slot Menunggu Pemenang',
                    text: 'Slot ini dialokasikan untuk pemenang partai duel babak sebelumnya dan akan terisi secara otomatis.',
                    confirmButtonColor: '#3b82f6'
                });
            } else {
                alert('Slot ini dialokasikan untuk pemenang partai duel babak sebelumnya dan akan terisi secara otomatis.');
            }
            return;
        }

        // Klik slot yang sama -> Batalkan pilihan
        if (selectedTableSlot && selectedTableSlot.matchId === matchId && selectedTableSlot.corner === corner) {
            cancelTableSelectedSlot();
            return;
        }

        // KLIK PERTAMA: MEMILIH SOURCE
        if (!selectedTableSlot) {
            if ((!athleteId || athleteId === 'null') && !isByeSlot) {
                const message = 'Pilih atlet atau slot BYE terlebih dahulu.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'info', title: 'Pilih Atlet', text: message, confirmButtonColor: '#3b82f6' });
                } else {
                    alert(message);
                }
                return;
            }
            selectedTableSlot = { matchId, corner, partai, babak, athleteId, athleteName, element: el };
            el.classList.add('table-slot-selected');

            const badge = document.createElement('span');
            badge.className = 'table-badge-selected ml-2 inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black bg-amber-600 text-white shrink-0 animate-bounce';
            badge.innerHTML = '<i class="fas fa-check mr-0.5"></i> DIPILIH';
            const nameTd = el.querySelector('[data-athlete-name-cell]') || el;
            nameTd.appendChild(badge);

            const badgeBanner = document.getElementById('badgeTableSelectedSlot');
            if (badgeBanner) {
                badgeBanner.innerText = `Asal: Partai #${partai} [${corner.toUpperCase()}] - ${athleteName}`;
                badgeBanner.classList.remove('hidden');
            }
            const btnCancel = document.getElementById('btnCancelTableSlot');
            if (btnCancel) btnCancel.classList.remove('hidden');

            const instr = document.getElementById('tableEditInstruction');
            if (instr) instr.innerText = 'Sekarang klik atlet lain atau slot BYE pada babak yang sama.';
            return;
        }

        // KLIK KEDUA: MEMILIH TARGET
        if (selectedTableSlot.babak !== babak) {
            const message = 'Posisi hanya dapat ditukar dengan atlet atau slot BYE pada babak yang sama.';
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'Babak Tidak Sesuai', text: message, confirmButtonColor: '#d97706' });
            } else {
                alert(message);
            }
            return;
        }

        const targetSlot = { matchId, corner, partai, athleteName, element: el };
        const cornerLabel = (c) => (c === 'merah' ? 'Merah' : 'Putih');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Tukar Posisi Bagan',
                html: `
                    <div class="text-left text-sm p-3.5 bg-neutral-50 rounded-2xl border border-input shadow-inner">
                        <div class="flex items-start gap-2.5 mb-3">
                            <span class="w-3.5 h-3.5 rounded-full ${selectedTableSlot.corner === 'merah' ? 'bg-red-600' : 'bg-blue-600'} mt-1 shrink-0"></span>
                            <div>
                                <div class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Slot Asal (Partai #${selectedTableSlot.partai} - Sudut ${cornerLabel(selectedTableSlot.corner)}):</div>
                                <div class="font-extrabold text-base text-foreground">${selectedTableSlot.athleteName}</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-center my-2 text-amber-500">
                            <div class="h-px bg-neutral-200 flex-1"></div>
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-black flex items-center gap-1.5 border border-amber-300">
                                <i class="fas fa-arrows-alt-v"></i> DITUKAR DENGAN
                            </span>
                            <div class="h-px bg-neutral-200 flex-1"></div>
                        </div>

                        <div class="flex items-start gap-2.5 mt-3">
                            <span class="w-3.5 h-3.5 rounded-full ${targetSlot.corner === 'merah' ? 'bg-red-600' : 'bg-blue-600'} mt-1 shrink-0"></span>
                            <div>
                                <div class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Slot Tujuan (Partai #${targetSlot.partai} - Sudut ${cornerLabel(targetSlot.corner)}):</div>
                                <div class="font-extrabold text-base text-foreground">${targetSlot.athleteName}</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-neutral-500 mt-3 text-center mb-0">
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
                    executeTableSwap(selectedTableSlot, targetSlot);
                } else {
                    cancelTableSelectedSlot();
                }
            });
        } else {
            if (confirm(`Tukar posisi:\n${selectedTableSlot.athleteName} (Partai #${selectedTableSlot.partai})\ndengan\n${targetSlot.athleteName} (Partai #${targetSlot.partai})?`)) {
                executeTableSwap(selectedTableSlot, targetSlot);
            } else {
                cancelTableSelectedSlot();
            }
        }
    };

    window.cancelTableSelectedSlot = function() {
        if (selectedTableSlot && selectedTableSlot.element) {
            selectedTableSlot.element.classList.remove('table-slot-selected');
            const badge = selectedTableSlot.element.querySelector('.table-badge-selected');
            if (badge) badge.remove();
        }
        selectedTableSlot = null;
        const badgeBanner = document.getElementById('badgeTableSelectedSlot');
        if (badgeBanner) badgeBanner.classList.add('hidden');
        const btnCancel = document.getElementById('btnCancelTableSlot');
        if (btnCancel) btnCancel.classList.add('hidden');
        const instr = document.getElementById('tableEditInstruction');
        if (instr) instr.innerText = 'Klik baris peserta yang ingin ditukar, lalu klik slot tujuan pada babak yang sama.';
    };

    window.executeTableSwap = function(source, target) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Menyimpan Perubahan Bagan...',
                text: 'Sedang memproses penukaran posisi dan menyelaraskan bagan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

        fetch('/api/randori/swap-bracket', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            body: JSON.stringify({
                id_kelas: tableListKategoriId,
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
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menukar Bagan',
                        text: result.data.message || 'Terjadi kesalahan pada server saat menukar bagan.',
                        confirmButtonColor: '#e11d48'
                    });
                } else {
                    alert('Gagal menukar bagan: ' + (result.data.message || 'Terjadi kesalahan'));
                }
                cancelTableSelectedSlot();
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Ditukar!',
                    text: result.data.message || 'Posisi bagan berhasil diperbarui.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                alert('Posisi bagan berhasil diperbarui!');
                window.location.reload();
            }
        })
        .catch(err => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan',
                    text: 'Gagal terhubung ke server: ' + err.message,
                    confirmButtonColor: '#e11d48'
                });
            } else {
                alert('Gagal terhubung ke server: ' + err.message);
            }
            cancelTableSelectedSlot();
        });
    };
@endsection
