<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    //
    protected $guarded = ['id'];

    public function penulis() {
        return $this->belongsTo(Penulis::class);
    }

    public function KategoriBuku() {
        return $this->hasMany(KategoriBuku::class);
    }
}
