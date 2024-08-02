<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplicationModel;
use App\Models\ProposalApplicationModel;
use App\Models\ProposalFileModel;
use App\Models\ProposalTypesModel; // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalApplicationController extends Controller
{
    public function getView()
    {
        $account_id = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);

        $model_proposal = new ProposalApplicationModel();
        $model_leaveapp = new LeaveApplicationModel();
        $data = $model_proposal->getListProposal();
        $proposal_types = $model_proposal->getProposalTypes();
        $employee_name = $model_proposal->getEmployeeName();
        // dd($employee_name);
        $employee_name = $model_leaveapp->getEmployeeName();
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
        if($permission == 3){
            $proposalApp = ProposalApplicationModel::findOrFail($id);
            $proposalApp->progress = 1;
            $proposalApp->save();
        }elseif($permission == 4){
            $proposalApp = ProposalApplicationModel::findOrFail($id);
            $proposalApp->progress = 2;
            $proposalApp->save();
        }


        return response()->json([
            'success' => true,
            'message' => 'Proposal application approved successfully'
        ]);
    }
}
