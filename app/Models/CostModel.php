<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostModel extends Model
{
    use HasFactory;
    protected $table = 'project_costs';
    protected $primaryKey = 'project_cost_id';
    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'project_cost_description',
        'project_cost_labor_qty',
        'project_cost_labor_unit',
        'project_cost_budget_qty',
        'project_budget_unit',
        'project_cost_labor_cost',
        'project_cost_misc_cost',
        'project_cost_perdiempay',
        'project_cost_remaks',
        'project_cost_group_id',
        'project_cost_datagroup',
        'project_cost_ot_budget'
    ];
}
