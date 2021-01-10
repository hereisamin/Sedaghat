<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Friend;
use App\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $started_challenges=[];
        $done_started_challenges=[];
        $invited_challenges=[];
        $done_invited_challenges=[];
        $challenges=Challenge::where('user_id', Auth::id())->orWhere('user2_id', Auth::id())->get();
        foreach ($challenges as $challenge){
            if($challenge->user_id==Auth::id()) {
                if ($challenge->done_user1 && !$challenge->done) {
                    $started_challenges = Arr::add($started_challenges, $challenge->id, ['id'=>$challenge->user2->id, 'name'=>$challenge->user2->name, 'likes'=>$challenge->likes]);
                }
                if ($challenge->done) {
                    $done_started_challenges=Arr::add($done_started_challenges, $challenge->id, ['id'=>$challenge->user2->id, 'name'=>$challenge->user2->name, 'likes'=>$challenge->likes]);
                }
            }
            if($challenge->user2_id==Auth::id()) {
                if ($challenge->done_user1 && !$challenge->done) {
                    $invited_challenges = Arr::add($invited_challenges, $challenge->id, ['id'=>$challenge->user->id, 'name'=>$challenge->user->name, 'likes'=>$challenge->likes]);
                }
                if ($challenge->done) {
                    $done_invited_challenges = Arr::add($done_invited_challenges, $challenge->id, ['id'=>$challenge->user->id, 'name'=>$challenge->user->name, 'likes'=>$challenge->likes]);
                }
            }
        }
        $friends=Friend::where('user_id', Auth::id())->orWhere('friend_id', Auth::id())->get();
        $friendsList=[];
        foreach ($friends as $friend){
            if($friend->user_id==Auth::id()){
                $friendsList=Arr::add($friendsList, $friend->id, [
                    'name'=>$friend->name,
                    'mobile'=>$friend->user2->mobile
                ]);
            }else{
                $friendsList=Arr::add($friendsList, $friend->id, [
                    'name'=>$friend->user->name,
                    'mobile'=>$friend->user->mobile
                ]);
            }
        }
        $groups = Group::whereHas('users', function (Builder $query) {
            $query->where('id','=', Auth::id());
        })->get();
        $challenges_to_send=[
            'started_challenges'=>$started_challenges,
            'invited_challenges'=>$invited_challenges,
            'done_invited_challenges'=>$done_invited_challenges,
            'done_started_challenges'=>$done_started_challenges,
            'friends_list'=>$friendsList,
            'groups'=>$groups,
            ];




        return view('home',$challenges_to_send);
    }
}
/*$friendsLists=Friend::where('user_id', Auth::id())->orWhere('friend_id', Auth::id())->get();
        foreach ($friendsLists as $friendsList){
            $friends=[$friendsList->id=>$friendsList->name];
        }*/
