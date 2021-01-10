<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    public $timestamps = false;
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function user2(){
        return $this->belongsTo('App\User', 'friend_id');
    }
}
