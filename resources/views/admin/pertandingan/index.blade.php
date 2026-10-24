@extends('tamplate')
@section('title', 'Tanding')
@section('content')
    <div class="kempo-card mb-6">
        <div class="p-6 border-b border-input flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-heading font-bold text-foreground">Data Tanding</h2>
                <p class="text-sm text-paragraph mt-1">Daftar kelas dan nomor pertandingan yang tersedia</p>
            </div>
            <div>
                <a href="{{ url('pertandingan/create') }}" class="btn-kempo-1 inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold uppercase tracking-wider shadow-sm hover:opacity-95 transition-all">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
            </div>
        </div>

        <div class="kempo-table-wrapper">
            <table class="kempo-table">
                <thead>
                    <tr>
                        <th class="w-16 text-center tabular-nums">No</th>
                        <th>Nama Pertandingan</th>
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
                                <td colspan="3" class="py-2.5 px-4 font-heading font-bold text-xs uppercase tracking-wide text-foreground">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-primary inline-block"></span>
                                        <span>Kategori: {{ $a->kategori->nama ?? "Tidak Ada Kategori" }}</span>
                                    </div>
                                </td>
                            </tr>
                            @if ($a->jenis_kelamin != $jenis_kelamin)
                                @php
                                    $jenis_kelamin = $a->jenis_kelamin;
                                @endphp
                                <tr class="bg-neutral-50 hover:bg-neutral-50/90 border-b border-input">
                                    <td colspan="3" class="py-2 px-6 text-xs font-semibold text-neutral-600">
                                        &bull; Jenis Kelamin: <span class="text-foreground">{{ $jenis_kelamin ?? "Campuran" }}</span>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-center font-semibold text-neutral-500 tabular-nums">{{ $loop->iteration }}</td>
                                <td class="font-medium text-foreground">{{ $a->nama }}</td>
                                <td class="text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ url('pertandingan/'.$a->id.'/edit') }}" class="btn-kempo-3 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                        <form action="{{ url('pertandingan/'.$a->id) }}" method="post" class="delete-form inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-destructive hover:bg-red-700 rounded-2xl shadow-xs transition-all cursor-pointer">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @if ($a->jenis_kelamin != $jenis_kelamin)
                                @php
                                    $jenis_kelamin = $a->jenis_kelamin;
                                @endphp
                                <tr class="bg-neutral-50 hover:bg-neutral-50/90 border-b border-input">
                                    <td colspan="3" class="py-2 px-6 text-xs font-semibold text-neutral-600">
                                        &bull; Jenis Kelamin: <span class="text-foreground">{{ $jenis_kelamin ?? "Campuran" }}</span>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-center font-semibold text-neutral-500 tabular-nums">{{ $loop->iteration }}</td>
                                <td class="font-medium text-foreground">{{ $a->nama }}</td>
                                <td class="text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ url('pertandingan/'.$a->id.'/edit') }}" class="btn-kempo-3 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                        <form action="{{ url('pertandingan/'.$a->id) }}" method="post" class="delete-form inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-destructive hover:bg-red-700 rounded-2xl shadow-xs transition-all cursor-pointer">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-paragraph">Belum ada data pertandingan.</td>
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
