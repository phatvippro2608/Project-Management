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
            'files.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,txt|max:2048'

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
            'files.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,txt|max:2048'
        ]);

        $proposalApp = ProposalApplicationModel::findOrFail($id);
        $proposalApp->update($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $file->storeAs('proposal_files/' . $proposalApp->employee_id, $fileName);

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

        foreach ($proposalApp->files as $file) {
            $filePath = public_path('proposal_files/' . $proposalApp->employee_id . '/' . $file->proposal_file_name);
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
}
