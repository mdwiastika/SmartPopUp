<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = ['id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class, 'difficulty_id', 'id');
    }
}
