<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\table;

class CustomerController extends Controller
{

    private $status = [1 => 'Active', 2 => 'Offine', 3 => 'Locked'];

    function getView()
    {
        $customer = DB::table('customers')->join('contracts', 'customers.id_customer', 'contracts.id_customer')->get();
        return view('auth.customer.customer', ['customer' => $customer, 'status' => $this->status]);
    }

    function getUpdateView(Request $request)
    {
        $customer = DB::table('customers')->join('contracts', 'customers.id_customer', 'contracts.id_customer')->get();
        return view('auth.customer.customer', ['customer' => $customer, 'status' => $this->status]);
    }

    function add(Request $request)
    {
        $new_customer = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'status' => $request->status,
        ];
        if ($id_new_customer=DB::table('customers')->insertGetId($new_customer)) {
            $new_contract = [
                'id_customer' => $id_new_customer,
                'contract_name' => $request->contract_name,
                'contract_date' => $request->contract_date,
                'contract_end_date' => $request->contract_end_date,
                'contract_details' => $request->contract_details
            ];
            if($id_new_contract=DB::table('contracts')->insertGetId($new_contract)){
                return AccountController::status('Thêm thành công', 200);
            }else{
                DB::table('customers')->where('id_customer', $id_new_customer)->delete();
                return AccountController::status('Thêm thất bại', 500);
            }
        } else {
            return AccountController::status('Thêm thất bại', 500);
        }
    }

    function update(Request $request)
    {
        $new_customer = [
            'id_customer' => $request->id_customer,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'status' => $request->status,
        ];

        if ($id_new_customer=DB::table('customers')->insertGetId($new_customer)) {
            $new_contract = [
                'id_customer' => $id_new_customer,
                'contract_name' => $request->contract_name,
                'contract_date' => $request->contract_date,
                'contract_end_date' => $request->contract_end_date,
                'contract_details' => $request->contract_details
            ];
            if($id_new_contract=DB::table('contracts')->insertGetId($new_contract)){
                return AccountController::status('Thêm thành công', 200);
            }else{
                DB::table('customers')->where('id_customer', $id_new_customer)->delete();
                return AccountController::status('Thêm thất bại', 500);
            }
        } else {
            return AccountController::status('Thêm thất bại', 500);
        }
    }

    function delete(Request $request)
    {
        if (DB::table('team')->where('id_team', $request->id_team)->delete()) {
            return AccountController::status('Xóa thành công', 200);
        } else {
            return AccountController::status('Xóa thất bại', 500);
        }
    }

}
