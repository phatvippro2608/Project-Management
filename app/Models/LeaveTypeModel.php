<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveTypeModel extends Model
{
    use HasFactory;

    protected $table = 'leave_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'leave_type',
        'number_of_days'
    ];

    public $timestamps = false;
}
