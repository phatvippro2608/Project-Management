<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LeaveApplicationModel extends Model
{
    use HasFactory;
    //Get Data
    function getEmployeeName()
    {
        $sql = "SELECT * from employees WHERE id_employee NOT IN(SELECT id_employee from account)";
        return DB::select($sql);
    }

    function getLeaveTypes()
    {
        return DB::table('leave_types')->get();
    }

    //Add data
    protected $table = 'leave_applications';
    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id',
        'pin',
        'leave_type',
        'apply_date',
        'start_date',
        'end_date',
        'duration',
        'leave_status',
    ];

    public $timestamps = false;

    // Thiết lập mối quan hệ với bảng employees
    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id_employee');
    }

//    // Thiết lập mối quan hệ với bảng leave_types
//    public function leaveType()
//    {
//        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id');
//    }
}
