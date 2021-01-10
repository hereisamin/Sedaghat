<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class To_quiz extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'user2_id', 'group_id',];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function user2(){
        return $this->belongsTo('App\User', 'user2_id');
    }
    public function group(){
        return $this->belongsTo('App\Group');
    }
}
