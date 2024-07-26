<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcessModel;
use App\Models\MaterialsModel;

class MaterialsController extends Controller
{
    function getView()
    {
        $materials = MaterialsModel::all();

        $sub_total = $materials->sum('total_price');
        $vat_of_goods = $materials->sum(function ($material) {
            return $material->total_price * ($material->vat / 100);
        });

        $grand_total = $sub_total + $vat_of_goods;

        return view('auth.materials.materials-management', compact('materials', 'sub_total', 'vat_of_goods', 'grand_total'));
    }

    // Lưu vật tư
    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_code' => 'required|string',
            'material_name' => 'required|string',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'nullable|integer',
            'unit_price' => 'nullable|numeric',
            'labor_price' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'delivery_time' => 'nullable|string|max:255',
            'warranty_time' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $quantity = $validated['quantity'] ?? 0;
        $unit_price = $validated['unit_price'] ?? 0;
        $labor_price = $validated['labor_price'] ?? 0;

        $total_price = $quantity * $unit_price + $labor_price;

        $data = array_merge($validated, ['total_price' => $total_price]);


        $material = MaterialsModel::create($data);

        $materials = MaterialsModel::all();
        $sub_total = $materials->sum('total_price');
        $vat = $materials->sum(function ($material) {
            return $material->total_price * ($material->vat / 100);
        });
        $grand = $sub_total + $vat;

        return response()->json([
            'success' => true,
            'material' => $material,
            'message' => 'Create material successfully',
            'sub_total' => $sub_total,
            'vat_of_goods' => $vat,
            'grand_total' => $grand
        ]);
    }

    // Hiển thị form cập nhật vật tư
    public function edit($id)
    {
        $material = MaterialsModel::findOrFail($id);
        return view('auth.materials.edit', compact('material'));
    }

    // Cập nhật vật tư
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'material_code' => 'required|string|max:50',
            'material_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'nullable|integer',
            'unit_price' => 'nullable|numeric',
            'labor_price' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'delivery_time' => 'nullable|string|max:255',
            'warranty_time' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $quantity = $validated['quantity'] ?? 0;
        $unit_price = $validated['unit_price'] ?? 0;
        $labor_price = $validated['labor_price'] ?? 0;

        $total_price = $quantity * $unit_price + $labor_price;

        $data = array_merge($validated, ['total_price' => $total_price]);


        $materials = MaterialsModel::findOrFail($id);
        $materials->update($data);

        return redirect()->route('materials.index')->with('success', 'Update material successfully');
    }

    //Xóa vật tư
    public function destroy($id)
    {
        $materials = MaterialsModel::findOrFail($id);
        $materials->delete();

        $materials = MaterialsModel::all();
        $sub_total = $materials->sum('total_price');
        $vat = $materials->sum(function ($material) {
            return $material->total_price * ($material->vat / 100);
        });
        $grand = $sub_total + $vat;

        return response()->json([
            'success' => true,
            'message' => 'Delete material successfully',
            'sub_total' => $sub_total,
            'vat_of_goods' => $vat,
            'grand_total' => $grand
        ]);
    }

    public function show($id)
    {
        $material = MaterialsModel::findOrFail($id);
        return response()->json($material);
    }
}