<?php

namespace App\Exports;

use App\Models\P5Kelompok;
use App\Models\P5Proyek;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FormatImportP5Export implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $kelompok_id;
    protected $proyek_id;
    protected $sub_elemens;

    public function __construct($kelompok_id, $proyek_id)
    {
        $this->kelompok_id = $kelompok_id;
        $this->proyek_id = $proyek_id;
        
        $proyek = P5Proyek::with('targetSubElemens')->find($proyek_id);
        $this->sub_elemens = $proyek ? $proyek->targetSubElemens : collect();
    }

    public function collection()
    {
        $kelompok = P5Kelompok::with('siswas')->find($this->kelompok_id);
        return $kelompok ? $kelompok->siswas()->orderBy('nama_lengkap')->get() : collect();
    }

    public function headings(): array
    {
        $headings = [
            'ID Siswa (Jangan Diubah)',
            'Nama Siswa',
            'NISN',
            'NIS'
        ];

        foreach ($this->sub_elemens as $se) {
            $headings[] = "Subelemen: " . substr($se->nama_sub_elemen, 0, 30) . "... [ID_SUB: " . $se->id . "]";
        }

        return $headings;
    }

    public function map($siswa): array
    {
        $row = [
            $siswa->id,
            $siswa->nama_lengkap,
            $siswa->nisn,
            $siswa->nis,
        ];

        // Fill empty columns for subelements to be filled by user
        foreach ($this->sub_elemens as $se) {
            $row[] = ''; 
        }

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '991B1B']]],
        ];
    }
}
