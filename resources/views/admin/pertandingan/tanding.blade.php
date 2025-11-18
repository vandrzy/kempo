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
                                                {{-- <select name="urutan" id="" class="pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow">
                                                    <option value="1">Acak Kontigen Pertama</option>
                                                    <option value="2">Acak Kontigen Kedua</option>
                                                </select> --}}
                                                <input type="hidden" name="pertandingan_id" value="{{ $pertandingan->pertandingan->id }}">
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
                        @if(isset($_GET['kabupaten_id']))
                            <h4 class="pl-3">Peserta dari kabupaten {{ $kabupaten_first->name ?? "Bye" }} </h4>
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

                                    @if($peserta->count() > 0)
                                        @foreach ($peserta as $a)
                                            <tr>
                                                <td style="text-align: center" class="text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <h6 class="mb-0 text-sm leading-normal pl-4 dark:text-white">{{ $loop->iteration }}</h6>
                                                </td>
                                                <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->Peserta->nama }} </p>
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
                                    @endif
                                </tbody>
                            </table>

                            <br>

                                <table class="items-center w-full ">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th style="text-align: center;" style="width: 10%;" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">No. Urut</th>
                                            <th style="text-align: left;" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Urutan Komposisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($pesertas->count() == 0)
                                                @for ($i = 0; $i < 6; $i++)
                                                    <tr>
                                                        <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;">{{ $i + 1 }}</p>
                                                        </td>
                                                        <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                            <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;">&nbsp;</p>
                                                        </td>
                                                    </tr>
                                                @endfor
                                        @else
                                            @foreach ($pesertas as $peserta)
                                                @php
                                                    $kategoriList = $peserta->PertandinganPeserta->pluck('list_kategori.kategori')->unique()->values();
                                                    $pertandingan = $peserta->PertandinganPeserta->pluck('list_kategori.pertandingan')->unique()->values();
                                                @endphp
                                                @php
                                                    $kategori_id = 0;
                                                    $urutan = 0;
                                                    $peserta_list = $loop->iteration;

                                                @endphp
                                                @foreach ($peserta->PertandinganPeserta as $item)
                                                    @if($peserta_list == 1)
                                                        @php
                                                            if($item->list_kategori->kategori->id != $kategori_id) {
                                                                $kategori_id = $item->list_kategori->kategori->id;
                                                            }
                                                        @endphp
                                                        @if (@$item->UrutanKomposisiPeserta->urutan_komposisi)
                                                            @php
                                                                $kelas = explode(",",$item->UrutanKomposisiPeserta->urutan_komposisi);
                                                                $value_kelas = [];
                                                                for($i = 0; $i < 6; $i++) {
                                                                    # code...
                                                                    if(isset($kelas[$i])) {
                                                                        $value_kelas[] = $kelas[$i];
                                                                    } else {
                                                                        $value_kelas[] = "";
                                                                    }
                                                                }
                                                            @endphp
                                                            @foreach ($value_kelas as $index => $a)
                                                                <tr>
                                                                    <td style="text-align: center" class="text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">

                                                                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $index + 1 }} </p>

                                                                    </td>
                                                                    <td style="text-align: left" class="text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a }} </p>

                                                                    </td>
                                                                </tr>

                                                                {{-- <input type="text" name="urutan_tanding[{{ $urutan }}][]" ids="{{ $urutan }}"  id="urutan_tanding_{{ $urutan }} urutan_tanding" class="urutan_tandings dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id=""> --}}
                                                            @endforeach
                                                            {{-- @foreach ($kelas as $a)
                                                                @if($a != "")
                                                                @endif
                                                            @endforeach --}}
                                                        @endif
                                                    @endif
                                                    @php
                                                        $urutan = $urutan + 1;
                                                    @endphp
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                        @else

                            <h4 class="pl-3">Peserta tersedia : {{ $peserta_ready }}</h4>
                            <br>
                            @if(!$kolom1->isEmpty())
                                @if($pertandingan->pertandingan->bagan == 1)
                                    <div class="p-2">
                                        <a href="{{ url('/pertandingan/119?kegiatan_id=8&print=1') }}" target="_blank" style="background-color: rgb(69, 69, 69);" class="lihat_peserta text-white px-4 py-2 rounded">Bagan Babak 1</a>
                                        <a href="{{ url('/pertandingan/119?kegiatan_id=8&print=2') }}" target="_blank" style="background-color: rgb(99, 99, 99);" class="lihat_peserta text-white px-4 py-2 rounded">Bagan Babak 2</a>
                                    </div>
                                @endif
                            @endif

                        <div class="flex items-center justify-start space-x-4 mb-4" >


                            <!-- Kiri - Urutan 1 -->
                            @if(!$kolom1->isEmpty())
                                <div>
                                    <table class="min-w-full bg-white border rounded-lg overflow-hidden">
                                        <thead class="bg-gray-100 border-b">
                                            <tr>
                                                <th class="px-3 py-2 border-r">Kabupaten</th>
                                                <th class="px-3 py-2 border-r">Babak 1</th>
                                                <th class="px-3 py-2 border-r">Babak 2</th>
                                                <th class="px-3 py-2">Jumlah Peserta</th>
                                                <th class="px-3 py-2">Aksi</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $databye = [];
                                                foreach ($kolom2 as $item) {
                                                    $databye[] = $item;
                                                }
                                                $list_byte = 0;
                                                // dd($databye);

                                            @endphp
                                            @foreach ($kolom1 as $index_list => $item)
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
                                            <tr id="tr_team_{{ $index_list }}">
                                                <td style="text-align: left" rowspan="1" class="col-team-{{ $index_list }} p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: {{ $warna }}">
                                                        @if (isset($item->kabupaten->name))
                                                            <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$pertandingan->kegiatan->id.'&kabupaten_id='.$item->kabupaten->id) }}">{{ $item->kabupaten->name  }}</a>

                                                        @else
                                                            Bye
                                                        @endif
                                                    </p>
                                                </td>
                                                <td style="text-align: center" rowspan="1" class="col-team-{{ $index_list }}  p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: {{ $warna }}">
                                                        {{ $item->undian + 1 }}
                                                    </p>
                                                </td>
                                                <td style="text-align: center" rowspan="1" class="col-team-{{ $index_list }}  p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: {{ $warna }}">
                                                        @if(isset($item->kabupaten->KontigenPertandinganKedua->undian))
                                                            {{ $item->kabupaten->KontigenPertandinganKedua->undian + 1 }}
                                                        @else
                                                            {{$databye[$list_byte]->undian + 1}}
                                                        @endif

                                                        {{-- {{ $item->kabupaten->KontigenPertandinganKedua->undian  ?? 'null' }} --}}
                                                        {{-- @dd($item->kabupaten->KontigenPertandinganKedua->undian + 1); --}}
                                                    </p>
                                                </td>
                                                <td style="text-align: center" rowspan="1" class=" col-team-{{ $index_list }} p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: {{ $warna }}"> {{ $total_peserta }} Peserta</p>
                                                </td>
                                                <td>
                                                    <a style="background-color: rgb(30, 120, 255);" class="text-white px-4 py-2 rounded" href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$pertandingan->kegiatan->id.'&kabupaten_id='.($item->kabupaten->id ?? null) ) }}">Detail Kontigen</a>
                                                    <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$pertandingan->kegiatan->id.'&kabupaten_id='.($item->kabupaten->id ?? null).'&status=print&kontigen_id_1='.$item->id.'&kontigen_id_2='. (isset($item->kabupaten->KontigenPertandinganKedua->id) ? $item->kabupaten->KontigenPertandinganKedua->id: $databye[$list_byte]->id) ) }}" target="_blank" style="background-color: rgb(76, 76, 76);" class="text-white px-4 py-2 rounded">Print Daftar Penilaian</a>
                                                    <button style="background-color: rgb(102, 233, 100);" status="-" total_peserta="{{ $total_peserta }}" index="{{ $index_list }}" kabupaten_id="{{ $item->kabupaten->id ?? "-" }}" class="lihat_peserta text-white px-4 py-2 rounded">Lihat Peserta</button>

                                                </td>
                                                @if(!isset($item->kabupaten->KontigenPertandinganKedua->undian))
                                                    @php
                                                        unset($databye[$list_byte]);
                                                        $list_byte += 1;
                                                    @endphp
                                                @endif

                                            </tr>
                                            @endforeach
                                            {{-- <tr>
                                                <td colspan="2">
                                                    <br>
                                                    <center>
                                                        <a href="{{ url('pertandingan/'.$pertandingan->id.'?kegiatan_id='.$pertandingan->kegiatan->id.'&print=1') }}" style="background-color: rgb(53, 53, 53);" class="text-white px-4 py-2 rounded">Print</a>
                                                    </center>
                                                    <br>
                                                </td>
                                            </tr > --}}
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <!-- Kanan - Urutan 2 -->
                            {{-- @if(!$kolom2->isEmpty())
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
                            @endif --}}

                        </div>



                            @if($peserta_ready > 0)
                                @if($kolom1->isEmpty() &&  $kolom2->isEmpty())
                                                                    <table class="items-center w-full ">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th colspan="1" style="text-align: left;" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Kabupaten</th>
                                            <th colspan="1" style="text-align: left;" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Babak 1</th>
                                            <th colspan="1" style="text-align: left;" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Babak 2</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kabupaten as $kabId => $items)
                                            @php
                                                $kabupatenId = optional($items->first()->Peserta->Kabupaten)->id;
                                                $kabupatenName = optional($items->first()->Peserta->Kabupaten)->name;
                                                // $urutan1 = optional($items->first()->Peserta->Kabupaten)->first();
                                                // dd($urutan1);
                                                $kabupatenTotalPeserta = $items->count();
                                            @endphp

                                            @if ($kabupatenName)
                                                <tr>
                                                    <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;">{{ $kabupatenName ?? 'Tanpa Kabupaten' }} : {{ $kabupatenTotalPeserta  }} Peserta</p>
                                                    </td>
                                                    <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;"></p>
                                                    </td>
                                                    <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: green;"></p>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
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

                                <div>

                                </div>
                            @endif
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
  @section('script')
    @if(!isset($_GET['kabupaten_id'] ))
        @if (!$kolom1->isEmpty())
            $('.lihat_peserta').click(function() {
                let kabupaten_id = $(this).attr('kabupaten_id');
                let index_list = $(this).attr('index');
                let status = $(this).attr('status');
                let total_peserta = parseInt($(this).attr('total_peserta')) + 1;
                console.log(total_peserta);

                if(status == "true") {
                    $(`.tr_team_all_${index_list}`).hide();
                    $('.col-team-' + index_list).attr('rowspan', 1);

                    $(this).attr('status', 'false');
                } else if(status == "false") {
                    $(`.tr_team_all_${index_list}`).show();
                    $('.col-team-' + index_list).attr('rowspan', total_peserta);
                    $(this).attr('status', 'true');
                }



                if(status == "-") {
                    if(kabupaten_id != '-') {
                        $.ajax({
                            url: "{{ url('pertandingan/'.$pertandingan->id) }}",
                            type: 'GET',
                            data: {
                                kegiatan_id: "{{ $pertandingan->kegiatan->id }}",
                                kabupaten_id: kabupaten_id,
                                status: "true",
                            },
                            success: function (response) {
                                let html = "";
                                response.data.forEach(function(item, index) {
                                    html += `<tr class="tr_team_all_${index_list}">
                                                <td style="text-align: left" rowspan="1" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80" style="color: {{ $warna }}"> ${index + 1}. ${item.peserta.nama}</p>
                                                </td>
                                            </tr>`;
                                });
                                $('#tr_team_' + index_list).after(html);
                                $('.col-team-' + index_list).attr('rowspan', response.total_data + 1);
                                console.log('Berhasil:', response);
                            },
                            error: function (xhr) {
                                console.log('Error:', xhr.responseText);
                            }
                        });


                        $(this).attr('status', 'true');

                    } else {
                        alert("Peserta tidak tersedia!");
                    }
                }
                //
            });
        @endif
    @endif
  @endsection
</script>
@endsection
