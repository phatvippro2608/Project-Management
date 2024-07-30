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
        'phase_id',
        'project_id',
        'request',
        'engineers',
        'start_date',
        'end_date',
    ];

    public $timestamps = false;
}