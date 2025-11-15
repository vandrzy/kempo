@extends('tamplate')
@section('title', 'Data Kontigen ')
@section('content')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="dark:text-white">Data Kontigen</h4>
                    </div>
                    <form action="" method="get">
                        <select name="kegiatan_id" onchange="this.form.submit()" class="pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="kegiatan_id">
                            <option value="">Pilih Kegiatan</option>
                            @foreach ($kegiatan as $a)
                                <option value="{{ $a->id }}" {{ Request('kegiatan_id') == $a->id ? 'selected' : (@$kegiatan_get->id == $a->id ? 'selected' : '') }} >{{ $a->nama }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                {{-- <div class="flex items-center justify-between mb-4">
                    <a href="{{ url('kegiatan/create') }}" style="border: solid 2px green; color: green;"  class="inline-block px-8 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Tambah Data</a>
                    <form action="" method="get">
                        <input type="text" name="cari"
                            class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500"
                            placeholder="Cari data...">
                        <button type="submit"
                            class="px-4 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                            Cari
                        </button>
                    </form>
                </div> --}}
            </div>
            <br>
            <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                <thead class="align-bottom">
                    <tr>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama Kegiatan</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Jumlah Peserta terdaftar</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kabupatens as $a)
                        <tr>
                            <td class="text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <h6 class="mb-0 text-sm leading-normal pl-4 dark:text-white">{{ $loop->iteration }}</h6>
                            </td>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->name }}</p>
                            </td>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->peserta->count() }} Peserta</p>
                            </td>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                @if ($a->peserta->count() > 0)
                                    <div class="flex justify-center items-center space-x-4">
                                        <a href="{{ url('kontingen/'.$a->id.'/edit'.'?kegiatan_id='.($_GET['kegiatan_id'] ?? 0)) }}" style="border: solid 2px blue; color: blue;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Print</a>
                                        <a href="{{ url('kontingen/'.$a->id.'?kegiatan_id='.($_GET['kegiatan_id'] ?? 0)) }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Detail</a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{-- {{ $kegiatan->withQueryString()->links('vendor.pagination.tailwind') }} --}}
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
<script>
    function copyText(texts) {
        let text = texts;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                alert("Teks berhasil disalin: " + text);
            });
        } else {
            // fallback execCommand
            let $temp = $("<textarea>");
            $("body").append($temp);
            $temp.val(text).select();
            document.execCommand("copy");
            $temp.remove();
            alert("Url berhasil disalin!");
        }
    }
</script>
@endsection
