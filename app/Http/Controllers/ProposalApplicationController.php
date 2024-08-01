<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplicationModel;
use App\Models\ProposalApplicationModel;
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
        $employee_name = $model_leaveapp->getEmployeeName();
        //permis = 0 -> employee (uesr)
        //permis = 3 -> Direct department (trưởng phòng ban)
        //permis = 4 -> Direct Manager (Quản lý phòng ban)

//        dd($data);
        return view('auth.proposal.proposal-application',
            compact('data', 'employee_name', 'proposal_types'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|int',
            'proposal_id' => 'required|string',
            'description' => 'required|string',
        ]);

        $validated['progress'] = 0;
        ProposalApplicationModel::create($validated);

        return response()->json([
            'success' => true,
            "status" => 200,
            'message' => 'Proposal application added successfully',
        ]);
    }
}
