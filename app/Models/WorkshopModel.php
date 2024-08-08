<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopModel extends Model
{
    use HasFactory;
    public $table = 'workshops';
    public $primaryKey = 'workshop_id';

    public $fillable = [
        'workshop_id',
        'workshop_name',
        'workshop_description',
        'workshop_image_url',
        'workshop_image'
    ];
}
