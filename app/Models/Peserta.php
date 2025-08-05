<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    //
    protected $guarded = ['id'];


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
