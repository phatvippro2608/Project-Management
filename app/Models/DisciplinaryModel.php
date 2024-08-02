<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DisciplinaryModel extends Model
{
    use HasFactory;
    protected $table = 'disciplinaries';
    protected $primaryKey = 'disciplinary_id';
    protected $fillable = [
        'disciplinary_id',
        'disciplinary_type_id',
        'disciplinary_date',
        'disciplinary_hidden',
        'description'
    ];

    public function getRecognitions()
    {
        try {
            // Lấy dữ liệu với left join các bảng liên quan và phân trang
            $recognitions = DB::table('recognitions')
                ->Join('employees', 'employees.employee_id', '=', 'recognitions.employee_id')
                ->Join('recognition_types', 'recognition_types.recognition_type_id', '=', 'recognitions.recognition_type_id')
                ->select(
                    'employees.employee_id', 'employees.employee_code', 'employees.last_name', 'employees.first_name', // Chọn các cột cần thiết từ bảng employees
                    'recognitions.recognition_id', 'recognitions.recognition_date', 'recognitions.recognition_hidden', // Chọn các cột cần thiết từ bảng recognitions
                    'recognition_types.recognition_type_id', 'recognition_types.recognition_type_name' // Chọn các cột cần thiết từ bảng recognition_types
                )->get();
            return $recognitions;
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public static function getRecognitionTypes()
    {
        return DB::table('recognition_types')->get();
    }

}
