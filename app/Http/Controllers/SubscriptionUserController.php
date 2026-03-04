<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionUserRequest;
use App\Http\Requests\UpdateSubscriptionUserRequest;
use App\Http\Resources\SubscriptionUserCollection;
use App\Models\Subscription;
use App\Models\SubscriptionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubscriptionUserController extends Controller
{
    public function index()
    {
        try {
            $subscriptionUsers = SubscriptionUser::query()->where('user_id', Auth::id())->with(['subscription', 'user'])->cursorPaginate(10);
            return new SubscriptionUserCollection(true, 'Subscription users retrieved successfully', $subscriptionUsers);
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, 'Failed to retrieve subscription users', []);
        }
    }

    public function store(StoreSubscriptionUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $subscription = Subscription::query()->findOrFail($validated['subscription_id']);
            $validated['amount'] = $subscription->amount;
            $validated['discount'] = $subscription->discount;
            $validated['duration'] = $subscription->duration;
            $validated['status'] = 'SUCCESSFUL';
            $subscriptionUser = SubscriptionUser::create($validated);
            return new SubscriptionUserCollection(true, 'Subscription user created successfully', $subscriptionUser->load(['subscription', 'user']));
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, 'Failed to create subscription user', []);
        }
    }

    public function storeWithFlip(StoreSubscriptionUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $subscription = Subscription::query()->findOrFail($validated['subscription_id']);
            $validated['amount'] = $subscription->amount;
            $validated['discount'] = $subscription->discount;
            $validated['duration'] = $subscription->duration;

            $flipSecretKey = env('FLIP_SECRET_KEY');
            $flipUrl = 'https://bigflip.id/big_sandbox_api/v2/pwf/bill';
            $expiredDate = now()->addHour()->format('Y-m-d H:i');
            $payload = [
                'title' => $subscription->name,
                'type' => 'SINGLE',
                'amount' => (int) $validated['amount'],
                'sender_name' => Auth::user()->name,
                'sender_email' => Auth::user()->email,
                'expired_date' => $expiredDate,
                'step' => '2',
                "item_details" => [
                    [
                        "name" => $subscription->name,
                        "price" => (int) $validated['amount'],
                        "quantity" => 1
                    ]
                ]
            ];

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($flipSecretKey . ':'),
            ];

            $response = Http::withHeaders($headers)->post($flipUrl, $payload);
            if ($response->failed()) {
                throw new \Exception('Failed to create bill with Flip: ' . $response->body());
            }
            $responseData = $response->json();
            $validated['status'] = 'PENDING';
            $validated['flip_bill_id'] = $responseData['link_id'];
            $validated['flip_payment_url'] = $responseData['link_url'];


            $subscriptionUser = SubscriptionUser::create($validated);
            return new SubscriptionUserCollection(true, 'Subscription user created successfully', $subscriptionUser->load(['subscription', 'user']));
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, 'Failed to create subscription user', [
                'message' => $th->getMessage(),
                'expired_date' => $expiredDate ?? null,
            ]);
        }
    }

    public function handleFlipWebhook(Request $request)
    {
        try {
            Log::info('Flip Webhook Request', $request->all());
            $rawData = $request->input('data');
            $token = $request->input('token');

            if ($token !== env('FLIP_TOKEN_VALIDATION')) {
                return new SubscriptionUserCollection(false, 'Invalid token', []);
            }

            $data = json_decode($rawData, true);

            if ($data['status'] === 'SUCCESSFUL') {
                $subscriptionUser = SubscriptionUser::query()->where('flip_bill_id', $data['bill_link_id'])->first();
                if ($subscriptionUser) {
                    $subscriptionUser->update([
                        'status' => 'SUCCESSFUL',
                    ]);
                }
            }

            return new SubscriptionUserCollection(true, 'Webhook handled successfully', []);
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, 'Failed to handle webhook: ' . $th->getMessage(), []);
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
            $subscription = Subscription::query()->findOrFail($validated['subscription_id'] ?? $subscriptionUser->subscription_id);
            $validated['amount'] = $subscription->amount;
            $validated['discount'] = $subscription->discount;
            $validated['duration'] = $subscription->duration;
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
                ->whereRaw("created_at + (duration * INTERVAL '1 day') > NOW()")
                ->where('status', 'SUCCESSFUL')
                ->with('subscription')
                ->first();
            if ($subscriptionUser) {
                $subscriptionUser['remaining_days'] = $subscriptionUser ? max(0, ceil((strtotime($subscriptionUser->created_at) + ($subscriptionUser->duration * 24 * 60 * 60) - time()) / (24 * 60 * 60))) : 0;
                return new SubscriptionUserCollection(true, 'User has an active subscription', $subscriptionUser);
            } else {
                return new SubscriptionUserCollection(false, 'User does not have an active subscription', []);
            }
        } catch (\Throwable $th) {
            return new SubscriptionUserCollection(false, $th->getMessage(), []);
        }
    }
}
