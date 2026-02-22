<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(SubscriptionDetail::class);
    }
}
