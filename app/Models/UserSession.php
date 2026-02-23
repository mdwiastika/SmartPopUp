<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'user_session_id', 'id');
    }
}
