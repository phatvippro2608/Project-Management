<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarnLeaveModel extends Model
{
    use HasFactory;
    protected $table = 'leave_earn';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'employee_pin',
        'employee_name',
        'total_hour',
    ];
}
