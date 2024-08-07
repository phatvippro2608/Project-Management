<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalCertificatesController extends Controller
{
    //
    public function getViewUser()
    {
        $employees = DB::table('employees')
            ->join('certificates', 'certificates.employee_id', '=', 'employees.employee_id')
            ->join('certificate_types', 'certificate_types.certificate_type_id', '=', 'certificates.type_certificate_id')
            ->join('certificate_bodys', 'certificate_bodys.certificate_body_id', '=', 'certificate_types.certificate_body_id')
            ->where('employees.fired', '=', 'false')
            ->get();
        // dd($employess);
        return view('auth.certificate.InternalCertificate', [
            'employees' => $employees
        ]);
    }

    public function getViewType()
    {
        $certificates = DB::table('certificate_internals')
            ->join('certificate_bodys', 'certificate_bodys.certificate_body_id', '=', 'certificate_internals.certificate_body_id')
            ->get();
        return view('auth.certificate.InternalCertificateType', [
            'certificates' => $certificates
        ]);
    }
}
