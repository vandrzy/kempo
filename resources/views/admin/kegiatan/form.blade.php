<div class="mb-6">
    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
        Nama Kegiatan <span class="text-[#e54b4f]">*</span>
    </label>
    <input type="text" name="nama" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" value="{{ old('nama', @$kegiatan->nama) }}" placeholder="Contoh: Kejuaraan Shorinji Kempo Antar Dojo 2026" id="nama" />
    @error('nama')
        <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
    @enderror
</div>

<div class="mb-6">
    @php
        if(@$kegiatan) {
            $selected_kategori_ids = $kegiatan->kategori_terpilih()->pluck('kategori_id')->toArray();
            $unique_kategori_ids = collect($selected_kategori_ids)->unique()->values()->toArray();
        } else {
            $unique_kategori_ids = [];
        }
        $selected = old('list_kategori_id', $unique_kategori_ids ?? []);
    @endphp
    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-1">
        Pilih Kategori &amp; Pertandingan <span class="text-[#e54b4f]">*</span>
    </label>
    <p class="text-xs text-neutral-400 mb-4">Centang kategori untuk memuat dan memilih nomor pertandingan yang disertakan</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($main_kategori as $a)
            <div class="p-4 rounded-2xl border border-[#ebebeb] bg-[#fdfdfd] hover:border-[#f2470c]/30 transition-all">
                <label class="inline-flex items-center gap-2.5 cursor-pointer font-heading font-bold text-sm text-[#000000]">
                    <input type="checkbox" class="list_kategori_id w-4 h-4 rounded text-[#f2470c] border-[#ebebeb] accent-[#f2470c] cursor-pointer" id="{{ $a->id }}" array="{{ $a->id }}" name="list_kategori_id[]" {{ in_array($a->id, $selected) ? 'checked' : '' }} value="{{ $a->id }}">
                    <span>{{ $a->nama }}</span>
                </label>
                <div class="mt-3 pl-6 space-y-2 border-t border-[#ebebeb]/60 pt-3" id="list_pertandingan_{{ $a->id }}">
                    @if(in_array($a->id, $selected))
                        @php
                            if(@$kegiatan) {
                                $selected_pertandingan_ids = $kegiatan->kategori_terpilih()->pluck('pertandingan_id')->toArray();
                                $selected_old = old('list_pertandingan_id', $selected_pertandingan_ids ?? []);
                            } else {
                                $selected_old = [];
                            }
                        @endphp
                        @foreach ($a->pertandingan as $b)
                            <label class="flex items-center gap-2 text-xs font-medium text-neutral-700 cursor-pointer">
                                <input type="checkbox" {{ in_array($b->id, $selected_old) ? 'checked' : '' }} value="{{ $b->id }}" name="list_pertandingan_id[{{ $a->id }}][]" class="w-3.5 h-3.5 rounded text-[#f2470c] border-[#ebebeb] accent-[#f2470c]">
                                <span>{{ $b->nama }}</span>
                            </label>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@section('script')
    function AmbilDataPertandingan(id, arrays) {
        let array = arrays;
        $.ajax({
            url: `{{ url("pertandingan_list") }}/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let data = "";
                let ids = id;
                if(response.data && response.data.length > 0) {
                    $.each(response.data, function(index, value) {
                        data += `<label class="flex items-center gap-2 text-xs font-medium text-neutral-700 cursor-pointer">
                            <input type="checkbox" value="${value.id}" checked name="list_pertandingan_id[${array}][]" class="w-3.5 h-3.5 rounded text-[#f2470c] border-[#ebebeb] accent-[#f2470c]">
                            <span>${value.nama}</span>
                        </label>`;
                    });
                } else {
                    data += `<p class="text-xs text-neutral-400 italic mb-0">Tidak tersedia pertandingan untuk kategori ini.</p>`;
                }
                $('#list_pertandingan_' + ids).html(data);
            },
            error: function(xhr, status, error) {
                console.error('Gagal:', error);
                alert('Gagal mengambil data pertandingan!');
            }
        });
    }

    $(document).on('change', '.list_kategori_id', function() {
        let catId = $(this).attr('id');
        let catArr = $(this).attr('array');
        if ($(this).is(':checked')) {
            AmbilDataPertandingan(catId, catArr);
        } else {
            $('#list_pertandingan_' + catId).html("");
        }
    });
@endsection
