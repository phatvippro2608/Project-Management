<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountModel extends Model
{
    use HasFactory;
    protected $table = 'account';
    protected $primaryKey = 'id_account';


    static function getAll($keyword){
        $sql = "SELECT * FROM ACCOUNT, EMPLOYEES WHERE ACCOUNT.ID_EMPLOYEE = EMPLOYEES.ID_EMPLOYEE
                AND(LAST_NAME LIKE '%$keyword%' OR FIRST_NAME LIKE '%$keyword%' OR LAST_NAME+' '+FIRST_NAME LIKE '%$keyword%'
                OR EMPLOYEE_CODE LIKE '%$keyword%' OR USERNAME LIKE '%$keyword%')";
        return DB::select($sql);
    }
}
