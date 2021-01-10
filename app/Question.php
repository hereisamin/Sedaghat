<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function challenge_name()
    {
        return $this->belongsTo('App\Challenge_name');
    }
    public function quiz()
    {
        return $this->belongsTo('App\Quiz');
    }
}
