<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
