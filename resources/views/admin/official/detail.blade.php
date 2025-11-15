@extends('tamplate')
@section('title', 'Detail Peserta')
@section('content')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between mb-4">
                        @if(isset($_GET['kontigen']))
                            <a href="{{ url('kontingen/'.$_GET['kontigen'].'?kegiatan_id='.$_GET['ehh']) }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Kembali</a>
                        @else
                            <a href="{{ url('official') }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Kembali</a>
                        @endif
                        <h4 class="dark:text-white">Detail Official</h4>
                    </div>
                </div>
                <div class="flex flex-wrap mt-4 -mx-3">
                    <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
                        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                            <div class="p-4 pb-0 mb-0 rounded-t-4">
                                <div class="flex justify-between">
                                    <h6 class="mb-2 dark:text-white">Foto Official</h6>
                                </div>
                            </div>
                            <div class="overflow-x-auto p-3">
                                <img src="{{ @$peserta ? url($peserta->foto) : '' }}" alt="Foto Official" class="w-3/4 border border-black rounded shadow" id="preview">
                            </div>
                        </div>
                    </div>
                    <div class="w-full max-w-full px-3 mt-0 lg:w-5/12 lg:flex-none">
                        <div class="border-black/12.5 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                            <div class="p-4 pb-0 rounded-t-4">
                                <h6 class="mb-0 dark:text-white">Data Official</h6>
                                {{-- <a href="#" class="bg-black text-white px-4 py-3 rounded">Urusan Komposisi</a> --}}
                            </div>
                            <div class="flex-auto p-4">
                                <table class="min-w-full bg-white border border-gray-200">
                                    <tbody>
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Kontigen</th>
                                            <td class="px-6 py-4 border-b">{{ $peserta->kabupaten->name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Nama</th>
                                            <td class="px-6 py-4 border-b">{{ $peserta->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Jenis Kelamin</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->jenis_kelamin }}</td>
                                        </tr>
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Golongan Darah</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->golongan_darah }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Disabilitas</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->disabilitas == "1" ? "Ya" : "Tidak" }}</td>
                                        </tr> --}}
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">NIK</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->nik }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Kategori</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->tingkat }}</td>
                                        </tr> --}}
                                        {{-- <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Berat Badan (Kg)</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->berat_badan }} Kg</td>
                                        </tr> --}}
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Tingkat</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->tingkat }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Status</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->status == "P" ? "Peserta / Atlet" : "Official"  }}</td>
                                        </tr> --}}
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Tempat, Tanggal Lahir</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->tempat }}, {{ $peserta->tanggal_lahir }}</td>
                                        </tr>
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Alamat Rumah</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->alamat }} </td>
                                        </tr>
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left">Telepon</td>
                                            <td class="px-6 py-4 border-b">{{ $peserta->telepon }}</td>
                                        </tr>
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left" colspan="2">Keterangan Khusus Official Prestasi Terbaik</td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 border-b" colspan="2">{{ $peserta->keterangan }}</td>
                                        </tr>
                                        <tr>
                                            <th class="px-6 py-4 border-b" style="text-align: left" colspan="2">Sertifikat Pelatih yang diikuti</td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 border-b" colspan="2">{{ $peserta->sertifikat }}</td>
                                        </tr>

                                        {{-- @foreach ($peserta->PertandinganPeserta as $a)
                                            <tr>
                                                <td class="px-6 py-4 border-b">{{ $a->urutan }}</td>
                                                <td class="px-6 py-4 border-b">{{ $a->list_kategori->pertandingan->nama ?? "" }}</td>
                                            </tr>
                                        @endforeach --}}

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
