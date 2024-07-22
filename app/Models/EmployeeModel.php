<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeModel extends Model
{
    use HasFactory;
    function getEmployee()
    {
        $perPage = intval(env('ITEM_PER_PAGE'));
        return DB::table('employees')->paginate($perPage);
    }
    public function getAllJobDetails()
    {
        $jobTitles = DB::table('job_title')->get();
        $jobCategories = DB::table('job_category')->get();
        $jobPositions = DB::table('job_position')->get();
        $jobTeams = DB::table('job_team')->get();
        $jobLevels = DB::table('job_level')->get();
        $jobTypeContract = DB::table('job_type_contract')->get();
        $jobCountry = DB::table('job_country')->get();
        $jobLocation = DB::table('job_location')->get();


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
}
