<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplicationModel;
use App\Models\ProposalApplicationModel;
use App\Models\ProposalFileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalApplicationController extends Controller
{
    public function getView()
    {
        $account_id = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);

        $model_proposal = new ProposalApplicationModel();
        $data = $model_proposal->getListProposal();
        $proposal_types = $model_proposal->getProposalTypes();
        $employee_name = $model_proposal->getEmployeeName();
        // dd($employee_name);
        return view(
            'auth.proposal.proposal-application',
            compact('data', 'employee_name', 'proposal_types')
        );
    }
    public function add(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|int',
            'proposal_id' => 'required|int',
            'description' => 'nullable|string',
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
}
