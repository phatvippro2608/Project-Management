<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Request;
use App\Models\ProposalTypesModel;


class ProposalApplicationModel extends Model
{
    use HasFactory;
    protected $table = 'proposal_applications';
    protected $primaryKey = 'proposal_application_id';

    protected $fillable = [
        'employee_id',
        'proposal_id',
        'proposal_description',
        'progress'
    ];
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'employee_id');
    }

    public function proposalType()
    {
        return $this->belongsTo(ProposalTypesModel::class, 'proposal_id', 'proposal_type_id');
    }

    public function files()
    {
        return $this->hasMany(ProposalFileModel::class, 'proposal_app_id', 'proposal_application_id');
    }


    public function getEmployeeName()
    {
        return DB::table('employees')->get();
    }

    public function getProposalTypes()
    {
        return DB::table('proposal_types')->get();
    }

    public function getEmployeeCurrent($account_id)
    {
        return DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->where('accounts.account_id', $account_id)
            ->select('employees.employee_id', 'employees.first_name', 'employees.last_name')
            ->first();
    }

    public function getListProposal()
    {
        $account_id = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
        $permission = DB::table('accounts')
            ->where('accounts.account_id', $account_id)
            ->pluck('permission')
            ->first(); // Lấy giá trị đầu tiên (giả sử luôn có một hàng được trả về)

        $employee_id = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->where('accounts.account_id', $account_id)
            ->pluck('employees.employee_id')
            ->first(); // Lấy giá trị đầu tiên (giả sử luôn có một hàng được trả về)

        $department_id = DB::table('job_details')
            ->where('employee_id', $employee_id)
            ->pluck('department_id')
            ->first();

        $employee_of_depart = DB::table('employees')
            ->join('job_details', 'employees.employee_id', '=', 'job_details.employee_id')
            ->where('job_details.department_id', $department_id)
            ->get();

        $employee_current = DB::table('employees')
            ->where('employee_id', $employee_id)
            ->first();

        $list_proposal = [];
        if ($permission == 0) {
            $list_proposal = DB::table('proposal_applications')
                ->join('employees', 'employees.employee_id', '=', 'proposal_applications.employee_id')
                ->join('proposal_types', 'proposal_applications.proposal_id', '=', 'proposal_types.proposal_type_id')
                ->where('employees.employee_id', $employee_id)
                ->get();
        } else if ($permission == 9) {
            $department_id = DB::table('employees')
                ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')
                ->join('departments', 'job_details.department_id', '=', 'departments.department_id')
                ->where('employees.employee_id', $employee_id)
                ->first()->department_id;
            $list_proposal = DB::table('proposal_applications')
                ->join('employees', 'employees.employee_id', '=', 'proposal_applications.employee_id')
                ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')
                ->join('proposal_types', 'proposal_applications.proposal_id', '=', 'proposal_types.proposal_type_id')
                ->join('departments', 'job_details.department_id', '=', 'departments.department_id')
                ->where('departments.department_id', $department_id)
                ->get();
        } else if ($permission == 10) {
            $list_proposal = DB::table('proposal_applications')
                ->join('employees', 'employees.employee_id', '=', 'proposal_applications.employee_id')
                ->join('proposal_types', 'proposal_applications.proposal_id', '=', 'proposal_types.proposal_type_id')
                ->get();
        }

        return [
            'list_proposal' => $list_proposal,
            'permission' => $permission,
            'employee_of_depart' => $employee_of_depart,
            'employee_current' => $employee_current
        ];
    }
}
