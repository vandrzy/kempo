@extends('tamplate')
@section('title', 'Official')
@section('content')
<style>
    .check-hidden:not(:checked) {
    display: none;
    }

    .big-checkbox {
    width: 100px;
    height: 30px;
    accent-color: green;
    }

    .big-checkbox:disabled {
        accent-color: green; /* tetap hijau meski disabled */
    }
</style>
    <div class="w-full min-w-0 max-w-full">
        <div class="kempo-card">
            <!-- Header Kartu & Aksi Tambah -->
            <div class="px-6 py-5 border-b border-[#ebebeb] bg-white flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Data Official</h4>
                        <p class="text-xs text-neutral-400 m-0 mt-0.5">Kelola data manajer, pelatih, dan official tiap kontingen</p>
                    </div>
                    <div>
                        <!-- Tombol Tambah Data (Button 1 Primary Action) -->
                        <a href="{{ url('official/create?kegiatan_id='.@$kegiatan_get->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:shadow-sm hover:-translate-y-px transition-all">
                            <i class="fas fa-plus text-[10px]"></i>
                            <span>Tambah Data</span>
                        </a>
                    </div>
                </div>

                <!-- Baris Filter & Pencarian -->
                <div class="pt-3 border-t border-[#ebebeb]/60 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                    <form action="{{ url('official') }}" method="get" id="filterFormOfficial" class="flex flex-wrap items-center gap-2.5 m-0 flex-1">
                        <!-- Select Kegiatan -->
                        <select name="kegiatan_id" id="kegiatan_id" onchange="HTMLFormElement.prototype.submit.call(this.form);"
                            class="px-3.5 py-2 text-xs rounded-xl bg-white border border-[#ebebeb] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs text-[#000000] font-medium min-w-[200px]">
                            @foreach ($kegiatan as $a)
                                <option value="{{ $a->id }}" {{ Request('kegiatan_id') == $a->id ? 'selected' : (@$kegiatan_get->id == $a->id ? 'selected' : '') }}>
                                    {{ $a->nama }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Input Cari Nama -->
                        <input type="text" name="nama" value="{{ Request('nama') }}" placeholder="Cari Nama Official..."
                            class="px-3.5 py-2 text-xs rounded-xl bg-white border border-[#ebebeb] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs text-[#000000] placeholder:text-neutral-400 min-w-[180px]">

                        <!-- Tombol Cari (Button 1 Primary) -->
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:-translate-y-px transition-all cursor-pointer">
                            <i class="fas fa-search text-[10px]"></i>
                            <span>Cari</span>
                        </button>

                        <!-- Tombol Export (Button 4 Info/Utility) -->
                        <button type="submit" name="action" value="export"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-[#3276e4] hover:bg-[#2862c2] shadow-xs hover:-translate-y-px transition-all cursor-pointer">
                            <i class="fas fa-file-excel text-[10px]"></i>
                            <span>Export Excel</span>
                        </button>
                    </form>

                    <!-- Tombol Hapus Duplikat (Destructive Outline) -->
                    <form action="{{ route('official.hapus-duplikat') }}" method="post" class="m-0 inline shrink-0" onsubmit="return confirm('Apakah Anda yakin ingin membersihkan data official duplikat?');">
                        @csrf
                        <input type="hidden" name="kegiatan_id" value="{{ Request('kegiatan_id') ?? @$kegiatan_get->id }}">
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-[#e54b4f] bg-rose-50 hover:bg-[#e54b4f] hover:text-white border border-[#e54b4f]/30 shadow-2xs hover:-translate-y-px transition-all cursor-pointer">
                            <i class="fas fa-user-times text-[10px]"></i>
                            <span>Hapus Duplikat</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tabel Data Official -->
            <div class="kempo-table-wrapper kempo-scrollbar">
                <table class="kempo-table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center w-14">No</th>
                            <th rowspan="2" class="text-left">Kontingen</th>
                            <th rowspan="2" class="text-left">Nama Lengkap</th>
                            <th rowspan="2" class="text-left">NIK</th>
                            <th rowspan="2" class="text-center">Usia</th>
                            <th colspan="2" class="text-center border-b border-[#ebebeb]">Jenis Kelamin</th>
                            <th colspan="2" class="text-center border-b border-[#ebebeb]">Tingkat</th>
                            <th colspan="2" class="text-center border-b border-[#ebebeb]">Status</th>
                            <th rowspan="2" class="text-center w-36">Aksi</th>
                        </tr>
                        <tr>
                            <th class="text-center w-10">L</th>
                            <th class="text-center w-10">P</th>
                            <th class="text-center w-10">K</th>
                            <th class="text-center w-10">D</th>
                            <th class="text-center w-10">P</th>
                            <th class="text-center w-10">O</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peserta as $a)
                            <tr>
                                <td class="text-center tabular-nums text-xs font-semibold text-neutral-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="text-left text-neutral-700 text-xs sm:text-sm">
                                    {{ $a->kabupaten->name ?? '-' }}
                                </td>
                                <td class="text-left font-semibold text-[#000000]">
                                    {{ $a->nama }}
                                </td>
                                <td class="text-left tabular-nums text-neutral-600 text-xs sm:text-sm">
                                    {{ $a->nik }}
                                </td>
                                <td class="text-center tabular-nums text-neutral-700 text-xs sm:text-sm">
                                    {{ (Date('Y')) - (Date('Y', strtotime($a->tanggal_lahir))) }} Thn
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->jenis_kelamin, 0, 1)) == "L")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-bold" title="Laki-laki">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->jenis_kelamin, 0, 1)) == "P")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-bold" title="Perempuan">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->tingkat, 0, 1)) == "K" || strtoupper(substr($a->tingkatan_sabuk, 0, 1)) == "K")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-bold" title="KYU">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->tingkat, 0, 1)) == "D" || strtoupper(substr($a->tingkatan_sabuk, 0, 1)) == "D")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-bold" title="DAN">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->status, 0, 1)) == "P")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-bold" title="Peserta">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->status, 0, 1)) == "O")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-bold" title="Official">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Detail (Button 4 Info/Utility) -->
                                        <a href="{{ url('official/'.$a->id) }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#3276e4] hover:bg-[#2862c2] shadow-2xs hover:-translate-y-px transition-all"
                                            title="Detail Official">
                                            <i class="fas fa-eye text-[10px]"></i>
                                            <span>Detail</span>
                                        </a>

                                        <!-- Tombol Edit (Button 3 Warning/Action) -->
                                        <a href="{{ url('official/'.$a->id.'/edit') }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#fd822b] hover:bg-[#e06e1f] shadow-2xs hover:-translate-y-px transition-all"
                                            title="Edit Official">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            <span>Edit</span>
                                        </a>

                                        <!-- Tombol Hapus (Destructive) -->
                                        <form action="{{ url('official/'.$a->id) }}" method="post" class="delete-form m-0 inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#e54b4f] hover:bg-[#c93b3f] shadow-2xs hover:-translate-y-px transition-all cursor-pointer"
                                                title="Hapus Official">
                                                <i class="fas fa-trash text-[10px]"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-8 text-neutral-400 text-sm">
                                    <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                    Tidak ada data official ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-[#ebebeb] bg-[#fdfdfd] flex justify-center">
                {{ $peserta->withQueryString()->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>



@endsection

@section('script')
    window.copyText = function(texts) {
        let text = texts;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                alert("Teks berhasil disalin: " + text);
            });
        } else {
            // fallback execCommand
            let $temp = $("<textarea>");
            $("body").append($temp);
            $temp.val(text).select();
            document.execCommand("copy");
            $temp.remove();
            alert("Url berhasil disalin!");
        }
    };

    $('#kegiatan_id').on('change', function() {
        const form = document.getElementById('filterFormOfficial');
        if (form) HTMLFormElement.prototype.submit.call(form);
    });
@endsection

