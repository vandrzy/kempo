@extends('tamplate')
@section('title', 'Data Kontingen')
@section('content')
    <div class="w-full min-w-0 max-w-full">
        <div class="kempo-card">
            <!-- Header Kartu & Filter Kegiatan -->
            <div class="px-6 py-5 border-b border-[#ebebeb] bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Data Kontingen</h4>
                    <p class="text-xs text-neutral-400 m-0 mt-0.5">Rekapitulasi kontingen kabupaten/kota peserta kejuaraan</p>
                </div>
                <div>
                    <form action="" method="get" class="m-0">
                        <select name="kegiatan_id" onchange="this.form.submit()"
                            class="px-3.5 py-2 text-xs rounded-xl bg-white border border-[#ebebeb] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs text-[#000000] font-medium"
                            id="kegiatan_id">
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach ($kegiatan as $a)
                                <option value="{{ $a->id }}" {{ Request('kegiatan_id') == $a->id ? 'selected' : (@$kegiatan_get->id == $a->id ? 'selected' : '') }}>
                                    {{ $a->nama }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <!-- Area Tabel Data -->
            @if (isset($_GET['kegiatan_id']) && $_GET['kegiatan_id'] != "")
                <div class="kempo-table-wrapper kempo-scrollbar">
                    <table class="kempo-table">
                        <thead>
                            <tr>
                                <th class="text-center w-14">No</th>
                                <th class="text-left">Kontingen / Kabupaten</th>
                                <th class="text-center">Peserta Terdaftar</th>
                                <th class="text-center">Official Terdaftar</th>
                                <th class="text-center w-64">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kabupatens as $a)
                                <tr>
                                    <td class="text-center tabular-nums text-xs font-semibold text-neutral-500">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="text-left font-semibold text-[#000000]">
                                        {{ $a->name }}
                                    </td>
                                    <td class="text-center tabular-nums">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-neutral-100 text-neutral-800 border border-neutral-200">
                                            <i class="fas fa-user-ninja mr-1.5 text-neutral-500 text-[10px]"></i>
                                            {{ $a->peserta->count() }} Peserta
                                        </span>
                                    </td>
                                    <td class="text-center tabular-nums">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-neutral-100 text-neutral-800 border border-neutral-200">
                                            <i class="fas fa-user-tie mr-1.5 text-neutral-500 text-[10px]"></i>
                                            {{ $a->official->count() }} Official
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($a->peserta->count() > 0)
                                            <div class="flex items-center justify-center gap-1.5">
                                                <!-- Print Peserta (Button 4 Info/Utility) -->
                                                <a href="{{ url('kontingen/'.$a->id.'/edit'.'?kegiatan_id='.($_GET['kegiatan_id'] ?? 0).'&print=peserta') }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#3276e4] hover:bg-[#2862c2] shadow-2xs hover:-translate-y-px transition-all"
                                                    title="Cetak Berkas Peserta">
                                                    <i class="fas fa-print text-[10px]"></i>
                                                    <span>Print Peserta</span>
                                                </a>

                                                <!-- Print Official (Button 4 Info/Utility) -->
                                                <a href="{{ url('kontingen/'.$a->id.'/edit'.'?kegiatan_id='.($_GET['kegiatan_id'] ?? 0).'&print=official') }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-sky-700 bg-sky-50 border border-sky-200 hover:bg-sky-600 hover:text-white shadow-2xs hover:-translate-y-px transition-all"
                                                    title="Cetak Berkas Official">
                                                    <i class="fas fa-print text-[10px]"></i>
                                                    <span>Print Official</span>
                                                </a>

                                                <!-- Detail (Button 1 Primary Action) -->
                                                <a href="{{ url('kontingen/'.$a->id.'?kegiatan_id='.($_GET['kegiatan_id'] ?? 0)."&status=peserta") }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-2xs hover:-translate-y-px transition-all"
                                                    title="Lihat Detail Peserta & Official Kontingen">
                                                    <i class="fas fa-eye text-[10px]"></i>
                                                    <span>Detail</span>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-xs text-neutral-400 italic">Belum ada peserta</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-neutral-400 text-sm">
                                        <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                        Tidak ada data kontingen ditemukan untuk kegiatan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center text-neutral-400">
                    <i class="fas fa-flag text-3xl mb-3 text-neutral-300 block"></i>
                    <p class="font-heading font-semibold text-base text-[#000000] m-0">Pilih Kegiatan Terlebih Dahulu</p>
                    <p class="text-xs text-neutral-400 mt-1 m-0">Gunakan dropdown filter di pojok kanan atas untuk memuat data kontingen.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
