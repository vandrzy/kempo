@extends('tamplate')
@section('title', 'Tambah Pengguna')
@section('content')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="dark:text-white">Tambah Data Pengguna</h4>
                    </div>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <form action="{{ url('users') }}" method="post" id="registerForm">
                            <div class="flex items-center justify-between mb-4 p-3">
                                <a href="{{ url('users') }}" style="background-color: rgb(91, 184, 91);" class="text-white px-4 py-2 rounded">Kembali</a>
                                <button type="submit" style="background-color: rgb(91, 184, 91);" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                            </div>
                            @csrf
                            <div class="p-3">
                                @include('admin.users.form')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
