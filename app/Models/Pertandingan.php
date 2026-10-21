<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pertandingan extends Model
{
    //
    protected $guarded = ['id'];

    protected $casts = [
        'data_scoresheet' => 'array',
    ];

    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }

    public function totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)
    {
        if($jenis_kelamin == 'Mix') {
            return $this->select('jenis_kelamin', DB::raw('SUM(emas) as total_emas'), DB::raw('SUM(perak) as total_perak'), DB::raw('SUM(perunggu) as total_perunggu'))
                ->where('kategori_id', $kategori_id)
                ->whereNull('jenis_kelamin')
                ->groupBy('jenis_kelamin')
                ->first();
        } else {
            return $this->select('jenis_kelamin', DB::raw('SUM(emas) as total_emas'), DB::raw('SUM(perak) as total_perak'), DB::raw('SUM(perunggu) as total_perunggu'))
                ->where('kategori_id', $kategori_id)
                ->where('jenis_kelamin', $jenis_kelamin)
                ->groupBy('jenis_kelamin')
                ->first();
        }
    }

}
