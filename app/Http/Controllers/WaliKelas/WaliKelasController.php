<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LegerExport;

class WaliKelasController extends Controller
{
    public function dashboard()
    {
        return view('walikelas.dashboard');
    }

    public function exportLeger()
    {
        return Excel::download(new LegerExport, 'leger_nilai_kelas.xlsx');
    }
}
