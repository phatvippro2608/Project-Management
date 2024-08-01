<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTaskModel extends Model
{
    use HasFactory;

    protected $table = 'sub_tasks';
    protected $primaryKey = 'sub_task_id';

    protected $fillable = [
        'sub_task_name',
        'task_id',
        'request',
        'start_date',
        'end_date',
    ];

    public $timestamps = false;
}