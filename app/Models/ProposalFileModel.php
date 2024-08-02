<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalFileModel extends Model
{
    use HasFactory;

    protected $table = 'proposal_files';

    protected $primaryKey = 'proposal_file_id';

    protected $fillable = [
        'proposal_file_name',
        'proposal_app_id',
    ];
    public $timestamps = false;

    public function proposalApplication()
    {
        return $this->belongsTo(ProposalApplicationModel::class, 'proposal_app_id', 'proposal_application_id');
    }
}
