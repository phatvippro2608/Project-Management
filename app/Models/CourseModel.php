<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModel extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $primaryKey = 'course_id';
    protected $fillable = [
        'course_id',
        'course_name',
        'description',
        'course_image',
        'course_type_id',
    ];

    public $timestamps = false;
}
