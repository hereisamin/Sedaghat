<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    public function challenge()
    {
        return $this->belongsTo('App\Challenge');
    }
    public function question()
    {
        return $this->hasOne('App\Question', 'id', 'question_id');
    }
    public function like_quiz(){
        return $this->hasOne('App\Like_quiz');
    }
}
