<?php

namespace App\Http\Controllers;

use App\Models\ContractModel;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\table;

class CustomerController extends Controller
{

    private $status = [1 => 'Active', 2 => 'Offine', 3 => 'Locked'];

    function getView()
    {
        $customer = DB::table('customers')->join('contracts', 'customers.customer_id', 'contracts.customer_id')->get();
        return view('auth.customer.customer', ['customer' => $customer, 'status' => $this->status]);
    }

    function getUpdateView(Request $request)
    {
        $id_customer = $request->customer_id;
        $customer = CustomerModel::where('customer_id', $id_customer)->first();
        $contracts = ContractModel::where('customer_id', $id_customer)->get();
        return view('auth.customer.customer-update', ['customer' => $customer, 'contracts' => $contracts, 'status' => $this->status]);
    }

    function add(Request $request)
    {
        $new_customer = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
            'status' => $request->status,
        ];
        if ($id_new_customer = DB::table('customers')->insertGetId($new_customer)) {
            $new_contract = [
                'customer_id' => $id_new_customer,
                'contract_name' => $request->contract_name,
                'contract_date' => $request->contract_date,
                'contract_end_date' => $request->contract_end_date,
                'contract_details' => $request->contract_details
            ];
            if ($id_new_contract = DB::table('contracts')->insertGetId($new_contract)) {
                return AccountController::status('Thêm thành công', 200);
            } else {
                DB::table('customers')->where('customer_id', $id_new_customer)->delete();
                return AccountController::status('Added Customer', 500);
            }
        } else {
            return AccountController::status('Fail To Add Customer', 500);
        }
    }

    function update(Request $request)
    {
        $update_customer = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
            'status' => $request->status,
        ];
        $count = DB::table('customers')->where('customer_id', $request->customer_id)->update($update_customer);

        if ($count>0) {
            return AccountController::status('Updated Customer', 200);
        } else {
            return AccountController::status('Fail To Update', 500);
        }
    }

    function delete(Request $request)
    {
        if (DB::table('customers')->where('customer_id', $request->customer_id)->delete()) {
            return AccountController::status('Deleted Customer', 200);
        } else {
            return AccountController::status('Fail To Delete', 500);
        }
    }

    function query()
    {
        DB::table("projects")->all();
        return true;
    }
}
