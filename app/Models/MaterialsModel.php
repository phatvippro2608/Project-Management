<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MaterialsModel extends Model
{
    use HasFactory;

    protected $table = 'materials';
    protected $primaryKey = 'material_id';

    protected $fillable = [
        'material_code',
        'material_name',
        'description',
        'brand',
        'origin',
        'unit',
        'quantity',
        'unit_price',
        'labor_price',
        'total_price',
        'vat',
        'delivery_time',
        'warranty_time',
        'remarks',
    ];

    public $timestamps = false;
}
