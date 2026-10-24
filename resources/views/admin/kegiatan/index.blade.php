@extends('tamplate')
@section('title', 'Kegiatan')
@section('content')
    <div class="w-full min-w-0 max-w-full">
        <div class="kempo-card">
            <!-- Header Kartu & Filter -->
            <div class="px-6 py-5 border-b border-[#ebebeb] bg-white flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Data Kegiatan</h4>
                        <p class="text-xs text-neutral-400 m-0 mt-0.5">Kelola daftar seluruh kejuaraan dan kegiatan Shorinji Kempo</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2 border-t border-[#ebebeb]/60">
                    <div>
                        @if(in_array(Auth::user()->role, ['admin', 'operator', 1, '1', 2, '2']))
                            <a href="{{ url('kegiatan/create') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:shadow-sm hover:-translate-y-px transition-all">
                                <i class="fas fa-plus text-[10px]"></i>
                                <span>Tambah Data</span>
                            </a>
                        @endif
                    </div>
                    <form action="" method="get" class="flex items-center gap-2 m-0">
                        <input type="text" name="cari" value="{{ Request('cari') }}"
                            class="px-3.5 py-2 text-xs rounded-xl bg-white border border-[#ebebeb] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs text-[#000000] placeholder:text-neutral-400"
                            placeholder="Cari nama kegiatan...">
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold rounded-xl text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:-translate-y-px transition-all cursor-pointer">
                            <i class="fas fa-search mr-1 text-[10px]"></i> Cari
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="kempo-table-wrapper kempo-scrollbar">
                <table class="kempo-table">
                    <thead>
                        <tr>
                            <th class="text-center w-14">No</th>
                            <th class="text-left">Nama Kegiatan</th>
                            <th class="text-center">Peserta Terdaftar</th>
                            <th class="text-center">Aksi & Tautan Khusus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatan as $a)
                            <tr>
                                <td class="text-center tabular-nums text-xs font-semibold text-neutral-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="text-left font-semibold text-[#000000]">
                                    {{ $a->nama }}
                                </td>
                                <td class="text-center tabular-nums">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-neutral-100 text-neutral-800 border border-neutral-200">
                                        <i class="fas fa-users mr-1.5 text-neutral-500 text-[10px]"></i>
                                        {{ $a->peserta_terdaftar->count() }} Peserta
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex flex-wrap items-center justify-center gap-1.5 py-1">
                                        <!-- Tautan Salin Link -->
                                        <button type="button" onclick="copyLink('{{ url('pendaftaran/'.base64_encode($a->nama).'-'.base64_encode($a->id).'/daftar') }}', 'Pendaftaran Peserta', '{{ addslashes($a->nama) }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#fff0eb] text-[#f2470c] hover:bg-[#f2470c] hover:text-white border border-[#f2470c]/25 shadow-2xs hover:-translate-y-px transition-all cursor-pointer"
                                            title="Salin Link Pendaftaran Peserta">
                                            <i class="fas fa-copy text-[11px]"></i>
                                            <span>Link Peserta</span>
                                        </button>

                                        <button type="button" onclick="copyLink('{{ url('official/'.base64_encode($a->nama).'-'.base64_encode($a->id).'/daftar') }}', 'Pendaftaran Official', '{{ addslashes($a->nama) }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-50 text-[#3276e4] hover:bg-[#3276e4] hover:text-white border border-[#3276e4]/25 shadow-2xs hover:-translate-y-px transition-all cursor-pointer"
                                            title="Salin Link Pendaftaran Official">
                                            <i class="fas fa-copy text-[11px]"></i>
                                            <span>Link Official</span>
                                        </button>

                                        <button type="button" onclick="copyLink('{{ url('teknik/'.base64_encode($a->nama).'-'.base64_encode($a->id)) }}', 'Input Teknik', '{{ addslashes($a->nama) }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white border border-amber-200 shadow-2xs hover:-translate-y-px transition-all cursor-pointer"
                                            title="Salin Link Input Teknik">
                                            <i class="fas fa-copy text-[11px]"></i>
                                            <span>Link Teknik</span>
                                        </button>

                                        <!-- Tombol Detail -->
                                        <a href="{{ url('kegiatan/'.$a->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#3276e4] hover:bg-[#2862c2] shadow-2xs hover:-translate-y-px transition-all"
                                            title="Detail Kegiatan">
                                            <i class="fas fa-eye text-[10px]"></i> Detail
                                        </a>

                                        <!-- Tombol Papan Skor -->
                                        <a href="{{ route('kegiatan.papan-skor', $a->id) }}" target="_blank"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-sky-700 bg-sky-50 border border-sky-200 hover:bg-sky-600 hover:text-white shadow-2xs hover:-translate-y-px transition-all"
                                            title="Buka Papan Skor">
                                            <i class="fas fa-tv text-[10px]"></i> Papan Skor
                                        </a>

                                        @if(in_array(Auth::user()->role, ['admin', 'operator', 1, '1', 2, '2']))
                                            <!-- Tombol Edit (Button 3) -->
                                            <a href="{{ url('kegiatan/'.$a->id.'/edit') }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#fd822b] hover:bg-[#e06e1f] shadow-2xs hover:-translate-y-px transition-all"
                                                title="Edit Kegiatan">
                                                <i class="fas fa-pen text-[10px]"></i> Edit
                                            </a>

                                            <!-- Tombol Hapus (Destructive) -->
                                            <form action="{{ url('kegiatan/'.$a->id) }}" method="post" class="delete-form m-0 inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#e54b4f] hover:bg-[#c93b3f] shadow-2xs hover:-translate-y-px transition-all cursor-pointer"
                                                    title="Hapus Kegiatan">
                                                    <i class="fas fa-trash text-[10px]"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-neutral-400 text-sm">
                                    <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                    Tidak ada data kegiatan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-[#ebebeb] bg-[#fdfdfd] flex justify-center">
                {{ $kegiatan->withQueryString()->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL LINK DISALIN (SHORINJI KEMPO DESIGN SYSTEM) -->
    <!-- ============================================== -->
    <div id="copyLinkModal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-xs p-4 transition-all duration-200">
        <div class="relative w-full max-w-lg bg-white rounded-3xl border border-[#ebebeb] shadow-2xl p-6 sm:p-7 overflow-hidden">
            <!-- Close Button (X) -->
            <button type="button" onclick="closeCopyModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-500 hover:text-[#000000] flex items-center justify-center transition-colors cursor-pointer border-0">
                <i class="fas fa-times text-xs"></i>
            </button>

            <!-- Header Modal -->
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#fff0eb] text-[#f2470c] flex items-center justify-center shrink-0 text-xl border border-[#f2470c]/25 shadow-2xs">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="flex-1 min-w-0 pr-6">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-1.5">
                        <i class="fas fa-check text-[9px]"></i> Link Berhasil Disalin
                    </span>
                    <h3 id="copyModalTitle" class="font-heading font-bold text-lg text-[#000000] mb-1 leading-snug">
                        Tautan Pendaftaran Disalin
                    </h3>
                    <p class="text-xs text-neutral-500 mb-0 font-body">
                        Kegiatan: <span class="font-semibold text-neutral-800" id="copyModalKegiatanNama">-</span>
                    </p>
                </div>
            </div>

            <!-- Content Area: Copied Link Box -->
            <div class="mt-5 space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-0">
                        Tautan yang Disalin:
                    </label>
                    <span id="copyToastNotice" class="text-[11px] font-semibold text-emerald-600 transition-opacity opacity-0">
                        <i class="fas fa-check-circle mr-1"></i> Tersalin ke clipboard!
                    </span>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-2xl bg-[#fdfdfd] border border-[#ebebeb] focus-within:border-[#f2470c] focus-within:ring-2 focus-within:ring-[#f2470c]/20 transition-all shadow-2xs">
                    <input type="text" id="copyModalInput" readonly
                        class="w-full bg-transparent border-0 px-2.5 py-1 text-xs font-mono font-medium text-[#000000] select-all outline-none" />
                    <button type="button" onclick="reCopyLink()" id="btnReCopy"
                        class="shrink-0 px-3.5 py-1.5 rounded-xl text-xs font-semibold text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-2xs hover:-translate-y-px transition-all cursor-pointer flex items-center gap-1.5 border-0">
                        <i class="fas fa-copy text-[10px]"></i>
                        <span id="reCopyText">Salin Ulang</span>
                    </button>
                </div>
                <p class="text-[11px] text-neutral-400 mt-1.5 mb-0 flex items-center gap-1">
                    <i class="fas fa-info-circle text-[#f2470c]"></i>
                    <span>Tautan ini siap dibagikan kepada calon peserta atau dibuka langsung di peramban.</span>
                </p>
            </div>

            <!-- Footer Action Buttons -->
            <div class="mt-6 pt-4 border-t border-[#ebebeb] flex items-center justify-end gap-2.5">
                <a href="#" id="copyModalOpenLink" target="_blank"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-[#3276e4] hover:bg-[#2862c2] shadow-2xs hover:-translate-y-px transition-all no-underline">
                    <i class="fas fa-external-link-alt text-[10px]"></i>
                    <span>Buka Tautan</span>
                </a>
                <button type="button" onclick="closeCopyModal()"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-neutral-700 bg-neutral-100 hover:bg-neutral-200 border border-neutral-200 transition-all cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    window.performClipboardCopy = function(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text).catch(function() {
                return fallbackClipboardCopy(text);
            });
        }
        return fallbackClipboardCopy(text);
    };

    function fallbackClipboardCopy(text) {
        return new Promise(function(resolve, reject) {
            try {
                var $temp = $("<textarea>");
                $("body").append($temp);
                $temp.val(text).select();
                document.execCommand("copy");
                $temp.remove();
                resolve();
            } catch (e) {
                reject(e);
            }
        });
    }

    window.copyLink = function(url, typeName, kegiatanNama) {
        typeName = typeName || 'Pendaftaran';
        kegiatanNama = kegiatanNama || '';

        window.performClipboardCopy(url).then(function() {
            showCopyNotification(url, typeName, kegiatanNama);
        }).catch(function() {
            showCopyNotification(url, typeName, kegiatanNama);
        });
    };

    window.copyText = function(url, typeName, kegiatanNama) {
        window.copyLink(url, typeName, kegiatanNama);
    };

    function showCopyNotification(url, typeName, kegiatanNama) {
        $('#copyModalTitle').text('Link ' + typeName + ' Disalin!');
        $('#copyModalKegiatanNama').text(kegiatanNama || '-');
        $('#copyModalInput').val(url);
        $('#copyModalOpenLink').attr('href', url);

        $('#copyToastNotice').removeClass('opacity-0').addClass('opacity-100');
        setTimeout(function() {
            $('#copyToastNotice').removeClass('opacity-100').addClass('opacity-0');
        }, 2000);

        $('#copyLinkModal').css('display', 'flex').hide().fadeIn(200);
    }

    window.reCopyLink = function() {
        var url = $('#copyModalInput').val();
        window.performClipboardCopy(url).then(function() {
            var $btn = $('#btnReCopy');
            var $txt = $('#reCopyText');
            $txt.text('Tersalin!');
            $btn.css('background-color', '#10b981');
            $('#copyToastNotice').removeClass('opacity-0').addClass('opacity-100');
            setTimeout(function() {
                $txt.text('Salin Ulang');
                $btn.css('background-color', '');
                $('#copyToastNotice').removeClass('opacity-100').addClass('opacity-0');
            }, 1500);
        });
    };

    window.closeCopyModal = function() {
        $('#copyLinkModal').fadeOut(200, function() {
            $(this).css('display', 'none');
        });
    };

    $(document).on('click', '#copyLinkModal', function(e) {
        if (e.target === this) {
            window.closeCopyModal();
        }
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#copyLinkModal').is(':visible')) {
            window.closeCopyModal();
        }
    });
@endsection
