<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    use HasFactory;
    protected $table = 'projects';

    protected $primaryKey = 'project_id';

    protected $fillable = [
        'project_id',
        'project_name',
        'project_description',
        'project_status',
        'project_owner',
    ];
}
