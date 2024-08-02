<?php

namespace App\Http\Controllers;

use App\Models\HolidaysModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidaysController extends Controller
{
    public function getView()
    {
        $holidays = HolidaysModel::all();
        return view('auth.leave.holidays', compact('holidays'));
    }

    public function create()
    {
        return view('holidays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'year' => 'required|string',
        ]);

        $start_date = Carbon::parse($validated['start_date']);
        $end_date = Carbon::parse($validated['end_date']);
        $days = $start_date->diffInDays($end_date) + 1;

        $validated['days'] = $days;

        $holiday = HolidaysModel::create($validated);

        return response()->json([
            'success' => true,
            "status" => 200,
            'holiday' => $holiday,
            'message' => 'Holiday added successfully',
        ]);
    }

    public function show($id)
    {
        $holiday = HolidaysModel::findOrFail($id);
        return view('holidays.show', compact('holiday'));
    }

    public function edit($id)
    {
        $holiday = HolidaysModel::findOrFail($id);

        return response()->json([
            'holiday' => $holiday,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'year' => 'required|string',
        ]);

        $start_date = \Carbon\Carbon::parse($validated['start_date']);
        $end_date = \Carbon\Carbon::parse($validated['end_date']);
        $days = $start_date->diffInDays($end_date) + 1;

        $validated['days'] = $days;

        $holiday = HolidaysModel::findOrFail($id);
        $holiday->update($validated);

        return response()->json([
            'success' => true,
            'holiday' => $holiday,
        ]);
    }


    public function destroy($id)
    {
        $holiday = HolidaysModel::findOrFail($id);
        $holiday->delete();

        return response()->json([
            'success' => true,
            'message' => 'Holiday deleted successfully',
        ]);
    }
}
