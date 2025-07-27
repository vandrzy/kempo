<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    //
    protected $guarded = ['id'];

    public function buku() {
        return $this->belongsTo(Buku::class);
    }

    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }
}
