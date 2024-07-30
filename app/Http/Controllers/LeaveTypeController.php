<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveTypeModel;

class LeaveTypeController extends Controller
{
    public function getView()
    {
        $leave_types = LeaveTypeModel::all();
        return view('auth.leave.leave-type', compact('leave_types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|string|max:255',
            'number_of_days' => 'required|int'
        ]);

        $leave_type = LeaveTypeModel::create($validated);

        return response()->json([
            'success' => true,
            "status" => 200,
            'leave_type' => $leave_type,
            'message' => 'Added successfully',
        ]);
    }

    public function show($id)
    {
        $leave_type = LeaveTypeModel::findOrFail($id);
        return view('leave-type.show', compact('leave_type'));
    }

    public function edit($id)
    {
        $leave_type = LeaveTypeModel::findOrFail($id);

        return response()->json([
            'leave_type' => $leave_type,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'leave_type' => 'required|string|max:255',
            'number_of_days' => 'required|int'
        ]);

        $leave_type = LeaveTypeModel::findOrFail($id);
        $leave_type->update($validated);

        return response()->json([
            'success' => true,
            'leave_type' => $leave_type,
        ]);
    }


    public function destroy($id)
    {
        $leave_type = LeaveTypeModel::findOrFail($id);
        $leave_type->delete();

        return response()->json([
            'success' => true,
            'message' => 'Holiday deleted successfully',
        ]);
    }
}
