<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDifficultyRequest;
use App\Http\Requests\UpdateDifficultyRequest;
use App\Http\Resources\DifficultyCollection;
use App\Models\Difficulty;

class DifficultyController extends Controller
{
    public function index()
    {
        try {
            $difficulties = Difficulty::all();
            return new DifficultyCollection(true, 'Difficulties retrieved successfully', $difficulties);
        } catch (\Throwable $th) {
            return new DifficultyCollection(false, 'Failed to retrieve difficulties', null);
        }
    }

    public function store(StoreDifficultyRequest $request)
    {
        try {
            $validated = $request->validated();
            $difficulty = Difficulty::create($validated);
            return new DifficultyCollection(true, 'Difficulty created successfully', $difficulty);
        } catch (\Throwable $th) {
            return new DifficultyCollection(false, 'Failed to create difficulty', null);
        }
    }

    public function show(Difficulty $difficulty)
    {
        try {
            return new DifficultyCollection(true, 'Difficulty retrieved successfully', $difficulty);
        } catch (\Throwable $th) {
            return new DifficultyCollection(false, 'Failed to retrieve difficulty', null);
        }
    }

    public function update(UpdateDifficultyRequest $request, Difficulty $difficulty)
    {
        try {
            $validated = $request->validated();
            $difficulty->update($validated);
            return new DifficultyCollection(true, 'Difficulty updated successfully', $difficulty);
        } catch (\Throwable $th) {
            return new DifficultyCollection(false, 'Failed to update difficulty', null);
        }
    }

    public function destroy(Difficulty $difficulty)
    {
        try {
            $difficulty->delete();
            return new DifficultyCollection(true, 'Difficulty deleted successfully', null);
        } catch (\Throwable $th) {
            return new DifficultyCollection(false, 'Failed to delete difficulty', null);
        }
    }
}
