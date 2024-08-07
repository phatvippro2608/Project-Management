<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalCertificatesController extends Controller
{
    //
    public function getViewUser()
    {
        return view('auth.certificate.InternalCertificate');
    }

    public function getViewType()
    {
        $certificates = DB::table('certificate_internals')
            ->join('certificate_bodys', 'certificate_bodys.certificate_body_id', '=', 'certificate_internals.certificate_body_id')
            ->get();
            // dd($certificates);
        return view('auth.certificate.InternalCertificateType', [
            'certificates' => $certificates
        ]);
    }
}
