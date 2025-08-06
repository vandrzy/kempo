<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    //
    protected $guarded = ['id'];


    public function kabupaten() {
        return $this->belongsTo(Kabupaten::class);
    }

    public function kecamatan() {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan() {
        return $this->belongsTo(Kelurahan::class);
    }

    public function PertandinganPeserta() {
        return $this->hasMany(PertandinganPeserta::class);
    }

    public function PertandinganPesertaUtama() {
        return $this->hasOne(PertandinganPeserta::class);
    }

    public function KegiatanPertandinganPeserta() {
        return $this->hasOne(PertandinganPeserta::class);
    }

}
