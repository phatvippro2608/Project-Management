<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\LeaveApplicationModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LeaveReportsController extends Controller
{
    public function getView()
    {
        $employees = EmployeeModel::all();
        return view('auth.leave.leave-report', compact('employees'));
    }

    public function searchReports(Request $request)
    {
        $monthYear = $request->input('report_month_year');
        $employeeId = $request->input('employee_id');

        $reports = LeaveApplicationModel::with(['leaveType', 'employee'])
            ->where('employee_id', $employeeId)
            ->whereYear('start_date', '=', Carbon::parse($monthYear)->year)
            ->whereMonth('start_date', '=', Carbon::parse($monthYear)->month)
            ->get();

        return response()->json($reports);
    }

    public function approveLeaveApplication($id)
    {
        $leaveApplication = LeaveApplicationModel::findOrFail($id);
        $leaveApplication->leave_status = 'Approved';
        $leaveApplication->apply_date = Carbon::now();
        $leaveApplication->save();

        return response()->json([
            'success' => true,
            'message' => 'Leave request approved successfully',
            'leave_application' => $leaveApplication
        ]);
    }
    public function destroy($id)
    {
        $holiday = LeaveApplicationModel::findOrFail($id);
        $holiday->delete();

        return response()->json([
            'success' => true,
            'message' => 'Leave reports deleted successfully',
        ]);
    }

    public function getLeaveApplications()
    {
        $leaveApplications = LeaveApplicationModel::with('employee', 'leaveType')->get();
        return response()->json($leaveApplications);
    }

    public function exportExcel()
    {
        $inputFileName = public_path('excel-example/leave-report-export.xlsx');

        $inputFileType = IOFactory::identify($inputFileName);

        $objReader = IOFactory::createReader($inputFileType);

        $excel = $objReader->load($inputFileName);

        $excel->setActiveSheetIndex(0);
        $excel->getDefaultStyle()->getFont()->setName('Times New Roman');

        $stt = 1;
        $cell = $excel->getActiveSheet();

        $employees = LeaveApplicationModel::with('employee', 'leaveType')->get();
//        dd($employees);
        $num_row = 3;

        foreach ($employees as $row) {
            $cell->setCellValue('A' . $num_row, $stt++);
            $cell->setCellValue('B' . $num_row, $row->employee->employee_code);

            $cell->setCellValue('C' . $num_row, $row->employee->first_name . ' ' . $row->employee->last_name);
            $cell->setCellValue('D' . $num_row, $row->leaveType->leave_type);
            $cell->setCellValue('E' . $num_row, $row->apply_date);
            $cell->setCellValue('F' . $num_row, $row->duration);
            $cell->setCellValue('G' . $num_row, $row->start_date);
            $cell->setCellValue('H' . $num_row, $row->end_date);
            $cell->setCellValue('I' . $num_row, $row->leave_status);

            $borderStyle = $cell->getStyle('A'.$num_row.':I' . $num_row)->getBorders();
            $borderStyle->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $cell->getStyle('A'.$num_row.':I' . $num_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $num_row++;
        }
        foreach (range('A', 'I') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $filename = "Leave-Report" . '.xlsx';
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');
    }
}
