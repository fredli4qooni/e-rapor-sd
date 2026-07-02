<?php

namespace App\Exports;

use App\Models\P5Kelompok;
use App\Models\P5Nilai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SheetCapaianP5Export implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $kelompok_id;
    protected $proyek_id;
    protected $dimensi;
    protected $sub_elemens;

    public function __construct($kelompok_id, $proyek_id, $dimensi, $sub_elemens)
    {
        $this->kelompok_id = $kelompok_id;
        $this->proyek_id = $proyek_id;
        $this->dimensi = $dimensi;
        $this->sub_elemens = $sub_elemens;
    }

    public function collection()
    {
        $kelompok = P5Kelompok::with('siswas')->find($this->kelompok_id);
        return $kelompok ? $kelompok->siswas()->orderBy('nama_lengkap')->get() : collect();
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Nama Siswa',
            'NISN',
            'NIS'
        ];

        foreach ($this->sub_elemens as $se) {
            $headings[] = $se->nama_sub_elemen;
        }

        return $headings;
    }

    public function map($siswa): array
    {
        static $no = 1;
        $row = [
            $no++,
            $siswa->nama_lengkap,
            $siswa->nisn,
            $siswa->nis,
        ];

        foreach ($this->sub_elemens as $se) {
            $nilai = P5Nilai::where('siswa_id', $siswa->id)
                ->where('p5_proyek_id', $this->proyek_id)
                ->where('p5_sub_elemen_id', $se->id)
                ->first();
                
            $row[] = $nilai ? $nilai->capaian : '-';
        }

        return $row;
    }

    public function title(): string
    {
        // Title cannot exceed 31 chars
        return substr($this->dimensi->nama_dimensi, 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '991B1B']]],
        ];
    }
}
