<?php

namespace App\Models;

use App\Traits\HocPhan;
use App\Traits\Phong;
use App\Traits\SinhVien;
use App\Traits\TaiKhoan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountModel extends Model
{

    protected $table = 'tb_account_employee';
    protected $primaryKey = 'id_account';
    public $timestamps = true;
    public $incrementing = true;

}
