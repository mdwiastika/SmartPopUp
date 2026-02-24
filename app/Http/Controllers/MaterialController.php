<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Http\Resources\MaterialCollection;
use App\Models\Material;

class MaterialController extends Controller
{
    public function index()
    {
        try {
            $materials = Material::all();
            return new MaterialCollection(true, 'Materials retrieved successfully', $materials);
        } catch (\Throwable $th) {
            return new MaterialCollection(false, 'Failed to retrieve materials', []);
        }
    }

    public function store(StoreMaterialRequest $request)
    {
        try {
            $validated = $request->validated();
            $material = Material::create($validated);

            return new MaterialCollection(true, 'Material created successfully', $material);
        } catch (\Throwable $th) {
            return new MaterialCollection(false, 'Failed to create material', []);
        }
    }

    public function show(Material $material)
    {
        try {
            return new MaterialCollection(true, 'Material retrieved successfully', $material);
        } catch (\Throwable $th) {
            return new MaterialCollection(false, 'Failed to retrieve material', []);
        }
    }

    public function update(UpdateMaterialRequest $request, Material $material)
    {
        try {
            $validated = $request->validated();
            $material->update($validated);

            return new MaterialCollection(true, 'Material updated successfully', $material);
        } catch (\Throwable $th) {
            return new MaterialCollection(false, 'Failed to update material', []);
        }
    }

    public function destroy(Material $material)
    {
        try {
            $material->delete();
            return new MaterialCollection(true, 'Material deleted successfully', []);
        } catch (\Throwable $th) {
            return new MaterialCollection(false, 'Failed to delete material', []);
        }
    }
}
