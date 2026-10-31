<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListKategori extends Model
{
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

    public function list_kategori()
    {
        return $this->hasMany(PertandinganPeserta::class);
    }

    public function pertandingan_peserta()
    {
        return $this->hasMany(PertandinganPeserta::class);
    }

    public function peserta_terpilih()
    {
        return $this->hasMany(PesertaTerpilih::class, 'list_kategori_id', 'id');
    }

    public function isBagan(): bool
    {
        return (bool) (
            optional($this->pertandingan)->bagan == 1 ||
            stripos(optional($this->pertandingan)->nama ?? '', 'randori') !== false ||
            stripos(optional($this->kategori)->nama ?? '', 'randori') !== false
        );
    }

    public function isRandori(): bool
    {
        return $this->isBagan();
    }
}
