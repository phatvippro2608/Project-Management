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

    public function updRecognitionType(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'recognition_type_name' => 'required|string|max:255',
            'recognition_type_hidden' => 'required|boolean',
        ]);

        // Find the recognition type by ID
        $recognitionType = RecognitionTypeModel::findOrFail($id);

        // Update the recognition type with new data
        $recognitionType->recognition_type_name = $request->input('recognition_type_name');
        $recognitionType->recognition_type_hidden = $request->input('recognition_type_hidden');
        $recognitionType->save();

        // Return a response, possibly the updated recognition type or a success message
        return response()->json([
            'success' => true,
            'message' => 'Recognition type updated successfully!',
            'data' => $recognitionType
        ]);
    }
}
