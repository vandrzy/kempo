@extends('tamplate')
@section('title', 'Pertandingan')
@section('content')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between mb-4">
                        @if(isset($_GET['kabupaten_id']))
                            <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$pertandingan->kegiatan->id) }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Kembali</a>
                        @else
                            <a href="{{ url('kegiatan/'.$pertandingan->kegiatan->id) }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Kembali</a>
                        @endif
                        <h4 class="dark:text-white">{{ $pertandingan->kegiatan->nama }} / {{ $pertandingan->pertandingan->nama }} / Tanding</h4>
                    </div>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                    @if(!isset($_GET['kabupaten_id']))
                        <div class="flex items-center justify-between mb-2 p-3">
                            <div>

                                    {{-- <form action="" id="kabupaten_change" method="get">
                                        <input type="hidden" name="kegiatan_id" value="{{ $pertandingan->kegiatan->id }}">
                                        <select name="kabupaten_id" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="kabupaten_select">
                                            <option value="">Menu Tanding Peserta</option>
                                        @foreach ($kabupaten as $kabId => $items)
                                            @php
                                                $kabupatenId = optional($items->first()->Peserta->Kabupaten)->id;
                                                $kabupatenName = optional($items->first()->Peserta->Kabupaten)->name;
                                            @endphp

                                            @if ($kabupatenName)
                                                <option value="{{ $kabupatenId }}" {{ isset($_GET['kabupaten_id']) ? ($_GET['kabupaten_id'] == $kabupatenId ? 'selected' : '' ) : '' }}>{{ $kabupatenName ?? 'Tanpa Kabupaten' }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </form> --}}
                            </div>
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
                                            <div class="flex items-center justify-between mb-4">
                                                <select name="urutan" id="" class="pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow">
                                                    <option value="1">Acak Kontigen Pertama</option>
                                                    <option value="2">Acak Kontigen Kedua</option>
                                                </select>
                                                <button type="submit" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Acak Nomor Tanding</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                        <div class="flex items-center gap-3">
                            <!-- Form Hapus -->
                        </div>
                        @if(isset($_GET['kabupaten_id']) && $_GET['kabupaten_id'] != "")
                            <h4 class="pl-3">Peserta dari kabupaten {{ $kabupaten_first->name }} </h4>
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

                                    @foreach ($peserta as $a)
                                        <tr>
                                            <td style="text-align: center" class="text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <h6 class="mb-0 text-sm leading-normal pl-4 dark:text-white">{{ $loop->iteration }}</h6>
                                            </td>
                                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->Peserta->nama }} | {{ $a->Peserta->kabupaten->name ?? "" }} </p>
                                            </td>
                                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ strtoupper(substr($a->Peserta->tingkatan_sabuk , 0, 1)) == "K" ? "V" : ''}}</p>
                                            </td>
                                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ strtoupper(substr($a->Peserta->tingkatan_sabuk , 0, 1)) == "D" ? 'V' : ''}}</p>
                                            </td>
                                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->Peserta->PertandinganPesertaUtama->list_kategori->pertandingan->kelas }}</p>
                                            </td>


                                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->Peserta->berat_badan }} Kg</p>
                                            </td>
                                        </tr>
                                        {{-- <div style="background-color: rgb(237, 162, 162); color: black;" class="bg-red-100 p-2 rounded shadow w-40 text-center">{{ $a->PertandinganPesertaPertama->urutan }} | {{ $a->PertandinganPesertaPertama->Peserta->nama }}</div>
                                        <div style="background-color: rgb(116, 202, 234); color: black;" class="bg-blue-100 p-2 rounded shadow w-40 text-center">{{ $a->PertandinganPesertaKedua->urutan ?? '0' }} | {{ $a->PertandinganPesertaKedua->Peserta->nama ?? 'WO' }}</div> --}}
                                    @endforeach
                                </tbody>
                            </table>
                        @else

                            <h4 class="pl-3">Peserta tersedia : {{ $peserta_ready }}</h4>
                        <div class="flex items-center justify-start space-x-4 mb-4" >


                            <!-- Kiri - Urutan 1 -->
                            @if(!$kolom1->isEmpty())
                                <div>
                                    <center>
                                        <h2 class="font-bold text-lg mb-3">Acak Kontigen 1</h2>
                                    </center>
                                    <table class="min-w-full bg-white border rounded-lg overflow-hidden">
                                        <thead class="bg-gray-100 border-b">
                                            <tr>
                                                <th class="px-3 py-2 border-r">Kabupaten</th>
                                                <th class="px-3 py-2">Jumlah Peserta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kolom1 as $item)
                                            @php
                                                    $kegiatan_id_get = $_GET['kegiatan_id'];
                                                    $list_kategori = $item->list_kategori_id;
                                                    // dd($item->kabupaten_id);
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

                                                    $warna = $loop->iteration % 2 === 1 ? 'green' : 'purple';


                                            @endphp
                                            <tr>
                                                <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: {{ $warna }}">
                                                        @if (isset($item->kabupaten->name))
                                                            <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$pertandingan->kegiatan->id.'&kabupaten_id='.$item->kabupaten->id) }}">{{ $item->kabupaten->name  }}</a>

                                                        @else
                                                            Bye
                                                        @endif
                                                    </p>
                                                </td>
                                                <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: {{ $warna }}"> {{ $total_peserta }} Peserta</p>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="2">
                                                    <br>
                                                    <center>
                                                        <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$pertandingan->kegiatan->id.'&print=1') }}" style="background-color: rgb(53, 53, 53);" class="text-white px-4 py-2 rounded">Print</a>
                                                    </center>
                                                    <br>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <!-- Kanan - Urutan 2 -->
                            @if(!$kolom2->isEmpty())
                            <div>
                                <center>
                                    <h2 class="font-bold text-lg mb-3">Acak Kontigen 2</h2>
                                </center>
                                <table class="min-w-full bg-white border rounded-lg overflow-hidden">
                                    <thead class="bg-gray-100 border-b">
                                        <tr>
                                            <th class="px-3 py-2 border-r">Kabupaten</th>
                                            <th class="px-3 py-2">Jumlah Peserta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kolom2 as $item)
                                            @php
                                                    $kegiatan_id_get = $_GET['kegiatan_id'];
                                                    $list_kategori = $item->list_kategori_id;
                                                    // dd($item->kabupaten_id);
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

                                                    $warna = $loop->iteration % 2 === 1 ? 'green' : 'purple';

                                            @endphp
                                            <tr>
                                                <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: {{ $warna }}">
                                                        @if (isset($item->kabupaten->name))
                                                            <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$pertandingan->kegiatan->id.'&kabupaten_id='.$item->kabupaten->id) }}">{{ $item->kabupaten->name  }}</a>

                                                        @else
                                                            Bye
                                                        @endif
                                                    </p>
                                                </td>
                                                <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: {{ $warna }}"> {{ $total_peserta }} Peserta</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2">
                                                <br>
                                                <center>
                                                    <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$pertandingan->kegiatan->id.'&print=2') }}" style="background-color: rgb(53, 53, 53);" class="text-white px-4 py-2 rounded">Print</a>
                                                </center>
                                                <br>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif

                        </div>



                            {{-- @if($peserta_ready > 0)
                                <table class="items-center w-full ">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th colspan="1" style="text-align: left;" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Kabupaten</th>
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
                                                <tr>
                                                    <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;">{{ $kabupatenName ?? 'Tanpa Kabupaten' }} : {{ $kabupatenTotalPeserta  }} Peserta</p>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <table class="items-center w-full ">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th rowspan="2" style="text-align: center;" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                                            <th rowspan="2" style="text-align: center;" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Kabupaten</th>
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
                                                    <h6 class="mb-0 text-sm leading-normal pl-4" style="color: green;">{{ $a->PertandinganPesertaPertama->urutan }}</h6>
                                                </td>
                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;">{{ $a->PertandinganPesertaPertama->Peserta->kabupaten->name ?? "" }} </p>
                                                </td>
                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;">{{ $a->PertandinganPesertaPertama->Peserta->nama }} </p>
                                                </td>
                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;">{{ strtoupper(substr($a->PertandinganPesertaPertama->Peserta->tingkatan_sabuk , 0, 1)) == "K" ? "V" : ''}}</p>
                                                </td>
                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;">{{ strtoupper(substr($a->PertandinganPesertaPertama->Peserta->tingkatan_sabuk , 0, 1)) == "D" ? 'V' : ''}}</p>
                                                </td>
                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;">{{ $a->PertandinganPesertaPertama->Peserta->PertandinganPesertaUtama->list_kategori->pertandingan->kelas }}</p>
                                                </td>


                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;">{{ $a->PertandinganPesertaPertama->Peserta->berat_badan }} Kg</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center" class="text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <h6 class="mb-0 text-sm leading-normal pl-4 " style="color: blue;">{{ @$a->PertandinganPesertaKedua->urutan ?? 0 }}</h6>
                                                </td>
                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: blue;">{{ $a->PertandinganPesertaKedua->Peserta->kabupaten->name ?? "" }}</p>
                                                </td>
                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: blue;">{{ @$a->PertandinganPesertaKedua->Peserta->nama ?? "WO" }}</p>
                                                </td>
                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: blue;">{{ strtoupper(substr(@$a->PertandinganPesertaKedua->Peserta->tingkatan_sabuk , 0, 1)) == "K" ? "V" : ''}}</p>
                                                </td>
                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: blue;">{{ strtoupper(substr(@$a->PertandinganPesertaKedua->Peserta->tingkatan_sabuk , 0, 1)) == "D" ? 'V' : ''}}</p>
                                                </td>
                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: blue;">{{ @$a->PertandinganPesertaKedua->Peserta->PertandinganPesertaUtama->list_kategori->pertandingan->kelas ?? ""}}</p>
                                                </td>


                                                <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: blue;">{{ @$a->PertandinganPesertaKedua->Peserta->berat_badan ?? ""}} Kg</p>
                                                </td>
                                            </tr>
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
                            @endif --}}

                        @endif
                </div>
            </div>
        </div>
    </div>








    </div><br>
<script>
    const select = document.getElementById('kabupaten_select');
  const form = document.getElementById('kabupaten_change');

  select.addEventListener('change', function() {
    form.submit(); // otomatis kirim form saat select berubah
  });
</script>
@endsection
