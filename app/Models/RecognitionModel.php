<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecognitionModel extends Model
{
    use HasFactory;
    protected $table = 'recognitions';

    public function getRecognitions()
    {
        $perPage = intval(env('ITEM_PER_PAGE', 15)); // Mặc định là 15 nếu không tìm thấy giá trị trong file .env
        return DB::table('employees')
            ->join('recognitions', 'employees.employee_id', '=', 'recognitions.employee_id')
            ->join('recognition_types', 'recognition_types.recognition_type_id', '=', 'recognitions.recognition_type_id')
            ->select('employees.*', 'recognitions.*', 'recognition_types.*') // Chọn các cột cần thiết
            ->paginate($perPage);
    }

    public static function getRecognitionTypes()
    {
        return DB::table('recognition_types')->get();
    }

    protected $fillable = [
        'employee_id',
        'recognition_type_id',
        'description'
    ];
}
