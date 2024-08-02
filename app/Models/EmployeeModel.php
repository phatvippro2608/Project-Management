<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeModel extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $primaryKey = 'employee_id';
    function getEmployee()
    {
        $perPage = intval(env('ITEM_PER_PAGE'));
        return DB::table('employees')
            ->join('job_details', 'employees.employee_id', '=', 'job_details.employee_id')
            ->join('contacts', 'employees.contact_id', '=', 'contacts.contact_id')
            ->where('employees.fired', 'false')
            ->paginate($perPage);
    }
    public function getAllJobDetails()
    {
        $jobTitles = DB::table('job_titles')->get();
        $jobCategories = DB::table('job_categories')->get();
        $jobPositions = DB::table('job_positions')->get();
        $jobTeams = DB::table('job_teams')->get();
        $jobLevels = DB::table('job_levels')->get();
        $jobTypeContract = DB::table('job_type_contracts')->get();
        $jobCountry = DB::table('job_countries')->get();
        $jobLocation = DB::table('job_locations')->get();


        return [
            'jobTitles' => $jobTitles,
            'jobCategories' => $jobCategories,
            'jobPositions' => $jobPositions,
            'jobTeams' => $jobTeams,
            'jobLevels' => $jobLevels,
            'jobTypeContract' => $jobTypeContract,
            'jobCountry' => $jobCountry,
            'jobLocation' => $jobLocation,
        ];
    }
    public function getTypeCertificate(){
        return DB::table('certificate_types')->get();
    }
    public static function getMedicalCheckUp(){
        return DB::table('medical_checkup')->get();
    }


    public function getAllEmployee()
    {
        $data = DB::table('employees')
            ->join('contacts', 'employees.contact_id', '=', 'contacts.contact_id')
            ->join('accounts', 'employees.employee_id', '=', 'accounts.employee_id')
            ->select(
                'employees.employee_code',
                'employees.first_name',
                'employees.last_name',
                'employees.en_name',
                'contacts.phone_number',
                'accounts.email',
                'employees.gender',
                'employees.marital_status',
                'employees.date_of_birth',
                'employees.national',
                'employees.military_service',
                'contacts.cic_number',
                'contacts.cic_issue_date',
                'contacts.cic_expiry_date',
                'contacts.cic_place_issue',
                'contacts.current_residence',
                'contacts.permanent_address'
            )
            ->get();
        return $data;
    }


    // Thiết lập mối quan hệ với bảng leave_applications
    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplicationModel::class, 'employee_id', 'employee_id');
    }

}
