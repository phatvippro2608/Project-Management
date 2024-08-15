<?php

namespace App\Models;

use App\Http\Controllers\EmployeesController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetailModel extends Model
{
    use HasFactory;

    protected $table = 'team_details';

    protected $fillable = [
        'project_id',
        'employee_id',
        // Các cột khác
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeesController::class, 'employee_id');
    }
}
