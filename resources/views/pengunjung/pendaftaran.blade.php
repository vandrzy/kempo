<div class="w-full min-w-0 max-w-full">
    <div class="kempo-card">
        <!-- Header Kartu -->
        <div class="px-6 py-5 border-b border-[#ebebeb] bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Pendaftaran Peserta</h4>
                <p class="text-xs text-neutral-400 m-0 mt-0.5">Kegiatan: <span class="font-semibold text-neutral-800">{{ $kegiatan->nama }}</span></p>
            </div>
            @if(Auth::user())
                <div class="flex items-center gap-2">
                    <button type="button" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-[#000000] bg-[#fff0eb] border border-[#f2470c]/25 hover:bg-[#ffe2d6] transition-all cursor-pointer shadow-2xs" id="copy_link">
                        <i class="fas fa-copy text-[#f2470c]"></i>
                        <span>Salin Link Pendaftaran</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Form -->
        <form action="{{ url('daftar') }}" method="post" id="form-peserta" enctype="multipart/form-data" class="p-6">
            @csrf
            @include('admin.peserta.form')
        </form>
    </div>
</div>

@section('script')
    document.getElementById('imageInput').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('preview').src = URL.createObjectURL(file);
        }
    });

    document.querySelector('input[name="berat_badan"]').addEventListener('keydown', function(e) {
        if (['e', 'E', '+', '-', '.'].includes(e.key)) e.preventDefault();
    });

    document.getElementById('form-peserta').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin ingin mendaftar?',
            text: "Pastikan data sudah benar sebelum melanjutkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, daftar sekarang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('.error').html("");
                const form = e.target;
                const data = new FormData(form);
                const errors = [];

                const rules = {
                    nama: 'Nama Peserta Wajib Diisi!',
                    jenis_kelamin: 'Jenis Kelamin Wajib Diisi!',
                    golongan_darah: 'Golongan Darah Wajib Diisi!',
                    nik: val => {
                        if (!val) return 'NIK Wajib Diisi!';
                        if (isNaN(val)) return 'NIK Wajib Diisi dengan Angka!';
                        if (val.length > 16) return 'NIK Maksimal 16 digit!';
                    },
                    status: 'Status Wajib Diisi!',
                    tingkatan_sabuk_a: 'Tingkat Wajib Diisi!',
                    tingkatan_sabuk_b: 'Tingkat Wajib Diisi!',
                    tempat: 'Tempat Lahir Wajib Diisi!',
                    tanggal_lahir: 'Tanggal Lahir Wajib Diisi!',
                    alamat: 'Alamat Peserta Wajib Diisi!',
                    telepon: val => {
                        if (!val) return 'Nomor Telepon Peserta Wajib Diisi!';
                        if (isNaN(val)) return 'Nomor Telepon harus angka!';
                        if (val.length > 13) return 'Nomor Telepon tidak boleh melebihi 13 digit!';
                    },
                    berat_badan: 'Berat Badan Peserta Wajib Diisi!',
                    keterangan: 'Keterangan Wajib Diisi!',
                    kabupaten_id: 'Kabupaten Wajib Diisi!',
                    kecamatan_id: 'Kecamatan Wajib Diisi!',
                    kelurahan_id: 'Kelurahan Wajib Diisi!',
                    'kategori_id[]': () => {
                        const select = form.querySelector('[name="kategori_id[]"]');
                        const selected = Array.from(select.options).filter(opt => opt.selected);
                        if (selected.length === 0) return 'Minimal pilih satu kategori!';
                    },
                    foto: file => {
                        if (!file) return 'Foto Peserta Wajib Diisi!';
                        const allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];
                        if (!allowed.includes(file.type)) return 'Foto hanya boleh JPEG, PNG, JPG, atau SVG!';
                        if (file.size > 1024 * 1024) return 'Foto tidak boleh lebih dari 1MB, mohon dikompres!';
                    }
                };

                for (const [field, message] of Object.entries(rules)) {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (typeof message === 'function') {
                        const val = field === 'foto' ? data.get(field) : data.get(field)?.trim();
                        const error = message(val);
                        if (error) {
                            errors.push(error);
                            const errorEl = input.closest('.mb-4').querySelector('.error');
                            if (errorEl) {
                                errorEl.textContent = error;
                            }
                        }
                    } else {
                        const val = data.get(field);
                        if (!val || (val instanceof File && !val.name)) {
                            errors.push(message);
                            const errorEl = input.closest('.mb-4').querySelector('.error');
                            if (errorEl) {
                                errorEl.textContent = message;
                            }
                        }
                    }
                }

                if (errors.length > 0) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Lengkapi form yang belum terisi!",
                    });
                    return false;
                }

                form.submit();
            }
        });
    });

    function copyText(texts) {
        let text = texts;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Tautan Berhasil Disalin!',
                    html: `<p class="text-xs text-neutral-600 mb-2">Tautan pendaftaran:</p><input type="text" readonly value="${text}" class="w-full text-xs p-2 rounded-lg border border-[#ebebeb] bg-neutral-50 text-center font-mono">`,
                    confirmButtonColor: '#f2470c',
                    confirmButtonText: 'Tutup'
                });
            });
        } else {
            let $temp = $("<textarea>");
            $("body").append($temp);
            $temp.val(text).select();
            document.execCommand("copy");
            $temp.remove();
            Swal.fire({
                icon: 'success',
                title: 'Tautan Berhasil Disalin!',
                html: `<p class="text-xs text-neutral-600 mb-2">Tautan pendaftaran:</p><input type="text" readonly value="${text}" class="w-full text-xs p-2 rounded-lg border border-[#ebebeb] bg-neutral-50 text-center font-mono">`,
                confirmButtonColor: '#f2470c',
                confirmButtonText: 'Tutup'
            });
        }
    }

    function getWilayah(type, spawning, callback) {
        let value = $(`#${type}`).val();
        let urls = `{{ url("getWilayah") }}/${type}/${value}`;

        $.ajax({
            url: urls,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let data = "";
                if(response.data.length > 0) {
                    $.each(response.data, function(index, value) {
                        data += `<option value="${value.id}">${value.name}</option>`;
                    });
                } else {
                    data += `<option value="" disabled>Data Tidak Tersedia!</option>`;
                }
                $(`#${spawning}`).html(data);
                if (callback) callback();
            },
            error: function(xhr, status, error) {
                console.error('Gagal:', error);
                alert('Gagal mengambil data!');
            }
        });
    }

    $('#kabupaten_id').on('change', function() {
        getWilayah('kabupaten_id', 'kecamatan_id', function() {
            $(`#kelurahan_id`).html("");
            getWilayah('kecamatan_id', 'kelurahan_id');
        });
    });

    $('#kecamatan_id').on('change', function() {
        getWilayah('kecamatan_id', 'kelurahan_id');
    });

    $('#copy_link').on('click', function() {
        copyText("{{ url()->current() }}");
    });

    $('.pilihan').select2({
        theme: 'mytheme',
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: true,
        placeholder: 'Pilih opsi...',
        closeOnSelect: false,
    });

    function AmbilDataPertandingan(tanggal_lahir, jenis_kelamin, kegiatan_id, kategori_id) {
        $('#pertandingan_id').attr('status', 'true');
        $('#pertandingan_id').html('');
        $.ajax({
            url: `{{url("pendaftarans")}}/${tanggal_lahir}/${jenis_kelamin}?kegiatan_id=${kegiatan_id}&kategori_id=${kategori_id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let data = "";
                if(response.data.length > 0) {
                    $.each(response.data, function(index, value) {
                        $('#tingkat').val(value.kategori.nama);
                        data += `<option value="${value.id}">${value.pertandingan.nama} | Urutan ${response.urutan[index]}</option>`;
                    });
                } else {
                    data += `<option value="" disabled>Pertandingan Tidak Tersedia!</option>`;
                }
                $('#pertandingan_id').html(data);
            },
            error: function(xhr, status, error) {
                console.error('Gagal:', error);
                alert('Gagal mengambil data!');
            }
        });
    }

    let lastValid = [];
    $('#pertandingan_id').on('change', function () {
        const selected = $(this).val();
        lastValid = selected;
        if (selected && selected.length > 3) {
            lastValid.splice(3, 1);
            alert('Maksimal hanya bisa memilih 3 pertandingan!');
            $('#pertandingan_id').val(lastValid).trigger('change');
            return;
        }

        lastValid = selected;
        let data = $(this).select2('data');
        let hasil = data.map(item => {
            return {
                value: item.id,
                text: item.text
            };
        });

        let data_urutan_list = "";
        for(var i = 0; i < hasil.length; i++) {
            let arr = hasil[i].text.split("|");
            data_urutan_list += `
                <div class="p-4 rounded-xl border border-[#ebebeb] bg-[#fff0eb]/30 mb-4">
                    <h5 class="font-heading font-bold text-sm text-[#000000] mb-3">${arr[0]}</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                        <div>
                            <label class="block text-[11px] font-bold text-[#000000] uppercase font-heading mb-1">Nama <span class="text-[#e54b4f]">**</span></label>
                            <input type="text" name="nama_tanding[${hasil[i].value}]" value="-" class="w-full px-3 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none" required placeholder="Nama">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[#000000] uppercase font-heading mb-1">Tingkat <span class="text-[#e54b4f]">**</span></label>
                            <input type="text" name="tingkat_tanding[${hasil[i].value}]" value="-" class="w-full px-3 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none" required placeholder="Tingkat">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[#000000] uppercase font-heading mb-1">No. Tanding <span class="text-[#e54b4f]">**</span></label>
                            <input type="text" readonly class="w-full px-3 py-2 text-xs font-medium rounded-xl bg-neutral-100 border border-[#ebebeb] text-neutral-600 tabular-nums outline-none" value="${arr[1] ?? "-"}">
                        </div>
                    </div>
                    <div class="urutan_${i}">
                        <label class="block text-[11px] font-bold text-[#000000] uppercase font-heading mb-1">Urutan Komposisi yang Dimainkan <span class="text-[#e54b4f]">**</span></label>
                        <div class="urutan_list" data-id="${i}">
                            <input type="text" name="urutan_tanding[${hasil[i].value}][]" value="-" ids="${hasil[i].value}" class="urutan_tanding_${i} urutan_tanding w-full px-3 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none" required placeholder="Urutan komposisi yang dimainkan">
                        </div>
                    </div>
                </div>
            `;
        }
        $(`#pertandingan_tahapan_teknik_${kategori_id}`).html(data_urutan_list);
    });

    $('#jenis_kelamin, #tanggal_lahir').on('change', function (e) {
        $('.kategori_show').html('');
        $('.kategori_pertandingan_show').html('');
        let kategoriVal = $("#kategori_id").val() || [];
        for(var i = 0; i < kategoriVal.length; i++) {
            AmbilDataPertandinganKategori($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val(), kategoriVal[i]);
        }
    });

    function AmbilDataPertandinganKategori(tanggal_lahir, jenis_kelamin, kegiatan_id, kategori_id) {
        $.ajax({
            url: `{{url("pendaftarans")}}/${tanggal_lahir}/${jenis_kelamin}?kegiatan_id=${kegiatan_id}&kategori_id=${kategori_id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let html = "";
                let html2 = "";
                let option = "";
                if(response.data.length > 0) {
                    $.each(response.data, function(index, value) {
                        option += `<option value="${value.id}">${value.pertandingan.nama} | Urutan ${response.urutan[index]}</option>`;
                    });
                } else {
                    option += `<option value="" disabled selected>Usia anda tidak tersedia untuk kategori ini!</option>`;
                }
                html += `
                    <div class="p-4 rounded-2xl bg-white border border-[#ebebeb] shadow-2xs mb-4">
                        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                            Kategori: <span class="text-[#f2470c] font-bold">${response.kategori.nama}</span>
                        </label>
                        <select name="pertandingan_id[]" ${response.data.length == 0 ? 'disabled' : ''} class="pertandingan_kategori w-full" multiple="multiple" status="{{ (is_array(old('pertandingan_id')) && count(old('pertandingan_id'))) > 0 ? 'true' : 'false' }}" kategori_id="${response.kategori.id}" id="pilihan_${response.kategori.id}">
                            ${option}
                        </select>
                    </div>
                `;

                html2 += `
                    <div class="p-4 rounded-2xl bg-white border border-[#ebebeb] shadow-2xs mb-4">
                        <h5 class="font-heading font-bold text-sm text-[#000000] mb-3">${response.kategori.nama}</h5>
                        <div id="pertandingan_tahapan_teknik_${response.kategori.id}"></div>
                    </div>
                `;

                $('.kategori_show').append(html);
                $('.kategori_pertandingan_show').append(html2);
                $(`#pilihan_${response.kategori.id}`).select2({
                    theme: 'mytheme',
                    allowClear: true,
                    width: '100%',
                    dropdownAutoWidth: true,
                    placeholder: 'Pilih nomor pertandingan...',
                    closeOnSelect: false,
                });
            },
            error: function(xhr, status, error) {
                console.error('Gagal:', error);
            }
        });
    }

    $(document).on("click", ".edit-tanding-form", function(e) {
        if($("#form-peserta")[0].checkValidity()) {
            // valid
        }
    });

    $(document).on("change", ".pertandingan_kategori", function () {
        let kategori_id = $(this).attr('kategori_id');
        let data = $(this).select2('data');
        let hasil = data.map(item => {
            return {
                value: item.id,
                text: item.text
            };
        });

        let data_urutan_list = "";
        for(var i = 0; i < hasil.length; i++) {
            let arr = hasil[i].text.split("|");
            data_urutan_list += `
                <div class="p-4 rounded-xl border border-[#ebebeb] bg-[#fff0eb]/30 mb-4">
                    <h5 class="font-heading font-bold text-xs text-[#000000] mb-3">${arr[0]}</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                        <div>
                            <label class="block text-[11px] font-bold text-[#000000] uppercase font-heading mb-1">Nama <span class="text-[#e54b4f]">**</span></label>
                            <input type="text" name="nama_tanding[${hasil[i].value}]" value="-" class="w-full px-3 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none" required placeholder="Nama">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[#000000] uppercase font-heading mb-1">Tingkat <span class="text-[#e54b4f]">**</span></label>
                            <input type="text" name="tingkat_tanding[${hasil[i].value}]" value="-" class="w-full px-3 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none" required placeholder="Tingkat">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[#000000] uppercase font-heading mb-1">No. Tanding <span class="text-[#e54b4f]">**</span></label>
                            <input type="text" readonly class="w-full px-3 py-2 text-xs font-medium rounded-xl bg-neutral-100 border border-[#ebebeb] text-neutral-600 tabular-nums outline-none" value="${arr[1] ?? "-"}">
                        </div>
                    </div>
                    <div class="urutan_${i}">
                        <label class="block text-[11px] font-bold text-[#000000] uppercase font-heading mb-1">Urutan Komposisi yang Dimainkan <span class="text-[#e54b4f]">**</span></label>
                        <div class="urutan_list" data-id="${i}">
                            <input type="text" name="urutan_tanding[${hasil[i].value}][]" value="-" ids="${hasil[i].value}" class="urutan_tanding_${i} urutan_tanding w-full px-3 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none" required placeholder="Urutan komposisi yang dimainkan">
                        </div>
                    </div>
                </div>
            `;
        }
        $(`#pertandingan_tahapan_teknik_${kategori_id}`).html(data_urutan_list);
    });

    $(document).on("input", ".urutan_tanding", function () {
        var group = $(this).closest(".urutan_list");
        var inputs = group.find(".urutan_tanding");
        var kosong = inputs.filter(function() {
            return $(this).val().trim() === "";
        });

        if (kosong.length === 0) {
            var newInput = $(`
                <input type="text" name="urutan_tanding[${$(this).attr('ids')}][]" value="" class="urutan_tanding_${$(this).attr('ids')} urutan_tanding w-full px-3 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none mt-2" placeholder="Urutan komposisi yang dimainkan">
            `);
            group.append(newInput);
        } else {
            kosong.slice(1).remove();
        }
    });

    $(document).on("blur", ".urutan_tanding", function (e) {
        $(".urutan_list").each(function() {
            var kosong = $(this).find("input[name='urutan_tanding[]']").filter(function() {
                return $(this).val().trim() === "";
            });
            kosong.slice(1).remove();
        });
    });
@endsection
