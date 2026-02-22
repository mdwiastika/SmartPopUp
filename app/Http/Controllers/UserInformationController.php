<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserInformationRequest;
use App\Http\Requests\UpdateUserInformationRequest;
use App\Http\Resources\UserInformationCollection;
use App\Models\UserInformation;

class UserInformationController extends Controller
{
    public function index()
    {
        try {
            $userInformation = UserInformation::with(['user', 'grade', 'difficulty'])->get();
            return new UserInformationCollection(true, 'User information retrieved successfully', $userInformation);
        } catch (\Throwable $th) {
            return new UserInformationCollection(false, 'Failed to retrieve user information', []);
        }
    }

    public function store(StoreUserInformationRequest $request)
    {
        try {
            $validated = $request->validated();
            $userInformation = UserInformation::create($validated);
            return new UserInformationCollection(true, 'User information created successfully', $userInformation);
        } catch (\Throwable $th) {
            return new UserInformationCollection(false, 'Failed to create user information', []);
        }
    }

    public function show(UserInformation $userInformation)
    {
        try {
            $userInformation->load(['user', 'grade', 'difficulty']);
            return new UserInformationCollection(true, 'User information retrieved successfully', $userInformation);
        } catch (\Throwable $th) {
            return new UserInformationCollection(false, 'Failed to retrieve user information', []);
        }
    }

    public function update(UpdateUserInformationRequest $request, UserInformation $userInformation)
    {
        try {
            $validated = $request->validated();
            $userInformation->update($validated);
            return new UserInformationCollection(true, 'User information updated successfully', $userInformation);
        } catch (\Throwable $th) {
            return new UserInformationCollection(false, 'Failed to update user information', []);
        }
    }

    public function destroy(UserInformation $userInformation)
    {
        try {
            $userInformation->delete();
            return new UserInformationCollection(true, 'User information deleted successfully', $userInformation);
        } catch (\Throwable $th) {
            return new UserInformationCollection(false, 'Failed to delete user information', []);
        }
    }
}
