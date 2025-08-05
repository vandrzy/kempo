<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kategori extends Model
{
    //
    protected $guarded = ['id'];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function pertandingan()
    {
        return $this->hasMany(Pertandingan::class);
    }

    public function list_kategori()  {
        return $this->hasMany(ListKategori::class);
    }

    public function totalEmasPerJenisKelamin()
    {
        return $this->pertandingan()
            ->select('jenis_kelamin', DB::raw('SUM(emas) as total_emas'))
            ->groupBy('jenis_kelamin')
            ->get();
    }
}
