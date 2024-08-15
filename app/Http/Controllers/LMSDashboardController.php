<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeModel;
use App\StaticString;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Http\Request;

class LMSDashboardController extends Controller
{
    public function getView(Request $request)
{
    $account_id = $request->session()->get(\App\StaticString::ACCOUNT_ID);

    // Retrieve employee_id based on account_id
    $sql_get_employee_id = "SELECT * FROM employees, accounts WHERE employees.employee_id = accounts.employee_id AND account_id = ?";
    $employee = DB::selectOne($sql_get_employee_id, [$account_id]);

    // Fetch total count of courses and certificates
    $course_c = DB::table('courses')->count();
    $employee_id = $employee->employee_id;
    $certificate_c = 0;

    // Get all courses with their types
    $courses = DB::table('courses')
        ->leftJoin('course_types', 'courses.course_type_id', '=', 'course_types.course_type_id')
        ->select('courses.course_id', 'courses.course_name', 'courses.description', 'courses.course_image', 'course_types.type_name AS course_type')
        ->get(); // Get result as a collection

    // Fetch course data for the employee
    $data = DB::table('courses_employees')
        ->join('employees', 'courses_employees.employee_id', '=', 'employees.employee_id')
        ->join('courses', 'courses_employees.course_id', '=', 'courses.course_id')
        ->join('course_types', 'courses.course_type_id', '=', 'course_types.course_type_id')
        ->select(
            'employees.first_name', 
            'employees.last_name', 
            'courses.course_id', 
            'courses.course_name',
            'courses_employees.start_date', 
            'courses_employees.end_date', 
            'courses_employees.progress',
            'course_types.type_name AS course_type'
        )
        ->where('courses_employees.employee_id', $employee_id)
        ->get();

    // Calculate completed certificates
    $certificate_c = $data->where('progress', 100)->count();
    $count = $data->count();

    // Pass the necessary data to the view
    return view('auth.lms.lms-dashboard', [
        'employee' => $employee,
        'data' => $data, 
        'count' => $count,
        'course_c' => $course_c, 
        'certificate_c' => $certificate_c,
        'allCourse' => $courses, 
        'getType' => $courses->pluck('course_type', 'course_id')->toArray() 
    ]);
}
public function export(Request $request)
    {
        $account_id = $request->session()->get(\App\StaticString::ACCOUNT_ID);

    // Retrieve employee_id based on account_id
    $sql_get_employee_id = "SELECT * FROM employees, accounts WHERE employees.employee_id = accounts.employee_id AND account_id = ?";
    $employee = DB::selectOne($sql_get_employee_id, [$account_id]);

    // Fetch total count of courses and certificates
    $course_c = DB::table('courses')->count();
    $employee_id = $employee->employee_id;

        // Fetch course data for the employee
        $data = DB::table('courses_employees')
            ->join('employees', 'courses_employees.employee_id', '=', 'employees.employee_id')
            ->join('courses', 'courses_employees.course_id', '=', 'courses.course_id')
            ->join('course_types', 'courses.course_type_id', '=', 'course_types.course_type_id')
            ->select(
                'employees.first_name', 
                'employees.last_name', 
                'courses.course_id', 
                'courses.course_name',
                'courses_employees.start_date', 
                'courses_employees.end_date', 
                'courses_employees.progress',
                'course_types.type_name AS course_type'
            )
            ->where('courses_employees.employee_id', $employee_id)
            ->get();

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');

        // Set the column headers
        $sheet->setCellValue('A1', 'Course ID')
              ->setCellValue('B1', 'Course Name')
              ->setCellValue('C1', 'Start Date')
              ->setCellValue('D1', 'End Date')
              ->setCellValue('E1', 'Progress (%)')
              ->setCellValue('F1', 'Course Type');

        // Write data to the spreadsheet
        $rowIndex = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowIndex, $row->course_id)
                  ->setCellValue('B' . $rowIndex, $row->course_name)
                  ->setCellValue('C' . $rowIndex, $row->start_date)
                  ->setCellValue('D' . $rowIndex, $row->end_date)
                  ->setCellValue('E' . $rowIndex, $row->progress)
                  ->setCellValue('F' . $rowIndex, $row->course_type);

            // Apply border style
            $sheet->getStyle('A'.$rowIndex.':F'.$rowIndex)
                  ->getBorders()
                  ->getAllBorders()
                  ->setBorderStyle(Border::BORDER_THIN);

            // Align cells
            $sheet->getStyle('A'.$rowIndex.':F'.$rowIndex)
                  ->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_LEFT);

            $rowIndex++;
        }

        // Auto size columns
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set headers and output the file
        $filename = "$employee->employee_code-Courses-List.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    
}
