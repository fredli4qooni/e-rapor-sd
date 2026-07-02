<?php

namespace App\Services;

class CurriculumService
{
    /**
     * Determines which view template to use for rendering the report card.
     */
    public function getRaporView(string $kurikulum): string
    {
        if (strtoupper($kurikulum) === 'K2013') {
            return 'walikelas.cetak.rapor_k13';
        }

        if (strtoupper($kurikulum) === 'MERDEKA') {
            return 'walikelas.cetak.rapor_merdeka';
        }

        // Default fallback
        return 'walikelas.cetak.rapor_merdeka';
    }

    /**
     * Get the grading scale logic specific to the curriculum.
     */
    public function getGradingScale(string $kurikulum): array
    {
        if (strtoupper($kurikulum) === 'K2013') {
            return [
                'type' => 'alphabetical', // A, B, C, D
                'kkm_dependent' => true,
                'has_spiritual_social_aspects' => true
            ];
        }

        return [
            'type' => 'numerical', // 0-100 with TP descriptions
            'kkm_dependent' => false,
            'has_p5_project' => true
        ];
    }
}
