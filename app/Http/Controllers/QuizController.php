<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    function getView()
    {
        return view('auth.quiz.quiz');
    }
}
