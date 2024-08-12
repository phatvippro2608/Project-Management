<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestQuizController extends Controller
{
    public function getView(Request $request)
    {
        // Lấy giá trị từ query
        $course_id = $request->query('course_id');
        $employee_id = $request->query('employee_id');

        // Kiểm tra xem employee_id có course_id trong bảng courses_employees hay không
        $course_exists = DB::table('courses_employees')
            ->where('employee_id', $employee_id)
            ->where('course_id', $course_id)
            ->exists();

        if (!$course_exists) {
            return redirect()->route('quiz.index');
        }

        $question = DB::table('exam_questions')
            ->join('question_bank','question_bank.question_bank_id','=','exam_questions.question_bank_id')
            ->where('question_bank.course_id',$course_id)
            ->get();

        dd($question);
        // Nếu tồn tại, trả về view test-quiz
        return view('auth.quiz.test-quiz');
    }
}
