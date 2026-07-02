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

class NilaiKokurikulerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $kelompok_id;
    protected $proyek_id;
    protected $sub_elemens;

    public function __construct($kelompok_id, $proyek_id)
    {
        $this->kelompok_id = $kelompok_id;
        $this->proyek_id = $proyek_id;
        
        $proyek = P5Proyek::with('targetSubElemens.elemen.dimensi')->find($proyek_id);
        $this->sub_elemens = $proyek ? $proyek->targetSubElemens->sortBy('elemen.p5_dimensi_id') : collect();
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
            $dimensi_nama = $se->elemen && $se->elemen->dimensi ? $se->elemen->dimensi->nama_dimensi : '';
            $headings[] = "[$dimensi_nama] " . substr($se->nama_sub_elemen, 0, 30) . "... [ID_SUB: " . $se->id . "]";
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
        // Add note
        $lastRow = $this->collection()->count() + 3;
        $sheet->setCellValue('A' . $lastRow, 'Petunjuk Pengisian:');
        $sheet->setCellValue('A' . ($lastRow + 1), 'Isi kolom subdimensi dengan angka:');
        $sheet->setCellValue('A' . ($lastRow + 2), '1 = Berkembang');
        $sheet->setCellValue('A' . ($lastRow + 3), '2 = Cakap');
        $sheet->setCellValue('A' . ($lastRow + 4), '3 = Mahir');

        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '991B1B']]],
        ];
    }
}
