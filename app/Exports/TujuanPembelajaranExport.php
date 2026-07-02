<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TujuanPembelajaranExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            // [1, 'memahami makna kedaulatan rakyat dalam sistem pemerintahan Indonesia'],
            // [1, 'menganalisis pelaksanaan sistem pemerintahan yang baik'],
        ];
    }

    public function headings(): array
    {
        return [
            'tingkat',
            'deskripsi',
        ];
    }
}
