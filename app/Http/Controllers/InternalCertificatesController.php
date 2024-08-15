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

        $employeeQuery = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->join('permissions', 'permissions.permission_num', '=', 'accounts.permission')
            ->join('employee_signatures', 'employee_signatures.employee_id', '=', 'employees.employee_id');

        if (!$permission) {
            $employeeQuery->where('accounts.account_id', '=', session()->get(StaticString::ACCOUNT_ID));
        }

        $employee = $employeeQuery->get();
        return view('auth.certificate.InternalCertificateSignature', [
            'employee' => $employee,
        ]);
    }

    public function updateSignatureCertificate(Request $request)
    {
        // Xác thực dữ liệu yêu cầu
        $request->validate([
            'signatureId' => 'required|integer|exists:employee_signatures,employee_signature_id',
            'signature' => 'required|string', // Đây là dữ liệu Base64
        ]);

        // Lấy dữ liệu từ yêu cầu
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
            ->where('employee_signature_id', $signatureId)
            ->update([
                'employee_signature_img' => 'assets/img/signature/' . $hashedFileName,
                'updated_at' => now()
        ]);

        // Trả về phản hồi thành công
        return response()->json(['message' => 'Signature updated successfully'], 200);
    }

    public function getViewCreate()
    {
        $this->updateCreatePermissions();
        return view('auth.certificate.InternalCertificateCreate');
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
            ->first();

        $teacher = DB::table('employees')
            ->join('accounts', 'accounts.employee_id', '=', 'employees.employee_id')
            ->join('permissions', 'permissions.permission_num', '=', 'accounts.permission')
            ->join('employee_signatures', 'employee_signatures.employee_id', '=', 'employees.employee_id')
            ->where('permissions.permission_str', '=', 'teacher')
            // ->where('employees.employee_id', '=', '191')
            ->first();

        return view('auth.certificate.certificate', [
            'employee' => $employee,
            'director' => $director,
            'teacher' => $teacher
        ]);
    }
}
