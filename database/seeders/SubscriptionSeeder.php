<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\SubscriptionDetail;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptions = [
            [
                'name' => 'Bulanan',
                'amount' => 15000,
                'discount' => 0,
                'duration' => 30,
                'details' => [
                    'Akses ke semua fitur',
                    'Dukungan pelanggan 24/7',
                    'Pembaruan rutin',
                    'Akses 30 hari penuh'
                ],
            ],
            [
                'name' => 'Semesteran',
                'amount' => 75000,
                'discount' => 0,
                'duration' => 180,
                'details' => [
                    'Akses ke semua fitur',
                    'Dukungan pelanggan 24/7',
                    'Pembaruan rutin',
                    'Akses 180 hari penuh'
                ],
            ],
            [
                'name' => 'Tahunan',
                'amount' => 150000,
                'discount' => 20,
                'duration' => 365,
                'details' => [
                    'Akses ke semua fitur',
                    'Dukungan pelanggan 24/7',
                    'Pembaruan rutin',
                    'Akses 365 hari penuh'
                ],
            ],
        ];

        foreach ($subscriptions as $subscription) {
            $createdSubscription = Subscription::create([
                'name' => $subscription['name'],
                'amount' => $subscription['amount'],
                'discount' => $subscription['discount'],
                'duration' => $subscription['duration'],
            ]);

            foreach ($subscription['details'] as $feature) {
                SubscriptionDetail::create([
                    'subscription_id' => $createdSubscription->id,
                    'feature' => $feature,
                ]);
            }
        }
    }
}
