<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaliSantri extends Model
{
    protected $table = 'wali_santri';

    protected $fillable = [
        'wali_user_id',
        'santri_id',
        'relasi',
    ];

    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_user_id');
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }
}
