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
    public function get($recognition_id)
    {
        return DB::table("recognitions")->where('recognition_id', $recognition_id)->get();
    }
    // Hàm này trả về view cùng với danh sách recognitions và employees
    public function getView()
    {
        $recognitions = RecognitionModel::getRecognitions();
        $recognition_types = RecognitionModel::getRecognitionTypes();
        $employees = DB::table('employees')->get();

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

    public function update()
    {
//        return request();
        try {
            $validated = request()->validate([
                'recognition_id' => 'required|string',
                'recognition_type_id' => 'required|string',
                'recognition_date' => 'required|date',
                'recognition_hidden' => 'nullable|boolean',
                'description' => 'required|string',
            ]);

            // Xử lý giá trị của recognition_hidden
            $validated['recognition_hidden'] = request()->has('recognition_hidden') ? True : False;

            // Tìm bản ghi bằng recognition_id
            $recognition = RecognitionModel::where('recognition_id', $validated['recognition_id'])->first();
            if ($recognition) {
                // Cập nhật bản ghi với dữ liệu đã được xác thực
                $recognition->update($validated);
                return response()->json(['status' => 200, 'message' => 'Updated recognition successfully']);
            } else {
                return response()->json(['status' => 404, 'message' => 'Recognition not found']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Trả về lỗi xác thực cụ thể
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $e->errors(),  // Trả về chi tiết lỗi
            ]);
        } catch (\Exception $e) {
            // Trả về lỗi hệ thống khác
            return response()->json([
                'status' => 500,
                'message' => 'Failed to update recognition',
                'error' => $e->getMessage()
            ]);
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
            return response()->json(['status' => 500, 'message' => 'Không có tệp Excel nào được tải lên']);
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
                return response()->json(['status' => 404, 'message' => "Không tìm thấy employee với employee_code: $employee_code"]);
            }
        }
        return response()->json(['status' => 200, 'message' => 'Import thành công']);
    }
}
