<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Official extends Model
{
    //
    protected $guarded = ['id'];

    public function kabupaten() {
        return $this->belongsTo(Kabupaten::class);
    }
}
