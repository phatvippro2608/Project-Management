<?php

namespace App\Http\Controllers;

use App\Models\CreateQuizModel;
use Illuminate\Http\Request;

class CreateQuizController extends Controller
{
     function getView() {
         $model = new CreateQuizModel();
        return view('auth.quiz.create-quiz',['courses' => $model->getCourse()]);
    }

    function add(Request $request)
    {
        $validated = $request->validate([
            'add_question' => 'required|string',
            'add_answer_a' => 'required|string',
            'add_answer_b' => 'required|string',
            'add_answer_c' => 'required|string',
            'add_answer_d' => 'required|string',
            'add_correct_answer' => 'required|string'
        ]);

        if ($request->hasFile('add_question_img')) {
            $file = $request->file('add_question_img');
            $imagePath = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/question_bank_image/'), $imagePath);
        }

        CreateQuizModel::create([
            'question' => $validated['add_question'],
            'question_image' => $validated['add_question_img'],
            'answer_a' => $validated['add_answer_a'],
            'answer_b' => $validated['add_answer_b'],
            'answer_c' => $validated['add_answer_c'],
            'answer_d' => $validated['add_answer_d'],
            'correct' => $validated['add_correct_answer']
        ]);

        return response()->json([
            'success' => true,
            'status'  => 200,
            'message' => 'Question added successfully',
        ]);
    }
}
