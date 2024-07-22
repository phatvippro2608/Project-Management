<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeModel extends Model
{
    use HasFactory;
    function getEmployee()
    {
        $perPage = intval(env('ITEM_PER_PAGE'));

        return DB::table('tb_employee')->paginate($perPage);
    }
}
