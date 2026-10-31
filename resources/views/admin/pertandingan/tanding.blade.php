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
                        <button type="button" onclick="openScoresheetModal({{ $pertandingan->id }})" class="btn-kempo-4 inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold uppercase tracking-wider shadow-xs hover:opacity-90 transition-all cursor-pointer">
                            <i class="fa fa-file-text-o"></i> Score Sheet &amp; PDF
                        </button>
                        <button type="button" onclick="openMedaliModal({{ $pertandingan->id }})" class="btn-kempo-5 inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold uppercase tracking-wider shadow-xs hover:opacity-90 transition-all cursor-pointer">
                            <i class="fa fa-trophy"></i> Peraih Medali
                        </button>
                        @if($peserta_ready == 0)
                            <a href="{{ url('pertandingans/'.$pertandingan->id.'/print') }}?kegiatan_id={{ $current_kegiatan_id }}" target="_blank" class="btn-kempo-4 inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold uppercase tracking-wider shadow-xs hover:opacity-90 transition-all cursor-pointer">
                                <i class="fa fa-print"></i> Print Nomor Tanding
                            </a>
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
            @if(!$kolom1->isEmpty())
                @if($pertandingan->pertandingan->bagan == 1)
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/pertandingan/'.$pertandingan->id.'?kegiatan_id='.$current_kegiatan_id.'&print=1') }}" target="_blank" class="btn-kempo-4 inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold uppercase tracking-wider shadow-xs hover:opacity-90 transition-all">
                            <i class="fa fa-sitemap"></i> Bagan Pertandingan
                        </a>
                    </div>
                @endif

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
                            </div>
                        </div>

                        <div class="kempo-table-wrapper">
                            <table class="kempo-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="w-32 text-center">Babak &amp; Partai</th>
                                        <th rowspan="2" class="w-36">Sudut</th>
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
                                    @forelse ($peserta_lawan as $a)
                                        @php
                                            $isReady = $a->pertandingan_peserta_kiri_id && $a->pertandingan_peserta_kanan_id;
                                            $isBye = ($a->pertandingan_peserta_kanan_id === null && $a->keterangan_menang === 'BYE');
                                            
                                            $namaBabak = 'Babak ' . $a->babak;
                                            if ($a->babak == $totalBabakKategori) {
                                                $namaBabak = 'Final';
                                            } elseif ($totalBabakKategori >= 2 && $a->babak == $totalBabakKategori - 1) {
                                                $namaBabak = 'Semifinal';
                                            } elseif ($totalBabakKategori >= 3 && $a->babak == $totalBabakKategori - 2) {
                                                $namaBabak = 'Perempatfinal';
                                            }

                                            $kiriMenang = ($a->pemenang_id && $a->pemenang_id == $a->pertandingan_peserta_kiri_id);
                                            $kananMenang = ($a->pemenang_id && $a->pemenang_id == $a->pertandingan_peserta_kanan_id);
                                        @endphp

                                        <!-- Tim Merah (Sudut Merah) -->
                                        <tr class="bg-red-50/30 hover:bg-red-50/60 border-b border-input/60 duel-match-row" data-duel-babak="{{ $a->babak }}">
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

                                            <!-- Kontingen -->
                                            <td class="font-semibold text-xs text-foreground">
                                                {{ $a->PertandinganPesertaPertama->Peserta->kabupaten->name ?? ($a->PertandinganPesertaPertama->Peserta->kontigen ?? ($a->pertandingan_peserta_kiri_id ? 'Tanpa Kontingen' : 'Belum Ada')) }}
                                            </td>

                                            <!-- Nama Atlet -->
                                            <td class="font-medium text-foreground">
                                                @if($a->PertandinganPesertaPertama)
                                                    <span class="font-bold text-sm {{ $kiriMenang ? 'text-emerald-700' : '' }}">
                                                        {{ $a->PertandinganPesertaPertama->Peserta->nama ?? 'Atlet' }}
                                                    </span>
                                                    @if($kiriMenang)
                                                        <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                            ✓ MENANG
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-neutral-400 italic text-xs">Menunggu Pemenang Babak {{ $a->babak - 1 }}</span>
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
                                                            <i class="fas fa-trophy text-amber-500"></i> {{ $kiriMenang ? 'Merah Menang' : 'Biru Menang' }}
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
                                                            kanan_nama: '{{ addslashes($a->PertandinganPesertaKedua->Peserta->nama ?? 'Atlet Putih/Biru') }}',
                                                            kanan_kontingen: '{{ addslashes($a->PertandinganPesertaKedua->Peserta->kabupaten->name ?? ($a->PertandinganPesertaKedua->Peserta->kontigen ?? '-')) }}',
                                                            skor_kanan: {{ $a->skor_kanan !== null ? $a->skor_kanan : 'null' }},
                                                            pemenang_id: {{ $a->pemenang_id ?? 'null' }},
                                                            keterangan_menang: '{{ $a->keterangan_menang ?? 'POIN' }}'
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

                                        <!-- Tim Biru/Putih (Sudut Biru) -->
                                        <tr class="bg-blue-50/30 hover:bg-blue-50/60 border-b-2 border-input duel-match-row" data-duel-babak="{{ $a->babak }}">
                                            <!-- Sudut -->
                                            <td class="font-bold text-xs uppercase tracking-wide text-btn-4">
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> [Putih/Biru]
                                                </span>
                                            </td>

                                            <!-- Kontingen -->
                                            <td class="font-semibold text-xs text-foreground">
                                                {{ $a->PertandinganPesertaKedua->Peserta->kabupaten->name ?? ($a->PertandinganPesertaKedua->Peserta->kontigen ?? ($a->pertandingan_peserta_kanan_id ? 'Tanpa Kontingen' : 'BYE')) }}
                                            </td>

                                            <!-- Nama Atlet -->
                                            <td class="font-medium text-foreground">
                                                @if($a->PertandinganPesertaKedua)
                                                    <span class="font-bold text-sm {{ $kananMenang ? 'text-emerald-700' : '' }}">
                                                        {{ $a->PertandinganPesertaKedua->Peserta->nama ?? 'Atlet' }}
                                                    </span>
                                                    @if($kananMenang)
                                                        <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                            ✓ MENANG
                                                        </span>
                                                    @endif
                                                @elseif($isBye)
                                                    <span class="text-neutral-400 italic text-xs">BYE (Slot Kosong)</span>
                                                @else
                                                    <span class="text-neutral-400 italic text-xs">Menunggu Pemenang Babak {{ $a->babak - 1 }}</span>
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

                                            <!-- Skor Putih/Biru -->
                                            <td class="text-center font-black text-base text-blue-600 tabular-nums">
                                                {{ $a->skor_kanan !== null ? $a->skor_kanan : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="py-8 text-center text-paragraph">Tidak ada partai duel pada babak ini atau belum ada bagan tanding yang diacak.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

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
                                            <span class="inline-block px-2.5 py-1 rounded bg-neutral-100 text-neutral-800">
                                                {{ $item->undian + 1 }}
                                            </span>
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
                                                @if(isset($item->kabupaten))
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
                                                    @if($kabupatenId)
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

                                    <!-- Tim Biru (Sudut Biru) -->
                                    <tr class="bg-blue-50/40 hover:bg-blue-50/70 border-b-2 border-input">
                                        <td class="text-center font-bold text-btn-4 tabular-nums">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-btn-4 text-xs">
                                                {{ @$a->PertandinganPesertaKedua->urutan ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="font-bold text-foreground">
                                            <span class="text-btn-4 font-bold text-xs uppercase mr-1">[Biru]</span>
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
@endsection
