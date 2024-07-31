<?php

namespace App\Http\Controllers;

use App\Models\DisciplinaryModel;
use Illuminate\Http\Request;

class DisciplinaryController extends Controller
{
    function getView()
    {
        $disciplinaries = DisciplinaryModel::all();
        return view('auth.disciplinary.disciplinary', compact('disciplinaries'));
    }
}
