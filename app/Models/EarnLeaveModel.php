<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EarnLeaveModel extends Model
{
    use HasFactory;

    protected $table = 'leave_earn';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'employee_pin',
        'employee_name',
        'total_hour',
    ];
    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'employee_id');
    }

    public static function getEmployeeLeaveSummary()
    {
        return DB::table('leave_applications')
            ->join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->select(
                'employees.employee_code',
                DB::raw('CONCAT(employees.first_name, " ", employees.last_name) as employee_name'),
                DB::raw('SUM(CASE WHEN leave_applications.leave_status = "approved" THEN leave_applications.duration ELSE 0 END) * 24 as totalhour')
            )
            ->groupBy('employees.employee_code', 'employees.first_name', 'employees.last_name', 'leave_applications.employee_id')
            ->get();
    }
}
