<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAnswerModel extends Model
{
    use HasFactory;

    protected $table = 'exam_answers';
    protected $primaryKey = 'exam_answer_id';

    protected $fillable = [
        'exam_result_id',
        'question_bank_id',
        'selected_answer',
        'is_correct',
    ];

    public function examResult()
    {
        return $this->belongsTo(ExamResultModel::class, 'exam_result_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizModel::class, 'question_bank_id');
    }
}
