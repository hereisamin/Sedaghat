<?php

namespace App\Http\Controllers\Challenge;

use App\Challenge;
use App\Group;
use App\Http\Controllers\Controller;
use App\Question;
use App\Quiz;
use App\To_quiz;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\User;
use App\Friend;
use Ipecompany\Smsirlaravel\Smsirlaravel;


class ChallengeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function invite(){
        return view('challenge.getUser');
    }

    public function getUser(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:45|min:3',
            'mobile' => 'required|string|regex:/^[0]{1}[9]{1}\d{9}$/',
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors(), 'status'=>true]);
        }
        if($request->mobile==Auth::user()->mobile){
            return response()->json(['errors'=>['mobile'=>'نمیتونی با خودت چالش کنی که! شــــیریـــن٬ با نمک ;)'], 'status'=>true]);
        }
        if($request->group){
            $groupExist=Group::find($request->group);
            if($groupExist){$groupID=$request->group;}else{$groupID=null;}
        }else{$groupID=null;}
        $friend=User::where('registered_mobile', $request->mobile)->first();
        if($friend){ // user exist
            if($groupID){
                $groups = Group::where('id', $groupID)->whereHas('users', function (Builder $query) use ($friend) {
                    $query->where('id','=', $friend->id);
                })->count();
                if (!$groups){
                    $friend->groups()->attach($groupID);
                }
            }
            $friendship=Friend::where('friend_id', $friend->id)->where('user_id', Auth::id())->orWhere('user_id', $friend->id)->where('friend_id', Auth::id())->first();
            if($friendship){//they are old friends
                $doneChallenge=Challenge::where('user_id', $friend->id)->where('user2_id', Auth::id())->orWhere('user2_id', $friend->id)->where('user_id', Auth::id())->first();
                if($doneChallenge){//they did challenge
                    if($doneChallenge->done){return response()->json(['quiz'=>true]);}//send error
                    return response()->json(['quiz'=>true, 'code'=>$doneChallenge->id]);
                }else{//old friends but not challenged do challenge
                    $toQuiz=To_quiz::updateOrCreate(
                        ['user_id' => Auth::id()],
                        [   'user2_id' => $friend->id,
                            'private_type' => 1,    ]);
                    if($toQuiz){ return response()->json(['StartQuiz'=>true]); }else{ return back(); }
                }
            }else{//user exist but they are not firend yet
                $newfriend = new Friend;
                $newfriend->name = $request->name;
                $newfriend->friend_id = $friend->id;
                $newfriend->user_id=Auth::id();
                //do challenge
                if($newfriend->save()){
                    $toQuiz=To_quiz::updateOrCreate(
                        ['user_id' => Auth::id()],
                        [   'user2_id' => $friend->id,
                            'private_type' => 1,    ]);
                    if($toQuiz){ return response()->json(['StartQuiz'=>true]); }else{ return back(); }
                }else{ return back(); }
            }
        }else{//user does not exist ADD USER and ...
            $newUser=User::updateOrCreate(
                ['mobile' => $request->mobile],
                [
                    'name' => $request->name,
                    'password' => $request->mobile,
                ]
            );
            if ($newUser) {
                if($groupID){
                    $newUser->groups()->attach($groupID);
                }
                $newfriend = new Friend;
                $newfriend->name = $request->name;
                $newfriend->friend_id = $newUser->id;
                $newfriend->user_id = Auth::id();
                //-------------------------------------- to done send sms
                if($newfriend->save()){
                    $toQuiz=To_quiz::updateOrCreate(
                        ['user_id' => Auth::id()],
                        [   'user2_id' => $newUser->id,
                            'private_type' => 1,    ]);
                    if($toQuiz){ return response()->json(['StartQuiz'=>true]); }else{ return back(); }
                }else{ return back(); }
            }else{ return back(); }
        }
    }

    public function quizStart(){
        $questionNum=Question::count();
        $user_2 = To_quiz::where('user_id', Auth::id())
            ->leftJoin('users', 'to_quizzes.user2_id', '=', 'users.id')
            ->select('users.name','users.id')->first();
        if (!$user_2){return redirect()->route('challenge.invite');}
        $user2=Friend::where('user_id', Auth::id())->where('friend_id', $user_2->id)->select('friends.name')->first();
        return view('challenge.startQuiz', ['name2'=>$user2->name, 'qNum'=>$questionNum]);
    }

    public function quizRun(Request $request){
        if($request->start){
            $user2 = To_quiz::where('user_id', Auth::id())->first();
            if(!$user2){return back();}
            $isExist= Challenge::where('user_id', $user2->user_id)->where('user2_id', $user2->user2_id)->where('private_type', 1)->first();
            if ($isExist){$challengeId=$isExist->id;}else {
                $challenge = new Challenge;
                $challenge->user_id = $user2->user_id;
                $challenge->user2_id = $user2->user2_id;
                $challenge->private_type = 1;
                $challenge->save();
                $challengeId=$challenge->id;
            }

            $questions = Question::where('challenge_name_id', '1')->get();
            foreach ($questions as $question){
                $isExist= Quiz::where('challenge_id', $challengeId)->where('question_id', $question->id)->first();
                if(!$isExist) {
                    $quiz = new Quiz;
                    $quiz->challenge_id = $challengeId;
                    $quiz->question_id = $question->id;
                    $quiz->save();
                }
            }
            $thisQuestion=Quiz::where('challenge_id', $challengeId)->where('answer1', null)
                ->leftJoin('questions', 'quizzes.question_id', '=', 'questions.id')
                ->select('questions.question', 'quizzes.id')->first();
            if($thisQuestion) {
                return response()->json(['question' => $thisQuestion->question, 'qNum' => $thisQuestion->id]);
            }else{
                return response()->json(['error' => 'جوابهای این چالش پاسخ داده شده است']);
            }
        }

        if ($request->Answer){
            $answer=intval($request->aNs);
            $quizId= intval($request->qNum);
            //$challengeId=Quiz::select('challenge_id')->where('id', $quizId)->first();
            //$sec=Challenge::where('id', $challengeId->challenge_id)->where('user_id', Auth::id())->first();
            //if(!$sec){return response()->json(['Error'=>'Secur/sec']);}
            //$upAns=Quiz::where('id', $quizId)->update(['answer1' => $answer]);

            $upAns=Quiz::find($quizId);
            $upAns->answer1=$answer;
            //dd($upAns->challenge_id);
            //$upAns=Quiz::where('id', $quizId)->update(['answer2' => $answer]);
            if ($upAns->save()){
                $thisQuestion=Quiz::where('challenge_id', $upAns->challenge_id)->where('answer1', null)->first();

//                $thisQuestion=Quiz::where('challenge_id', $challengeId->challenge_id)->where('answer1', null)
//                    ->leftJoin('questions', 'quizzes.question_id', '=', 'questions.id')
//                    ->select('questions.question', 'quizzes.id')->first();
                if($thisQuestion){ return response()->json(['question'=>$thisQuestion->question->question, 'qNum'=>$thisQuestion->id]);}else{
                    //done update table
                    $upChallenge=Challenge::find($upAns->challenge_id);
                    $upChallenge->done_user1 = 1;
                    if ($upChallenge->save()) {
                        //done get num and send sms
                        $user2 = User::find($upChallenge->user2_id);
                        $salam = "سلام\n";
                        $bashomare = ' با شماره';
                        $shomaradavatkarde = " درباره شما در چالش کیو کیو نظر داده.\n";
                        $barayePasokh = "برای پاسخ به سایت QQ3  آی آر بروید.";
                        $sms = $salam . Auth::user()->name."\n". $bashomare . Auth::user()->mobile ."\n". $shomaradavatkarde . $barayePasokh;
                        Smsirlaravel::sendTheSms($user2->mobile, $sms);
                        return response()->json(['question' => 'Done']);
                    }
                }return response()->json(['Error'=>'error done']);
            }return response()->json(['Error'=>'error update']);
        }else return back();
    }

    public function answerLoad($challengeId){
        $challenge = Challenge::where('id', $challengeId)->first();
        $questionNum=Question::count();
        if ($challenge->user2_id != Auth::id()) {
            return redirect('home');
        }
        if($challenge->done==1){
            $msg='شما قبلا با '.$challenge->user->name.' این چالش را انجام داده‌اید میتوانید در چالشهای تمام شده آن را ببینی.';
            return redirect()->route('home')->with('status', $msg);
        }
        return view('challenge.answerQuiz', ['name2'=>$challenge->user->name, 'id'=>$challengeId, 'qNum'=>$questionNum]);
    }


    public function answerStart(Request $request){
        if($request->start){
            $challengeId=$request->code;
            $challenge = Challenge::where('id', $challengeId)->first();
            if ($challenge->user2_id != Auth::id()) {
                return redirect('home');
            }

            $thisQuestion=Quiz::where('challenge_id', $challengeId)->where('answer2', null)->first();

            if($thisQuestion) {
                return response()->json(['question' => $thisQuestion->question->question, 'qNum' => $thisQuestion->id]);
            }else{
                return response()->json(['error' => 'جوابهای این چالش پاسخ داده شده است']);
            }
        }

        if ($request->Answer){
            $answer=intval($request->aNs);
            $quizId= intval($request->qNum);
            //$sec=Challenge::where('id', $challengeId->challenge_id)->where('user2_id', Auth::id())->first();
            //if(!$sec){return response()->json(['Error'=>'Secur/sec']);}
            $upAns=Quiz::find($quizId);
            $upAns->answer2=$answer;

            //dd($upAns->challenge_id);
            //$upAns=Quiz::where('id', $quizId)->update(['answer2' => $answer]);
            if ($upAns->save()){
                //$challengeId=Quiz::select('challenge_id')->where('id', $quizId)->first();
                $thisQuestion=Quiz::where('challenge_id', $upAns->challenge_id)->where('answer2', null)->first();
                if($thisQuestion){ return response()->json(['question' => $thisQuestion->question->question, 'qNum' => $thisQuestion->id]);
                }else{
                    //done update table
                    $upChallenge=Challenge::where('id', $upAns->challenge_id)->first();
                    $upChallenge->done_user2 = 1;
                    $upChallenge->done=1;
                    if ($upChallenge->save()) {
                        $user_2 = User::find($upChallenge->user_id);
                        $salam = "سلام\n";
                        $bashomare = ' با شماره ';
                        $shomaradavatkarde = " در چالش کیو کیو جواب شما را داده.\n";
                        $barayePasokh = "برای دیدن جواب به سایت QQ3  آی آر بروید.";
                        $sms = $salam . Auth::user()->name ."\n". $bashomare . Auth::user()->mobile ."\n". $shomaradavatkarde . $barayePasokh;
                        Smsirlaravel::sendTheSms($user_2->mobile, $sms);


                        return response()->json(['question' => 'Done']);
                    }
                }return response()->json(['Error'=>'error done']);
            }return response()->json(['Error'=>'error update']);
        }else return back();


    }

    public function prepGroup(){
        $groups = Group::whereHas('users', function (Builder $query) {
            $query->where('id','=', Auth::id());
        })->get();
        return view('challenge.prepGroup', [
            'groups'=>$groups,
        ]);
    }

    public function createGroup(Request $request){
        if ($request->createGroup){
            $name=trim($request->name);
            $des=trim($request->des);
            if($name){
                $group = new Group();
                $group->name=$name;
                $group->info=$des;
                $group->user_id=Auth::id();
                if ($group->save()){
                    $group->users()->attach(Auth::id());
                    return response()->json(['success' => true, 'GP'=>$group->id, 'groupName'=>$group->name]);
                }
                //return response()->json(['name' => $request->name.' Trimed CT']);
            }
        }

        if ($request->selectGroup){
            $groupId=$request->group;
            $groupUsers= User::whereHas('groups', function (Builder $query) use ($groupId) {
                $query->where('id','=', $groupId);
            })->get();
            $sendingGroupUsers=[];
            foreach ($groupUsers as $groupUser){
                $disable='';
                if ($groupUser->id==Auth::id()){$disable='disabled';}
                $didChalleneg=Challenge::where('user_id',Auth::id())->where('user2_id', $groupUser->id)->where('done_user1', 1)
                    ->orWhere('user2_id',Auth::id())->where('user_id', $groupUser->id)->where('done', 1)->first();
                if ($didChalleneg){
                    $disable='disabled';
                }
                $sendingGroupUsers=Arr::add($sendingGroupUsers, $groupUser->id,['id'=>$groupUser->id, 'name'=>$groupUser->name, 'mobile'=>$groupUser->mobile, 'disabled'=>$disable]);

            }


            $thisGroup=Group::find($groupId);
            return response()->json(['name' => $sendingGroupUsers, 'group'=>$thisGroup]);
        }

        if($request->validation){
            if ($request->selectUser){
                $userId=$request->user;
                $user=User::find($userId);
                if($user){
                    $userName=$user->name;
                    $userMobile=$user->mobile;
                }
            }else{
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:45|min:3',
                    'mobile' => 'required|string|regex:/^[0]{1}[9]{1}\d{9}$/',
                ]);
                if ($validator->fails()){
                    return response()->json(['errors'=>$validator->errors(), 'status'=>true]);
                }else{
                    $userName=$request->name;
                    $userMobile=$request->mobile;
                }
            }
            if($request->group){
                $groupExist=Group::find($request->group);
                if($groupExist){$groupID=$request->group; $groupName=$groupExist->name;}else{$groupID=null; $groupName=null;}
            }else{$groupID=null; $groupName=null;}

            if($userMobile==Auth::user()->mobile){
                return response()->json(['errors'=>['mobile'=>'نمیتونی با خودت چالش کنی که! شــــیطون ;)'], 'status'=>true]);
            }

            $friend=User::where('mobile', $userMobile)->first();
            if($friend){ // user exist
                if($groupID){
                    $groups = Group::where('id', $groupID)->whereHas('users', function (Builder $query) use ($friend) {
                        $query->where('id','=', $friend->id);
                    })->count();
                    if (!$groups){
                        $friend->groups()->attach($groupID);
                    }
                }

                $friendship=Friend::where('friend_id', $friend->id)->where('user_id', Auth::id())->orWhere('user_id', $friend->id)->where('friend_id', Auth::id())->first();
                if($friendship){//they are old friends
                    $doneChallenge=Challenge::where('user_id', $friend->id)->where('user2_id', Auth::id())->orWhere('user2_id', $friend->id)->where('user_id', Auth::id())->first();
                    if($doneChallenge){//they did challenge
                        if($doneChallenge->done){return response()->json(['quiz'=>true]);}//send error
                        if($doneChallenge->user_id==Auth::id() && $doneChallenge->done_user1==1){
                            return response()->json(['errors'=>['mobile'=>'این چالش را انجام داده‌اید و منتظر پاسخ '.$friend->name.' میباشد.'], 'status'=>true]);
                        }
                        return response()->json(['validated'=>true, 'info'=>['GPcode'=>$groupID, 'GPname'=>$groupName, 'name'=>$userName, 'mobile'=>$userMobile ]]);
                    }else{//old friends but not challenged do challenge
                        return response()->json(['validated'=>true, 'info'=>['GPcode'=>$groupID, 'GPname'=>$groupName, 'name'=>$userName, 'mobile'=>$userMobile ]]);
                    }
                }else{//user exist but they are not firend yet
                    return response()->json(['validated'=>true, 'info'=>['GPcode'=>$groupID, 'GPname'=>$groupName, 'name'=>$userName, 'mobile'=>$userMobile]]);
                }
            }else{//user does not exist ADD USER and ...
                return response()->json(['validated'=>true, 'info'=>['GPcode'=>$groupID, 'GPname'=>$groupName, 'name'=>$userName, 'mobile'=>$userMobile ]]);
            }
        }
    }
}
