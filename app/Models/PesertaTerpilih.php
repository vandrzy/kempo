<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaTerpilih extends Model
{
    //
    protected $guarded = ['id'];

    public function PertandinganPesertaPertama() {
        return $this->belongsTo(PertandinganPeserta::class, 'pertandingan_peserta_kiri_id', 'id');
    }

    public function PertandinganPesertaKedua() {
        return $this->belongsTo(PertandinganPeserta::class, 'pertandingan_peserta_kanan_id', 'id');
    }
}
