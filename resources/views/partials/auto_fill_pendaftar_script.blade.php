{{-- Script Auto-Fill Data Lama Pendaftar (Peserta & Official) --}}
(function () {
    const TIPE_PENDAFTAR = '{{ $tipe ?? "peserta" }}';
    const inputNama = document.querySelector('input[name="nama"]');
    const confirmBox = document.getElementById('existing-data-confirmation');
    const refInput = document.getElementById('existing-reference-id');
    let debounceTimer = null;
    let userDismissedQuery = '';
    let isAutoFilling = false;

    if (!inputNama || !confirmBox) return;

    inputNama.addEventListener('input', function () {
        if (isAutoFilling) return;

        clearTimeout(debounceTimer);
        const query = this.value.trim();

        // Jangan munculkan container jika query masih sama dengan nama yang baru saja dikonfirmasi/diabaikan
        if (userDismissedQuery && query.toLowerCase() === userDismissedQuery.toLowerCase()) {
            confirmBox.classList.add('hidden');
            confirmBox.innerHTML = '';
            return;
        }

        if (query.length < 3) {
            confirmBox.classList.add('hidden');
            confirmBox.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            const currentQuery = inputNama.value.trim();
            if (userDismissedQuery && currentQuery.toLowerCase() === userDismissedQuery.toLowerCase()) {
                confirmBox.classList.add('hidden');
                confirmBox.innerHTML = '';
                return;
            }

            fetch(`{{ url('/api/check-existing-pendaftar') }}?nama=${encodeURIComponent(currentQuery)}&tipe=${TIPE_PENDAFTAR}`)
                .then(res => res.json())
                .then(res => {
                    if (userDismissedQuery && inputNama.value.trim().toLowerCase() === userDismissedQuery.toLowerCase()) {
                        confirmBox.classList.add('hidden');
                        confirmBox.innerHTML = '';
                        return;
                    }

                    confirmBox.innerHTML = '';

                    if (!res.found || !res.items.length) {
                        confirmBox.classList.add('hidden');
                        return;
                    }

                    res.items.forEach(item => {
                        const alertEl = document.createElement('div');
                        alertEl.className = 'p-3.5 rounded-2xl bg-[#fff0eb] border border-[#f2470c]/25 shadow-2xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs transition-all';
                        
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

                        // Event ketika klik "Ya, Gunakan Data"
                        alertEl.querySelector('.btn-use-data').addEventListener('click', function () {
                            userDismissedQuery = inputNama.value.trim();
                            confirmBox.classList.add('hidden');
                            confirmBox.innerHTML = '';

                            isAutoFilling = true;
                            fillPersonalData(item.id, item.personal_data);
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
                                    title: 'Data pribadi berhasil dimuat!',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                            }
                        });

                        // Event ketika klik "Abaikan" -> Sembunyikan seluruh container konfirmasi
                        alertEl.querySelector('.btn-ignore-data').addEventListener('click', function () {
                            userDismissedQuery = inputNama.value.trim();
                            confirmBox.classList.add('hidden');
                            confirmBox.innerHTML = '';
                        });

                        confirmBox.appendChild(alertEl);
                    });

                    confirmBox.classList.remove('hidden');
                })
                .catch(err => console.error('Gagal memeriksa data pendaftar:', err));
        }, 400);
    });

    function fillPersonalData(id, data) {
        if (refInput) refInput.value = id;

        // Daftar field yang DILARANG di-autofill (kategori pertandingan, foto, token)
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

            // Wilayah ditangani secara spesifik berurutan
            if (key === 'kabupaten_id' || key === 'kecamatan_id' || key === 'kelurahan_id') {
                return;
            }

            setInputValue(key, data[key]);
        });

        // Tangani wilayah bertingkat untuk Peserta (Kabupaten -> Kecamatan -> Kelurahan)
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
