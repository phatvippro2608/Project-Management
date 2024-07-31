<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTypesModel extends Model
{
    use HasFactory;
    protected $table = 'proposal_types';
    protected $fillable = ['name'];
    public $timestamps = false;
}
