<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProposalTypesModel;

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
}
