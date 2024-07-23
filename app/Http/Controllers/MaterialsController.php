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
        $vat_of_goods = $materials->sum(function($material){
            return $material->total_price * ($material->vat / 100);
        });

        $grand_total = $sub_total + $vat_of_goods;

        return view('auth.materials.materials-management', compact('materials','sub_total','vat_of_goods','grand_total'));
    }

    // Lưu vật tư
    public function store(Request $request)
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
            'total_price' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'delivery_time' => 'nullable|string|max:255',
            'warranty_time' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        MaterialsModel::create($validated);

        return redirect()->route('materials.index')->with('success', 'Thêm vật tư thành công');
    }

    // Hiển thị form cập nhật vật tư
    public function edit($id) {
        $material = MaterialsModel::findOrFail($id);
        return view('auth.materials.edit', compact('material'));
    }

    // Cập nhật vật tư
    public function update(Request $request, $id) {
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
            'total_price' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'delivery_time' => 'nullable|string|max:255',
            'warranty_time' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $materials = MaterialsModel::findOrFail($id);
        $materials->update($validated);

        return redirect()->route('materials.index')->with('success', 'Cập nhật vật tư thành công');
    }

    //Xóa vật tư
    public function destroy($id)
    {
        $materials = MaterialsModel::findOrFail($id);
        $materials->delete();

        return redirect()->route('materials.index')->with('success', 'Xóa vật tư thành công');
    }
}
