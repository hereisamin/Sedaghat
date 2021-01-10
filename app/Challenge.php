<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function user2()
    {
        return $this->belongsTo('App\User', 'user2_id');
    }
    public function group()
    {
        return $this->belongsTo('App\Group');
    }
    public function quizzes()
    {
        return $this->hasMany('App\Quiz');
    }
}
