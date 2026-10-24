@extends('tamplate')
@section('title', 'Kategori')
@section('content')
    <div class="w-full min-w-0 max-w-full">
        <div class="kempo-card">
            <!-- Header Kartu -->
            <div class="px-6 py-4 border-b border-[#ebebeb] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white">
                <div>
                    <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Data Kategori</h4>
                    <p class="text-xs text-neutral-400 m-0 mt-0.5">Kelola kategori usia dan tahun kelahiran peserta pertandingan</p>
                </div>
                <div>
                    <a href="{{ url('kategori/create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:shadow-sm hover:-translate-y-px transition-all">
                        <i class="fas fa-plus text-[10px]"></i>
                        <span>Tambah Data</span>
                    </a>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="kempo-table-wrapper kempo-scrollbar">
                <table class="kempo-table">
                    <thead>
                        <tr>
                            <th class="text-center w-14">No</th>
                            <th class="text-left">Nama Kategori</th>
                            <th class="text-center">Rentang Usia</th>
                            <th class="text-center">Rentang Tahun Lahir</th>
                            <th class="text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategori as $a)
                            <tr>
                                <td class="text-center tabular-nums text-xs font-semibold text-neutral-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="text-left font-semibold text-[#000000]">
                                    {{ $a->nama }}
                                </td>
                                <td class="text-center tabular-nums text-neutral-700 text-xs sm:text-sm font-medium">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-neutral-100 text-neutral-800 text-xs">
                                        {{ $a->usia_awal }} - {{ $a->usia_akhir }} Tahun
                                    </span>
                                </td>
                                <td class="text-center tabular-nums text-neutral-700 text-xs sm:text-sm font-medium">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-neutral-100 text-neutral-800 text-xs">
                                        {{ $a->tahun_awal }} - {{ $a->tahun_akhir }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Edit (Button 3 Warning/Action) -->
                                        <a href="{{ url('kategori/'.$a->id.'/edit') }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#fd822b] hover:bg-[#e06e1f] shadow-2xs hover:-translate-y-px transition-all"
                                            title="Edit Kategori">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            <span>Edit</span>
                                        </a>

                                        <!-- Tombol Hapus (Destructive) -->
                                        <form action="{{ url('kategori/'.$a->id) }}" method="post" class="delete-form m-0 inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#e54b4f] hover:bg-[#c93b3f] shadow-2xs hover:-translate-y-px transition-all cursor-pointer"
                                                title="Hapus Kategori">
                                                <i class="fas fa-trash text-[10px]"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-neutral-400 text-sm">
                                    <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                    Tidak ada data kategori ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-[#ebebeb] bg-[#fdfdfd] flex justify-center">
                {{ $kategori->withQueryString()->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection
