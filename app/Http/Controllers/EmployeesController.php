<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\EmployeeModel;
use App\Models\SpreadsheetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $data = EmployeeModel::query()
            ->join('contacts', 'contacts.contact_id', '=', 'employees.contact_id')
            ->where('fired','false')
            ->orderBy('employees.employee_code')
            ->get();
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
        $exist = DB::table('employees')->where('employee_code',$request->input('employee_code'))->exists();
        if($exist){
            return json_encode((object)["status" => 500, "message" => "Employee code already exist"]);
        }
        $dataContact = [
            'phone_number' => $request->input('phone_number'),
        ];
        $contact_id = DB::table('contacts')->insertGetId($dataContact);

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
            'contact_id'=>$contact_id,
            'fired' => 'false'
        ];

        $employee_id = DB::table('employees')->insertGetId($dataEmployee);


        $data_account = [
            'email' => $request->email,
            'username' => explode('@', $request->email)[0],
            'password' => password_hash('123', PASSWORD_BCRYPT),
            'status' => 1,
            'permission' => 0,
            'employee_id' => $employee_id,
        ];

        DB::table('accounts')->insert($data_account);

        if(DB::table('job_details')->where('employee_id',$employee_id)->insert(['employee_id' => $employee_id])){
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

        $employee_id = DB::table('employees')->where('employee_code',$request->employee_code)->value('employee_id');
        $contact_id = DB::table('employees')->where('employee_id',$employee_id)->value('contact_id');

        DB::beginTransaction();
        try {
            $employeeExists = DB::table('employees')->where('employee_id', $employee_id)->exists();
            if ($employeeExists) {
                DB::table('employees')
                    ->where('employee_id', $employee_id)
                    ->update($dataEmployee);
            } else {
                $dataEmployee['employee_id'] = $employee_id;
                DB::table('employees')->insert($dataEmployee);
            }

            $passportExists = DB::table('passport')->where('employee_id', $employee_id)->exists();
            if ($passportExists) {
                DB::table('passport')
                    ->where('employee_id', $employee_id)
                    ->update($dataPassport);
            } else {
                $dataPassport['employee_id'] = $employee_id;
                DB::table('passport')->insert($dataPassport);
            }

            $contactExists = DB::table('contacts')->where('contact_id', $contact_id)->exists();
            if ($contactExists) {
                DB::table('contacts')
                    ->where('contact_id', $contact_id)
                    ->update($dataContact);
            } else {
                $dataContact['contact_id'] = $contact_id;
                DB::table('contacts')->insert($dataContact);
            }

            $jobDetailExists = DB::table('job_details')->where('employee_id', $employee_id)->exists();
            if ($jobDetailExists) {
                DB::table('job_details')
                    ->where('employee_id', $employee_id)
                    ->update($dataJob);
            } else {
                $dataJob['employee_id'] = $employee_id;
                DB::table('job_details')->insert($dataJob);
            }

            DB::commit();

            return json_encode((object)["status" => 200, "message" => "Action Success"]);
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e);
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }
    }

    function delete(Request $request){
        $employee_id = $request->id;
        Log::info($employee_id);
        $updateResult = DB::table('employees')
            ->where('employee_id', $employee_id)
            ->update(['fired' => "true"]);

        if ($updateResult){
            return json_encode((object)["status" => 200, "message" => "Action Success"]);
        }
        else{
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }
    }

    function getEmployee(Request $request,$employee_id){
        $data_employee = DB::table('employees')
            ->where('employee_id',$employee_id)->first();
        $data_passport = DB::table('passport')
            ->where('employee_id',$employee_id)->first();
        $data_medical_checkup = DB::table('medical_checkup')
            ->where('employee_id',$employee_id)->first();

        $contact_id = DB::table('employees')->where('employee_id',$employee_id)->value('contact_id');
        $data_contact = DB::table('contacts')
            ->where('contact_id',$contact_id)->first();
        $data_job_detail = DB::table('job_details')
            ->where('employee_id',$employee_id)
            ->first();
        $email = DB::table('accounts')->where('employee_id',$employee_id)->value('email');
        $data_cv = DB::table('employees')->where('employee_id',$employee_id)->value('cv');
        $data_medical_checkup = DB::table('medical_checkup')->where('employee_id',$employee_id)->get();
        $data_certificate = DB::table('certificates')
            ->join('certificate_types', 'certificate_types.certificate_type_id', '=', 'certificates.type_certificate_id')
            ->where('certificates.employee_id',$employee_id)->get();
        Log::info(json_encode($data_job_detail));
        $data = new EmployeeModel();
        $jobdetails = DB::table('job_details')
            ->join('job_titles', 'job_titles.job_title_id', '=', 'job_details.job_title_id')
            ->join('job_categories', 'job_categories.job_category_id', '=', 'job_details.job_category_id')
            ->join('job_type_contracts', 'job_type_contracts.type_contract_id', '=', 'job_details.job_type_contract_id')
            ->join('job_teams', 'job_teams.team_id', '=', 'job_details.job_team_id')
            ->join('job_countries', 'job_countries.country_id', '=', 'job_details.job_country_id')
            ->join('job_levels', 'job_levels.level_id', '=', 'job_details.job_level_id')
            ->join('job_locations', 'job_locations.location_id', '=', 'job_details.job_location_id')
            ->join('job_positions', 'job_positions.position_id', '=', 'job_details.job_position_id')
            ->where('employee_id',$employee_id)->get();
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

    static function getMedicalInfo($employee_id){
        $data_medical = DB::table('medical_checkup')->where('employee_id',$employee_id)->get();
        return json_encode($data_medical);
    }

    static function getEmploymentContract($employee_id){
        $data_employment_contract = DB::table('employment_contract')->where('employee_id',$employee_id)->get();
        return json_encode($data_employment_contract);
    }

    static function getCertificateInfo($employee_id)
    {
        $data_certificates = DB::table('certificates')
            ->join('certificate_types', 'certificates.type_certificate_id', '=', 'certificate_types.certificate_type_id')
            ->where('employee_id',$employee_id)->get();
        return json_encode($data_certificates);
    }

    static function generateEmployeeCode()
    {
        $year = date('y');

        $lastCode = DB::table('employees')
            ->where('employee_code', 'like', $year . '%')
            ->orderBy('employee_code', 'desc')
            ->value('employee_code');

        $nextId = $lastCode ? intval(substr($lastCode, 2)) + 1 : 1;

        $employee_id = str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $employee_code = $year . $employee_id;

        return $employee_code;
    }
    static function getPassportInfo($employee_id)
    {
        $data_passport = DB::table('passport')->where('employee_id',$employee_id)->get();
        return $data_passport;
    }
    public function checkFileExists(Request $request)
    {
        $filePath = public_path($request->input('path'));
        return response()->json(['exists' => file_exists($filePath)]);
    }

    public function deleteFile(Request $request){
        $employee_id = DB::table('employees')->where('employee_code', $request->employee_code)->value('employee_id');
        $filename = $request->filename;
        $file_of = $request->file_of;

        if ($file_of == "cv") {
            $cv_list = json_decode(DB::table('employees')->where('employee_id', $employee_id)->value('cv'));
            if (in_array($filename, $cv_list)) {
                $filePath = public_path("uploads/$employee_id/$filename");
                if (File::exists($filePath)) {
                    // Delete the file from the server
                    File::delete($filePath);

                    // Remove the file from the CV list
                    $cv_list = array_diff($cv_list, [$filename]);

                    // Update the database with the new CV list
                    DB::table('employees')->where('employee_id', $employee_id)->update(['cv' => json_encode(array_values($cv_list))]);

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
            $medical_checkup_id = $request->medical_checkup_id;
            $medical_checkup_file = DB::table('medical_checkup')->where('medical_checkup_id', $medical_checkup_id)->value('medical_checkup_file');
            $filePath = public_path("uploads/$employee_id/$medical_checkup_file");
            if (File::exists($filePath)) {
                // Delete the file from the server
                File::delete($filePath);

                DB::table('medical_checkup')->where('medical_checkup_id', $medical_checkup_id)->delete();

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
            $certificate_id = $request->certificate_id;
            $certificate_file = DB::table('certificates')->where('certificate_id', $certificate_id)->value('certificate');
            $filePath = public_path("uploads/$employee_id/$certificate_file");
            if (File::exists($filePath)) {
                // Delete the file from the server
                File::delete($filePath);

                DB::table('certificates')->where('certificate_id', $certificate_id)->delete();

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
    static function getEmployeeId($employee_code)
    {
        return DB::table('employees')->where('employee_code', $employee_code)->value('employee_id');
    }
    public function loadExcel(Request $request){
        $dataExcel = SpreadsheetModel::readExcel($request->file('file-excel'));
        $tong = 0;
        $num_row = 0;
        $tt = 0;
        foreach ($dataExcel['data'] as $item) {
            $num_row++;

            $first_name = trim($item[0]);
            $last_name = trim($item[1]);
            $en_name = trim($item[2]);
            $email = trim($item[3]);
            $phone_number = trim($item[4]);
            if (strlen($first_name) === 0 ||
                strlen($last_name) === 0 || strlen($en_name) === 0 ||
                strlen($email) === 0 || strlen($phone_number) === 0) {
                continue;
            }
            $data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'en_name' => $en_name,
                'email' => $email,
                'phone_number' => $phone_number,
            ];

            $processedData[] = $data;
        }
        return response()->json($processedData);
    }
    public function import(Request $request) {
        // Decode JSON data if necessary
        $dataExcel = json_decode($request->input('dataExcel'), true);

        $num_row = 0;
        $successfulRows = [];
        $errorRows = [];

        foreach ($dataExcel as $item) {
            $num_row++;
            if ($num_row == 1) {
                continue; // Skip header row
            }

            $employee_code = EmployeesController::generateEmployeeCode();
            $first_name = trim($item['first_name']);
            $last_name = trim($item['last_name']);
            $en_name = trim($item['en_name']);
            $email = trim($item['email']);
            $phone_number = trim($item['phone_number']);

            // Check if account with the same email already exists
            if (AccountModel::where('email', $email)->exists()) {
                $errorRows[] = [
                    'row' => $num_row,
                    'data' => $item,
                    'error' => 'Account with this email already exists',
                ];
                continue; // Skip this item and continue to the next one
            }

            // Check for missing fields
            if (strlen($first_name) === 0 ||
                strlen($last_name) === 0 || strlen($en_name) === 0 ||
                strlen($email) === 0 || strlen($phone_number) === 0) {
                $errorRows[] = [
                    'row' => $num_row,
                    'data' => $item,
                    'error' => 'Missing required fields',
                ];
                continue;
            }

            try {
                DB::beginTransaction();

                // Insert contact and get the ID
                $contact_id = DB::table('contacts')->insertGetId(['phone_number' => $phone_number]);

                // Insert employee data
                $data_employee = [
                    'employee_code' => $employee_code,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'en_name' => $en_name,
                    'photo' => null,
                    'fired' => "false",
                    'contact_id' => $contact_id,
                ];
                $employee_id = DB::table('employees')->insertGetId($data_employee);

                // Insert job details
                DB::table('job_details')->insert(['employee_id' => $employee_id]);

                // Insert account data

                $data_account = [
                    'email' => $email,
                    'username' => DB::table('employees')->where('employee_id', $employee_id)->value('employee_code'),
                    'password' => password_hash('123456', PASSWORD_BCRYPT),
                    'status' => 1,
                    'permission' => 0,
                    'employee_id' => $employee_id,
                ];

                DB::table('accounts')->insert($data_account);

                DB::commit();

                $successfulRows[] = [
                    'employee_code' => $employee_code,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'en_name' => $en_name,
                    'email' => $email,
                    'phone_number' => $phone_number,
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                continue;
            }
        }


        return response()->json([
            'successfulRows' => $successfulRows,
            'errorRows' => $errorRows
        ]);
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
//            dd($row);
            $cell->setCellValue('A' . $num_row, $stt++);
            $cell->setCellValue('B' . $num_row, $row['employee_code']);
            $cell->setCellValue('C' . $num_row, $row['first_name']);
            $cell->setCellValue('D' . $num_row, $row['last_name']);
            $cell->setCellValue('E' . $num_row, $row['en_name']);
            $cell->setCellValue('F' . $num_row, $row['phone_number']);
            $cell->setCellValue('G' . $num_row, $row['email'] ?? '');
            $cell->setCellValue('H' . $num_row, $row['gender'] == 0 ? 'Male' : 'Female');
            $cell->setCellValue('I' . $num_row, $row['marital_status']);
            $cell->setCellValue('J' . $num_row, $row['date_of_birth']);
            $cell->setCellValue('K' . $num_row, $row['national']);
            $cell->setCellValue('L' . $num_row, $row['military_service']);
            $cell->setCellValue('M' . $num_row, $row['cic_number']);
            $cell->setCellValue('N' . $num_row, $row['cic_issue_date']);
            $cell->setCellValue('O' . $num_row, $row['cic_expiry_date']);
            $cell->setCellValue('P' . $num_row, $row['cic_place_issue']);
            $cell->setCellValue('Q' . $num_row, $row['current_residence']);
            $cell->setCellValue('R' . $num_row, $row['permanent_address']);
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

    function updateView(Request $request) {
        $item = new EmployeeModel();
        $jobdetails = $item->getAllJobDetails();
        $type_certificate = $item->getTypeCertificate();

        $employee_id = $request->employee_id;
//        dd($employee_id);
        $item = DB::table('employees')
            ->join('contacts', 'contacts.contact_id', '=', 'employees.contact_id')
            ->where('employees.employee_id', $employee_id)
            ->orderBy('employees.employee_code')
            ->first();

        $itemArray = (array)$item;
        $jobdetail = DB::table('job_details')
            ->where('employee_id', $employee_id)
            ->first();
        if ($jobdetail) {
            $jobdetailsArray = (array)$jobdetail;
            $itemArray = array_merge($itemArray, $jobdetailsArray);
        }

        $item = (object)$itemArray;
        if ($item) {
            // Augment the employee data with additional information
            $item->medical = EmployeesController::getMedicalInfo($employee_id);
            $item->certificates = EmployeesController::getCertificateInfo($employee_id);
            $item->passport = EmployeesController::getPassportInfo($employee_id);
            $item->employment_contract = EmployeesController::getEmploymentContract($employee_id);
            $item->email = DB::table('accounts')->where('employee_id', $employee_id)->value('email');
        }
//        dd($jobdetails);

        return view('auth.employees.update_employee',[
            'item' => $item,
            'jobdetails' => $jobdetails,
            'type_certificate' => $type_certificate,
        ]);
    }

    function inactiveView()
    {
        $data = DB::table('employees')->join('contacts', 'contacts.contact_id', '=', 'employees.contact_id')->where('fired', 'true')->orderBy('employee_code')->get();
        return view('auth.employees.inactive_employee',[
            'data' => $data
        ]);
    }
}
