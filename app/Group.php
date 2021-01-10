<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    public function challenges()
    {
        return $this->hasMany('App\Challenge');
    }
    public function to_quizzes()
    {
        return $this->hasMany('App\To_quiz');
    }
}
