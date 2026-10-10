<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasmi extends Model
{
    protected $table = 'tasmi';

    protected $fillable = [
        'santri_id',
        'musyrif_id',
        'juz',
        'surah',
        'ayat_awal',
        'ayat_akhir',
        'nilai',
        'catatan',
        'tanggal_tasmi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_tasmi' => 'date',
        ];
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function musyrif()
    {
        return $this->belongsTo(User::class, 'musyrif_id');
    }
}