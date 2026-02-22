<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Http\Resources\GradeCollection;
use App\Models\Grade;

class GradeController extends Controller
{
    public function index()
    {
        try {
            $grades = Grade::all();
            return new GradeCollection(true, 'Grades retrieved successfully', $grades);
        } catch (\Throwable $th) {
            return new GradeCollection(false, 'Failed to retrieve grades', []);
        }
    }

    public function store(StoreGradeRequest $request)
    {
        try {
            $validated = $request->validated();
            $grade = Grade::create($validated);
            return new GradeCollection(true, 'Grade created successfully', $grade);
        } catch (\Throwable $th) {
            return new GradeCollection(false, 'Failed to create grade', null);
        }
    }

    public function show(Grade $grade)
    {
        try {
            return new GradeCollection(true, 'Grade retrieved successfully', $grade);
        } catch (\Throwable $th) {
            return new GradeCollection(false, 'Failed to retrieve grade', null);
        }
    }

    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        try {
            $validated = $request->validated();
            $grade->update($validated);
            return new GradeCollection(true, 'Grade updated successfully', $grade);
        } catch (\Throwable $th) {
            return new GradeCollection(false, 'Failed to update grade', null);
        }
    }

    public function destroy(Grade $grade)
    {
        try {
            $grade->delete();
            return new GradeCollection(true, 'Grade deleted successfully', null);
        } catch (\Throwable $th) {
            return new GradeCollection(false, 'Failed to delete grade', null);
        }
    }
}
