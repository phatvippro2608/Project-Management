<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\DisciplinaryModel;
use App\Models\DisciplinaryTypeModel;
use App\Models\RecognitionModel; // Đảm bảo RecognitionModel được import
use App\Models\SpreadsheetModel; // Đảm bảo tên model khớp nhau
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Đảm bảo sử dụng Log để ghi lỗi

class DisciplinaryController extends Controller
{
    public function get($recognition_id)
    {
        return DB::table("disciplinaries")->where('disciplinary_id', $recognition_id)->get();
    }

    // Hàm này trả về view cùng với danh sách recognitions và employees
    public function getView()
    {
        $disciplinary_types = DisciplinaryModel::getDisciplinaryTypes();
        $disciplinaries = DisciplinaryModel::getDisciplinaries();
        $employees = DB::table('employees')->get();

        // Hiển thị kết quả với view recognition
        return view('auth.disciplinary.disciplinary', [
            'disciplinaries' => $disciplinaries,
            'disciplinary_types' => $disciplinary_types,
            'employees' => $employees,
            'title' => 'Disciplinary'
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string',
            'disciplinary_type_id' => 'required|string',
            'disciplinary_date' => 'required|date',
            'description' => 'required|string',
        ]);

        try {
            DisciplinaryModel::create($validated);
            return response()->json(['status' => 200, 'message' => 'Added disciplinary']);
        } catch (\Exception $e) {
            Log::error('Failed to add disciplinary: ', ['error' => $e->getMessage()]);
            return response()->json(['status' => 500, 'message' => 'Failed to add disciplinary', 'error' => $e->getMessage()]);
        }
    }

    public function update()
    {
        try {
            $validated = request()->validate([
                'disciplinary_id' => 'required|string',
                'disciplinary_type_id' => 'required|string',
                'disciplinary_date' => 'required|date',
                'disciplinary_hidden' => 'nullable|boolean',
                'description' => 'required|string',
            ]);

            // Xử lý giá trị của recognition_hidden
            $validated['disciplinary_hidden'] = request()->has('disciplinary_hidden') ? true : false;

            // Tìm bản ghi bằng recognition_id
            $recognition = DisciplinaryModel::where('disciplinary_id', $validated['disciplinary_id'])->first();
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
            Log::error('Failed to update disciplinary: ', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 500,
                'message' => 'Failed to update disciplinary',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function addType(Request $request)
    {
        $validated = $request->validate([
            'disciplinary_type_name' => 'required|string',
        ]);

        try {
            DisciplinaryTypeModel::create($validated);
            return response()->json(['status' => 200, 'message' => 'Added recognition']);
        } catch (\Exception $e) {
            Log::error('Failed to add recognition: ', ['error' => $e->getMessage()]);
            return response()->json(['status' => 500, 'message' => 'Failed to add recognition', 'error' => $e->getMessage()]);
        }
    }

    public function import(Request $request)
    {
        // Kiểm tra nếu không có tệp Excel nào được tải lên
        if (!$request->hasFile('file-excel')) {
            return response()->json(['status' => 500, 'message' => 'Không có tệp Excel nào được tải lên']);
        }

        try {
            // Đọc dữ liệu từ tệp Excel
            $file = $request->file('file-excel');
            $dataExcel = SpreadsheetModel::readExcel($file);

            // Kiểm tra dữ liệu Excel
            if (empty($dataExcel['data'])) {
                return response()->json(['status' => 400, 'message' => 'Dữ liệu Excel không hợp lệ hoặc trống']);
            }

            // Bỏ qua dòng đầu tiên
            $dataExcel['data'] = array_slice($dataExcel['data'], 1);

            $errors = [];
            foreach ($dataExcel['data'] as $item) {
                // Kiểm tra và lấy employee_code từ dữ liệu Excel
                $employee_code = trim($item[1] ?? '');

                if ($employee_code === '') {
                    $errors[] = "Dòng không hợp lệ trong tệp Excel: " . json_encode($item);
                    continue;
                }

                // Truy vấn để lấy employee_id từ bảng employees
                $employee = DB::table('employees')->where('employee_code', $employee_code)->first();

                // Nếu tìm thấy employee với employee_code tương ứng
                if ($employee) {
                    $data = [
                        'employee_id' => $employee->employee_id,
                        'disciplinary_type_id' => $request->disciplinary_type_id,
                        'disciplinary_date' => $request->disciplinary_date,
                    ];

                    // Chèn dữ liệu vào bảng disciplinaries
                    DB::table('disciplinaries')->insert($data);
                } else {
                    $errors[] = "Không tìm thấy employee với employee_code: $employee_code";
                }
            }

            if (!empty($errors)) {
                return response()->json(['status' => 404, 'message' => 'Một số bản ghi không được nhập:', 'errors' => $errors]);
            }

            return response()->json(['status' => 200, 'message' => 'Import thành công']);

        } catch (\Exception $e) {
            // Ghi log lỗi chi tiết
            Log::error('Failed to import: ', ['error' => $e->getMessage()]);
            return response()->json(['status' => 500, 'message' => 'Failed to import', 'error' => $e->getMessage()]);
        }
    }
}
