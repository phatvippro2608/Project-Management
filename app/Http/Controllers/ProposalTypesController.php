<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProposalTypesModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProposalTypesController extends Controller
{
    public function getView()
    {
        $model = ProposalTypesModel::all();
        return view('auth.proposal.proposal-types', compact('model'));
    }

    public function add(Request $request)
    {
        $validated =  $request->validate(['name' => 'required|string']);
        $proposal_type = ProposalTypesModel::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Proposal type added successfully',
            'proposal_type' => $proposal_type
        ]);
    }

    public function show($id)
    {
        $proposal_type = ProposalTypesModel::findOrFail($id);
        return response()->json([
            'proposal_type' => $proposal_type
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate((['name' => 'required|string']));
        $proposal_type = ProposalTypesModel::findOrFail($id);
        $proposal_type->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Proposal type updated successfully',
            'proposal_type' => $proposal_type
        ]);
    }

    public function destroy($id)
    {
        $proposal_type = ProposalTypesModel::findOrFail($id);
        $proposal_type->delete();
        return response()->json([
            'success' => true,
            'message' => 'Proposal type deleted successfully'
        ]);
    }

    public function exportExcel()
    {
        $inputFileName = public_path('excel-example/proposal-types-export.xlsx');

        $inputFileType = IOFactory::identify($inputFileName);

        $objReader = IOFactory::createReader($inputFileType);

        $excel = $objReader->load($inputFileName);

        $excel->setActiveSheetIndex(0);
        $excel->getDefaultStyle()->getFont()->setName('Times New Roman');

        $cell = $excel->getActiveSheet();
        $cell->setCellValue('A2', 'No');
        $cell->setCellValue('B2', 'Name');

        $stt = 1;
        $cell = $excel->getActiveSheet();

        $proposalTypes = ProposalTypesModel::all();
        $num_row = 3;

        foreach ($proposalTypes as $row) {
            $cell->setCellValue('A' . $num_row, $stt++);
            $cell->setCellValue('B' . $num_row, $row->name);

            $borderStyle = $cell->getStyle('A' . $num_row . ':B' . $num_row)->getBorders();
            $borderStyle->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $cell->getStyle('A' . $num_row . ':B' . $num_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $num_row++;
        }

        foreach (range('A', 'B') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $filename = "Proposal-Types-Report" . '.xlsx';
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');
    }
}
