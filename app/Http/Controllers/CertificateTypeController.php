<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateTypeController extends Controller
{
    public function getView(){
        $certificate_types = DB::table('certificate_types')->where('status','show')->get();
//        dd($certificate_types);
        return view('auth.employees.certificate_type',[
            'certificate_types' => $certificate_types
        ]);
    }
    public function add(Request $request) {
        $certificate_type_name = $request->input('certificate_type_name');
        $sql = DB::table('certificate_types')->insert(['certificate_type_name' => $certificate_type_name,'status'=>'show']);
        if ($sql) {
            return response()->json(['status' => 200, 'message' => 'Action Success']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Action Failed']);
        }
    }


    public function update(Request $request){
        $certificate_type_id = $request->certificate_type_id;
        $old_certificate_type_name = DB::table('certificate_types')->where('certificate_type_id',$certificate_type_id)->select('certificate_type_name')->first();
        if ($old_certificate_type_name && $old_certificate_type_name->certificate_type_name === $request->input('certificate_type_name')) {
            return response()->json(['status' => 200, 'message' => 'Action Success']);
        }
        $updated = DB::table('certificate_types')->where('certificate_type_id',$certificate_type_id)->update(['certificate_type_name' => $request->certificate_type_name]);
        if ($updated) {
            return response()->json(['status' => 200, 'message' => 'Action Success']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Action Failed']);
        }
    }

    public function delete(Request $request){
        $certificate_type_id = $request->certificate_type_id;
        $sql = DB::table('certificate_types')->where('certificate_type_id',$certificate_type_id)->update(['status' => 'hide']);
        if ($sql) {
            return response()->json(['status' => 200, 'message' => 'Action Success']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Action Failed']);
        }
    }
}
