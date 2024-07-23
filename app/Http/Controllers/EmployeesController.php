<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\table;

class EmployeesController extends Controller
{
    function getView()
    {
        $data = new EmployeeModel();
        $jobdetails = $data->getAllJobDetails();
//        dd($jobdetails);
        return view('auth.employees.employees',
            [
                'data' => $data->getEmployee(),
                'jobdetails'=>$jobdetails,
            ]
        );
    }

    function put(Request $request){
        $dataContact = [
            'phone_number' => $request->input('phone_number'),
            'passport_number' => $request->input('passport_number'),
            'passport_place_issue' => $request->input('passport_place_issue'),
            'passport_issue_date'=> $request->passport_issue_date,
            'passport_expiry_date'=> $request->passport_expiry_date,
            'cic_number'=> $request->input('cic_number'),
            'cic_place_issue'=> $request->cic_place_issue,
            'cic_issue_date'=> $request->cic_issue_date,
            'cic_expiry_date'=> $request->cic_expiry_date,
            'current_residence'=> $request->input('current_residence'),
            'permanent_address'=> $request->input('permanent_address'),
            'medical_checkup_date'=> $request->medical_checkup_date
        ];
        $id_contact = DB::table('contacts')->insertGetId($dataContact);

        $dataEmployee = [
            'employee_code'=>$request->input('employee_code'),
            'first_name'=>$request->input('first_name'),
            'last_name'=>$request->input('last_name'),
            'en_name'=>$request->input('en_name'),
            'gender'=>$request->input('gender'),
            'marital_status' =>$request->input('marital_status'),
            'military_service' =>$request->input('military_service'),
            'date_of_birth'=> $request->date_of_birth,
            'national'=>$request->input('national'),
            'id_contact'=>$id_contact,
        ];
        $id_employee = DB::table('employees')->insertGetId($dataEmployee);

        $dataDetails = [
            'id_job_title' => $request->input('job_title'),
            'id_job_category'=> $request->input('job_category'),
            'id_job_position' => $request->input('job_position'),
            'id_job_team' => $request->input('job_team'),
            'id_job_level'=> $request->input('job_level'),
            'email' => $request->input('email'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'id_job_type_contract' => $request->input('job_type_contract'),
            'id_job_country' => $request->input('job_country'),
            'id_job_location' => $request->input('job_location'),
            'id_employee' => $id_employee,
        ];

        if($id_job_detail = DB::table('job_detail')->insert($dataDetails)){
            return json_encode((object)["status" => 200, "message" => "Action Success", 'id_employee'=>$id_employee]);
        }else{
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }

    }
    function post(Request $request){

    }

    function delete(Request $request){

    }

}
