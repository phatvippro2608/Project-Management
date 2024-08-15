<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function getView()
    {
        $departments = DepartmentModel::all();
        return view('auth.departments.department-list', compact('departments'));
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
//        return view('departments.show', compact('department'));
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


    public function getEmployeeOfDepartment(Request $request, $department_id)
    {
        $department_name = DB::table('departments')->where('department_id', $department_id)->value('department_name');
        $data = DB::table('departments')
            ->join('employees', 'departments.department_id', '=', 'employees.department_id')
            ->where('departments.department_id', $department_id)->get();
//        dd($data);
        return view('auth.departments.department',[
            'department_name' => $department_name,
            'data' => $data,
        ]);
    }


}
