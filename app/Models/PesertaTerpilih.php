<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaTerpilih extends Model
{
    /**
     * Bobot nilai (poin) teknik kategori Randori Shorinji Kempo
     */
    public const RANDORI_POINTS = [
        'mujoken_kachi' => 15,
        'ippon'         => 10,
        'waza_ari'      => 5,
        'batsu_5'       => 5,
        'batsu_10'      => 10,
        'yusei_kachi'   => 5,
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'merah_mujoken_kachi' => 'integer',
        'merah_ippon'         => 'integer',
        'merah_waza_ari'      => 'integer',
        'merah_batsu_5'       => 'integer',
        'merah_batsu_10'      => 'integer',
        'merah_yusei_kachi'   => 'integer',
        'putih_mujoken_kachi' => 'integer',
        'putih_ippon'         => 'integer',
        'putih_waza_ari'      => 'integer',
        'putih_batsu_5'       => 'integer',
        'putih_batsu_10'      => 'integer',
        'putih_yusei_kachi'   => 'integer',
        'skor_kiri'           => 'float',
        'skor_kanan'          => 'float',
    ];

    /**
     * Hitung total skor dari frekuensi teknik yang dilakukan (Array teknik => count)
     */
    public static function calculateScoreFromTeknik(array $teknikCounts): int
    {
        $total = 0;
        foreach (self::RANDORI_POINTS as $teknik => $poin) {
            $total += max(0, (int) ($teknikCounts[$teknik] ?? 0)) * $poin;
        }
        return $total;
    }

    /**
     * Hitung total skor sudut merah berdasarkan frekuensi teknik
     */
    public function hitungTotalSkorMerah(): int
    {
        return ($this->merah_mujoken_kachi * self::RANDORI_POINTS['mujoken_kachi'])
            + ($this->merah_ippon * self::RANDORI_POINTS['ippon'])
            + ($this->merah_waza_ari * self::RANDORI_POINTS['waza_ari'])
            + ($this->merah_batsu_5 * self::RANDORI_POINTS['batsu_5'])
            + ($this->merah_batsu_10 * self::RANDORI_POINTS['batsu_10'])
            + ($this->merah_yusei_kachi * self::RANDORI_POINTS['yusei_kachi']);
    }

    /**
     * Hitung total skor sudut putih berdasarkan frekuensi teknik
     */
    public function hitungTotalSkorPutih(): int
    {
        return ($this->putih_mujoken_kachi * self::RANDORI_POINTS['mujoken_kachi'])
            + ($this->putih_ippon * self::RANDORI_POINTS['ippon'])
            + ($this->putih_waza_ari * self::RANDORI_POINTS['waza_ari'])
            + ($this->putih_batsu_5 * self::RANDORI_POINTS['batsu_5'])
            + ($this->putih_batsu_10 * self::RANDORI_POINTS['batsu_10'])
            + ($this->putih_yusei_kachi * self::RANDORI_POINTS['yusei_kachi']);
    }

    public function PertandinganPesertaPertama()
    {
        return $this->belongsTo(PertandinganPeserta::class, 'pertandingan_peserta_kiri_id', 'id');
    }

    public function PertandinganPesertaKedua()
    {
        return $this->belongsTo(PertandinganPeserta::class, 'pertandingan_peserta_kanan_id', 'id');
    }

    public function pemenang()
    {
        return $this->belongsTo(PertandinganPeserta::class, 'pemenang_id', 'id');
    }

    public function list_kategori()
    {
        return $this->belongsTo(ListKategori::class, 'list_kategori_id', 'id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'id');
    }

    public function pertandingan()
    {
        return $this->belongsTo(Pertandingan::class, 'pertandingan_id', 'id');
    }

    public function getNamaBabakAttribute(): string
    {
        return match ((int) $this->babak) {
            1 => 'Penyisihan',
            2 => 'Semifinal',
            3 => 'Final',
            default => 'Babak ' . ($this->babak ?: 1),
        };
    }

    /**
     * Hitung koordinat pertandingan di babak berikutnya (next match pointer)
     * berdasarkan rumus pohon biner eliminasi:
     * - Next Babak = babak + 1
     * - Next Nomor Partai = ceil(nomor_partai / 2)
     * - Sudut: Ganjil = 'kiri' (Merah), Genap = 'kanan' (Putih)
     */
    public function getNextMatchCoordinates(): array
    {
        $currentPartai = (int) ($this->nomor_partai ?: 1);
        $currentBabak = (int) ($this->babak ?: 1);

        // Kasus khusus bagan 6 peserta dengan dua partai BYE tambahan di Babak 1.
        if ($currentBabak === 1 && $this->list_kategori_id) {
            $b1Count = self::where('list_kategori_id', $this->list_kategori_id)->where('babak', 1)->count();
            $b2Count = self::where('list_kategori_id', $this->list_kategori_id)->where('babak', 2)->count();
            if ($b1Count === 4 && $b2Count === 2) {
                if ($currentPartai <= 2) {
                    return [
                        'babak'        => 2,
                        'nomor_partai' => $currentPartai,
                        'sudut'        => 'kiri',
                    ];
                }

                return [
                    'babak'        => 2,
                    'nomor_partai' => $currentPartai - 2,
                    'sudut'        => 'kanan',
                ];
            }

            if ($b1Count === 2 && $b2Count === 2) {
                return [
                    'babak'        => 2,
                    'nomor_partai' => $currentPartai,
                    'sudut'        => 'kiri',
                ];
            }
        }

        return [
            'babak'        => $currentBabak + 1,
            'nomor_partai' => (int) ceil($currentPartai / 2),
            'sudut'        => ($currentPartai % 2 !== 0) ? 'kiri' : 'kanan',
        ];
    }

    /**
     * Dapatkan teks placeholder atlet menunggu pemenang babak sebelumnya
     */
    public function getWaitingPlaceholderText(string $corner = 'kiri'): string
    {
        $babak = (int) ($this->babak ?: 1);
        $prevBabak = $babak - 1;
        if ($prevBabak < 1) {
            return 'Belum Ada';
        }

        // Kasus khusus bagan 6 peserta (Babak 2 ada 2 partai dan Babak 1 ada 2 partai)
        // Partai 1 Babak 2 (Semifinal 1) menunggu Babak 1 Partai 1
        // Partai 2 Babak 2 (Semifinal 2) menunggu Babak 1 Partai 2
        if ($babak === 2 && $this->list_kategori_id) {
            $b1Count = self::where('list_kategori_id', $this->list_kategori_id)->where('babak', 1)->count();
            $b2Count = self::where('list_kategori_id', $this->list_kategori_id)->where('babak', 2)->count();
            if ($b1Count === 2 && $b2Count === 2) {
                if ($corner === 'kiri') {
                    return "Menunggu Pemenang Babak 1 Partai #{$this->nomor_partai}";
                }
            }
        }

        $sourcePartai = ($corner === 'kiri')
            ? (2 * (int) $this->nomor_partai) - 1
            : 2 * (int) $this->nomor_partai;

        return "Menunggu Pemenang Babak {$prevBabak} Partai #{$sourcePartai}";
    }

    /**
     * Cek apakah partai ini sudah dimainkan / memiliki skor atau pemenang
     */
    public function isPlayed(): bool
    {
        return !empty($this->pemenang_id)
            || floatval($this->skor_kiri) > 0
            || floatval($this->skor_kanan) > 0
            || in_array(strtoupper($this->keterangan_menang ?? ''), ['HANSOKU', 'KIKEN', 'POIN', 'HANTEI']);
    }

    /**
     * Cek apakah partai ini berstatus BYE
     */
    public function isBye(): bool
    {
        return strtoupper($this->keterangan_menang ?? '') === 'BYE'
            || (!empty($this->pertandingan_peserta_kiri_id) && empty($this->pertandingan_peserta_kanan_id))
            || (empty($this->pertandingan_peserta_kiri_id) && !empty($this->pertandingan_peserta_kanan_id));
    }
}
