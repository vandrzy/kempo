<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PertandinganPeserta extends Model
{
    protected $fillable = [
        'pertandingan_id',
        'peserta_id',
        'main_kegiatan_id',
        'list_kategori_id',
        'kegiatan_id',
        'sudah_terpilih',
        'urutan',
        'waktu',
        'nilai_1',
        'nilai_2',
        'nilai_3',
        'nilai_4',
        'nilai_5',
        'minus',
        'total_nilai',
        'medali',
    ];

    public function Peserta() {
        return $this->belongsTo(Peserta::class);
    }

    public function list_kategori() {
        return $this->belongsTo(ListKategori::class);
    }

    public function UrutanKomposisiPeserta() {
        return $this->hasOne(UrutanKomposisiPeserta::class);
    }

    public function kegiatan() {
        return $this->belongsTo(Kegiatan::class);
    }
}
