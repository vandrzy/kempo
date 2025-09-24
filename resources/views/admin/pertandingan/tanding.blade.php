@extends('tamplate')
@section('title', 'Pertandingan')
@section('content')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="dark:text-white">{{ $pertandingan->kegiatan->nama }} / {{ $pertandingan->pertandingan->nama }} / Tanding</h4>
                    </div>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <div class="flex items-center justify-between mb-4 p-3">
                            <a href="{{ url('kegiatan/'.$pertandingan->kegiatan->id) }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Kembali</a>
                            <div>
                                <div class="flex items-center justify-between mb-4 p-3">
                                    @if($peserta_ready == 0)
                                        <a href="{{ url('pertandingans/'.$pertandingan->id.'/print') }}?kegiatan_id={{ $pertandingan->kegiatan->id }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Print Nomor Tanding</a>
                                        <form action="{{ url('pertandingans/tanding-delete/') }}" method="post" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="list_kategori_id" value="{{ $pertandingan->id }}">

                                            {{-- <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
                                            <input type="hidden" name="pertandingan_id" value="{{ $pertandingan->id }}"> --}}
                                            <button type="submit" style="border: solid 2px red; color: red;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Hapus Nomor Tanding</button>
                                        </form>
                                    @else
                                        <!-- Form Tambah Penantang -->
                                        <form action="{{ url('pertandingans/tanding/'.$pertandingan->id) }}" method="post" class="tanding-form">
                                            @csrf
                                            <button type="submit" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Acak Nomor Tanding</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Form Hapus -->
                        </div>
                        <h4 class="pl-3">Peserta tersedia : {{ $peserta_ready }}</h4>
                        <table class="items-center w-full ">
                            <thead class="align-bottom">
                                <tr>
                                    <th rowspan="2" style="text-align: center;" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                                    <th rowspan="2" style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama</th>
                                    <th colspan="2" style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tingkat</th>
                                    <th rowspan="2" style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Kelas</th>
                                    <th rowspan="2" style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Berat Badan</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">KYU</th>
                                    <th style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">DAN</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($peserta_lawan as $a)
                                    <tr>
                                        <td style="text-align: center" class="text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <h6 class="mb-0 text-sm leading-normal pl-4 dark:text-white">{{ $a->PertandinganPesertaPertama->urutan }}</h6>
                                        </td>
                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->PertandinganPesertaPertama->Peserta->nama }}</p>
                                        </td>
                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ strtoupper(substr($a->PertandinganPesertaPertama->Peserta->tingkatan_sabuk , 0, 1)) == "K" ? "V" : ''}}</p>
                                        </td>
                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ strtoupper(substr($a->PertandinganPesertaPertama->Peserta->tingkatan_sabuk , 0, 1)) == "D" ? 'V' : ''}}</p>
                                        </td>
                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->PertandinganPesertaPertama->Peserta->PertandinganPesertaUtama->list_kategori->pertandingan->kelas }}</p>
                                        </td>


                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->PertandinganPesertaPertama->Peserta->berat_badan }} Kg</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center" class="text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <h6 class="mb-0 text-sm leading-normal pl-4 dark:text-white">{{ @$a->PertandinganPesertaKedua->urutan ?? 0 }}</h6>
                                        </td>
                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ @$a->PertandinganPesertaKedua->Peserta->nama ?? "WO" }}</p>
                                        </td>
                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ strtoupper(substr(@$a->PertandinganPesertaKedua->Peserta->tingkatan_sabuk , 0, 1)) == "K" ? "V" : ''}}</p>
                                        </td>
                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ strtoupper(substr(@$a->PertandinganPesertaKedua->Peserta->tingkatan_sabuk , 0, 1)) == "D" ? 'V' : ''}}</p>
                                        </td>
                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ @$a->PertandinganPesertaKedua->Peserta->PertandinganPesertaUtama->list_kategori->pertandingan->kelas ?? ""}}</p>
                                        </td>


                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ @$a->PertandinganPesertaKedua->Peserta->berat_badan ?? ""}} Kg</p>
                                        </td>
                                    </tr>
                                    {{-- <div style="background-color: rgb(237, 162, 162); color: black;" class="bg-red-100 p-2 rounded shadow w-40 text-center">{{ $a->PertandinganPesertaPertama->urutan }} | {{ $a->PertandinganPesertaPertama->Peserta->nama }}</div>
                                    <div style="background-color: rgb(116, 202, 234); color: black;" class="bg-blue-100 p-2 rounded shadow w-40 text-center">{{ $a->PertandinganPesertaKedua->urutan ?? '0' }} | {{ $a->PertandinganPesertaKedua->Peserta->nama ?? 'WO' }}</div> --}}
                                @endforeach
                            </tbody>
                        </table>

                        <div class="overflow-x-auto">

                            <div class="flex gap-8 p-6 overflow-x-auto bg-white">

                                <div class="flex flex-col justify-between gap-6">



                                </div>
                                <div class="d-flex justify-content-center">
                                    {{ $peserta_lawan->withQueryString()->links('vendor.pagination.tailwind') }}
                                </div>

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>








    </div><br>

@endsection
