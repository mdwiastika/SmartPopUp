<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionUserRequest;
use App\Http\Requests\UpdateSubscriptionUserRequest;
use App\Http\Resources\SubscriptionUserCollection;
use App\Models\SubscriptionUser;
use Illuminate\Support\Facades\Auth;

class SubscriptionUserController extends Controller
{
    public function index()
    {
        try {
            $subscriptionUsers = SubscriptionUser::query()->with(['subscription', 'user'])->cursorPaginate(10);
            return new SubscriptionUserCollection(true, 'Subscription users retrieved successfully', $subscriptionUsers);
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, 'Failed to retrieve subscription users', []);
        }
    }

    public function store(StoreSubscriptionUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = Auth::id();
            $subscriptionUser = SubscriptionUser::create($validated);
            return new SubscriptionUserCollection(true, 'Subscription user created successfully', $subscriptionUser->load(['subscription', 'user']));
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, 'Failed to create subscription user', []);
        }
    }

    public function show(SubscriptionUser $subscriptionUser)
    {
        try {
            return new SubscriptionUserCollection(true, 'Subscription user retrieved successfully', $subscriptionUser->load(['subscription', 'user']));
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, 'Failed to retrieve subscription user', []);
        }
    }

    public function update(UpdateSubscriptionUserRequest $request, SubscriptionUser $subscriptionUser)
    {
        try {
            $validated = $request->validated();
            $subscriptionUser->update($validated);
            return new SubscriptionUserCollection(true, 'Subscription user updated successfully', $subscriptionUser->load(['subscription', 'user']));
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, 'Failed to update subscription user', []);
        }
    }

    public function destroy(SubscriptionUser $subscriptionUser)
    {
        try {
            $subscriptionUser->delete();
            return new SubscriptionUserCollection(true, 'Subscription user deleted successfully', []);
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, 'Failed to delete subscription user', []);
        }
    }

    public function checkUserSubscription()
    {
        try {
            $userId = Auth::id();
            $subscriptionUser = SubscriptionUser::query()->where('user_id', $userId)
                ->whereRaw('created_at + INTERVAL duration DAY > NOW()')
                ->with('subscription')
                ->first();

            if ($subscriptionUser) {
                return new SubscriptionUserCollection(true, 'User has an active subscription', $subscriptionUser);
            } else {
                return new SubscriptionUserCollection(false, 'User does not have an active subscription', []);
            }
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, 'Failed to check user subscription', []);
        }
    }
}
