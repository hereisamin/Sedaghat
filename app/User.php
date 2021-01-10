<?php

namespace App;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile', 'password', 'code', 'registered_mobile',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'mobile_verified_at' => 'datetime',
        'sent_code_time'    => 'datetime',
    ];
    public function hasVerifiedMobile()
    {
        return ! is_null($this->mobile_verified_at);
    }
    public function challenge_names()
    {
        return $this->hasMany('App\Challenge_name');
    }
    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }
    public function challenges()
    {
        return $this->hasMany('App\Challenge');
    }

    public function challenges2()
    {
        return $this->hasMany('App\Challenge', 'user2_id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }
    public function friends(){
        return $this->hasMany('App\Friend');
    }
    public function friends2(){
        return $this->hasMany('App\Friend', 'friend_id');
    }
    public function to_quiz(){
        return $this->hasOne('App\To_quiz');
    }

    public function like_quizzes(){
        return $this->hasMany('App\Like_quiz');
    }

}
