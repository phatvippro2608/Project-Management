<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\SpreadsheetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use function Laravel\Prompts\table;

class EmployeesController extends Controller
{
    function getView(Request $request)
    {
        $data = new EmployeeModel();
        $jobdetails = $data->getAllJobDetails();
        $type_certificate = $data->getTypeCertificate();
        $perPage = (int)env('ITEM_PER_PAGE');
        $keyword = $request->input('keyw', '');
        $keyword = trim($keyword);
        $keyword = $this->removeVietnameseAccents($keyword);

        $data = EmployeeModel::query()
            ->join('contacts', 'contacts.id_contact', '=', 'employees.id_contact')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('last_name', 'like', "%{$keyword}%")
                    ->orWhere('first_name', 'like', "%{$keyword}%")
                    ->orWhere('en_name','like', "%{$keyword}%")
                    ->orWhere('employee_code', 'like', "%{$keyword}%");
            })
            ->where('fired','false')
            ->paginate($perPage);
        return view('auth.employees.employees',
            [
                'data' => $data,
                'jobdetails'=>$jobdetails,
                'type_certificate'=> $type_certificate,
//                'medical_checkup'=> $medical_checkup
            ]
        );
    }

    function importView()
    {
        return view('auth.employees.import');
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
        if(DB::table('employees')->where('employee_code',$request->input('employee_code'))->exists()){
            return json_encode((object)["status" => 500, "message" => "Employee already exists"]);
        }

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
    public function import(Request $request){
        $dataExcel = SpreadsheetModel::readExcel($request->file('file-excel'));
        $tong = 0;
        $num_row = 0;
        $tt = 0;
        foreach ($dataExcel['data'] as $item) {
            $num_row++;
            if ($num_row == 1) {
                continue; // Skip header row
            }

            // Extract and trim data from Excel row
            $employee_code = trim($item[0]);
            $first_name = trim($item[1]);
            $last_name = trim($item[2]);
            $en_name = trim($item[3]);
            $email = trim($item[4]);
            $phone_number = trim($item[5]);
            if (strlen($employee_code) === 0 || strlen($first_name) === 0 ||
                strlen($last_name) === 0 || strlen($en_name) === 0 ||
                strlen($email) === 0 || strlen($phone_number) === 0) {
                continue; // Skip this row if any field is empty
            }
            try {
                DB::beginTransaction();

                $id_contact = DB::table('contacts')->insertGetId(['phone_number' => $phone_number]);

                $data_employee = [
                    'employee_code' => $employee_code,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'en_name' => $en_name,
                    'photo' => null,
                    'fired' => "false",
                    'id_contact' => $id_contact,
                ];
                $id_employee = DB::table('employees')->insertGetId($data_employee);

                DB::table('job_detail')->insert(['id_employee' => $id_employee]);

                $data_account = [
                    'email' => $email,
                    'username' => explode('@', $email)[0],
                    'password' => password_hash('123456', PASSWORD_BCRYPT),
                    'status' => 1,
                    'permission' => 0,
                    'id_employee' => $id_employee,
                ];
                DB::table('account')->insertGetId($data_account);

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'File deleted successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to process row ' . $num_row . ': ' . $e->getMessage());
                continue;
            }
        }
    }
    public function export(Request $request)
    {
        $inputFileName = public_path('excel-example/Employees.xlsx');

        $inputFileType = IOFactory::identify($inputFileName);

        $objReader = IOFactory::createReader($inputFileType);

        $excel = $objReader->load($inputFileName);

        $excel->setActiveSheetIndex(0);
        $excel->getDefaultStyle()->getFont()->setName('Times New Roman');

        $stt = 1;
        $cell = $excel->getActiveSheet();

        $data = new EmployeeModel();
        $data = $data->getAllEmployee();
        $num_row = 2;

        foreach ($data as $row) {
            $cell->setCellValue('A' . $num_row, $stt++);
            $cell->setCellValue('B' . $num_row, $row->employee_code);

            $cell->setCellValue('C' . $num_row, $row->first_name);
            $cell->setCellValue('D' . $num_row, $row->last_name);
            $cell->setCellValue('E' . $num_row, $row->en_name);
            $cell->setCellValue('F' . $num_row, $row->phone_number);
            $cell->setCellValue('G' . $num_row, $row->email);
            $cell->setCellValue('H' . $num_row, $row->gender == 0 ? 'Male' : 'Female');
            $cell->setCellValue('I' . $num_row, $row->marital_status);
            $cell->setCellValue('J' . $num_row, $row->date_of_birth);
            $cell->setCellValue('K' . $num_row, $row->national);
            $cell->setCellValue('L' . $num_row, $row->military_service);
            $cell->setCellValue('M' . $num_row, $row->cic_number);
            $cell->setCellValue('N' . $num_row, $row->cic_issue_date);
            $cell->setCellValue('O' . $num_row, $row->cic_expiry_date);
            $cell->setCellValue('P' . $num_row, $row->cic_place_issue);
            $cell->setCellValue('Q' . $num_row, $row->current_residence);
            $cell->setCellValue('R' . $num_row, $row->permanent_address);
            $borderStyle = $cell->getStyle('A'.$num_row.':R' . $num_row)->getBorders();
            $borderStyle->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $cell->getStyle('A'.$num_row.':R' . $num_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $num_row++;
        }
        foreach (range('A', 'R') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $filename = "Employees-List" . '.xlsx';
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');
    }
    function removeVietnameseAccents($str) {
        $accents_arr = [
            'a' => ['à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ'],
            'e' => ['è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ'],
            'i' => ['ì', 'í', 'ị', 'ỉ', 'ĩ'],
            'o' => ['ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ'],
            'u' => ['ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ'],
            'y' => ['ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ'],
            'd' => ['đ'],
            'A' => ['À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ'],
            'E' => ['È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ'],
            'I' => ['Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ'],
            'O' => ['Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ'],
            'U' => ['Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ'],
            'Y' => ['Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ'],
            'D' => ['Đ']
        ];

        foreach ($accents_arr as $non_accent => $accents) {
            $str = str_replace($accents, $non_accent, $str);
        }

        return $str;
    }
}
