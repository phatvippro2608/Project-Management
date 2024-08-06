<?php

namespace App\Http\Controllers;

use App\Models\HolidaysModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class HolidaysController extends Controller
{
    public function getView()
    {
        $holidays = HolidaysModel::all();
        return view('auth.leave.holidays', compact('holidays'));
    }

    public function create()
    {
        return view('holidays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'year' => 'required|string',
        ]);

        $start_date = Carbon::parse($validated['start_date']);
        $end_date = Carbon::parse($validated['end_date']);
        $days = $start_date->diffInDays($end_date) + 1;

        $validated['days'] = $days;

        $holiday = HolidaysModel::create($validated);

        return response()->json([
            'success' => true,
            "status" => 200,
            'holiday' => $holiday,
            'message' => 'Holiday added successfully',
        ]);
    }

    public function show($id)
    {
        $holiday = HolidaysModel::findOrFail($id);
        return view('holidays.show', compact('holiday'));
    }

    public function edit($id)
    {
        $holiday = HolidaysModel::findOrFail($id);

        return response()->json([
            'holiday' => $holiday,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'year' => 'required|string',
        ]);

        $start_date = \Carbon\Carbon::parse($validated['start_date']);
        $end_date = \Carbon\Carbon::parse($validated['end_date']);
        $days = $start_date->diffInDays($end_date) + 1;

        $validated['days'] = $days;

        $holiday = HolidaysModel::findOrFail($id);
        $holiday->update($validated);

        return response()->json([
            'success' => true,
            'holiday' => $holiday,
        ]);
    }


    public function destroy($id)
    {
        $holiday = HolidaysModel::findOrFail($id);
        $holiday->delete();

        return response()->json([
            'success' => true,
            'message' => 'Holiday deleted successfully',
        ]);
    }

    public function exportExcel()
    {

        $inputFileName = public_path('excel-example/holiday-export.xlsx');

        $inputFileType = IOFactory::identify($inputFileName);

        $objReader = IOFactory::createReader($inputFileType);

        $excel = $objReader->load($inputFileName);

        $excel->setActiveSheetIndex(0);
        $excel->getDefaultStyle()->getFont()->setName('Times New Roman');

        $stt = 1;
        $cell = $excel->getActiveSheet();
        $holidays = HolidaysModel::all();


        $num_row = 3;
        foreach ($holidays as $row) {
            $cell->setCellValue('A' . $num_row, $stt++);
            $cell->setCellValue('B' . $num_row, $row->name);
            $cell->setCellValue('C' . $num_row, $row->start_date);
            $cell->setCellValue('D' . $num_row, $row->end_date);
            $cell->setCellValue('E' . $num_row, $row->days);
            $cell->setCellValue('F' . $num_row, $row->year);
            $borderStyle = $cell->getStyle('A'.$num_row.':F' . $num_row)->getBorders();
            $borderStyle->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $cell->getStyle('A'.$num_row.':F' . $num_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $num_row++;
        }
        foreach (range('A', 'F') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $filename = "Holiday-export" . '.xlsx';
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');
    }
}
