<?php

namespace App\Imports;

use App\Models\TujuanPembelajaran;
use App\Models\Semester;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TujuanPembelajaranImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $semesterId;
    protected $mataPelajaranId;

    public function __construct($mataPelajaranId)
    {
        $this->mataPelajaranId = $mataPelajaranId;
        $semesterAktif = Semester::where('is_aktif', true)->first();
        $this->semesterId = $semesterAktif ? $semesterAktif->id : null;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row['tingkat']) || empty($row['deskripsi'])) {
            return null;
        }

        return new TujuanPembelajaran([
            'mata_pelajaran_id' => $this->mataPelajaranId,
            'tingkat'           => $row['tingkat'],
            'semester_id'       => $this->semesterId,
            'deskripsi'         => trim($row['deskripsi']),
            'is_aktif'          => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'tingkat' => 'required|integer|min:1|max:6',
            'deskripsi' => 'required|string|max:500',
        ];
    }
}
