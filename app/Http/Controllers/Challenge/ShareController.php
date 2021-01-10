<?php

namespace App\Http\Controllers\Challenge;

use App\Challenge;
use App\Http\Controllers\Controller;
use App\Like_quiz;
use App\Quiz;
use App\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function show($url){
        $shared=Share::where('url', $url)->first();
        $challengeId=$shared->challenge_id;

        //get result
        $total=0;
        $right1=0;
        $right2=0;
        $selectChallenge=Challenge::find($challengeId);
        $done=$selectChallenge->done;
        if (!$done && $selectChallenge->user_id != Auth::id()){return redirect('home');}//abort_if(! Auth::user()->isAdmin(), 403);abort_unless(Auth::user()->isAdmin(), 403);
        //if ($selectChallenge->done==1 && $selectChallenge->private_type ==1){ if($selectChallenge->user_id !=Auth::id() && $selectChallenge->user2_id != Auth::id()){return redirect('home');}}
        $user1=$selectChallenge->user;
        $user2=$selectChallenge->user2;
        $fname1= explode(' ',trim($user1->name))[0];
        $fname2= explode(' ',trim($user2->name))[0];
        if ($fname1==$fname2){
            if ($user1->name==$user2->name){
                $fname1=$fname1.' ۱ ';
                $fname2=$fname2.' ۲ ';
            }else{
                $fname1=$user1->name;
                $fname2=$user2->name;
            }
        }
//dd($done);
        $doneUser2=$selectChallenge->done_user2;
        $questions=Quiz::where('challenge_id', $selectChallenge->id)->get();
        $answers=[];
        $right_answers=[];
        foreach ($questions as $question) {
            $myLike1=false;
            $myLike2=false;
            $like1 = Like_quiz::where('user_id', Auth::id())->where('quiz_id', $question->id)->where('which', 1)->first();
            $like2 = Like_quiz::where('user_id', Auth::id())->where('quiz_id', $question->id)->where('which', 2)->first();
            if ($like1) {
                $myLike1 = true;
            }
            if ($like2) {
                $myLike2 = true;
            }
            $total++;
            $answers = Arr::add($answers, $question->id, ['myLike1' => $myLike1, 'myLike2' => $myLike2, 'question' => $question->question->question, 'answer1' => $question->answer1, 'likes1' => $question->likes1, 'answer2' => $question->answer2, 'likes2' => $question->likes2]);
            if ($doneUser2==1) {
                if ($question->answer1 != $question->answer2) {
                    if ($question->answer1 == 1) {
                        $right1++;
                        if ($right1 < 4) {
                            $right_answers = Arr::add($right_answers, $question->id, ['name' => $selectChallenge->user->name, 'question' => $question->question->question]);
                        }
                    } else {
                        $right2++;
                        if ($right2 < 4) {
                            $right_answers = Arr::add($right_answers, $question->id, ['name' => $selectChallenge->user2->name, 'question' => $question->question->question]);
                        }
                    }
                }
                $waiting=false;
            }else{
                $waiting=true;
            }
        }
        shuffle($right_answers);
        if($waiting){
            $percentage= 'در انتظار پاسخ';
        }else {
            $percentage = 100 * ($right1 + $right2) / $total;
            $percentage = $percentage.'% '.': تفاهم ';
        }
        return view('challenge.shared', [
            'info'=>[
                'user1'=>$user1,
                'user2'=>$user2,
                'done'=>$done,
                'fname1'=>$fname1,
                'fname2'=>$fname2,
                'challenge'=>$challengeId,
            ],
            'answers'=>$answers,
            'percentage'=>$percentage,
            'rights'=>$right_answers
        ]);
    }

    public function setUrl(Request $request)
    {
        if ($request->share) {
            $challengeId = $request->challenge;
            $shared=Share::where('challenge_id', $challengeId)->first();
            if ($shared) {
                return response()->json(['name' => route('share', $shared->url)]);
            }else{
                $challenge = Challenge::find($challengeId);
                $user1=$challenge->user;
                $user2=$challenge->user2;
                if ($user1->id==Auth::id()||$user2->id==Auth::id()) {
                    $url = $user1->id.$user2->id.random_int(100, 999).$challengeId;
                        $share= Share::create([
                                'challenge_id'=>$challengeId,
                                'like1'=>0,
                                'like2'=>0,
                                'url'=>$url,
                                ]);
                        if ($share){
                            return response()->json(['name' => route('share', $url)]);
                        }
                    }
                }
        }
    }
}
