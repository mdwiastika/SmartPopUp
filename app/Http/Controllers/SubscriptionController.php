<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\SubscriptionCollection;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index()
    {
        try {
            $subscriptions = Subscription::query()->with('details')->orderBy('created_at', 'asc')->cursorPaginate(10);
            return new SubscriptionCollection(true, 'Subscriptions retrieved successfully', $subscriptions);
        } catch (\Throwable $th) {
            return new SubscriptionCollection(false, 'Failed to retrieve subscriptions', []);
        }
    }

    public function store(StoreSubscriptionRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $details = $validated['details'];
            unset($validated['details']);
            $subscription = Subscription::create($validated);

            foreach ($details as $feature) {
                $subscription->details()->create(['feature' => $feature['feature']]);
            }

            DB::commit();

            return new SubscriptionCollection(true, 'Subscription created successfully', $subscription->load('details'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return new SubscriptionCollection(false, $th->getMessage(), []);
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
            DB::beginTransaction();
            $validated = $request->validated();
            $details = $validated['details'] ?? null;
            unset($validated['details']);
            $subscription->update($validated);

            if (isset($details)) {
                $subscription->details()->delete();
                foreach ($details as $feature) {
                    $subscription->details()->create(['feature' => $feature['feature']]);
                }
            }

            DB::commit();

            return new SubscriptionCollection(true, 'Subscription updated successfully', $subscription->load('details'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return new SubscriptionCollection(false, 'Failed to update subscription', []);
        }
    }

    public function destroy(Subscription $subscription)
    {
        try {
            DB::beginTransaction();
            $subscription->details()->delete();
            $subscription->delete();

            DB::commit();
            return new SubscriptionCollection(true, 'Subscription deleted successfully', []);
        } catch (\Throwable $th) {
            DB::rollBack();
            return new SubscriptionCollection(false, 'Failed to delete subscription', []);
        }
    }
}
