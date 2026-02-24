<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionUser extends Model
{
    protected $guarded = ['id'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
