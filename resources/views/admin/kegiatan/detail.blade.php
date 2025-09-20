@extends('tamplate')
@section('title', 'Pertandingan')
@section('content')
    @php
        use App\Models\Pertandingan;
    @endphp
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="dark:text-white">{{ $kegiatan->nama }}</h4>
                </div>
                <div class="flex items-center justify-between mb-4">
                    {{-- <a href="{{ url('pertandingan/create') }}?kegiatan_id={{ (@$kegiatan_get->id ? $kegiatan_get->id : Request('kegiatan_id')) }}" style="border: solid 2px green; color: green;"  class="inline-block px-8 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Tambah Data</a> --}}
                    <a href="{{ url('kegiatan') }}" style="border: solid 2px black; color: black; font-size: 16px;"  class="inline-block px-8 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Kembali</a>
                    <form action="" method="get">
                        <div class="flex items-center gap-2">
                            <select name="kategori_id" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500" id="kategori_id">
                                @foreach ($kategori as $a)
                                    <option value="{{ $a->kategori->id }}" {{ Request('kategori_id') == $a->kategori->id ? 'selected' : (@$kegiatan_get->id == $a->kategori->id ? 'selected' : '') }} >{{ $a->kategori->nama }}</option>

                                    {{-- <option value="{{ $a->id }}" {{ Request('kegiatan_id') == $a->id ? 'selected' : ''}}>{{ $a->nama }}</option> --}}
                                @endforeach
                            </select>

                            <input type="text" name="cari"
                                class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500"
                                placeholder="Cari data...">

                            <button type="submit"
                                class="px-4 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>

            </div>
            <br>
            <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                <thead class="align-bottom">
                    <tr>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-4xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama Pertandingan</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_gender_group = 0;
                        $kategori_id = 0;
                        $jenis_kelamin = "-";
                    @endphp
                @foreach ($pertandingan as $a)

                    @if ($kategori_id != $a->kategori_id)
                        @php
                            $kategori_id = $a->kategori_id;
                        @endphp
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left;" colspan="3">{{ $a->kategori->nama }}</th>
                        </tr>
                        @if ($a->pertandingan->jenis_kelamin != $jenis_kelamin)
                            @php
                                $jenis_kelamin = $a->pertandingan->jenis_kelamin;
                            @endphp
                            <tr>
                                <th class="px-6 py-4 border-b" style="text-align: left;" colspan="3">- {{ $jenis_kelamin ?? "Campuran" }}</th>
                                {{-- <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_emas }}</th>
                                <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_perak }}</th>
                                <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_perunggu }}</th>
                                <th class="px-6 py-4 border-b" style="text-align: left;"></th> --}}
                            </tr>
                        @endif
                        <tr>
                            <td class="px-6 py-4 border-b">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 border-b">{{ $a->pertandingan->nama }}</td>
                            {{-- <td class="px-6 py-4 border-b">{{ $jenis_kelamin }}</td> --}}
                            {{-- <td class="text-center px-6 py-4 border-b">{{ $a->emas }}</td>
                            <td class="text-center px-6 py-4 border-b">{{ $a->perak }}</td>
                            <td class="text-center px-6 py-4 border-b">{{ $a->perunggu }}</td> --}}

                            <td class="px-6 py-4 border-b">
                                <div class="flex justify-center items-center space-x-4">
                                    <a href="{{ url('pertandingan/'.$a->id) }}?kegiatan_id={{ $kegiatan->id }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Nomor Tanding</a><br>
                                    {{-- <a href="{{ url('pertandingan/'.$a->id.'/edit') }}?kegiatan_id={{ (@$kegiatan_get->id ? $kegiatan_get->id : Request('kegiatan_id')) }}" style="border: solid 2px blue; color: blue;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Edit</a><br>
                                    <form action="{{ url('pertandingan/'.$a->id) }}?kegiatan_id={{ (@$kegiatan_get->id ? $kegiatan_get->id : Request('kegiatan_id')) }}" method="post" style="border: solid 2px blue; color: blue;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75 delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>
                    @else
                        @if ($a->pertandingan->jenis_kelamin != $jenis_kelamin)
                            @php
                                $jenis_kelamin = $a->pertandingan->jenis_kelamin;
                                // dd($a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_emas );
                            @endphp
                            <tr>
                                <th class="px-6 py-4 border-b" style="text-align: left;" colspan="3">- {{ $jenis_kelamin ?? 'Campuran' }}</th>
                                {{-- <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_emas ?? '0' }}</th>
                                <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_perak ?? '0' }}</th>
                                <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_perunggu ?? '0' }}</th>
                                <th class="px-6 py-4 border-b" style="text-align: left;"></th> --}}
                            </tr>
                        @endif
                        <tr>
                            <td class="px-6 py-4 border-b">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 border-b">{{ $a->pertandingan->nama }}</td>
                            {{-- <td class="px-6 py-4 border-b">{{ $jenis_kelamin }}</td> --}}
                            {{-- <td class="text-center px-6 py-4 border-b">{{ $a->emas }}</td>
                            <td class="text-center px-6 py-4 border-b">{{ $a->perak }}</td>
                            <td class="text-center px-6 py-4 border-b">{{ $a->perunggu }}</td> --}}

                            <td class="px-6 py-4 border-b">
                                <div class="flex justify-center items-center space-x-4">
                                    <a href="{{ url('pertandingan/'.$a->id) }}?kegiatan_id={{ $kegiatan->id }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Nomor Tanding</a><br>
                                    {{-- <a href="{{ url('pertandingan/'.$a->id.'/edit') }}?kegiatan_id={{ (@$kegiatan_get->id ? $kegiatan_get->id : Request('kegiatan_id')) }}" style="border: solid 2px blue; color: blue;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Edit</a><br>
                                    <form action="{{ url('pertandingan/'.$a->id) }}?kegiatan_id={{ (@$kegiatan_get->id ? $kegiatan_get->id : Request('kegiatan_id')) }}" method="post" style="border: solid 2px blue; color: blue;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75 delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $pertandingan->withQueryString()->links('vendor.pagination.tailwind') }}
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>

@endsection
