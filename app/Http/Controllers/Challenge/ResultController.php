<?php

namespace App\Http\Controllers\challenge;

use App\Challenge;
use App\Http\Controllers\Controller;
use App\Like_quiz;
use App\Question_like;
use App\Quiz;
use App\Qusetion_like;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use function Composer\Autoload\includeFile;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($challengeId){
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
        return view('challenge.result', [
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

    public function likes(Request $request){
        if ($request->section=='like1'){
            //return response()->json(['section'=>$request->section, 'code'=>$request->code, 'liked'=>true]);
            $quizId=$request->code;
            $liked=Like_quiz::where('user_id', Auth::id())->where('quiz_id', $quizId)->where('which', 1)->count();
            if(!$liked){
                Like_quiz::create([
                    'user_id'=>Auth::id(),
                    'quiz_id'=>$quizId,
                    'which'=>1,
                ]);
                Quiz::where('id', $quizId)->increment('likes1');
                return response()->json(['liked'=>true]);
            }
        }
        if ($request->section=='unlike1'){
            //return response()->json(['section'=>$request->section, 'code'=>$request->code, 'liked'=>true]);
            $quizId=$request->code;
            $unliked=Like_quiz::where('user_id', Auth::id())->where('quiz_id', $quizId)->where('which', 1)->delete();
            if($unliked){
                Quiz::where('id', $quizId)->decrement('likes1');
                return response()->json(['liked'=>true]);
            }
        }
        if ($request->section=='like2'){
            //return response()->json(['section'=>$request->section, 'code'=>$request->code, 'liked'=>true]);
            $quizId=$request->code;
            $liked=Like_quiz::where('user_id', Auth::id())->where('quiz_id', $quizId)->where('which', 2)->count();
            if(!$liked){
                Like_quiz::create([
                    'user_id'=>Auth::id(),
                    'quiz_id'=>$quizId,
                    'which'=>2,
                ]);
                Quiz::where('id', $quizId)->increment('likes2');
                return response()->json(['liked'=>true]);
            }
        }
        if ($request->section=='unlike2'){
            //return response()->json(['section'=>$request->section, 'code'=>$request->code, 'liked'=>true]);
            $quizId=$request->code;
            $unliked=Like_quiz::where('user_id', Auth::id())->where('quiz_id', $quizId)->where('which', 2)->delete();
            if($unliked){
                Quiz::where('id', $quizId)->decrement('likes2');
                return response()->json(['liked'=>true]);
            }
        }

    }
}
