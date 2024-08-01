<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\RecognitionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecognitionController extends Controller
{
    // Hàm này trả về view cùng với danh sách recognitions và employees
    public function getView()
    {
        $recognitions = (new RecognitionModel)->getRecognitions();
        $recognition_types = (new RecognitionModel)->getRecognitionTypes();
        $employees = (new EmployeeModel)->getEmployee();

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
            'description' => 'required|string',
            'recognition_date' => 'required|date',
        ]);

        try {
//            \Log::info('Validated data: ', $validated); // Log dữ liệu đã validate
            RecognitionModel::create($validated);
            return response()->json(['status' => 200, 'message' => 'Added recognition']);
        } catch (\Exception $e) {
//            \Log::error('Error adding recognition: ' . $e->getMessage()); // Log lỗi
            return response()->json(['status' => 500, 'message' => 'Failed to add recognition', 'error' => $e->getMessage()]);
        }
    }
}
