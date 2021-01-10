<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Friend;
use App\Group;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($groupID){
        $isUserIn = User::where('id', Auth::id())->whereHas('groups', function (Builder $query) use ($groupID) {
            $query->where('id','=', $groupID);
        })->count();
        abort_unless($isUserIn, 403);
            $groupUsers = User::whereHas('groups', function (Builder $query) use ($groupID) {
            $query->where('id','=', $groupID);
        })->get();
        $usersID=[];
        foreach ($groupUsers as $groupUser){
            $usersID=Arr::add($usersID, $groupUser->id, $groupUser->id);
        }
        $challenges=[];
        foreach ($groupUsers as $user){
            foreach ($usersID as $id){
                $challenge=Challenge::where('user_id',$user->id)->where('user2_id',$id)->where('done',1)
                    ->orWhere('user_id',$id)->where('user2_id',$user->id)->where('done',1)->first();
                if ($challenge){
                    $firstUser=User::find($challenge->user_id);
                    $secondUser=User::find($challenge->user2_id);
                    $challenges=Arr::add($challenges, $challenge->id, ['firstUser'=>$firstUser->name, 'secondUser'=>$secondUser->name]);
                }
            }
        }
        $friends=Friend::where('user_id', Auth::id())->orWhere('friend_id', Auth::id())->get();
        $friendsList=[];
        foreach ($friends as $friend){
            if($friend->user_id==Auth::id()){
                $friendsList=Arr::add($friendsList, $friend->user2->id, [
                    'name'=>$friend->name,
                    'mobile'=>$friend->user2->mobile
                ]);
            }else{
                $friendsList=Arr::add($friendsList, $friend->user->id, [
                    'name'=>$friend->user->name,
                    'mobile'=>$friend->user->mobile
                ]);
            }
        }
        //dd($challenge);
        $selectedGroup=Group::find($groupID);
        return view('group', [
            'users'=>$groupUsers,
            'selectedGroup'=>$selectedGroup,
            'challenges'=>$challenges,
            'friends'=>$friendsList,
            ]);
    }

    public function addUser(Request $request){
        $groupId=$request->GP;
        $newUser=$request->code;
        $isUserIn = User::where('id', Auth::id())->whereHas('groups', function (Builder $query) use ($groupId) {
            $query->where('id','=', $groupId);
        })->count();
        abort_unless($isUserIn, 403);
        $isNewUserIn = User::where('id', $newUser)->whereHas('groups', function (Builder $query) use ($groupId) {
            $query->where('id','=', $groupId);
        })->count();
        $newUser=User::find($newUser);
        if($isNewUserIn){
            return response()->json(['exist'=>true, 'newUser'=>$newUser]);
        }
        $newUser->groups()->attach($groupId);
        return response()->json(['success'=>$newUser]);
    }
}
