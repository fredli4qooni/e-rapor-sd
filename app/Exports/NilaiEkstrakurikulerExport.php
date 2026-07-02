<?php

namespace App\Exports;

use App\Models\NilaiEkstrakurikuler;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiEkstrakurikulerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $ekstrakurikuler_id;
    protected $rombel_id;

    public function __construct($ekstrakurikuler_id, $rombel_id)
    {
        $this->ekstrakurikuler_id = $ekstrakurikuler_id;
        $this->rombel_id = $rombel_id;
    }

    public function collection()
    {
        return NilaiEkstrakurikuler::with(['siswa', 'ekstrakurikuler'])
            ->where('ekstrakurikuler_id', $this->ekstrakurikuler_id)
            ->where('rombel_id', $this->rombel_id)
            ->get()
            ->sortBy('siswa.nama_lengkap');
    }

    public function headings(): array
    {
        return [
            'ID Nilai Ekskul (Jangan Diubah)',
            'Nama Siswa',
            'NISN',
            'NIS',
            'Ekskul',
            'Predikat',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->siswa->nama_lengkap,
            $row->siswa->nisn,
            $row->siswa->nis,
            $row->ekstrakurikuler->nama_ekskul,
            $row->predikat,
            $row->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '991B1B']]],
        ];
    }
}
