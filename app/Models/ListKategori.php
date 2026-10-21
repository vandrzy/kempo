<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListKategori extends Model
{
    //
    protected $guarded = ['id'];

    protected $casts = [
        'data_scoresheet' => 'array',
        'data_juara' => 'array',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function pertandingan()
    {
        return $this->belongsTo(Pertandingan::class);
    }

    public function list_kategori() {
        return $this->hasMany(PertandinganPeserta::class);
    }
}
