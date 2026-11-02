<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPerubahanBagan extends Model
{
    protected $guarded = ['id'];

    public function list_kategori()
    {
        return $this->belongsTo(ListKategori::class, 'list_kategori_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function matchAsal()
    {
        return $this->belongsTo(PesertaTerpilih::class, 'peserta_terpilih_id_asal', 'id');
    }

    public function matchTujuan()
    {
        return $this->belongsTo(PesertaTerpilih::class, 'peserta_terpilih_id_tujuan', 'id');
    }

    public function atletAsal()
    {
        return $this->belongsTo(PertandinganPeserta::class, 'atlet_asal_id', 'id');
    }

    public function atletTujuan()
    {
        return $this->belongsTo(PertandinganPeserta::class, 'atlet_tujuan_id', 'id');
    }
}
