<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class P5MasterController extends Controller
{
    public function index()
    {
        $temas = \App\Models\P5Tema::all();
        $dimensis = \App\Models\P5Dimensi::with('elemens.subElemens')->get();

        return view('admin.p5.master.index', compact('temas', 'dimensis'));
    }
}
