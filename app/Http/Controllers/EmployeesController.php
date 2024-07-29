<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
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
        $dataEmployee = array_filter(json_decode($request->dataEmployee, true), function ($value) {
            return $value !== "";
        });

        $dataPassport = array_filter(json_decode($request->dataPassport, true), function ($value) {
            return $value !== "";
        });

        $dataContact = array_filter(json_decode($request->dataContact, true), function ($value) {
            return $value !== "";
        });

        $dataJob = array_filter(json_decode($request->dataJob, true), function ($value) {
            return $value !== "";
        });

        $id_employee = $request->id_employee;
        $id_contact = $request->id_contact;

        DB::beginTransaction();
        try {
            $employeeExists = DB::table('employees')->where('id_employee', $id_employee)->exists();
            if ($employeeExists) {
                DB::table('employees')
                    ->where('id_employee', $id_employee)
                    ->update($dataEmployee);
            } else {
                $dataEmployee['id_employee'] = $id_employee;
                DB::table('employees')->insert($dataEmployee);
            }

            $passportExists = DB::table('passport')->where('id_employee', $id_employee)->exists();
            if ($passportExists) {
                DB::table('passport')
                    ->where('id_employee', $id_employee)
                    ->update($dataPassport);
            } else {
                $dataPassport['id_employee'] = $id_employee;
                DB::table('passport')->insert($dataPassport);
            }

            $contactExists = DB::table('contacts')->where('id_contact', $id_contact)->exists();
            if ($contactExists) {
                DB::table('contacts')
                    ->where('id_contact', $id_contact)
                    ->update($dataContact);
            } else {
                $dataContact['id_contact'] = $id_contact;
                DB::table('contacts')->insert($dataContact);
            }

            $jobDetailExists = DB::table('job_detail')->where('id_employee', $id_employee)->exists();
            if ($jobDetailExists) {
                DB::table('job_detail')
                    ->where('id_employee', $id_employee)
                    ->update($dataJob);
            } else {
                $dataJob['id_employee'] = $id_employee;
                DB::table('job_detail')->insert($dataJob);
            }

            DB::commit();

            return json_encode((object)["status" => 200, "message" => "Action Success"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }


    }

    function delete(Request $request){
        $id_employee = $request->id;
        Log::info($id_employee);
        $updateResult = DB::table('employees')
            ->where('id_employee', $id_employee)
            ->update(['fired' => "true"]);

        if ($updateResult){
            return json_encode((object)["status" => 200, "message" => "Action Success"]);
        }
        else{
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }
    }

    function getEmployee(Request $request,$id_employee){
        $data_employee = DB::table('employees')
            ->where('id_employee',$id_employee)->first();
        $data_passport = DB::table('passport')
            ->where('id_employee',$id_employee)->first();
        $data_medical_checkup = DB::table('medical_checkup')
            ->where('id_employee',$id_employee)->first();

        $id_contact = DB::table('employees')->where('id_employee',$id_employee)->value('id_contact');
        $data_contact = DB::table('contacts')
            ->where('id_contact',$id_contact)->first();
        $data_job_detail = DB::table('job_detail')
            ->where('id_employee',$id_employee)
            ->first();
        $email = DB::table('account')->where('id_employee',$id_employee)->value('email');
        $data_cv = DB::table('employees')->where('id_employee',$id_employee)->value('cv');
        $data_medical_checkup = DB::table('medical_checkup')->where('id_employee',$id_employee)->get();
        $data_certificate = DB::table('certificates')
            ->join('certificate_type', 'certificate_type.id_certificate_type', '=', 'certificates.id_type_certificate')
            ->where('certificates.id_employee',$id_employee)->get();
        Log::info(json_encode($data_job_detail));
        $data = new EmployeeModel();
        $jobdetails = $data->getAllJobDetails();
        return view('auth.employees.info',[
            'data_employee' => $data_employee,
            'data_contact' => $data_contact,
            'data_job_detail' => $data_job_detail,
            'jobdetails' => $jobdetails,
            'email' => $email,
            'data_passport' => $data_passport,
            'data_cv' => $data_cv,
            'data_medical_checkup' => $data_medical_checkup,
            'data_certificate' => $data_certificate
        ]);
    }

    static function getMedicalInfo($id_employee){
        $data_medical = DB::table('medical_checkup')->where('id_employee',$id_employee)->get();
        return json_encode($data_medical);
    }

    static function getCertificateInfo($id_employee)
    {
        $data_certificates = DB::table('certificates')
            ->join('certificate_type', 'certificates.id_type_certificate', '=', 'certificate_type.id_certificate_type')
            ->where('id_employee',$id_employee)->get();
        return json_encode($data_certificates);
    }

    static function getPassportInfo($id_employee)
    {
        $data_passport = DB::table('passport')->where('id_employee',$id_employee)->get();
        return json_encode($data_passport);
    }
    public function checkFileExists(Request $request)
    {
        $filePath = public_path($request->input('path'));
        return response()->json(['exists' => file_exists($filePath)]);
    }

    public function deleteFile(Request $request){
        $id_employee = $request->id_employee;
        $filename = $request->filename;
        $file_of = $request->file_of;

        if ($file_of == "cv") {
            $cv_list = json_decode(DB::table('employees')->where('id_employee', $id_employee)->value('cv'));

            if (in_array($filename, $cv_list)) {
                $filePath = public_path("uploads/$id_employee/$filename");
                if (File::exists($filePath)) {
                    // Delete the file from the server
                    File::delete($filePath);

                    // Remove the file from the CV list
                    $cv_list = array_diff($cv_list, [$filename]);

                    // Update the database with the new CV list
                    DB::table('employees')->where('id_employee', $id_employee)->update(['cv' => json_encode(array_values($cv_list))]);

                    return response()->json([
                        'status' => 200,
                        'message' => 'File deleted successfully'
                    ]);
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'File not found on server'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'File not found in CV list'
                ]);
            }
        } else if($file_of == "medical"){
            $id_medical_checkup = $request->id_medical_checkup;
            $medical_checkup_file = DB::table('medical_checkup')->where('id_medical_checkup', $id_medical_checkup)->value('medical_checkup_file');
            $filePath = public_path("uploads/$id_employee/$medical_checkup_file");
            if (File::exists($filePath)) {
                // Delete the file from the server
                File::delete($filePath);

                DB::table('medical_checkup')->where('id_medical_checkup', $id_medical_checkup)->delete();

                return response()->json([
                    'status' => 200,
                    'message' => 'File deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'File not found on server'
                ]);
            }
        } else if($file_of == "certificate"){
            $id_certificate = $request->id_certificate;
            $certificate_file = DB::table('certificates')->where('id_certificate', $id_certificate)->value('certificate');
            $filePath = public_path("uploads/$id_employee/$certificate_file");
            if (File::exists($filePath)) {
                // Delete the file from the server
                File::delete($filePath);

                DB::table('certificates')->where('id_certificate', $id_certificate)->delete();

                return response()->json([
                    'status' => 200,
                    'message' => 'File deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'File not found on server'
                ]);
            }
        }
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid file type'
            ]);
        }

    }
}
