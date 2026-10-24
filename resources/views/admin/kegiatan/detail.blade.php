@extends('tamplate')
@section('title', 'Pertandingan Kegiatan')
@section('content')
    <div class="kempo-card mb-6">
        <div class="p-6 border-b border-input flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ url('kegiatan') }}" class="btn-kempo-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                    <h2 class="text-xl font-heading font-bold text-foreground mb-0">{{ $kegiatan->nama }}</h2>
                </div>
                <p class="text-sm text-paragraph mt-1.5">Daftar kelas pertandingan dan jumlah peserta yang terdaftar pada kegiatan ini</p>
            </div>

            <!-- Filter & Search Bar -->
            <form action="" method="get" class="flex flex-wrap items-center gap-2">
                <select name="kategori_id" class="px-3 py-2 text-xs font-medium border border-input rounded-xl bg-white text-foreground focus:outline-none focus:border-primary" id="kategori_id">
                    @foreach ($kategori as $a)
                        <option value="{{ $a->kategori->id }}" {{ Request('kategori_id') == $a->kategori->id ? 'selected' : (@$kegiatan_get->id == $a->kategori->id ? 'selected' : '') }}>
                            {{ $a->kategori->nama }}
                        </option>
                    @endforeach
                </select>

                <div class="relative">
                    <input type="text" name="cari" value="{{ Request('cari') }}"
                        class="px-3 py-2 text-xs font-medium border border-input rounded-xl bg-white text-foreground focus:outline-none focus:border-primary placeholder:text-neutral-400"
                        placeholder="Cari pertandingan...">
                </div>

                <button type="submit" class="btn-kempo-4 inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold uppercase tracking-wider shadow-xs hover:opacity-90 transition-all cursor-pointer">
                    <i class="fa fa-search"></i> Cari
                </button>
            </form>
        </div>

        <div class="kempo-table-wrapper">
            <table class="kempo-table">
                <thead>
                    <tr>
                        <th class="w-16 text-center tabular-nums">No</th>
                        <th>Nama Pertandingan</th>
                        <th class="text-center w-56">Jumlah Peserta</th>
                        <th class="text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_gender_group = 0;
                        $kategori_id = 0;
                        $jenis_kelamin = "-";
                    @endphp
                    @forelse ($pertandingan as $a)
                        @if ($kategori_id != $a->kategori_id)
                            @php
                                $kategori_id = $a->kategori_id;
                            @endphp
                            <tr class="bg-neutral-100/80 hover:bg-neutral-100 border-t border-b border-input">
                                <td colspan="4" class="py-2.5 px-4 font-heading font-bold text-xs uppercase tracking-wide text-foreground">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-primary inline-block"></span>
                                        <span>Kategori: {{ $a->kategori->nama }}</span>
                                    </div>
                                </td>
                            </tr>
                            @if ($a->pertandingan->jenis_kelamin != $jenis_kelamin)
                                @php
                                    $jenis_kelamin = $a->pertandingan->jenis_kelamin;
                                @endphp
                                <tr class="bg-neutral-50 hover:bg-neutral-50/90 border-b border-input">
                                    <td colspan="4" class="py-2 px-6 text-xs font-semibold text-neutral-600">
                                        &bull; Jenis Kelamin: <span class="text-foreground">{{ $jenis_kelamin ?? "Campuran" }}</span>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-center font-semibold text-neutral-500 tabular-nums">{{ $loop->iteration }}</td>
                                <td class="font-medium text-foreground">{{ $a->pertandingan->nama }}</td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg bg-neutral-100 text-neutral-700 tabular-nums">
                                        <i class="fa fa-users text-neutral-500"></i> {{ $a->list_kategori->count() }} Peserta
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('pertandingan/'.$a->id) }}?kegiatan_id={{ $kegiatan->id }}" class="btn-kempo-4 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                                        <i class="fa fa-list-ol"></i> Nomor Tanding
                                    </a>
                                </td>
                            </tr>
                        @else
                            @if ($a->pertandingan->jenis_kelamin != $jenis_kelamin)
                                @php
                                    $jenis_kelamin = $a->pertandingan->jenis_kelamin;
                                @endphp
                                <tr class="bg-neutral-50 hover:bg-neutral-50/90 border-b border-input">
                                    <td colspan="4" class="py-2 px-6 text-xs font-semibold text-neutral-600">
                                        &bull; Jenis Kelamin: <span class="text-foreground">{{ $jenis_kelamin ?? 'Campuran' }}</span>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-center font-semibold text-neutral-500 tabular-nums">{{ $loop->iteration }}</td>
                                <td class="font-medium text-foreground">{{ $a->pertandingan->nama }}</td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg bg-neutral-100 text-neutral-700 tabular-nums">
                                        <i class="fa fa-users text-neutral-500"></i> {{ $a->list_kategori->count() }} Peserta
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('pertandingan/'.$a->id) }}?kegiatan_id={{ $kegiatan->id }}" class="btn-kempo-4 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                                        <i class="fa fa-list-ol"></i> Nomor Tanding
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-paragraph">Belum ada pertandingan pada kegiatan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-input flex justify-center">
            {{ $pertandingan->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
