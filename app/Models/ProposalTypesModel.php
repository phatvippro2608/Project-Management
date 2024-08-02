<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTypesModel extends Model
{
    use HasFactory;
    protected $table = 'proposal_types';
    protected $primaryKey = 'proposal_type_id';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function proposalApplications()
    {
        return $this->hasMany(ProposalApplicationModel::class, 'proposal_id', 'proposal_type_id');
    }
}
