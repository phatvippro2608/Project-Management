<?php

namespace App\Models;

use App\Traits\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectModel extends Model
{
    use HasFactory;
    use Project;

    protected $table = 'projects';

    protected $primaryKey = 'project_id';

    protected $fillable = [
        'project_name',
        'project_description',
        'project_address',
        'project_date_start',
        'project_date_end',
        'project_contact_name',
        'project_contact_phone',
        'project_contact_address',
        'project_contact_website',
        'project_contract_amount',
        'project_contractor_id',
    ];

    public function getCustomer()
    {
        // Truy xuất customer_id từ hợp đồng liên kết
        $contractId = $this->contract_id;

        // Truy vấn khách hàng dựa trên contract_id
        return DB::table('customers')
            ->join('contracts', 'customers.customer_id', '=', 'contracts.customer_id')
            ->where('contracts.contract_id', $contractId)
            ->select('customers.*')
            ->first();
    }

    public function getContract()
    {
        // Truy xuất contract_id từ project hiện tại
        $contractId = $this->contract_id;

        // Truy vấn hợp đồng dựa trên contract_id
        return DB::table('contracts')
            ->where('contract_id', $contractId)
            ->select('*')
            ->first();
    }

    public function getEmployee()
    {
        // Truy xuất employee_id từ project hiện tại
        $employeeId = $this->employee_id;

        // Truy vấn thông tin nhân viên dựa trên employee_id
        return DB::table('employees')
            ->where('employee_id', $employeeId)
            ->select('*')
            ->first();
    }

    public function getReport()
    {
        return "Hello";
    }
}
