<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kabupaten extends Model
{
    //
    protected $guarded = ['id'];

    public function peserta() {
        // return $this->hasMany(Peserta::class)->whereHas('PertandinganPeserta', function($q) {
        //     $q->select('peserta_id', DB::raw('MIN(id) as id'))
        //         ->groupBy('peserta_id')->whereHas('list_kategori');
        // });

        return $this->hasMany(Peserta::class);
    }

    public function KontigenPertandinganPertama() {
        return $this->hasOne(KontigenPertandingan::class)
                ->where('urutan', 1);
    }

    public function KontigenPertandinganKedua() {
        return $this->hasOne(KontigenPertandingan::class)
                ->where('urutan', 2);
    }
}
