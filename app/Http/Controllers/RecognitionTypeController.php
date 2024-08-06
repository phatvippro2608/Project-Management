<?php

namespace App\Http\Controllers;

use App\Models\RecognitionTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecognitionTypeController extends Controller
{
    public function getView()
    {
        $recognition_types = RecognitionTypeModel::getRecognitionTypes();

//        dd($recognition_types);

        // Hiển thị kết quả với view recognition
        return view('auth.recognition.recognition-type', [
            'recognition_types' => $recognition_types,
            'title' => 'Recognition Types'
        ]);
    }
}
