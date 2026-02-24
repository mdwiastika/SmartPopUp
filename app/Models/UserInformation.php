<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class);
    }
}
