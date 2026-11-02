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
}
