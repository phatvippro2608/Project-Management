<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveReportsModel extends Model
{
    use HasFactory;
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
        return $this->belongsTo(LeaveTypeModel::class, 'leave_type', 'id');
    }
}
