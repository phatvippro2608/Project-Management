<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisciplinaryTypeController extends Controller
{
    public function getView()
    {
        $disciplinary_types = DB::table('disciplinary_types')->get();

        // Hiển thị kết quả với view recognition
        return view('auth.disciplinary.disciplinary-type', [
            'disciplinary_types' => $disciplinary_types,
            'title' => 'Disciplinary'
        ]);
    }
}
