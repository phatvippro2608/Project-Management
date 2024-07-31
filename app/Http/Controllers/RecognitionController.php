<?php

namespace App\Http\Controllers;

use App\Models\RecognitionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecognitionController extends Controller
{
    // Hàm này trả về view cùng với danh sách recognitions và employees
    public function getView()
    {
        // Lấy danh sách recognition và employee có phân trang
        $recognitions = (new RecognitionModel)->getEmployee();

        // Lấy các loại recognition với điều kiện cụ thể
        $recognition_types = DB::table('recognition_types')->where('recognition_type_id', 5)->get();

        // Hiển thị kết quả với view recognition
        return view('auth.recognition.recognition', [
            'recognition_types' => $recognition_types,
            'recognitions' => $recognitions,
            'title' => 'Recognition'
        ]);
    }
}
