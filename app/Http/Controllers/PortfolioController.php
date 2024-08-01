<?php

namespace App\Http\Controllers;

use Composer\XdebugHandler\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    public function getView()
    {
        $sql = DB::table('employees')->get()->map(function ($item) {
            // Kiểm tra xem photo có tồn tại và không phải là null
            $item->photoExists = !is_null($item->photo) && file_exists(public_path($item->photo));
            return $item;
        });
        return view('auth.portfolio.portfolio', ['sql' => $sql]);
    }
    public function getViewHasId($id)
    {
        $existsCode = DB::table('employees')->where('employee_code', $id)->exists();
        if (!$existsCode) {
            return abort(404); // Trả về trang 404 nếu không tìm thấy mã nhân viên
        }
        $employee = DB::table('employees')->where('employee_code', $id)->first();
        $dateOfJoin = DB::table('job_certificates')->where('employee_id', $employee->employee_id)->latest('job_certificate_id')->first();
        return view('auth.portfolio.portfolioHasId', ['employee' => $employee, 'dateOfJoin' => $dateOfJoin]);
    }


}
