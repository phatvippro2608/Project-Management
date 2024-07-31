<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'department_id';

    protected $fillable = [
        'department_name',
        'description',
    ];

    public $timestamps = false;
}
