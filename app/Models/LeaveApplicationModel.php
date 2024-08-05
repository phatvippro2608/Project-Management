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
        $sql = "SELECT * from employees WHERE employee_id NOT IN(SELECT employee_id from accounts)";
        return DB::select($sql);
    }

    function getLeaveTypes()
    {
        return DB::table('leave_types')->get();
    }

    //Add data
    protected $table = 'leave_applications';
    protected $primaryKey = 'leave_application_id';
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
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'employee_id');
    }

    // Thiết lập mối quan hệ với bảng leave_types
    public function leaveType()
    {
        return $this->belongsTo(LeaveTypeModel::class, 'leave_type', 'leave_type_id');
    }
}
