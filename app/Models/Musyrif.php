<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Musyrif extends Model
{
    protected $table = 'musyrif';

    protected $fillable = [
        'user_id',
        'spesialisasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
