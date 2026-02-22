<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::query()->cursorPaginate(10);
            return new UserCollection(true, 'Users retrieved successfully', $users);
        } catch (\Throwable $th) {
            return new UserCollection(false, 'Failed to retrieve users', null);
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::create($validated);

            return new UserCollection(true, 'User created successfully', $user);
        } catch (\Throwable $th) {
            return new UserCollection(false, 'Failed to create user', null);
        }
    }

    public function show(User $user)
    {
        try {
            return new UserCollection(true, 'User retrieved successfully', $user);
        } catch (\Throwable $th) {
            return new UserCollection(false, 'Failed to retrieve user', null);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $validated = $request->validated();
            $user->update($validated);

            return new UserCollection(true, 'User updated successfully', $user);
        } catch (\Throwable $th) {
            return new UserCollection(false, 'Failed to update user', null);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return new UserCollection(true, 'User deleted successfully', null);
        } catch (\Throwable $th) {
            return new UserCollection(false, 'Failed to delete user', null);
        }
    }
}
