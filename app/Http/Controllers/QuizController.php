<?php

namespace App\Http\Controllers;

use App\Models\CreateQuizModel;
use App\Models\QuizModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    function getView()
    {
        $model = new QuizModel();
//        dd($model->getInfo());
        return view('auth.quiz.quiz',
            ['data'=>$model->getInfo()]);
    }

    function getViewQuestionBank() {
        $model = new QuizModel();
        return view('auth.quiz.question-bank',['courses' => $model->getCourse()]);
    }

    function addQuestion(Request $request)
    {
//        dd($request);
        $validated = $request->validate([
            'add_question' => 'required|string',
            'add_answer_a' => 'required|string',
            'add_answer_b' => 'required|string',
            'add_answer_c' => 'required|string',
            'add_answer_d' => 'required|string',
            'add_correct_answer' => 'required|string',
            'add_course_name' => 'required|int'
        ]);

        $imagePath = null;
        if ($request->hasFile('add_question_img')) {
            $file = $request->file('add_question_img');
            $imagePath = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('question_bank_image/'), $imagePath);
        }

        QuizModel::create([
            'question' => $validated['add_question'],
            'question_image' => $imagePath,
            'question_a' => $validated['add_answer_a'],
            'question_b' => $validated['add_answer_b'],
            'question_c' => $validated['add_answer_c'],
            'question_d' => $validated['add_answer_d'],
            'correct' => $validated['add_correct_answer'],
            'course_id' => $validated['add_course_name']
        ]);

        return response()->json([
            'success' => true,
            'status'  => 200,
            'message' => 'Question added successfully',
        ]);
    }

    function getQuestionList($id)
    {
        $question_list = DB::table('question_bank')
            ->join('courses','question_bank.course_id','=','courses.course_id')
            ->where('question_bank.course_id', $id)
            ->get();
        return response()->json([
            'question_list' => $question_list,
        ]);
    }

    public function destroy($id)
    {
//        dd($id);
        $question = QuizModel::findOrFail($id);

        $directoryPath = public_path('question_bank_image/');
        $filePath = $directoryPath . $question->question_image;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $question->delete();
        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully'
        ]);
    }

    public function edit($id)
    {
        $question = QuizModel::findOrFail($id);

        return response()->json([
            'question' => $question,
        ]);
    }

    function update(Request $request, $id)
    {
//        dd($request, $id);
        $validated = $request->validate([
            'question' => 'string',
            'question_a' => 'string',
            'question_b' => 'string',
            'question_c' => 'string',
            'question_d' => 'string',
            'correct' => 'string',
            'course_id' => 'int'
        ]);

        $model = new QuizModel();
        $imgOld = $model->getQuestionImg($id);

        $imagePath = $imgOld;
//        dd($request->hasFile('question_image'));
        if ($request->hasFile('question_image')) {
            $file = $request->file('question_image');
            $imagePath = time() . '_' . $file->getClientOriginalName();
            // Move the uploaded file to the desired location
            $file->move(public_path('question_bank_image/'), $imagePath);
        }


        // Find the question by ID and update the data
        $question = QuizModel::findOrFail($id);
        $question->update(array_merge($validated, ['question_image' => $imagePath]));

        // Return a JSON response indicating success
        return response()->json([
            'success' => true,
            'question' => $question,
        ]);
    }
}
