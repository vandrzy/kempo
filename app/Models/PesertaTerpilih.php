<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaTerpilih extends Model
{
    protected $guarded = ['id'];

    public function PertandinganPesertaPertama()
    {
        return $this->belongsTo(PertandinganPeserta::class, 'pertandingan_peserta_kiri_id', 'id');
    }

    public function PertandinganPesertaKedua()
    {
        return $this->belongsTo(PertandinganPeserta::class, 'pertandingan_peserta_kanan_id', 'id');
    }

    public function pemenang()
    {
        return $this->belongsTo(PertandinganPeserta::class, 'pemenang_id', 'id');
    }

    public function list_kategori()
    {
        return $this->belongsTo(ListKategori::class, 'list_kategori_id', 'id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'id');
    }

    public function pertandingan()
    {
        return $this->belongsTo(Pertandingan::class, 'pertandingan_id', 'id');
    }

    public function getNamaBabakAttribute(): string
    {
        return match ((int) $this->babak) {
            1 => 'Penyisihan',
            2 => 'Semifinal',
            3 => 'Final',
            default => 'Babak ' . ($this->babak ?: 1),
        };
    }
}
