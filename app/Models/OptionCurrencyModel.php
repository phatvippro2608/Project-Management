<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionCurrencyModel extends Model
{
    use HasFactory;
    protected $table = 'options_currency';

    // Khóa chính
    protected $primaryKey = 'currency_id';

    // Các trường có thể được gán giá trị hàng loạt
    protected $fillable = [
        'currency_currency',
        'currency_symbol',
        'currency_country'
    ];

    // Định nghĩa mối quan hệ với model Option
    public function options()
    {
        return $this->hasMany(OptionModel::class, 'currency_id', 'currency_id');
    }

}
