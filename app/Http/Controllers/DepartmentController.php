<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function getView()
    {
        $departments = DepartmentModel::all();
        return view('auth.departments.department', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:255',
        ]);

        $department = DepartmentModel::create($validated);

        return response()->json([
            'success' => true,
            'department' => $department,
            'message' => 'Department added successfully',
        ]);
    }

    public function show($id)
    {
        $department = DepartmentModel::find($id);
        return view('departments.show', compact('department'));
    }

    public function edit($id)
    {
        $department = DepartmentModel::findOrFail($id);

        return response()->json([
            'department' => $department,
        ]);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department = DepartmentModel::findOrFail($id);
        $department->update($validated);

        return response()->json([
            'success' => true,
            'department' => $department,
        ]);
    }

    public function destroy($id)
    {
        $department = DepartmentModel::findOrFail($id);
        $department->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
