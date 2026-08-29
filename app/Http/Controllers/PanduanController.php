<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\Semester;

class PanduanController extends Controller
{
    public function index(Request $request)
    {
        $sekolah = Sekolah::first();
        $semesterAktif = Semester::where('is_aktif', true)->first();
        $role = auth()->user()->role ?? 'siswa';

        return view('panduan.index', compact('sekolah', 'semesterAktif', 'role'));
    }
}
