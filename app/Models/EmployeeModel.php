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
        return DB::table('certificate_types')->where('status','show')->get();
    }
    public static function getMedicalCheckUp(){
        return DB::table('medical_checkup')->get();
    }


    public function getAllEmployee()
    {
        $data = DB::table('employees')
            ->join('contacts', 'employees.contact_id', '=', 'contacts.contact_id')
            ->where('employees.fired', 'false')
            ->select(
                'employees.employee_id', // Ensure this is the common key
                'employees.employee_code',
                'employees.first_name',
                'employees.last_name',
                'employees.en_name',
                'contacts.phone_number',
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

        $accounts = DB::table('accounts')
            ->whereIn('employee_id', function($query) {
                $query->select('employee_id')
                    ->from('employees')
                    ->where('fired', false);
            })
            ->get();

        $dataArray = $data->mapWithKeys(function ($item) {
            return [$item->employee_id => (array) $item]; // Changed to 'employee_id'
        })->toArray();

        $accountsArray = $accounts->mapWithKeys(function ($item) {
            return [$item->employee_id => (array) $item]; // Ensure you use 'employee_id'
        })->toArray();

        foreach ($dataArray as $employeeId => $employeeData) {
            if (isset($accountsArray[$employeeId])) {
                $dataArray[$employeeId] = array_merge($employeeData, $accountsArray[$employeeId]);
            }
        }

        $data = (object) $dataArray;

        return $data;
    }


    // Thiết lập mối quan hệ với bảng leave_applications
    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplicationModel::class, 'employee_id', 'employee_id');
    }

}
