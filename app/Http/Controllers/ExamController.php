<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamModel;
use App\Models\ExamQuestionModel;
use App\Models\CourseModel;
use App\Models\QuizModel;

class ExamController extends Controller
{
    public function getView()
    {
        $exams = ExamModel::with('course')->get();
        $courses = CourseModel::all();
        $questions = QuizModel::all();
        return view('auth.quiz.exam', compact('exams', 'courses', 'questions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|int',
            'exam_name' => 'required|string',
            'exam_date' => 'required|date',
            'time' => 'required|int',
            'questions' => 'required|array',
        ]);

        $exam = ExamModel::create([
            'course_id' => $validated['course_id'],
            'exam_name' => $validated['exam_name'],
            'exam_date' => $validated['exam_date'],
            'time' => $validated['time'],
        ]);

        foreach ($validated['questions'] as $index => $question_id) {
            ExamQuestionModel::create([
                'exam_id' => $exam->exam_id,
                'question_bank_id' => $question_id,
                'question_order' => $index + 1,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $exam = ExamModel::with('questions')->findOrFail($id);
        return response()->json(['exam' => $exam]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'course_id' => 'required|int',
            'exam_name' => 'required|string',
            'exam_date' => 'required|date',
            'time' => 'required|int',
            'questions' => 'required|array',
        ]);

        $exam = ExamModel::findOrFail($id);
        $exam->update([
            'course_id' => $validated['course_id'],
            'exam_name' => $validated['exam_name'],
            'exam_date' => $validated['exam_date'],
            'time' => $validated['time'],
        ]);

        ExamQuestionModel::where('exam_id', $id)->delete();

        foreach ($validated['questions'] as $index => $question_id) {
            ExamQuestionModel::create([
                'exam_id' => $exam->exam_id,
                'question_bank_id' => $question_id,
                'question_order' => $index + 1,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $exam = ExamModel::findOrFail($id);
        ExamQuestionModel::where('exam_id', $id)->delete();
        $exam->delete();

        return response()->json(['success' => true]);
    }

    public function getQuestionsByCourse($id)
    {
        $questions = QuizModel::where('course_id', $id)->get();
        return response()->json(['questions' => $questions]);
    }
}
