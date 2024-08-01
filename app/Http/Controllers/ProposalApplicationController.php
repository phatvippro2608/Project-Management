<?php

namespace App\Http\Controllers;

use App\Models\ProposalApplicationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalApplicationController extends Controller
{
    public function getView()
    {
        $account_id = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);

        $model = new ProposalApplicationModel();
        $list_proposal = $model->getListProposal();
        //permis = 0 -> employee (uesr)
        //permis = 3 -> Direct department (trưởng phòng ban)
        //permis = 4 -> Direct Manager (Quản lý)

//        dd($list_proposal);
        return view('auth.proposal.proposal-application',
            compact('list_proposal'));
    }
}
