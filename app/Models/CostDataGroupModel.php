<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostDataGroupModel extends Model
{
    use HasFactory;
    protected $table = 'project_cost_datagroup';
    protected $primaryKey = 'project_cost_datagroup_name';
    protected $fillable = [
        'project_cost_groupdata_name'
    ];
}
