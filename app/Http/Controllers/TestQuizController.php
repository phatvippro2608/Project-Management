<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestQuizController extends Controller
{
    function getView()
    {
        return view('auth.quiz.test-quiz');
    }
}
