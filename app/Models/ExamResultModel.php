<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResultModel extends Model
{
    use HasFactory;

    protected $table = 'exam_results';
    protected $primaryKey = 'exam_result_id';

    protected $fillable = [
        'exam_id',
        'employee_id',
        'score',
        'exam_date',
    ];

    public function exam()
    {
        return $this->belongsTo(ExamModel::class, 'exam_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id');
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswerModel::class, 'exam_result_id');
    }
}
