<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecognitionTypeModel extends Model
{
    use HasFactory;
    protected $table = 'recognition_types';

    protected $fillable = [
        'recognition_type_name'
    ];
}
