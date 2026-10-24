@extends('tamplate')
@section('title', 'Pengguna')
@section('content')
    <div class="w-full min-w-0 max-w-full">
        <div class="kempo-card">
            <!-- Header Kartu -->
            <div class="px-6 py-4 border-b border-[#ebebeb] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white">
                <div>
                    <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Data Pengguna</h4>
                    <p class="text-xs text-neutral-400 m-0 mt-0.5">Kelola akun dan hak akses pengguna sistem Shorinji Kempo</p>
                </div>
                <div>
                    <a href="{{ url('users/create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:shadow-sm hover:-translate-y-px transition-all">
                        <i class="fas fa-plus text-[10px]"></i>
                        <span>Tambah Data</span>
                    </a>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="kempo-table-wrapper kempo-scrollbar">
                <table class="kempo-table">
                    <thead>
                        <tr>
                            <th class="text-center w-14">No</th>
                            <th class="text-left">Nama Pengguna</th>
                            <th class="text-left">Email</th>
                            <th class="text-center">Nomor Whatsapp</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Role</th>
                            <th class="text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $a)
                            <tr>
                                <td class="text-center tabular-nums text-xs font-semibold text-neutral-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="text-left font-semibold text-[#000000]">
                                    {{ $a->name }}
                                </td>
                                <td class="text-left text-neutral-600 text-xs sm:text-sm">
                                    {{ $a->email }}
                                </td>
                                <td class="text-center tabular-nums text-neutral-700 text-xs sm:text-sm">
                                    {{ $a->no_wa ?? '-' }}
                                </td>
                                <td class="text-center">
                                    @if ($a->email_verified_at)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fas fa-check-circle mr-1 text-[10px]"></i> Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#fff0eb] text-[#f2470c] border border-[#f2470c]/20">
                                            <i class="fas fa-clock mr-1 text-[10px]"></i> Belum Verifikasi
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $displayRole = match($a->role) {
                                            'admin', 1, '1' => 'Super Admin',
                                            'operator', 2, '2' => 'Operator',
                                            'wasit' => 'Wasit',
                                            'user', 0, '0' => 'User / Kontingen',
                                            default => ucfirst($a->role ?? '-'),
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-neutral-100 text-neutral-800 border border-neutral-200">
                                        {{ $displayRole }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Edit (Button 3 Warning/Action) -->
                                        <a href="{{ url('users/'.$a->id.'/edit') }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#fd822b] hover:bg-[#e06e1f] shadow-2xs hover:-translate-y-px transition-all"
                                            title="Edit Pengguna">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            <span>Edit</span>
                                        </a>

                                        <!-- Tombol Hapus (Destructive) -->
                                        <form action="{{ url('users/'.$a->id) }}" method="post" class="delete-form m-0 inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#e54b4f] hover:bg-[#c93b3f] shadow-2xs hover:-translate-y-px transition-all cursor-pointer"
                                                title="Hapus Pengguna">
                                                <i class="fas fa-trash text-[10px]"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-neutral-400 text-sm">
                                    <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                    Tidak ada data pengguna ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-[#ebebeb] bg-[#fdfdfd] flex justify-center">
                {{ $users->withQueryString()->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection
