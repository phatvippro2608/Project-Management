<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\SpreadsheetModel;

class ProjectMaterialsController extends Controller
{
    public function getView(Request $request, $id)
    {
        $indexLocation = $request->input('location', '');
        $locationName = '';
        if (!empty($indexLocation)) {
            $materialData = DB::table('project_materials')
                ->join('project_locations', 'project_materials.project_location_id', '=', 'project_locations.project_location_id')
                ->join('projects', 'projects.project_id', '=', 'project_locations.project_id')
                ->select('project_materials.*', 'project_locations.*')
                ->where('projects.project_id', $id)->where('project_locations.project_location_id', $indexLocation)->get();
            $locationName = DB::table('project_locations')->select('project_locations.project_location_name')->where('project_locations.project_location_id', $indexLocation)->first();

        } else {
            $materialData = DB::table('project_materials')
                ->join('project_locations', 'project_materials.project_location_id', '=', 'project_locations.project_location_id')
                ->join('projects', 'projects.project_id', '=', 'project_locations.project_id')
                ->select('project_materials.*', 'project_locations.*', 'projects.project_name')
                ->where('projects.project_id', $id)->get();
        }
        $subtotal = [];
        $subvat = [];
        foreach ($materialData as $data) {
            $subtotal[] = $data->project_material_quantity * $data->project_material_unit_price;
            $subvat[] = $data->project_material_quantity * $data->project_material_unit_price * ($data->project_material_vat / 100);
        }
        $total = array_sum($subtotal);
        $vat = array_sum($subvat);
        $grandtotal = $total - $vat;
        return view('auth.projects.project-materials', [
            'materialData' => $materialData,
            'subtotal' => $subtotal,
            'total' => $total,
            'subvat' => $subvat,
            'vat' => $vat,
            'grandtotal' => $grandtotal,
            'indexLocation' => $indexLocation,
            'projectName' => DB::table('projects')->select('project_name')->where('project_id', $id)->first(),
            'locationName' => $locationName,
            'id' => $id
        ]);
    }
    public function add(Request $request)
    {
        $indexLocation = $request->input('indexLocation', '');
        $validator = Validator::make($request->all(), [
            'material_name' => 'required|string|max:255',
            'material_code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'unit' => 'nullable|integer',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|integer|min:0',
            'labor_price' => 'nullable|integer|min:0',
            'vat' => 'nullable|integer|min:0|max:100',
            'delivery_time' => 'nullable|string|max:255',
            'warranty_time' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ]);
        }

        $id = DB::table('project_materials')->insertGetId([
            'project_material_name' => $request->material_name,
            'project_material_remark' => $request->remarks,
            'project_material_code' => $request->material_code,
            'project_material_brand' => $request->brand,
            'project_material_origin' => $request->origin,
            'project_material_unit' => $request->unit,
            'project_material_quantity' => $request->quantity,
            'project_material_unit_price' => $request->unit_price,
            'project_material_labor_price' => $request->labor_price,
            'project_material_vat' => $request->vat,
            'project_material_delivery_time' => $request->delivery_time,
            'project_material_warranty_time' => $request->warranty_time,
            'project_material_description' => $request->description,
            'project_location_id' => $indexLocation,
            'date_create' => today(),
        ]);

        $newMaterial = DB::table('project_materials')->where('project_material_id', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Material added successfully!',
            'data' => $newMaterial,
        ]);
    }
    public function showDetails($project_id, $id)
    {
        $material = DB::table('project_materials')->select('project_materials.*')->where('project_material_id', $id)->first();

        if (!$material) {
            return response()->json(['success' => false, 'message' => 'Material not found']);
        }

        return response()->json(['success' => true, 'data' => $material]);
    }
    public function editMaterial($projectId, $materialId)
{
    try {
        $material = DB::table('project_materials')->where('project_material_id', $materialId)->first();
        return response()->json(['success' => true, 'data' => $material]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to retrieve material']);
    }
}

public function updateMaterial(Request $request, $id, $materialId)
{
    $validatedData = $request->validate([
        'material_name' => 'required|string',
        'material_code' => 'required|string',
        'description' => 'nullable|string',
        'brand' => 'nullable|string',
        'origin' => 'nullable|string',
        'unit' => 'nullable|integer',
        'quantity' => 'nullable|integer',
        'unit_price' => 'nullable|integer',
        'labor_price' => 'nullable|integer',
        'vat' => 'nullable|integer',
        'delivery_time' => 'nullable|string',
        'warranty_time' => 'nullable|string',
        'remarks' => 'nullable|string',
    ]);

    $material = DB::table('project_materials')->where('project_material_id', $materialId);

    if (!$material) {
        return response()->json(['success' => false, 'message' => 'Material not found.']);
    }

    $material->update([
        'project_material_name' => $request->input('material_name'),
        'project_material_code' => $request->input('material_code'),
        'project_material_description' => $request->input('description'),
        'project_material_brand' => $request->input('brand'),
        'project_material_origin' => $request->input('origin'),
        'project_material_unit' => $request->input('unit'),
        'project_material_quantity' => $request->input('quantity'),
        'project_material_unit_price' => $request->input('unit_price'),
        'project_material_labor_price' => $request->input('labor_price'),
        'project_material_vat' => $request->input('vat'),
        'project_material_delivery_time' => $request->input('delivery_time'),
        'project_material_warranty_time' => $request->input('warranty_time'),
        'project_material_remark' => $request->input('remarks'),
    ]);

    return response()->json(['success' => true, 'message' => 'Material updated successfully.', 'data' => $material]);
}


public function deleteMaterial($projectId, $materialId)
{
    try {
        DB::table('project_materials')->where('project_material_id', $materialId)->delete();
        return response()->json(['success' => true, 'message' => 'Material deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to delete material']);
    }
}
public function export(Request $request, $id) {
    $indexLocation = $request->input('indexLocation', '');
    if (!empty($indexLocation)) {
        $materialData = DB::table('project_materials')
            ->join('project_locations', 'project_materials.project_location_id', '=', 'project_locations.project_location_id')
            ->join('projects', 'projects.project_id', '=', 'project_locations.project_id')
            ->select('project_materials.*', 'project_locations.project_location_name')
            ->where('projects.project_id', $id)
            ->where('project_locations.project_location_id', $indexLocation)
            ->get();
        $locationName = DB::table('project_locations')
            ->select('project_location_name')
            ->where('project_location_id', $indexLocation)
            ->first();
    } else {
        $materialData = DB::table('project_materials')
            ->join('project_locations', 'project_materials.project_location_id', '=', 'project_locations.project_location_id')
            ->join('projects', 'projects.project_id', '=', 'project_locations.project_id')
            ->select('project_materials.*', 'project_locations.project_location_name')
            ->where('projects.project_id', $id)
            ->get();
    }

    $inputFileName = public_path('excel-example/project_material.xlsx');
    $inputFileType = IOFactory::identify($inputFileName);
    $objReader = IOFactory::createReader($inputFileType);
    $excel = $objReader->load($inputFileName);
    $excel->setActiveSheetIndex(0);
    $excel->getDefaultStyle()->getFont()->setName('Times New Roman');

    $stt = 1;
    $num_row = 2;  // bắt đầu từ dòng thứ 2 sau tiêu đề
    $cell = $excel->getActiveSheet();

    foreach ($materialData as $row) {
        $cell->setCellValue('A' . $num_row, $stt++);
        $cell->setCellValue('B' . $num_row, $row->project_material_name);
        $cell->setCellValue('C' . $num_row, $row->project_material_code);
        $cell->setCellValue('D' . $num_row, $row->project_material_description);
        $cell->setCellValue('E' . $num_row, $row->project_material_brand);
        $cell->setCellValue('F' . $num_row, $row->project_material_origin);
        $cell->setCellValue('G' . $num_row, $row->project_material_unit);
        $cell->setCellValue('H' . $num_row, $row->project_material_quantity);
        $cell->setCellValue('I' . $num_row, $row->project_material_unit_price);
        $cell->setCellValue('J' . $num_row, $row->project_material_labor_price);
        $cell->setCellValue('K' . $num_row, $row->project_material_vat);
        $cell->setCellValue('L' . $num_row, $row->project_material_delivery_time);
        $cell->setCellValue('M' . $num_row, $row->project_material_warranty_time);
        $cell->setCellValue('N' . $num_row, $row->project_location_name);
        $cell->setCellValue('O' . $num_row, $row->date_create);
        $cell->setCellValue('P' . $num_row, $row->project_material_remark);

        // Tạo border cho dòng
        $borderStyle = $cell->getStyle('A' . $num_row . ':P' . $num_row)->getBorders();
        $borderStyle->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $cell->getStyle('A' . $num_row . ':P' . $num_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $num_row++;
    }

    foreach (range('A', 'P') as $columnID) {
        $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $filename = "Materials_List_" . date('Ymd') . '.xlsx';
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($excel, 'Xlsx');
    $writer->save('php://output');
}
public function import(Request $request, $id) {
    try {
        // Read Excel data
        $dataExcel = SpreadSheetController::readExcel($request->file('file'));
        $location = $request->input('location', '');
        $project = DB::table('projects')->select('project_name')
                ->where('project_id', $id)
                ->first();

            $prjNameCurrent = $project->project_name;
            $prjName = $dataExcel['data'][1][1];
            $prjLocationName = $dataExcel['data'][2][1];
            $prjLocationId = DB::table('project_locations')->select('project_location_id')->where('project_id', $id)->where('project_location_name', $prjLocationName)->first();

            if ($prjNameCurrent != $prjName) {
                return response()->json([
                    'success' => false,
                    'message' => 'why did you import project ' . $prjNameCurrent . ' instead on project ' . $prjLocationName,
                ]);
            }
            $prjLocationId = $prjLocationId->project_location_id;

            // Bắt đầu từ dòng 5 trong file Excel
            for ($i = 4; $i < count($dataExcel['data']); $i++) {
                DB::table('project_materials')->insert([
                    'project_material_name' => $dataExcel['data'][$i][1],
                    'project_material_code' => $dataExcel['data'][$i][2],
                    'project_material_description' => $dataExcel['data'][$i][3],
                    'project_material_brand' => $dataExcel['data'][$i][4],
                    'project_material_origin' => $dataExcel['data'][$i][5],
                    'project_material_unit' => $dataExcel['data'][$i][6],
                    'project_material_quantity' => $dataExcel['data'][$i][7],
                    'project_material_unit_price' => $dataExcel['data'][$i][8],
                    'project_material_labor_price' => $dataExcel['data'][$i][9],
                    'project_material_vat' => $dataExcel['data'][$i][10],
                    'project_material_delivery_time' => $dataExcel['data'][$i][11],
                    'project_material_warranty_time' => $dataExcel['data'][$i][12],
                    'project_material_remark' => $dataExcel['data'][$i][13],
                    'project_location_id' => $prjLocationId,
                    'date_create' => today()
                ]);
            }

        return response()->json([
            'success' => true,
            'message' => 'Import successfully',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Import failed: ' . $e->getMessage(),
        ]);
    }
}




}
