@extends('tamplate')
@section('title', 'Data Tanding')
@section('content')
    <div class="w-full min-w-0 max-w-full">
        <div class="kempo-card">
            <!-- Header Kartu & Aksi Tambah -->
            <div class="px-6 py-5 border-b border-[#ebebeb] bg-white flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Data Tanding</h4>
                        <p class="text-xs text-neutral-400 m-0 mt-0.5">Daftar kelas dan nomor pertandingan yang tersedia</p>
                    </div>
                    <div>
                        <a href="{{ url('pertandingan/create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:shadow-sm hover:-translate-y-px transition-all">
                            <i class="fas fa-plus text-[10px]"></i>
                            <span>Tambah Data</span>
                        </a>
                    </div>
                </div>

                <!-- Baris Filter Kategori Tanding & Pencarian -->
                <div class="pt-3 border-t border-[#ebebeb]/60 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                    <form action="{{ url('pertandingan') }}" method="get" id="filterFormPertandingan" class="flex flex-wrap items-center gap-2.5 m-0 flex-1">
                        <!-- Dropdown Kategori Tanding -->
                        <div class="flex items-center gap-2">
                            <label for="kategori_id" class="text-xs font-bold text-[#000000] whitespace-nowrap">
                                <i class="fas fa-filter text-[#f2470c] text-[10px] mr-1"></i>Kategori:
                            </label>
                            <select name="kategori_id" id="kategori_id" onchange="HTMLFormElement.prototype.submit.call(this.form);"
                                class="px-3.5 py-2 text-xs rounded-xl bg-white border border-[#ebebeb] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs text-[#000000] font-medium min-w-[220px]">
                                <option value="">Semua Kategori Tanding</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}" {{ Request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Input Pencarian Nama / Kelas -->
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <input type="text" name="cari" value="{{ Request('cari') }}" placeholder="Cari nomor tanding / kelas..."
                                class="px-3.5 py-2 text-xs rounded-xl bg-white border border-[#ebebeb] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs text-[#000000] placeholder:text-neutral-400 min-w-[200px] flex-1 sm:flex-none">

                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:-translate-y-px transition-all cursor-pointer">
                                <i class="fas fa-search text-[10px]"></i>
                                <span>Cari</span>
                            </button>

                            @if(Request('kategori_id') || Request('cari'))
                                <a href="{{ url('pertandingan') }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-neutral-600 bg-neutral-100 hover:bg-neutral-200 transition-all"
                                    title="Reset Filter">
                                    <i class="fas fa-undo text-[10px]"></i>
                                    <span>Reset</span>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="kempo-table-wrapper kempo-scrollbar">
                <table class="kempo-table">
                    <thead>
                        <tr>
                            <th class="w-16 text-center tabular-nums">No</th>
                            <th>Nama Pertandingan</th>
                            <th class="text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $current_kategori_id = null;
                            $current_gender = null;
                        @endphp
                        @forelse ($pertandingan as $a)
                            @if ($current_kategori_id !== $a->kategori_id)
                                @php
                                    $current_kategori_id = $a->kategori_id;
                                    $current_gender = null;
                                @endphp
                                <tr class="bg-neutral-100/90 border-t border-b border-[#ebebeb]">
                                    <td colspan="3" class="py-2.5 px-5 font-heading font-bold text-xs uppercase tracking-wide text-[#000000]">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-[#f2470c] inline-block"></span>
                                            <span>Kategori: {{ $a->kategori->nama ?? 'Tidak Ada Kategori' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            @if ($current_gender !== $a->jenis_kelamin)
                                @php
                                    $current_gender = $a->jenis_kelamin;
                                @endphp
                                <tr class="bg-[#fff9f6] border-b border-[#ebebeb]/60">
                                    <td colspan="3" class="py-2 px-8 text-xs font-semibold text-neutral-700">
                                        &bull; Jenis Tanding: <span class="text-[#f2470c] font-bold">{{ $current_gender ?? 'Campuran' }}</span>
                                    </td>
                                </tr>
                            @endif

                            <tr class="hover:bg-neutral-50/80 transition-colors">
                                <td class="text-center font-semibold text-neutral-500 tabular-nums text-xs">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    <div class="font-bold text-[#000000] text-xs sm:text-sm">
                                        {{ $a->nama }}
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                                        @if($a->kelas)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-neutral-100 text-neutral-700 text-[11px] font-medium">
                                                <i class="fas fa-tag text-[9px] mr-1 text-neutral-400"></i>Kelas: {{ $a->kelas }}
                                            </span>
                                        @endif
                                        @if($a->bagan == 1)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[11px] font-medium border border-emerald-200">
                                                <i class="fas fa-sitemap text-[9px] mr-1"></i>Bagan Undian
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-neutral-50 text-neutral-500 text-[11px] font-medium border border-neutral-200">
                                                Non-Bagan
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Edit -->
                                        <a href="{{ url('pertandingan/'.$a->id.'/edit') }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#fd822b] hover:bg-[#e06e1f] shadow-2xs hover:-translate-y-px transition-all"
                                            title="Edit Pertandingan">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            <span>Edit</span>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ url('pertandingan/'.$a->id) }}" method="post" class="delete-form m-0 inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#e54b4f] hover:bg-[#c93b3f] shadow-2xs hover:-translate-y-px transition-all cursor-pointer"
                                                title="Hapus Pertandingan">
                                                <i class="fas fa-trash text-[10px]"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-10 text-neutral-400 text-xs sm:text-sm">
                                    <i class="fas fa-inbox text-3xl mb-2 block text-neutral-300"></i>
                                    Tidak ada data pertandingan ditemukan{{ Request('kategori_id') ? ' untuk kategori ini' : '' }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-[#ebebeb] bg-[#fdfdfd] flex justify-center">
                {{ $pertandingan->withQueryString()->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection
