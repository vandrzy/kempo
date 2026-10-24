@extends('tamplate')
@section('title', 'Detail Official')
@section('content')
    <div class="space-y-6">
        <!-- Top Bar Header Card -->
        <div class="kempo-card p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                @if(isset($_GET['kontigen']))
                    <a href="{{ url('kontingen/'.$_GET['kontigen'].'?kegiatan_id='.$_GET['ehh'].'&status=official') }}" class="btn-kempo-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                @else
                    <a href="{{ url('official') }}" class="btn-kempo-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold shadow-xs hover:opacity-90 transition-all">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                @endif
                <div>
                    <h2 class="text-xl font-heading font-bold text-foreground mb-0">Detail Official</h2>
                    <p class="text-xs text-paragraph mt-0.5">Informasi profil lengkap pelatih dan official kontingen</p>
                </div>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-blue-50 text-btn-4 border border-blue-200">
                    <i class="fa fa-id-badge"></i> Official / Pelatih
                </span>
            </div>
        </div>

        <!-- 2 Columns: Foto & Biodata -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Foto Official -->
            <div class="lg:col-span-5">
                <div class="kempo-card h-full flex flex-col">
                    <div class="p-5 border-b border-input">
                        <h3 class="text-sm font-heading font-bold uppercase tracking-wider text-neutral-600 flex items-center gap-2 mb-0">
                            <i class="fa fa-image text-primary"></i> Foto Official
                        </h3>
                    </div>
                    <div class="p-6 flex-1 flex flex-col items-center justify-center bg-neutral-50/40">
                        @if(@$peserta && $peserta->foto)
                            <div class="relative group rounded-2xl overflow-hidden border border-input shadow-md bg-white p-2">
                                <img src="{{ url($peserta->foto) }}" alt="Foto Official" class="max-h-96 w-auto object-cover rounded-xl" id="preview">
                            </div>
                        @else
                            <div class="w-48 h-64 rounded-2xl border-2 border-dashed border-input flex flex-col items-center justify-center text-neutral-400">
                                <i class="fa fa-user-circle text-5xl mb-2"></i>
                                <span class="text-xs font-medium">Foto Tidak Tersedia</span>
                            </div>
                        @endif
                        <h4 class="font-heading font-bold text-foreground text-base mt-4 text-center">{{ $peserta->nama }}</h4>
                        <p class="text-xs text-paragraph mb-0 text-center">{{ $peserta->kabupaten->name ?? 'Tanpa Kontingen' }}</p>
                    </div>
                </div>
            </div>

            <!-- Biodata Table -->
            <div class="lg:col-span-7">
                <div class="kempo-card overflow-hidden">
                    <div class="p-5 border-b border-input">
                        <h3 class="text-sm font-heading font-bold uppercase tracking-wider text-neutral-600 flex items-center gap-2 mb-0">
                            <i class="fa fa-id-card-o text-primary"></i> Data Official
                        </h3>
                    </div>
                    <div class="kempo-table-wrapper">
                        <table class="kempo-table">
                            <tbody>
                                <tr>
                                    <th class="w-48 text-neutral-500 font-semibold text-xs bg-neutral-50/70">Kontingen</th>
                                    <td class="font-bold text-foreground">{{ $peserta->kabupaten->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Nama Lengkap</th>
                                    <td class="font-bold text-foreground">{{ $peserta->nama }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Jenis Kelamin</th>
                                    <td class="text-foreground">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $peserta->jenis_kelamin == 'L' ? 'bg-blue-50 text-btn-4 border border-blue-200' : 'bg-pink-50 text-pink-700 border border-pink-200' }}">
                                            {{ $peserta->jenis_kelamin == 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Golongan Darah</th>
                                    <td class="text-foreground font-semibold">{{ $peserta->golongan_darah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">NIK</th>
                                    <td class="text-foreground font-semibold tabular-nums">{{ $peserta->nik }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Tingkat / Sabuk</th>
                                    <td>
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-bold rounded-md bg-neutral-100 text-neutral-800 border border-input">
                                            {{ $peserta->tingkat }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Tempat, Tgl Lahir</th>
                                    <td class="text-foreground">{{ $peserta->tempat }}, {{ $peserta->tanggal_lahir }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Alamat Rumah</th>
                                    <td class="text-foreground text-xs leading-relaxed">{{ $peserta->alamat }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Telepon / WhatsApp</th>
                                    <td class="text-foreground tabular-nums">{{ $peserta->telepon ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Keterangan Khusus</th>
                                    <td class="text-foreground text-xs">{{ $peserta->keterangan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-neutral-500 font-semibold text-xs bg-neutral-50/70">Sertifikat Pelatih</th>
                                    <td class="text-foreground text-xs font-medium">{{ $peserta->sertifikat ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
