<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DepartmentController extends Controller
{
    public function getView()
    {
        $departments = DepartmentModel::all();
        return view('auth.departments.department-list', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:255',
        ]);
        if(DB::table('departments')->where('department_name', $validated['department_name'])->exists()){
            return response()->json([
                'success' => false,
                'message' => 'Department already exist',
            ]);
        }

        $department = DepartmentModel::create($validated);


        return response()->json([
            'success' => true,
            'department' => $department,
            'message' => 'Department added successfully',
        ]);
    }

    public function show($id)
    {
        $department = DepartmentModel::find($id);
//        return view('departments.show', compact('department'));
    }

    public function edit(Request $request,$id)
    {
        $department = DepartmentModel::findOrFail($id);

        return response()->json([
            'department' => $department,
        ]);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department = DepartmentModel::findOrFail($id);
        $department->update($validated);

        return response()->json([
            'success' => true,
            'department' => $department,
        ]);
    }

    public function destroy($id)
    {
        $department = DepartmentModel::findOrFail($id);
        $department->delete();

        return response()->json([
            'success' => true,
        ]);
    }


    public function getEmployeeOfDepartment(Request $request, $department_id)
    {
        $department_name = DB::table('departments')->where('department_id', $department_id)->value('department_name');
        $data = DB::table('departments')
            ->join('employees', 'departments.department_id', '=', 'employees.department_id')
            ->where('departments.department_id', $department_id)->get();
//        dd($data);
        return view('auth.departments.department',[
            'department_name' => $department_name,
            'department_id' => $department_id,
            'data' => $data,
        ]);
    }

    public function addEmployee(Request $request, $department_id){
        $employees = explode(',', $request->employees);
        $add = DB::table('employees')->whereIn('employee_id', $employees)->update(['department_id' => $department_id]);
        if($add){
            return response()->json(['message' => 'Action successful', 'status' => 200]);
        }else{
            return response()->json(['message' => 'Action failed', 'status' => 400]);
        }
    }

    public function deleteEmployee(Request $request,$department_id, $employee_id){
        $delete = DB::table('employees')
            ->where('department_id', $department_id)
            ->where('employee_id', $employee_id)
            ->update(['department_id' => null]);

        if($delete){
            return response()->json(['message' => 'Action successful', 'status' => 200]);
        }else{
            return response()->json(['message' => 'Action failed', 'status' => 400]);
        }
    }

    static public function getEmployeeNotInDepartment(){
        $data = DB::table('employees')->where('department_id', null)->orderBy('employee_code')->get();
        return $data;
    }

    public function export(Request $request, $department_id)
    {
        $inputFileName = public_path('excel-example/employee_department.xlsx');
        $department_name = DB::table('departments')->where('department_id', $department_id)->value('department_name');
        $inputFileType = IOFactory::identify($inputFileName);

        $objReader = IOFactory::createReader($inputFileType);

        $excel = $objReader->load($inputFileName);

        $excel->setActiveSheetIndex(0);
        $excel->getDefaultStyle()->getFont()->setName('Times New Roman');

        $stt = 1;
        $cell = $excel->getActiveSheet();

        $data = DB::table('employees')
            ->join('contacts', 'employees.contact_id', '=', 'contacts.contact_id')
            ->join('accounts', 'employees.employee_id', '=', 'accounts.employee_id')
            ->join('departments', 'employees.department_id', '=', 'departments.department_id')
            ->where('employees.department_id', $department_id)->orderBy('employee_code')->get();
        $num_row = 2;
        foreach ($data as $row) {
            $cell->setCellValue('A' . $num_row, $stt++);
            $cell->setCellValue('B' . $num_row, $row->employee_code);
            $cell->setCellValue('C' . $num_row, $row->first_name);
            $cell->setCellValue('D' . $num_row, $row->last_name);
            $cell->setCellValue('E' . $num_row, $row->en_name);
            $cell->setCellValue('F' . $num_row, $row->phone_number);
            $cell->setCellValue('G' . $num_row, $row->email ?? '');
            $cell->setCellValue('H' . $num_row, $row->gender == 0 ? 'Male' : 'Female');
            $cell->setCellValue('I' . $num_row, $row->marital_status);
            $cell->setCellValue('J' . $num_row, $row->date_of_birth);
            $cell->setCellValue('K' . $num_row, $row->national);
            $cell->setCellValue('L' . $num_row, $row->military_service);
            $cell->setCellValue('M' . $num_row, $row->cic_number);
            $cell->setCellValue('N' . $num_row, $row->cic_issue_date);
            $cell->setCellValue('O' . $num_row, $row->cic_expiry_date);
            $cell->setCellValue('P' . $num_row, $row->cic_place_issue);
            $cell->setCellValue('Q' . $num_row, $row->current_residence);
            $cell->setCellValue('R' . $num_row, $row->permanent_address);
            $cell->setCellValue('S' . $num_row, $row->department_name);
            $borderStyle = $cell->getStyle('A'.$num_row.':S' . $num_row)->getBorders();
            $borderStyle->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $cell->getStyle('A'.$num_row.':S' . $num_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $num_row++;
        }
        foreach (range('A', 'S') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $filename = "Employees-List-Of-Department-". $department_name  . '.xlsx';
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');
    }

}
