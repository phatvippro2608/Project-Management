<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreateQuizController extends Controller
{
     function getView() {
        return view('auth.quiz.create-quiz');
    }
}
