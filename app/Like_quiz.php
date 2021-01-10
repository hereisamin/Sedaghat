<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like_quiz extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function quiz(){
        return $this->belongsTo('App\Quiz');
    }
}
