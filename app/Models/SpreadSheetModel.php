<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SpreadSheetModel extends Model
{
    public static function readExcel($file)
    {
        $data = [];
        $row_limit = 0;
        $column_limit = 0;
        if (file_exists($file)) {
            $spreadsheet = IOFactory::load($file);
            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $data = $sheet->toArray();
        }
        return [
            'col' => $column_limit,
            'row' => $row_limit,
            'data' => $data
        ];
    }
}
