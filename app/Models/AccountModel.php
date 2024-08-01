<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountModel extends Model
{
    use HasFactory;
    protected $table = 'accounts';
    protected $primaryKey = 'account_id';


    static function getAll(){
        $sql = "SELECT * FROM accounts, employees WHERE account.employee_id = employees.employee_id ORDER BY account_id DESC";
        return DB::select($sql);
    }
}
