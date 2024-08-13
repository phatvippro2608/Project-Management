<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamModel extends Model
{
    use HasFactory;

    protected $table = 'exams';

    protected $primaryKey = 'exam_id';

    protected $fillable = [
        'course_id',
        'exam_name',
        'exam_date',
        'time',
        'created_at',
        'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo(CourseModel::class, 'course_id', 'course_id');
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestionModel::class, 'exam_id', 'exam_id');
    }
}
