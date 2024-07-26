<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectListModel extends Model
{
    use HasFactory;
    protected $table = 'projects';

    protected $primaryKey = 'project_id';


}
