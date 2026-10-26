{{-- Script Auto-Fill Data Lama Pendaftar (Peserta & Official) --}}
(function () {
    const TIPE_PENDAFTAR = '{{ $tipe ?? "peserta" }}';
    const IS_GUEST = {{ Auth::check() ? 'false' : 'true' }};
    const inputNama = document.querySelector('input[name="nama"]');
    const confirmBox = document.getElementById('existing-data-confirmation');
    const refInput = document.getElementById('existing-reference-id');
    const unlockedInput = document.getElementById('unlocked-fields');

    let userDismissedQuery = '';
    let isAutoFilling = false;
    let lastSearchedQuery = '';

    // Field yang wajib disensor & didisable jika user belum login (Guest)
    const SENSITIVE_FIELDS = [
        { name: 'jenis_kelamin', label: 'Jenis Kelamin' },
        { name: 'golongan_darah', label: 'Golongan Darah' },
        { name: 'status', label: 'Status Keikutsertaan' },
        { name: 'nik', label: 'NIK (KTP/KIA)' },
        { name: 'telepon', label: 'Nomor Telepon / WA' },
        { name: 'tempat', label: 'Tempat Lahir' },
        { name: 'alamat', label: 'Alamat Lengkap Rumah' },
        { name: 'keterangan', label: 'Keterangan Prestasi' },
        { name: 'kabupaten_id', label: 'Kabupaten / Kota' },
        { name: 'kecamatan_id', label: 'Kecamatan' },
        { name: 'kelurahan_id', label: 'Kelurahan' },
    ];

    const maskedFields = new Set();
    const unlockedFields = new Set();

    // Export helper status masked ke window agar form validation bisa mengecek
    window.isFieldMasked = function (fieldName) {
        return maskedFields.has(fieldName);
    };

    if (!inputNama || !confirmBox) return;

    /**
     * Jalankan pencarian data lama HANYA KETIKA user telah selesai mengisi form nama
     * (misal saat event blur, change, atau tekan Enter)
     */
    function searchExistingPendaftar() {
        if (isAutoFilling) return;
        const query = inputNama.value.trim();

        if (query.length < 3) {
            confirmBox.classList.add('hidden');
            confirmBox.innerHTML = '';
            return;
        }

        // Jangan munculkan jika query ini baru saja diabaikan oleh user
        if (userDismissedQuery && query.toLowerCase() === userDismissedQuery.toLowerCase()) {
            return;
        }

        // Jangan request ulang jika query sama persis dengan yang sedang aktif tampil
        if (lastSearchedQuery && query.toLowerCase() === lastSearchedQuery.toLowerCase() && !confirmBox.classList.contains('hidden')) {
            return;
        }

        lastSearchedQuery = query;

        fetch(`{{ url('/api/check-existing-pendaftar') }}?nama=${encodeURIComponent(query)}&tipe=${TIPE_PENDAFTAR}`)
            .then(res => res.json())
            .then(res => {
                confirmBox.innerHTML = '';

                if (!res.found || !res.items || !res.items.length) {
                    confirmBox.classList.add('hidden');
                    return;
                }

                // HANYA menampilkan 1 data yang paling sesuai
                const item = res.items[0];

                const alertEl = document.createElement('div');
                alertEl.className = 'p-3.5 rounded-2xl bg-[#fff0eb] border border-[#f2470c]/25 shadow-2xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs transition-all animate-fadeIn';
                
                alertEl.innerHTML = `
                    <div class="flex items-start gap-2.5">
                        <div class="w-6 h-6 rounded-lg bg-[#f2470c]/10 text-[#f2470c] flex items-center justify-center shrink-0 text-xs mt-0.5">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="text-[#000000] leading-relaxed">
                            Data <strong class="font-bold text-[#f2470c]">"${escapeHtml(item.nama)}"</strong> pernah terdaftar sebagai <span class="font-semibold text-neutral-800">${escapeHtml(item.peran)}</span> pada kegiatan <strong class="font-bold text-[#000000]">"${escapeHtml(item.nama_kegiatan)}"</strong>. Ingin menggunakan data tersebut?
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                        <button type="button" class="btn-use-data inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold font-heading text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:-translate-y-px transition-all cursor-pointer">
                            <i class="fas fa-check text-[10px]"></i> Ya, Gunakan Data
                        </button>
                        <button type="button" class="btn-ignore-data inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-medium text-neutral-600 bg-white hover:bg-neutral-100 border border-[#ebebeb] transition-all cursor-pointer">
                            Abaikan
                        </button>
                    </div>
                `;

                // Event tombol "Ya, Gunakan Data"
                alertEl.querySelector('.btn-use-data').addEventListener('click', function () {
                    userDismissedQuery = inputNama.value.trim();
                    confirmBox.classList.add('hidden');
                    confirmBox.innerHTML = '';

                    isAutoFilling = true;
                    fillPersonalData(item.id, item.personal_data || {}, item);
                    if (item.personal_data && item.personal_data.nama) {
                        userDismissedQuery = item.personal_data.nama.trim();
                    }

                    setTimeout(() => {
                        isAutoFilling = false;
                    }, 500);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: IS_GUEST ? 'Data terdaftar berhasil dimuat!' : 'Data pribadi berhasil dimuat!',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                });

                // Event tombol "Abaikan"
                alertEl.querySelector('.btn-ignore-data').addEventListener('click', function () {
                    userDismissedQuery = inputNama.value.trim();
                    confirmBox.classList.add('hidden');
                    confirmBox.innerHTML = '';
                });

                confirmBox.appendChild(alertEl);
                confirmBox.classList.remove('hidden');
            })
            .catch(err => console.error('Gagal memeriksa data pendaftar:', err));
    }

    // Jalankan pencarian ketika user SELESAI mengetik nama (blur / change / enter)
    inputNama.addEventListener('blur', searchExistingPendaftar);
    inputNama.addEventListener('change', searchExistingPendaftar);
    inputNama.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchExistingPendaftar();
        }
    });

    // Reset pelacak query jika user mengosongkan / mengubah teks nama secara manual
    inputNama.addEventListener('input', function () {
        if (this.value.trim().length < 3) {
            confirmBox.classList.add('hidden');
            confirmBox.innerHTML = '';
        }
        lastSearchedQuery = '';
    });

    /**
     * Mengisi data personal dari hasil autofill
     */
    function fillPersonalData(id, data, item) {
        if (refInput) refInput.value = id;

        if (IS_GUEST && TIPE_PENDAFTAR === 'peserta') {
            // ========================================================
            // ATURAN UNTUK USER YANG TIDAK LOGIN (GUEST):
            // 1. Form selain: nama, tingkat sabuk (grade), berat badan,
            //    tanggal lahir di-disable dan diberikan tulisan
            //    "Menggunakan data yang sebelumnya sudah terdaftar".
            // 2. Data aslinya disensor (TIDAK ditampilkan).
            // 3. Khusus upload foto TIDAK di-disable.
            // 4. Preview foto diganti card "Menggunakan pas foto lama".
            // ========================================================

            // 1. Isi 4 field yang diizinkan terlihat & diedit
            if (data.nama) inputNama.value = data.nama;
            if (data.tingkatan_sabuk_a) {
                $('#tingkatan_sabuk_a').val(data.tingkatan_sabuk_a).trigger('change');
            }
            if (data.tingkatan_sabuk_b) {
                $('#tingkatan_sabuk_b').val(data.tingkatan_sabuk_b);
            }
            if (data.berat_badan) {
                $('#berat_badan').val(data.berat_badan);
            }
            if (data.tanggal_lahir) {
                $('#tanggal_lahir').val(data.tanggal_lahir).trigger('change');
            }

            // 2. Sensor dan Disable seluruh field lainnya
            SENSITIVE_FIELDS.forEach(field => {
                if (!unlockedFields.has(field.name)) {
                    maskField(field.name, field.label);
                }
            });

            // 3. Pas foto: Ganti preview foto dengan card "Menggunakan pas foto lama"
            const previewEl = document.getElementById('preview');
            const cardFotoLama = document.getElementById('card-foto-lama');
            const existingFotoEl = document.getElementById('existing_foto');

            if (previewEl) {
                previewEl.classList.add('hidden');
                previewEl.style.display = 'none';
                previewEl.src = '';
            }
            if (cardFotoLama) {
                cardFotoLama.classList.remove('hidden');
            }
            if (existingFotoEl) {
                existingFotoEl.value = item.foto_path || 'USE_OLD_PHOTO';
            }

        } else {
            // ========================================================
            // ATURAN UNTUK USER LOGIN (ADMIN / OPERATOR):
            // Tampilkan data penuh seperti biasa
            // ========================================================
            const excludedFields = [
                'pertandingan_id', 'pertandingan_id[]', 
                'kategori_id', 'kategori_id[]', 
                'kelas_id', '_token', 
                'foto', 'kegiatan_id'
            ];

            Object.keys(data).forEach(key => {
                if (excludedFields.includes(key) || data[key] === null || data[key] === undefined) {
                    return;
                }
                if (key === 'kabupaten_id' || key === 'kecamatan_id' || key === 'kelurahan_id') {
                    return;
                }
                setInputValue(key, data[key]);
            });

            if (data.kabupaten_id) {
                $('#kabupaten_id').val(data.kabupaten_id);
                if (typeof getWilayah === 'function') {
                    getWilayah('kabupaten_id', 'kecamatan_id', function () {
                        if (data.kecamatan_id) {
                            $('#kecamatan_id').val(data.kecamatan_id);
                            getWilayah('kecamatan_id', 'kelurahan_id', function () {
                                if (data.kelurahan_id) {
                                    $('#kelurahan_id').val(data.kelurahan_id);
                                }
                            });
                        }
                    });
                } else {
                    $('#kabupaten_id').trigger('change');
                }
            }

            if (item.foto_url) {
                const previewEl = document.getElementById('preview');
                const cardFotoLama = document.getElementById('card-foto-lama');
                if (cardFotoLama) cardFotoLama.classList.add('hidden');
                if (previewEl) {
                    previewEl.src = item.foto_url;
                    previewEl.classList.remove('hidden');
                    previewEl.style.display = 'block';
                }
                const existingFotoEl = document.getElementById('existing_foto');
                if (existingFotoEl) {
                    existingFotoEl.value = item.foto_path || '';
                }
            }
        }
    }

    /**
     * Memasang overlay sensor & disable pada field sensitif
     */
    function maskField(fieldName, fieldLabel) {
        const el = document.querySelector(`[name="${fieldName}"]`);
        if (!el) return;

        maskedFields.add(fieldName);

        const parent = el.parentElement;
        parent.classList.add('relative');

        // Cegah duplikasi overlay
        if (parent.querySelector(`.mask-overlay-${fieldName}`)) return;

        el.setAttribute('tabindex', '-1');
        el.style.pointerEvents = 'none';

        const overlay = document.createElement('div');
        overlay.className = `mask-overlay-${fieldName} absolute inset-0 z-10 flex items-center justify-between px-3.5 py-2 rounded-xl bg-neutral-100 border border-dashed border-neutral-300 text-neutral-600 text-xs font-medium cursor-pointer hover:bg-neutral-200/90 hover:border-neutral-400 transition-all shadow-2xs select-none`;
        overlay.title = 'Klik jika ingin mengganti data ini';
        overlay.innerHTML = `
            <div class="flex items-center gap-2 truncate">
                <i class="fas fa-lock text-[#f2470c] text-[11px] shrink-0"></i>
                <span class="truncate italic text-[11px] sm:text-xs text-neutral-600">Menggunakan data yang sebelumnya sudah terdaftar</span>
            </div>
            <span class="shrink-0 text-[10px] font-bold text-[#f2470c] bg-white px-2 py-0.5 rounded-lg border border-[#f2470c]/25 ml-2 shadow-2xs">
                <i class="fas fa-edit mr-0.5"></i> Ganti
            </span>
        `;

        overlay.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            confirmUnlockField(fieldName, fieldLabel);
        });

        parent.appendChild(overlay);
    }

    /**
     * Modal konfirmasi ketika field yang didisable / disensor di-klik
     */
    function confirmUnlockField(fieldName, fieldLabel) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: `Ganti Data ${fieldLabel}?`,
                text: `Apakah Anda ingin mengganti data ${fieldLabel}? Jika ya, kolom ini akan dibuka dan dikosongkan agar Anda dapat mengisinya secara manual.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f2470c',
                cancelButtonColor: '#737373',
                confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, ganti data',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    unmaskField(fieldName, fieldLabel);
                }
            });
        } else {
            if (confirm(`Apakah Anda ingin mengganti data ${fieldLabel}? Data akan dikosongkan.`)) {
                unmaskField(fieldName, fieldLabel);
            }
        }
    }

    /**
     * Membuka form yang didisable & MENGOSONGKAN isinya (jangan tampilkan data autofill)
     */
    function unmaskField(fieldName, fieldLabel) {
        const el = document.querySelector(`[name="${fieldName}"]`);
        if (!el) return;

        maskedFields.delete(fieldName);
        unlockedFields.add(fieldName);

        if (unlockedInput) {
            unlockedInput.value = Array.from(unlockedFields).join(',');
        }

        el.removeAttribute('tabindex');
        el.style.pointerEvents = '';

        // Hapus overlay sensor
        const overlay = el.parentElement.querySelector(`.mask-overlay-${fieldName}`);
        if (overlay) {
            overlay.remove();
        }

        // KOSONGKAN isian / kembalikan ke default - JANGAN tampilkan data dari hasil autofill!
        if (el.tagName === 'SELECT') {
            el.selectedIndex = 0;
            el.value = '';
            $(el).trigger('change');
            if (fieldName === 'kabupaten_id') {
                $('#kecamatan_id').html('<option selected disabled>Pilih Kecamatan</option>');
                $('#kelurahan_id').html('<option selected disabled>Pilih Kelurahan</option>');
            }
        } else {
            el.value = '';
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
        }

        setTimeout(() => {
            el.focus();
        }, 150);

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: `Kolom ${fieldLabel} telah dibuka. Silakan isi data baru.`,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        }
    }

    function setInputValue(name, value) {
        const elements = document.querySelectorAll(`[name="${name}"]`);
        if (!elements.length) return;

        elements.forEach(el => {
            if (el.type === 'radio') {
                if (String(el.value).toLowerCase() === String(value).toLowerCase()) {
                    el.checked = true;
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                }
            } else if (el.type === 'checkbox') {
                // Abaikan checkbox
            } else if (el.type === 'file') {
                // Abaikan input file foto
            } else {
                el.value = value;
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
})();
