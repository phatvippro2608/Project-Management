<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WorkshopChatModel extends Model
{
    use HasFactory;

    protected $fillable = ['workshop_id', 'user_id', 'message'];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function user()
    {
        $sql_get_employee_id = "SELECT * FROM employees, accounts WHERE employees.employee_id = accounts.employee_id AND account_id = $account_id";
        $employee_id = DB::selectOne($sql_get_employee_id);
        return $employee_id;
    }
}
