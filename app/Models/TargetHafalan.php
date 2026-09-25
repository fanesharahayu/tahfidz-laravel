<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetHafalan extends Model
{
    protected $table = 'target_hafalan';

    protected $fillable = [
        'santri_id',
        'target_juz',
        'periode',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }
}
