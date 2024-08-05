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
        'employee_id',
        'disciplinary_id',
        'disciplinary_type_id',
        'disciplinary_date',
        'disciplinary_hidden',
        'description'
    ];

    public static function getDisciplinaries()
    {
        try {
            // Lấy dữ liệu với left join các bảng liên quan và phân trang
            $disciplinaries = DB::table('disciplinaries')
                ->Join('employees', 'employees.employee_id', '=', 'disciplinaries.employee_id')
                ->Join('disciplinary_types', 'disciplinary_types.disciplinary_type_id', '=', 'disciplinaries.disciplinary_type_id')
                ->select(
                    'employees.employee_id', 'employees.employee_code', 'employees.last_name', 'employees.first_name', // Chọn các cột cần thiết từ bảng employees
                    'disciplinaries.disciplinary_id', 'disciplinaries.disciplinary_date', 'disciplinaries.disciplinary_hidden', // Chọn các cột cần thiết từ bảng recognitions
                    'disciplinary_types.disciplinary_type_id', 'disciplinary_types.disciplinary_type_name' // Chọn các cột cần thiết từ bảng recognition_types
                )->get();
            return $disciplinaries;
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public static function getDisciplinaryTypes()
    {
        return DB::table('disciplinary_types')->get();
    }

}
