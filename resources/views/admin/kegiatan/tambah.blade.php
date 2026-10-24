@extends('tamplate')
@section('title', 'Tambah Data Kegiatan')
@section('content')
    <div class="w-full min-w-0 max-w-full">
        <div class="kempo-card">
            <!-- Header Kartu -->
            <div class="px-6 py-5 border-b border-[#ebebeb] bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Tambah Data Kegiatan</h4>
                    <p class="text-xs text-neutral-400 m-0 mt-0.5">Isi formulir berikut untuk mendaftarkan kejuaraan kegiatan baru</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ url('kegiatan') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-[#000000] bg-[#ffad9e] hover:bg-[#ff9c8a] transition-all shadow-2xs">
                        <i class="fas fa-arrow-left text-[10px]"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ url('kegiatan') }}" method="post" class="p-6">
                @csrf
                @include('admin.kegiatan.form')

                <div class="mt-8 pt-5 border-t border-[#ebebeb] flex items-center justify-end gap-3">
                    <a href="{{ url('kegiatan') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-700 bg-neutral-100 hover:bg-neutral-200 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:-translate-y-px transition-all cursor-pointer">
                        <i class="fas fa-save text-[11px]"></i>
                        <span>Simpan Kegiatan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
