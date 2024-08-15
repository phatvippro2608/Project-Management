<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $primaryKey = 'task_id';

    protected $fillable = [
        'task_name',
        'project_id',
        'phase_id',
        'progress',
        'request',
        'engineers',
        'start_date',
        'end_date',
        'employee_id',
        'parent_id',
    ];

    public $timestamps = false;
}