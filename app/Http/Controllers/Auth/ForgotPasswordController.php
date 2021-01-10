<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Ipecompany\Smsirlaravel\Smsirlaravel;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }
    public function sendResetLinkEmail(Request $request){
        $user=User::where('mobile', $request->mobile)->first();
        if($user){
            $timediff=$user->sent_code_time->diffInSeconds();
            if($timediff >= 300){
                if (Smsirlaravel::sendTheCode($user->mobile, $user->code)) {
                    User::where('mobile', '=', $user->mobile)->update(['sent_code_time' => now()]);
                    return redirect('password/reset/'.$user->mobile)->withErrors(['mobile' => $user->mobile]);
                } else {
                    return redirect('password/reset/'.$user->mobile)->withErrors(['code' => 'خطا در ارسال کد٬ لطفا دقایقی دیگر امتحان کنید']);
                }
            }else{
                return redirect('password/reset/'.$user->mobile)->with(['mobile' => $user->mobile])->withErrors(['code' => ' زمان باقی مانده تا ارسال دوباره کد:'.(300-$timediff).' ثانیه میباشد']);
            }
        }else{
            return redirect('password/reset')->withErrors(['mobile'=>'شماره وارد شده موجود نمیباشد.']);
        }
    }
}
