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
        $type_certificate = $data->getTypeCertificate();
//        $medical_checkup = $data->getMedicalCheckup();
//        dd($jobdetails);
        return view('auth.employees.employees',
            [
                'data' => $data->getEmployee(),
                'jobdetails'=>$jobdetails,
                'type_certificate'=> $type_certificate,
//                'medical_checkup'=> $medical_checkup
            ]
        );
    }

    function put(Request $request){
        $dataContact = [
            'phone_number' => $request->input('phone_number'),
        ];
        $id_contact = DB::table('contacts')->insertGetId($dataContact);

        $dataEmployee = [
            'employee_code'=>$request->input('employee_code'),
            'first_name'=>$request->input('first_name'),
            'last_name'=>$request->input('last_name'),
            'en_name'=>$request->input('en_name'),
            'photo' => null,
            'gender'=>$request->input('gender'),
            'marital_status' =>$request->input('marital_status'),
            'military_service' =>$request->input('military_service'),
            'date_of_birth'=> $request->date_of_birth,
            'national'=>$request->input('national'),
            'id_contact'=>$id_contact,
            'fired' => 'false'
        ];
        $id_employee = DB::table('employees')->insertGetId($dataEmployee);
        if(DB::table('job_detail')->where('id_employee',$id_employee)->insert(['id_employee' => $id_employee])){
            return json_encode((object)["status" => 200, "message" => "Action Success"]);
        }else{
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }
    }
    function post(Request $request){

    }

    function delete(Request $request){

    }

    function getEmployee(Request $request,$id_employee){
        $data_employee = DB::table('employees')->where('id_employee',$id_employee)->first();
        $id_contact = DB::table('employees')->where('id_employee',$id_employee)->value('id_contact');
        $data_contact = DB::table('contacts')->where('id_contact',$id_contact)->first();
        $data_job_detail = DB::table('job_detail')
            ->where('id_employee',$id_employee)
            ->first();
        Log::info(json_encode($data_job_detail));
        $data = new EmployeeModel();
        $jobdetails = $data->getAllJobDetails();
        return view('auth.employees.info',[
            'data_employee' => $data_employee,
            'data_contact' => $data_contact,
            'data_job_detail' => $data_job_detail,
            'jobdetails' => $jobdetails
        ]);
    }

    static function getMedicalInfo($id_employee){
        $data_medical = DB::table('medical_checkup')->where('id_employee',$id_employee)->get();
        return json_encode($data_medical);
    }

    static function getCertificateInfo($id_employee)
    {
        $data_medical = DB::table('certificates')
            ->join('certificate_type', 'certificates.id_type_certificate', '=', 'certificate_type.id_certificate_type')
            ->where('id_employee',$id_employee)->get();
        return json_encode($data_medical);
    }
}
