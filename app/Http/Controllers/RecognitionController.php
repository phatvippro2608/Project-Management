<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\RecognitionModel;
use App\Models\RecognitionTypeModel;
use App\Models\SpreadSheetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecognitionController extends Controller
{
    // Hàm này trả về view cùng với danh sách recognitions và employees
    public function getView()
    {
        $recognitions = (new RecognitionModel)->getRecognitions();
        $recognition_types = (new RecognitionModel)->getRecognitionTypes();
        $employees = DB::table('employees')->get();

//        dd($recognitions);

        // Hiển thị kết quả với view recognition
        return view('auth.recognition.recognition', [
            'recognition_types' => $recognition_types,
            'recognitions' => $recognitions,
            'employees' => $employees,
            'title' => 'Recognition'
        ]);
    }

    public function add(Request $request)
    {
//        return $request;
        $validated = $request->validate([
            'employee_id' => 'required|string',
            'recognition_type_id' => 'required|string',
            'recognition_date' => 'required|date',
            'description' => 'required|string',
        ]);

        try {
            RecognitionModel::create($validated);
            return response()->json(['status' => 200, 'message' => 'Added recognition']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Failed to add recognition', 'error' => $e->getMessage()]);
        }
    }

    public function addType(Request $request)
    {
        $validated = $request->validate([
            'recognition_type_name' => 'required|string',
        ]);

        try {
            RecognitionTypeModel::create($validated);
            return response()->json(['status' => 200, 'message' => 'Added recognition']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Failed to add recognition', 'error' => $e->getMessage()]);
        }
    }

    function import(Request $request)
    {
        // Kiểm tra nếu không có tệp Excel nào được tải lên
        if (!$request->hasFile('file-excel')) {
            return self::status('Không có tệp Excel nào được tải lên', 400);
        }

        // Đọc dữ liệu từ tệp Excel
        $dataExcel = SpreadsheetModel::readExcel($request->file('file-excel'));

        // Bỏ qua dòng đầu tiên
        $dataExcel['data'] = array_slice($dataExcel['data'], 1);

        foreach ($dataExcel['data'] as $item) {
            // Lấy employee_code từ dữ liệu Excel
            $employee_code = trim($item[1]);

            // Truy vấn để lấy employee_id từ bảng employees
            $employee = DB::table('employees')->where('employee_code', $employee_code)->first();

            // Nếu tìm thấy employee với employee_code tương ứng
            if ($employee) {
                $data = [
                    'employee_id' => $employee->employee_id,
                    'recognition_type_id' => $request->recognition_type_id,
                    'recognition_date' => $request->recognition_date,
                ];

                // Chèn dữ liệu vào bảng recognitions
                DB::table('recognitions')->insert($data);
            } else {
                // Xử lý trường hợp không tìm thấy employee với employee_code tương ứng
                // Bạn có thể ghi log, bỏ qua, hoặc trả về lỗi tùy thuộc vào yêu cầu của bạn
                return self::status("Không tìm thấy employee với employee_code: $employee_code", 404);
            }
        }
        return self::status('Import thành công', 200);
    }
}
