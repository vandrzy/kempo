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
}
