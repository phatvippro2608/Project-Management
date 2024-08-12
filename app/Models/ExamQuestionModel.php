<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestionModel extends Model
{
    use HasFactory;

    protected $table = 'exam_questions';

    protected $primaryKey = 'exam_question_id';

    protected $fillable = [
        'exam_id',
        'question_bank_id',
        'question_order'
    ];

    public function exam()
    {
        return $this->belongsTo(ExamModel::class, 'exam_id', 'exam_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizModel::class, 'question_bank_id', 'question_bank_id');
    }
}
