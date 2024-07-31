<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecognitionModel extends Model
{
    use HasFactory;
    protected $table = 'recognitions';

    // Hàm này lấy danh sách employee cùng với recognition, có phân trang
    public function getEmployee()
    {
        $perPage = intval(env('ITEM_PER_PAGE', 15)); // Mặc định là 15 nếu không tìm thấy giá trị trong file .env
        return DB::table('employees')
            ->join('recognitions', 'employees.id_employee', '=', 'recognitions.employee_id')
            ->join('recognition_types', 'recognition_types.recognition_type_id', '=', 'recognitions.recognition_type_id')
            ->select('employees.*', 'recognitions.*', 'recognition_types.*') // Chọn các cột cần thiết
            ->paginate($perPage);
    }

    // Hàm này trả về đối tượng query builder cho bảng recognitions
    public static function getRecognitions()
    {
        return DB::table('recognitions')->get();
    }
}
