<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Ipecompany\Smsirlaravel\Smsirlaravel;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('signed')->only('verify');
        //$this->middleware('throttle:6,1')->only('verify', 'resend');
    }
    public function index(Request $request)
    {
        $mobile = $request->user()->mobile;
        $code= $request->user()->code;
        $lastSent=$request->user()->sent_code_time;
        if ($request->user()->hasVerifiedMobile()) {
            return redirect()->route('home')->with('status', 'شماره شما قبلا تایید شده است.');
        }
        if($lastSent){
            $timediff=$lastSent->diffInSeconds();
        }else{$timediff=500;}

        if($timediff >= 300){
            $resultOfSned=Smsirlaravel::sendTheCode($mobile, $code);
            if ($resultOfSned == 'sent') {
                User::where('mobile', '=', $mobile)->update(['sent_code_time' => now()]);
                return view('auth.verifyMobile', ['mobile' => $mobile]);
            } else {
                DB::table('errors')->insert(
                    ['user_id'=>Auth::id(), 'error' => 'reg code not sent: '.$resultOfSned, 'description' => 'Varification controller cant send code']
                );
                return view('auth.verifyMobile', ['mobile' => $mobile])->withErrors(['code' => 'خطا در ارسال کد. لطفا دقایقی دیگر تلاش کنید.']);
            }
        }else{
            return view('auth.verifyMobile', ['mobile' => $mobile])->withErrors(['code' => ' زمان باقی مانده تا ارسال دوباره کد:'.(300-$timediff).' ثانیه میباشد']);
    }

    }
    public function verify(Request $request){
        if($request->code==$request->user()->code){
            User::where('mobile', '=', $request->user()->mobile)
                 ->update(['mobile_verified_at' => now()]);
            return redirect()->route('home')->with('status', 'شماره شما تایید شد. خوش آمدید');
        }
        return view('auth.verifyMobile', ['mobile' => $request->user()->mobile])->withErrors(['code'=>'کد وارد شده صحیح نمیباشد.']) ;
    }
}
