<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecognitionTypeModel extends Model
{
    use HasFactory;

    protected $table = 'recognition_types';

    protected $fillable = [
        'recognition_type_name',
        'recognition_type_hidden',
    ];

    public static function getRecognitionTypes()
    {
        return self::all();
    }
}
