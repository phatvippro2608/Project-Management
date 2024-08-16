<?php

namespace App\Http\Controllers;

use App\Models\ContractModel;
use App\Models\CustomerModel;
use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use function Laravel\Prompts\table;

class CustomerController extends Controller
{

    private $status = [1 => 'Active', 2 => 'Offine', 3 => 'Locked'];

    function getView()
    {
        $sql = "SELECT customers.*, count(contracts.contract_id) as contract_count,sum(amount) as tong_phi
        FROM customers, contracts
        WHERE customers.customer_id = contracts.customer_id
        GROUP BY customers.customer_id, customers.last_name, customers.first_name, customers.date_of_birth, customers.address, customers.email, customers.phone_number, customers.company_name, customers.status, customers.created_at, customers.updated_at, customers.website";
        return view('auth.customer.customer', ['customer' => DB::select($sql), 'status' => $this->status]);
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
        $redirectUrl = $request->input('redirectUrl', '');
        if ($id_new_customer = DB::table('customers')->insertGetId($new_customer)) {
            $new_contract = [
                'customer_id' => $id_new_customer,
                'contract_name' => $request->contract_name,
                'contract_date' => $request->contract_date,
                'contract_end_date' => $request->contract_end_date,
                'contract_details' => $request->contract_details
            ];
            if(!empty($redirectUrl)){
                return AccountController::status($id_new_customer, 200);
            }
            if ($id_new_contract = DB::table('contracts')->insertGetId($new_contract)) {
                return AccountController::status('Added Customer', 200);
            } else {
                DB::table('customers')->where('customer_id', $id_new_customer)->delete();
                return AccountController::status('Fail to add Customer', 500);
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

    public function export(Request $request)
    {
        $inputFileName = public_path('excel-example/Customer.xlsx');

        $inputFileType = IOFactory::identify($inputFileName);

        $objReader = IOFactory::createReader($inputFileType);

        $excel = $objReader->load($inputFileName);

        $excel->setActiveSheetIndex(0);
        $excel->getDefaultStyle()->getFont()->setName('Times New Roman');

        $stt = 1;
        $cell = $excel->getActiveSheet();

        $data = DB::table('customers')->get();
        $num_row = 6;
        foreach ($data as $row) {
            $cell->setCellValue('A' . $num_row, $stt++);
            $cell->setCellValue('B' . $num_row, $row->last_name.' '.$row->first_name);
            $cell->setCellValue('C' . $num_row, $row->date_of_birth);
            $cell->setCellValue('D' . $num_row, $row->email);
            $cell->setCellValue('E' . $num_row, $row->phone_number);
            $cell->setCellValue('F' . $num_row, $row->address);
            $cell->setCellValue('G' . $num_row, $row->company_name);
            $cell->setCellValue('H' . $num_row, $row->website);
            $cell->setCellValue('I' . $num_row, $row->created_at);
            $borderStyle = $cell->getStyle('A'.$num_row.':I' . $num_row)->getBorders();
            $borderStyle->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $cell->getStyle('A'.$num_row.':I' . $num_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $num_row++;
        }
        foreach (range('A', 'I') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $filename = "Customer-List" . '.xlsx';
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');
    }

}
