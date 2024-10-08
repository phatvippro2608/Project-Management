<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\StaticString;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InternalCertificatesController extends Controller
{
    //
    public function getViewUser()
    {
        $employees = DB::table('employees')
            ->join('certificates', 'certificates.employee_id', '=', 'employees.employee_id')
            ->join('certificate_types', 'certificate_types.certificate_type_id', '=', 'certificates.type_certificate_id')
            ->join('certificate_bodys', 'certificate_bodys.certificate_body_id', '=', 'certificate_types.certificate_body_id')
            ->where('certificate_bodys.certificate_body_name', '=', 'Ventech')
            ->where('employees.fired', '=', 'false')
            ->get();
        // dd($employess);
        return view('auth.certificate.InternalCertificate', [
            'employees' => $employees
        ]);
    }

    public function deleteViewUser(Request $request)
    {
        $data = $request->json()->all();
        $id = $data['id'];
        $deleted = DB::table('certificates')->where('certificate_id', $id)->delete();
        if ($deleted) {
            return Response::json(['success' => 'Employee deleted successfully.']);
        } else {
            return Response::json(['error' => 'Employee not found.'], 404);
        }
    }

    public function getViewType()
    {
        $certificates = DB::table('certificate_types')
            ->join('certificate_bodys', 'certificate_bodys.certificate_body_id', '=', 'certificate_types.certificate_body_id')
            ->where('certificate_body_name', '=', 'Ventech')
            ->get();
        $companies = DB::table('certificate_bodys')->orderBy('certificate_body_name')->get();
        return view('auth.certificate.InternalCertificateType', [
            'certificates' => $certificates,
            'companies' => $companies
        ]);
    }

    public function deleteType(Request $request)
    {
        $data = $request->json()->all();
        $id = $data['id'];
        try {
            DB::table('certificate_types')->where('certificate_type_id', $id)->delete();
            return response()->json(['message' => 'Certificate type deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the certificate type.'], 500);
        }
    }

    public function updateCertificateType(Request $request)
    {
        $request->validate([
            'certificate_type_id' => 'required|integer|exists:certificate_types,certificate_type_id',
            // 'certificate_body_id' => 'required|integer|exists:certificate_bodys,certificate_body_id',
            'certificate_type_name' => 'required|string|max:255',
            'certificate_type_acronym' => 'nullable|string|max:255',
        ]);

        try {
            $certificate = DB::table('certificate_types')->where('certificate_type_id', $request->input('certificate_type_id'))->first();

            if (!$certificate) {
                return response()->json(['message' => 'Certificate not found.'], 404);
            }

            // Cập nhật chứng chỉ
            DB::table('certificate_types')->where('certificate_type_id', $request->input('certificate_type_id'))->update([
                // 'certificate_body_id' => $request->input('certificate_body_id'),
                'certificate_type_name' => $request->input('certificate_type_name'),
                'certificate_type_acronym' => $request->input('certificate_type_acronym'),
                'updated_at' => now()
            ]);

            return response()->json(['message' => 'Certificate updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the certificate.'], 500);
        }
    }

    public function addCertificateType(Request $request)
    {
        $request->validate([
            'certificate_type_name' => 'required|string|max:255',
            'certificate_type_acronym' => 'nullable|string|max:50',
        ]);
        try {
            $idTemp = 29;
            DB::table('certificate_types')->insert([
                'certificate_body_id' => $idTemp,
                'certificate_type_name' => $request->input('certificate_type_name'),
                'certificate_type_acronym' => $request->input('certificate_type_acronym'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json([
                'message' => 'Certificate type added successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the certificate type.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function updateCreatePermissions()
    {
        // Cập nhật quyền tạo chứng chỉ cho super admin và giám đốc
        DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->join('permissions', 'permissions.permission_num', '=', 'accounts.permission')
            ->where('permissions.permission_name', '=', 'Director')
            ->orWhere('permissions.permission_name', '=', 'Super Admin')
            ->update(['certificate_creation_permission' => 1]);
    }

    public function getViewSignature()
    {
        $this->updateCreatePermissions();

        $permission = DB::table('permissions')
            ->where('permissions.permission_num', '=', session()->get(StaticString::PERMISSION))
            ->where(function ($query) {
                $query->where('permissions.permission_name', '=', 'Director')
                    ->orWhere('permissions.permission_name', '=', 'Super Admin');
            })
            ->exists();
        $btnEdit = true;

        $latestSignatureSubQuery = DB::table('employee_signatures')
            ->select('employee_id', DB::raw('MAX(updated_at) as latest_updated_at'))
            ->groupBy('employee_id');

        $employeeQuery = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->join('permissions', 'permissions.permission_num', '=', 'accounts.permission')
            ->join('employee_signatures', function ($join) use ($latestSignatureSubQuery) {
                $join->on('employee_signatures.employee_id', '=', 'employees.employee_id')
                    ->joinSub($latestSignatureSubQuery, 'latest_signature', function ($join) {
                        $join->on('employee_signatures.employee_id', '=', 'latest_signature.employee_id')
                            ->on('employee_signatures.updated_at', '=', 'latest_signature.latest_updated_at');
                    });
            });

        if (!$permission) {
            $employeeQuery->where('accounts.account_id', '=', session()->get(StaticString::ACCOUNT_ID));
            $btnEdit = false;
        }

        $employee = $employeeQuery->get();
        // dd($employee);
        return view('auth.certificate.InternalCertificateSignature', [
            'employee' => $employee,
            'btnEdit' => $btnEdit,
        ]);
    }

    public function updateSignatureCertificate(Request $request)
    {
        // Xác thực dữ liệu yêu cầu
        $request->validate([
            'signatureId' => 'required|integer|exists:employee_signatures,employee_signature_id',
            'employeeId' => 'required|integer',
            'signature' => 'required|string',
        ]);

        $employeeCode = $request->input('employeeId');
        $employee = DB::table('employees')
            ->where('employee_code', '=', $employeeCode)
            ->first();
        $employeeUpdated = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->where('account_id', '=', session()->get(StaticString::ACCOUNT_ID))
            ->first();
        $signatureId = $request->input('signatureId');
        $signatureData = $request->input('signature');

        // Xử lý dữ liệu Base64
        $data = explode(',', $signatureData);
        $imageData = base64_decode(end($data));

        // Tạo tên file chữ ký mới với mã hóa
        $hashedFileName = md5(uniqid($signatureId, true)) . '.png';

        // Xác định đường dẫn để lưu file
        $directory = public_path('assets/img/signature/');
        $filePath = $directory . $hashedFileName;

        // Tạo thư mục nếu không tồn tại
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Lưu file vào thư mục public/assets/img/signature
        File::put($filePath, $imageData);
        // Cập nhật đường dẫn mới vào cơ sở dữ liệu
        DB::table('employee_signatures')
            ->insert([
                'employee_signature_img' => 'assets/img/signature/' . $hashedFileName,
                'employee_id' => $employee->employee_id,
                'employee_signature_created_id' => $employeeUpdated->employee_id
            ]);

        // Trả về phản hồi thành công
        return response()->json(['message' => 'Signature updated successfully'], 200);
    }

    public function loadHistorySignatureCertificate(Request $request)
    {
        $request->validate([
            'employeeCode' => 'required|integer',
        ]);
        $employeeCode = $request->input('employeeCode');
        $employee = DB::table('employees')
            ->where('employee_code', '=', $employeeCode)
            ->first();

        $history = DB::table('employee_signatures')
            ->join('employees AS creator', 'employee_signatures.employee_signature_created_id', '=', 'creator.employee_id')
            ->where('employee_signatures.employee_id', $employee->employee_id)
            ->select(
                'employee_signatures.employee_signature_img',
                'creator.employee_code as creator_code',
                'creator.first_name as creator_first_name',
                'creator.last_name as creator_last_name',
                'creator.photo as creator_photo',
                'employee_signatures.created_at'
            )
            ->orderBy('employee_signatures.created_at', 'desc')
            ->get();

        return response()->json([
            'employee' => $employee,
            'history' => $history,
        ]);
    }

    public function searchEmployee(Request $request)
    {
        $request->validate([
            'employeeValue' => 'required|string',
        ]);

        $employeeValue = strtolower(trim($request->input('employeeValue')));

        $employees = DB::table('employees')
            ->whereRaw("LOWER(employee_code) LIKE ?", ["%{$employeeValue}%"])
            ->orWhereRaw("LOWER(CONCAT(last_name, ' ', first_name)) LIKE ?", ["%{$employeeValue}%"])
            ->orWhereRaw("LOWER(REPLACE(CONCAT(last_name, ' ', first_name), ' ', '')) LIKE ?", ["%{$employeeValue}%"])
            ->orWhereRaw("LOWER(en_name) LIKE ?", ["%{$employeeValue}%"])
            ->orWhereRaw("LOWER(REPLACE(en_name, ' ', '')) LIKE ?", ["%{$employeeValue}%"])
            ->get();

        return response()->json([
            'employees' => $employees,
        ]);
    }

    public function addSignatureCertificate(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'img' => 'required|string',
            'employee' => 'required|integer'
        ]);

        $imageData = $request->input('img');
        $employeeCode = $request->input('employee');

        // Tìm nhân viên dựa trên mã nhân viên
        $employee = DB::table('employees')
            ->where('employee_code', $employeeCode)
            ->first();

        // Tìm nhân viên hiện tại đang đăng nhập
        $employeeUpdated = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->where('accounts.account_id', '=', session()->get(StaticString::ACCOUNT_ID))
            ->first();

        // Xử lý hình ảnh
        $imageParts = explode(',', $imageData);
        $imageExtension = 'png'; // Có thể thay đổi tùy nhu cầu
        $imageContent = base64_decode($imageParts[1]);

        // Tạo tên tệp tin và đường dẫn
        $imageName = 'signature_' . time() . '.' . $imageExtension;
        $imagePath = public_path('assets/img/signature/' . $imageName);

        // Lưu hình ảnh vào thư mục
        file_put_contents($imagePath, $imageContent);

        // Thêm thông tin vào cơ sở dữ liệu
        DB::table('employee_signatures')->insert([
            'employee_id' => $employee->employee_id,
            'employee_signature_img' => 'assets/img/signature/' . $imageName,
            'employee_signature_created_id' => $employeeUpdated->employee_id
        ]);

        return response()->json([
            'message' => 'Signature saved successfully!',
            'image_path' => $imagePath,
        ]);
    }

    public function getViewCreate()
    {
        $this->updateCreatePermissions();
        $employees = DB::table('employees')
            ->join('certificate_creates', 'employees.employee_id', '=', 'certificate_creates.employee_id')
            ->get();
        return view('auth.certificate.InternalCertificateCreate', [
            'employees' => $employees
        ]);
    }

    public function loadCertificateCreate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);
        $idCertificate = $request->input('id');
        $imgCertificate = DB::table('certificate_creates')
            ->where('certificate_create_id', '=', $idCertificate)
            ->first();
        return response()->json([
            'imgCertificate' => $imgCertificate
        ]);
    }

    public function updateStatusCertificateCreate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|integer'
        ]);

        $idCertificate = $request->input('id');
        $statusCertificate = $request->input('status');

        DB::table('certificate_creates')
            ->where('certificate_create_id', '=', $idCertificate)
            ->update(['certificate_create_status' => $statusCertificate]);

        return response()->json([
            'status' => "Updated"
        ]);
    }

    public function leftSignatureCertificateCreate(Request $request)
    {
        $director = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->join('permissions', 'permissions.permission_num', '=', 'accounts.permission')
            ->join('employee_signatures', function ($join) {
                $join->on('employee_signatures.employee_id', '=', 'employees.employee_id')
                    ->whereIn('employee_signatures.employee_signature_id', function ($query) {
                        $query->select(DB::raw('MAX(employee_signature_id)'))
                            ->from('employee_signatures')
                            ->groupBy('employee_id');
                    });
            })
            ->where('permissions.permission_name', '=', 'Director')
            ->select('employees.employee_id', 'employees.employee_code', 'employees.first_name', 'employees.last_name', 'employees.en_name', 'employee_signatures.employee_signature_img')
            ->groupBy('employees.employee_id', 'employees.employee_code', 'employees.first_name', 'employees.last_name', 'employees.en_name', 'employee_signatures.employee_signature_img')
            ->get();


        return response()->json([
            'director' => $director
        ]);
    }

    public function searchEmployeeHasSignature(Request $request)
    {
        $request->validate([
            'employeeValue' => 'required|string',
        ]);

        $employeeValue = strtolower(trim($request->input('employeeValue')));

        $employees = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->join('permissions', 'permissions.permission_num', '=', 'accounts.permission')
            ->join('employee_signatures', function ($join) {
                $join->on('employee_signatures.employee_id', '=', 'employees.employee_id')
                    ->whereIn('employee_signatures.employee_signature_id', function ($query) {
                        $query->select(DB::raw('MAX(employee_signature_id)'))
                            ->from('employee_signatures')
                            ->groupBy('employee_id');
                    });
            })
            ->whereRaw("LOWER(employees.employee_code) LIKE ?", ["%{$employeeValue}%"])
            ->orWhereRaw("LOWER(CONCAT(employees.last_name, ' ', employees.first_name)) LIKE ?", ["%{$employeeValue}%"])
            ->orWhereRaw("LOWER(REPLACE(CONCAT(employees.last_name, ' ', employees.first_name), ' ', '')) LIKE ?", ["%{$employeeValue}%"])
            ->orWhereRaw("LOWER(employees.en_name) LIKE ?", ["%{$employeeValue}%"])
            ->orWhereRaw("LOWER(REPLACE(employees.en_name, ' ', '')) LIKE ?", ["%{$employeeValue}%"])
            ->select('employees.employee_id', 'employees.employee_code', 'employees.first_name', 'employees.last_name', 'employees.en_name', 'employee_signatures.employee_signature_img')
            ->groupBy('employees.employee_id', 'employees.employee_code', 'employees.first_name', 'employees.last_name', 'employees.en_name', 'employee_signatures.employee_signature_img')
            ->get();
        ;

        return response()->json([
            'employees' => $employees,
        ]);
    }

    public function addCertificate(Request $request)
    {
        $request->validate([
            'img' => 'required|string',
            'employeeCode' => 'required|integer'
        ]);

        $imageData = $request->input('img');
        $employeeCode = $request->input('employeeCode');

        // Tìm nhân viên dựa trên mã nhân viên
        $employee = DB::table('employees')
            ->where('employee_code', $employeeCode)
            ->first();

        // Tìm nhân viên hiện tại đang đăng nhập
        $employeeUpdated = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->where('accounts.account_id', '=', session()->get(StaticString::ACCOUNT_ID))
            ->first();

        // Xử lý hình ảnh
        $imageParts = explode(',', $imageData);
        $imageExtension = 'png'; // Có thể thay đổi tùy nhu cầu
        $imageContent = base64_decode($imageParts[1]);

        // Tạo tên tệp tin và đường dẫn
        $imageName = 'certificate_' . time() . '.' . $imageExtension;
        $imagePath = public_path('assets/img/certificate/' . $imageName);

        // Lưu hình ảnh vào thư mục
        file_put_contents($imagePath, $imageContent);

        DB::table('certificate_creates')->insert([
            'employee_id' => $employee->employee_id,
            'certificate_create_img' => 'assets/img/certificate/' . $imageName,
            'employeed_update_id' => $employeeUpdated->employee_id
        ]);

        return;
    }

    public function temp()
    {

        $employee = DB::table('employees')->where('employee_id', '=', '3')->first();

        $director = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->join('employment_contract', 'employment_contract.employee_id', '=', 'employees.employee_id')
            ->join('employee_signatures', 'employee_signatures.employee_id', '=', 'employees.employee_id')
            ->join('permissions', 'permissions.permission_num', '=', 'accounts.permission')
            ->where(DB::raw('DATE(employment_contract.end_date)'), '>=', Carbon::now()->toDateString())
            ->where('permissions.permission_name', '=', 'Director')
            ->orderBy('employee_signatures.updated_at', 'desc')
            ->first();

        $teacher = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->join('permissions', 'permissions.permission_num', '=', 'accounts.permission')
            ->join('employee_signatures', 'employee_signatures.employee_id', '=', 'employees.employee_id')
            ->where('permissions.permission_str', '=', 'teacher')
            ->orderBy('employee_signatures.updated_at', 'desc')
            // ->where('employees.employee_id', '=', '191')
            ->first();

        return view('auth.certificate.certificate', [
            'employee' => $employee,
            'director' => $director,
            'teacher' => $teacher
        ]);
    }
}
