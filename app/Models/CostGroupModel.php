<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostGroupModel extends Model
{
    use HasFactory;
    protected $table = 'project_cost_group';
    protected $primaryKey = 'project_cost_group_id';
    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'project_cost_group_name'
    ];
}
