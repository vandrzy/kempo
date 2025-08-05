<div class="mb-4">
    <label for="">Nama Kegiatan <span style="color: red;">*</span></label>
    <input type="text" name="nama" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nama', @$kegiatan->nama) }}" placeholder="Nama Kegiatan" id="">
    @error('nama')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="mb-4">
    @php
        if(@$kegiatan) {
            $selected_kategori_ids = $kegiatan->kategori_terpilih()->pluck('kategori_id')->toArray();
            $unique_kategori_ids = collect($selected_kategori_ids)->unique()->values()->toArray();
        } else {
            $unique_kategori_ids = [];
        }
        $selected = old('list_kategori_id', $unique_kategori_ids ?? []);
    @endphp
    <label for="">Pilih Kategori <span style="color: red;">*</span></label> <br>
    @foreach ($main_kategori as $a)
        <input type="checkbox" class="list_kategori_id" id="{{ $a->id }}" array="{{ $a->id }}" name="list_kategori_id[]" {{ in_array($a->id, $selected) ? 'checked' : '' }} value="{{ $a->id }}" id=""> {{ $a->nama }} <br>
        <div class="px-4" id="list_pertandingan_{{ $a->id }}">
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
                    <input type="checkbox" {{ in_array($b->id, $selected_old) ? 'checked' : '' }} value="{{ $b->id }}" name="list_pertandingan_id[{{ $a->id }}][]"> {{$b->nama}} <br>
                @endforeach
            @endif
        </div>
    @endforeach
</div>

<script>
    @section('script')
    function AmbilDataPertandingan(id, arrays) {
        // $('#pertandingan_id').attr('status', 'true');
        // $('#pertandingan_id').html('');
        let array = arrays;
        $.ajax({
            url: `{{url("pertandingan_list")}}/${id}`, // URL API
            type: 'GET',
            dataType: 'json', // bisa 'html', 'text', dll tergantung respon
            success: function(response) {
                let data = "";
                let ids = "";

                console.log(response.data);

                if(response.data.length > 0) {
                    $.each(response.data, function(index, value) {
                        ids = value.kategori_id;
                        data += `<input type="checkbox" value="${value.id}" name="list_pertandingan_id[${array}][]"> ${value.nama} <br>`;
                    });
                } else {
                    data += `Tidak tersedia pertandingan!`;

                }

                // data += `<option value="" disabled>Pilih Pertandingan</option>`;

                $('#list_pertandingan_' + ids).html(data);


                // alert('Judul: ' + response.title);
            },
            error: function(xhr, status, error) {
                console.error('Gagal:', error);
                alert('Gagal mengambil data!');
            }
        });
    }


    $('.list_kategori_id').on('change', function() {
        if ($(this).is(':checked')) {
            AmbilDataPertandingan($(this).attr('id'), $(this).attr('array'));
        } else {
            $('#list_pertandingan_' + $(this).attr('id')).html("");
        }
    });
    @endsection

</script>
