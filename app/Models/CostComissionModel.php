<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostComissionModel extends Model
{
    use HasFactory;
    protected $table = 'project_cost_commission';
    protected $primaryKey = 'commission_id';
    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'description',
        'amount',
    ];
}
