<?php

namespace App\Http\Controllers;

use App\Models\QuizModel;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    function getView()
    {
        $model = new QuizModel();
        return view('auth.quiz.quiz', ['data'=>$model->getInfo()]);
    }
}
