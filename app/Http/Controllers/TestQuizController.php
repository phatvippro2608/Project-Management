<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ExamResultModel;
use App\Models\ExamAnswerModel;

class TestQuizController extends Controller
{
    public function getView(Request $request)
    {
        $course_id = $request->query('course_id');
        $employee_id = $request->query('employee_id');

        $course_exists = DB::table('courses_employees')
            ->where('employee_id', $employee_id)
            ->where('course_id', $course_id)
            ->exists();

        if (!$course_exists) {
            return redirect()->route('quiz.index');
        }

        $exam = DB::table('exams')
            ->where('course_id', $course_id)
            ->first();

        if (!$exam) {
            return redirect()->route('quiz.index');
        }

        $questions = DB::table('exam_questions')
            ->join('question_bank', 'question_bank.question_bank_id', '=', 'exam_questions.question_bank_id')
            ->where('exam_questions.exam_id', $exam->exam_id)
            ->select('question_bank.*', 'exam_questions.*')
            ->get()
            ->toArray();

        // Trộn các câu hỏi
        shuffle($questions);

        return view('auth.quiz.test-quiz', [
            'exam' => $exam,
            'questions' => $questions,
            'employee_id' => $employee_id,
        ]);
    }


    public function saveAnswer(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|int',
            'employee_id' => 'required|int',
            'question_id' => 'required|int',
            'selected_answer' => 'nullable|string',
        ]);

        try {
            $exam_result = ExamResultModel::updateOrCreate(
                [
                    'exam_id' => $validated['exam_id'],
                    'employee_id' => $validated['employee_id']
                ],
                [
                    'exam_date' => now()
                ]
            );

            $question = DB::table('question_bank')->where('question_bank_id', $validated['question_id'])->first();
            $is_correct = $question->correct == $validated['selected_answer'];

            ExamAnswerModel::updateOrCreate(
                [
                    'exam_result_id' => $exam_result->exam_result_id,
                    'question_bank_id' => $validated['question_id'],
                ],
                [
                    'selected_answer' => $validated['selected_answer'],
                    'is_correct' => $is_correct
                ]
            );

            return response()->json(['success' => true, 'answered' => $validated['selected_answer'] !== null]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function calculateScore(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|int',
            'employee_id' => 'required|int',
        ]);

        $exam_result = ExamResultModel::where('exam_id', $validated['exam_id'])
            ->where('employee_id', $validated['employee_id'])
            ->first();

        if (!$exam_result) {
            return response()->json(['success' => false, 'message' => 'Exam result not found']);
        }

        $correctAnswers = ExamAnswerModel::where('exam_result_id', $exam_result->exam_result_id)
            ->where('is_correct', 1)
            ->count();

        $totalQuestions = ExamAnswerModel::where('exam_result_id', $exam_result->exam_result_id)->count();

        $score = ($correctAnswers / $totalQuestions) * 10;
        $score = round($score * 2) / 2;  

        $exam_result->score = $score;
        $exam_result->passed = $score >= 5 ? 1 : 0; 
        $exam_result->save();

        return response()->json(['success' => true, 'score' => $score]);
    }



    public function markExamAsZero(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|int',
            'employee_id' => 'required|int',
        ]);

        $exam_result = ExamResultModel::updateOrCreate(
            [
                'exam_id' => $validated['exam_id'],
                'employee_id' => $validated['employee_id']
            ],
            [
                'score' => 0,
                'exam_date' => now()
            ]
        );

        return response()->json(['success' => true]);
    }
}
