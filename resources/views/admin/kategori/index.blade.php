@extends('tamplate')
@section('title', 'Kategori')
@section('content')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="dark:text-white">Data Kategori</h4>
                    {{-- <a href="{{ url('users/create') }}" class="bg-green-500 text-black px-4 py-2 rounded">Tambah Data</a> --}}
                    <a href="{{ url('kategori/create') }}" style="border: solid 2px green; color: green;"  class="inline-block px-8 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Tambah Data</a>
                </div>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                <thead class="align-bottom">
                    <tr>
                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-4xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama Kategori</th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Usia</th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tahun</th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategori as $a)
                        <tr>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <h6 class="mb-0 text-sm leading-normal pl-4 dark:text-white">{{ $loop->iteration }}</h6>
                            </td>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->nama }}</p>
                            </td>
                            <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl  font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->usia_awal }} - {{ $a->usia_akhir }}</p>
                            </td>
                            <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl  font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->tahun_awal }} - {{ $a->tahun_akhir }}</p>
                            </td>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <div class="flex justify-center items-center space-x-4">
                                    <a href="{{ url('kategori/'.$a->id.'/edit') }}" style="border: solid 2px blue; color: blue;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Edit</a>
                                    <form action="{{ url('kategori/'.$a->id) }}" method="post" class="delete-form pl-2">
                                        @csrf
                                        @method('DELETE')
                                        <button style="border: solid 2px red; color: red;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $kategori->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
@endsection
