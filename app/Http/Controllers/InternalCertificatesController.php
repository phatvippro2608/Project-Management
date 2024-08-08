<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

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
            'certificate_id' => 'required|exists:certificates,id',
            'certificate_body_name' => 'required|exists:companies,certificate_body_id',
            'certificate_body_Acronym' => 'required|string',
            'certificate_type_name' => 'required|string|max:255',
            'certificate_type_acronym' => 'required|string|max:255',
        ]);

        try {
            $certificate = DB::table('certificates')->where('certificate_id', $request->input('certificate_id'))->first();

            if (!$certificate) {
                return response()->json(['message' => 'Certificate not found.'], 404);
            }

            // Cập nhật chứng chỉ
            DB::table('certificates')->where('certificate_id', $request->input('certificate_id'))->update([
                'certificate_body_name' => $request->input('certificate_body_name'),
                'certificate_body_Acronym' => $request->input('certificate_body_Acronym'),
                'certificate_type_name' => $request->input('certificate_type_name'),
                'certificate_type_acronym' => $request->input('certificate_type_acronym'),
                'updated_at' => now()
            ]);

            return response()->json(['message' => 'Certificate updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the certificate.'], 500);
        }
    }

}
