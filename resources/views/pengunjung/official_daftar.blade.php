@extends('tamplate')
@section('title', 'Pendaftaran Official')
@section('content')
<div class="w-full min-w-0 max-w-full">
    <div class="kempo-card">
        <!-- Header Kartu -->
        <div class="px-6 py-5 border-b border-[#ebebeb] bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Pendaftaran Official</h4>
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
        <form action="{{ url('official_register') }}" method="post" id="form-peserta" enctype="multipart/form-data" class="p-6">
            @csrf
            @include('admin.official.form')
        </form>
    </div>
</div>
@endsection

@section('script')
    const imageInput = document.getElementById('imageInput');
    if (imageInput) {
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0];
            const preview = document.getElementById('preview');
            const existingFoto = document.getElementById('existing_foto');
            if (existingFoto) existingFoto.value = '';
            if (file && preview) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
                preview.style.display = 'block';
            }
        });
    }

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
                    nama: 'Nama Official Wajib Diisi!',
                    jenis_kelamin: 'Jenis Kelamin Wajib Diisi!',
                    golongan_darah: 'Golongan Darah Wajib Diisi!',
                    nik: val => {
                        if (!val) return 'NIK Wajib Diisi!';
                        if (isNaN(val)) return 'NIK Wajib Diisi dengan Angka!';
                        if (val.length > 16) return 'NIK Maksimal 16 digit!';
                    },
                    tingkatan_sabuk_a: 'Tingkat Wajib Diisi!',
                    tingkatan_sabuk_b: 'Tingkat Wajib Diisi!',
                    tempat: 'Tempat Lahir Wajib Diisi!',
                    tanggal_lahir: 'Tanggal Lahir Wajib Diisi!',
                    alamat: 'Alamat Official Wajib Diisi!',
                    telepon: val => {
                        if (!val) return 'Nomor Telepon Official Wajib Diisi!';
                        if (isNaN(val)) return 'Nomor Telepon harus angka!';
                        if (val.length > 13) return 'Nomor Telepon tidak boleh melebihi 13 digit!';
                    },
                    keterangan: 'Keterangan Wajib Diisi!',
                    sertifikat: 'Sertifikat Pelatih Wajib Diisi!',
                    kabupaten_id: 'Kabupaten Wajib Diisi!',
                    foto: file => {
                        const existingFoto = form.querySelector('[name="existing_foto"]')?.value;
                        if (existingFoto) return;
                        if (!file || (file instanceof File && !file.name)) return 'Foto Official Wajib Diisi!';
                        const allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];
                        if (!allowed.includes(file.type)) return 'Foto hanya boleh JPEG, PNG, JPG, atau SVG!';
                        if (file.size > 1024 * 1024) return 'Foto tidak boleh lebih dari 1MB, mohon dikompres!';
                    }
                };

                for (const [field, message] of Object.entries(rules)) {
                    // Lewati validasi jika field sedang dalam status menggunakan data lama yang disensor
                    if (window.isFieldMasked && window.isFieldMasked(field)) {
                        continue;
                    }

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

    @include('partials.auto_fill_pendaftar_script', ['tipe' => 'official'])
@endsection
