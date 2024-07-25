<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Submenu extends Model
{
    use HasFactory;

    public static function getData($sql){
        return $data=DB::select($sql);
    }

}
