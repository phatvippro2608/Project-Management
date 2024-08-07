<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidaysModel extends Model
{
    use HasFactory;

    protected $table = 'holidays';
    protected $primaryKey = 'holiday_id';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'days',
        'year',
    ];

    public $timestamps = false;
}
