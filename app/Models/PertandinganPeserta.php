<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PertandinganPeserta extends Model
{
    //
    protected $guarded = ['id'];

    public function Pertandingan() {
        return $this->belongsTo(Pertandingan::class);
    }

    public function Peserta() {
        return $this->belongsTo(Peserta::class);
    }

    public function kegiatan() {
        return $this->belongsTo(Kegiatan::class);
    }

}
