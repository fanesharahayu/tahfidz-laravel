<?php

namespace App\Helpers;

class ProgressHelper
{
    /**
     * Port dari computeProgress() di utils/helper.js (Express lama).
     *
     * @param  iterable  $setorans  Collection/array Setoran (punya jenis, juz, nilai)
     */
    public static function computeProgress(iterable $setorans, ?int $targetJuz = 30): array
    {
        $list = $setorans instanceof \Traversable ? iterator_to_array($setorans) : (array) $setorans;

        $juzSet = [];
        $totalHafalanBaru = 0;
        $lancar = 0;

        foreach ($list as $s) {
            $jenis = is_array($s) ? ($s['jenis'] ?? null) : ($s->jenis ?? null);
            $juz = is_array($s) ? ($s['juz'] ?? null) : ($s->juz ?? null);
            $nilai = is_array($s) ? ($s['nilai'] ?? null) : ($s->nilai ?? null);

            if ($jenis !== 'murajaah' && $juz !== null) {
                $juzSet[$juz] = true;
            }
            if ($jenis === 'hafalan_baru') {
                $totalHafalanBaru++;
            }
            if ($nilai === 'lancar') {
                $lancar++;
            }
        }

        $targetJuz = $targetJuz ?: 30;
        $juzTercapai = count($juzSet);

        return [
            'juzTercapai' => $juzTercapai,
            'targetJuz' => $targetJuz,
            'persenJuz' => $targetJuz ? min(100, (int) round($juzTercapai / $targetJuz * 100)) : 0,
            'totalSetoran' => count($list),
            'totalHafalanBaru' => $totalHafalanBaru,
            'lancar' => $lancar,
        ];
    }
}
