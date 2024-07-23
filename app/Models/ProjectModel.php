<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    use HasFactory;
    protected $table = 'projects';

    protected $primaryKey = 'project_id';

    static function getAll(){
        $sql = "SELECT * FROM account, employees WHERE account.id_employee = employees.id_employee ORDER BY id_account DESC";
        return DB::select($sql);
    }
}
