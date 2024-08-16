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

        // Hiển thị kết quả với view recognition
        return view('auth.recognition.recognition-type', [
            'recognition_types' => $recognition_types,
            'title' => 'Recognition Types'
        ]);
    }

    public function getRecognitionType($recognition_type_id)
    {
        return RecognitionTypeModel::getRecognitionTypes()->where('recognition_type_id', $recognition_type_id)->first();
    }
}
