<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kegiatan extends Model
{
    //
    protected $guarded = ['id'];

    public function kategori_terpilih()
    {
        return $this->hasMany(ListKategori::class);
    }

    public function pertandingan_terpilih()
    {
        return $this->hasMany(ListKategori::class);
    }

    public function peserta_terdaftar() {
        return $this->hasMany(PertandinganPeserta::class)
                ->select('peserta_id', DB::raw('MIN(id) as id'))
                ->groupBy('peserta_id')->whereHas('list_kategori');
    }

}
