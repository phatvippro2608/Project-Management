<?php

namespace App\Http\Controllers;

use App\Models\DisciplinaryModel;
use App\Models\DisciplinaryTypeModel;
use App\Models\RecognitionTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisciplinaryTypeController extends Controller
{
    public function getView()
    {
        $disciplinary_types = DB::table('disciplinary_types')->get();

        // Hiển thị kết quả với view recognition
        return view('auth.disciplinary.disciplinary-type', [
            'disciplinary_types' => $disciplinary_types,
            'title' => 'Disciplinary'
        ]);
    }

    public function get($id)
    {
        return DisciplinaryTypeModel::where('disciplinary_type_id', $id)->first();
    }

    public function upd(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'disciplinary_type_name' => 'required|string|max:255',
            'disciplinary_type_hidden' => 'required|boolean',
        ]);

        // Find the recognition type by ID
        $disciplinaryType = DisciplinaryTypeModel::findOrFail($id);

        // Update the recognition type with new data
        $disciplinaryType->disciplinary_type_name = $request->input('disciplinary_type_name');
        $disciplinaryType->disciplinary_type_hidden = $request->input('disciplinary_type_hidden');
        $disciplinaryType->save();

        // Return a response, possibly the updated recognition type or a success message
        return response()->json([
            'success' => true,
            'message' => 'Recognition type updated successfully!',
            'data' => $disciplinaryType
        ]);
    }

    public function del($id)
    {
        try {
            // Tìm loại theo ID -> Xóa
            DisciplinaryTypeModel::findOrFail($id)->delete();

            // Trả về phản hồi thành công
            return response()->json([
                'success' => true,
                'message' => 'Recognition type deleted successfully!',
            ]);
        } catch (\Exception $e) {
            // Trả về phản hồi lỗi
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while deleting recognition type.',
            ], 500);
        }
    }
}
