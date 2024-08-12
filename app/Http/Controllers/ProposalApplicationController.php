<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplicationModel;
use App\Models\ProposalApplicationModel;
use App\Models\ProposalFileModel;
use App\Models\ProposalTypesModel; // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProposalApplicationController extends Controller
{
    public function getView()
    {
        $account_id = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);

        $model_proposal = new ProposalApplicationModel();
//        $model_leaveapp = new LeaveApplicationModel();
        $data = $model_proposal->getListProposal();
        $proposal_types = $model_proposal->getProposalTypes();
        $employee_name = $model_proposal->getEmployeeName();
//        dd($data);
        return view(
            'auth.proposal.proposal-application',
            compact('data', 'employee_name', 'proposal_types')
        );
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|int',
            'proposal_id' => 'nullable|int',
            'proposal_description' => 'nullable|string',
            'files.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,ppt,pptx,txt|max:10000'

        ]);

        $validated['progress'] = 0;
        $proposalApplication = ProposalApplicationModel::create($validated);

        if ($request->hasFile('files')) {
            $folderName = $request->employee_id;

            $folderPath = public_path('proposal_files/' . $folderName);

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($folderPath, $fileName);

                ProposalFileModel::create([
                    'proposal_file_name' => $fileName,
                    'proposal_app_id' => $proposalApplication->proposal_application_id
                ]);
            }
        }

        return response()->json([
            'success' => true,
            "status" => 200,
            'message' => 'Proposal application added successfully',
        ]);
    }


    public function edit($id)
    {
        $proposalApp = ProposalApplicationModel::with('employee', 'proposalType', 'files')->findOrFail($id);
        $proposalTypes = ProposalTypesModel::all();

        return response()->json([
            'proposal_app' => $proposalApp,
            'proposal_types' => $proposalTypes
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|int',
            'proposal_id' => 'required|int',
            'proposal_description' => 'nullable|string',
            'files.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,ppt,pptx,txt|max:10000'
        ]);

        $proposalApp = ProposalApplicationModel::findOrFail($id);
        $proposalApp->update($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $employeeId = $proposalApp->employee_id;
                $filePath = 'proposal_files/' . $employeeId . '/' . $fileName;

                if (!file_exists(public_path('proposal_files/' . $employeeId))) {
                    mkdir(public_path('proposal_files/' . $employeeId), 0777, true);
                }

                $file->move(public_path('proposal_files/' . $employeeId), $fileName);

                ProposalFileModel::create([
                    'proposal_file_name' => $fileName,
                    'proposal_app_id' => $proposalApp->proposal_application_id
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Proposal application updated successfully',
            'proposal_app' => $proposalApp
        ]);
    }


    public function destroy($id)
    {
        $proposalApp = ProposalApplicationModel::findOrFail($id);

        $directoryPath = public_path('proposal_files/' . $proposalApp->employee_id);

        foreach ($proposalApp->files as $file) {
            $filePath = $directoryPath . '/' . $file->proposal_file_name;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $file->delete();
        }

        $proposalApp->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proposal application deleted successfully'
        ]);
    }

    public function removeFile($id)
    {
        $file = ProposalFileModel::findOrFail($id);

        $proposalApp = ProposalApplicationModel::findOrFail($file->proposal_app_id);
        $filePath = public_path('proposal_files/' . $proposalApp->employee_id . '/' . $file->proposal_file_name);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File removed successfully'
        ]);
    }

    public function approve($id,$permission)
    {
        if($permission == 9){
            $proposalApp = ProposalApplicationModel::findOrFail($id);
            $proposalApp->progress = 1;
            $proposalApp->save();
        }elseif($permission == 10){
            $proposalApp = ProposalApplicationModel::findOrFail($id);
            $proposalApp->progress = 2;
            $proposalApp->save();
        }


        return response()->json([
            'success' => true,
            'message' => 'Proposal application approved successfully'
        ]);
    }

    public function exportExcel(Request $request)
    {
        $permission = $request->query('permission');
        $employee_id = $request->query('employee_id');
        $inputFileName = public_path('excel-example/proposal_application-export.xlsx');

        $inputFileType = IOFactory::identify($inputFileName);

        $objReader = IOFactory::createReader($inputFileType);

        $excel = $objReader->load($inputFileName);

        $excel->setActiveSheetIndex(0);
        $excel->getDefaultStyle()->getFont()->setName('Times New Roman');

        $stt = 1;
        $cell = $excel->getActiveSheet();
        $list_proposal = [];
        if ($permission == 9){
            $department_id = DB::table('job_details')
                ->where('employee_id', $employee_id)
                ->pluck('department_id')
                ->first();

            $list_proposal = DB::table('proposal_applications')
                ->join('employees', 'employees.employee_id', '=', 'proposal_applications.employee_id')
                ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')
                ->join('proposal_types', 'proposal_applications.proposal_id', '=', 'proposal_types.proposal_type_id')
                ->join('departments', 'job_details.department_id', '=', 'departments.department_id')
                ->where('departments.department_id', $department_id)
                ->get();
        }else if ($permission == 10){
            $list_proposal = DB::table('proposal_applications')
                ->join('employees', 'employees.employee_id', '=', 'proposal_applications.employee_id')
                ->join('proposal_types', 'proposal_applications.proposal_id', '=', 'proposal_types.proposal_type_id')
                ->get();
        }
//        dd($list_proposal);


        $num_row = 3;
        foreach ($list_proposal as $row) {
            $cell->setCellValue('A' . $num_row, $stt++);
            $cell->setCellValue('B' . $num_row, $row->first_name . ' ' . $row->last_name);
            $cell->setCellValue('C' . $num_row, $row->name);
            $cell->setCellValue('D' . $num_row, $row->proposal_description);
            if($row->progress == '0'){
                $cell->setCellValue('E' . $num_row, 'Not approve');
                $cell->setCellValue('F' . $num_row, 'Not approve');
            }
            if($row->progress == '1'){
                $cell->setCellValue('E' . $num_row, 'Approve');
                $cell->setCellValue('F' . $num_row, 'Not approve');
            }
            if($row->progress == '2'){
                $cell->setCellValue('E' . $num_row, 'Approve');
                $cell->setCellValue('F' . $num_row, 'Approve');
            }




            $borderStyle = $cell->getStyle('A'.$num_row.':F' . $num_row)->getBorders();
            $borderStyle->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $cell->getStyle('A'.$num_row.':F' . $num_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $num_row++;
        }
        foreach (range('A', 'F') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $filename = "Proposal-Application-Report" . '.xlsx';
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');
    }
}
