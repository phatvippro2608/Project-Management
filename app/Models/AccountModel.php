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


    static function getAll(){
        $sql = "SELECT * FROM account, employees WHERE account.employee_id = employees.employee_id ORDER BY id_account DESC";
        return DB::select($sql);
    }
}
