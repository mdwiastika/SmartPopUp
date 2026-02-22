<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\SubscriptionCollection;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        try {
            $subscriptions = Subscription::query()->with('details')->cursorPaginate(10);
            return new SubscriptionCollection(true, 'Subscriptions retrieved successfully', $subscriptions);
        } catch (\Throwable $th) {
            return new SubscriptionCollection(false, 'Failed to retrieve subscriptions', []);
        }
    }

    public function store(StoreSubscriptionRequest $request)
    {
        try {
            $validated = $request->validated();
            $subscription = Subscription::create($validated);

            foreach ($validated['details'] as $feature) {
                $subscription->details()->create(['feature' => $feature]);
            }

            return new SubscriptionCollection(true, 'Subscription created successfully', $subscription->load('details'));
        } catch (\Throwable $th) {
            return new SubscriptionCollection(false, 'Failed to create subscription', []);
        }
    }

    public function show(Subscription $subscription)
    {
        try {
            return new SubscriptionCollection(true, 'Subscription retrieved successfully', $subscription->load('details'));
        } catch (\Throwable $th) {
            return new SubscriptionCollection(false, 'Failed to retrieve subscription', []);
        }
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        try {
            $validated = $request->validated();
            $subscription->update($validated);

            if (isset($validated['details'])) {
                $subscription->details()->delete();
                foreach ($validated['details'] as $feature) {
                    $subscription->details()->create(['feature' => $feature]);
                }
            }

            return new SubscriptionCollection(true, 'Subscription updated successfully', $subscription->load('details'));
        } catch (\Throwable $th) {
            return new SubscriptionCollection(false, 'Failed to update subscription', []);
        }
    }

    public function destroy(Subscription $subscription)
    {
        try {
            $subscription->delete();
            return new SubscriptionCollection(true, 'Subscription deleted successfully', []);
        } catch (\Throwable $th) {
            return new SubscriptionCollection(false, 'Failed to delete subscription', []);
        }
    }
}
