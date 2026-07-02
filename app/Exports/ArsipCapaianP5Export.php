<?php

namespace App\Exports;

use App\Models\P5Kelompok;
use App\Models\P5Proyek;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ArsipCapaianP5Export implements WithMultipleSheets
{
    use Exportable;

    protected $kelompok_id;
    protected $proyek_id;

    public function __construct($kelompok_id, $proyek_id)
    {
        $this->kelompok_id = $kelompok_id;
        $this->proyek_id = $proyek_id;
    }

    public function sheets(): array
    {
        $sheets = [];

        $proyek = P5Proyek::with('targetSubElemens.elemen.dimensi')->find($this->proyek_id);
        if (!$proyek) return [];

        $targetSubElemens = $proyek->targetSubElemens;
        $dimensi_ids = $targetSubElemens->pluck('elemen.p5_dimensi_id')->filter()->unique();
        $dimensis = \App\Models\P5Dimensi::whereIn('id', $dimensi_ids)->get();

        foreach ($dimensis as $dimensi) {
            // Get sub elements for this dimensi
            $sub_elemens_dimensi = $targetSubElemens->filter(function($se) use ($dimensi) {
                return $se->elemen->p5_dimensi_id == $dimensi->id;
            });
            
            $sheets[] = new SheetCapaianP5Export($this->kelompok_id, $this->proyek_id, $dimensi, $sub_elemens_dimensi);
        }

        return $sheets;
    }
}
