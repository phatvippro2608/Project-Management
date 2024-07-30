<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionModel extends Model
{
    use HasFactory;
    // Tên bảng
    protected $table = 'options';

    // Khóa chính
    protected $primaryKey = 'option_id';

    // Các trường có thể được gán giá trị hàng loạt
    protected $fillable = [
        'option_img',
        'option_title',
        'option_description',
        'option_copyright',
        'option_contact',
        'currency_id',
        'option_email',
        'option_address'
    ];

    // Định nghĩa mối quan hệ với model OptionCurrency
    public function currency()
    {
        return $this->belongsTo(OptionCurrencyModel::class, 'currency_id', 'currency_id');
    }
}
