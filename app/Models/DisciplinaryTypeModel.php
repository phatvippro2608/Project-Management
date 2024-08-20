<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaryTypeModel extends Model
{
    use HasFactory;
    protected $table = 'disciplinary_types';

    protected $primaryKey = 'disciplinary_type_id';

    protected $fillable = [
        'disciplinary_type_name',
        'disciplinary_type_hidden',
    ];

    public static function get()
    {
        return self::all();
    }
}
