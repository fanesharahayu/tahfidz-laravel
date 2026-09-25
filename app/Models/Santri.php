<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $table = 'santri';

    protected $fillable = [
        'user_id',
        'musyrif_id',
        'nis',
        'kelas',
        'target_juz',
        'tanggal_bergabung',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bergabung' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function musyrif()
    {
        return $this->belongsTo(User::class, 'musyrif_id');
    }

    public function setoran()
    {
        return $this->hasMany(Setoran::class, 'santri_id');
    }

    public function targets()
    {
        return $this->hasMany(TargetHafalan::class, 'santri_id');
    }

    public function waliLinks()
    {
        return $this->hasMany(WaliSantri::class, 'santri_id');
    }
}
