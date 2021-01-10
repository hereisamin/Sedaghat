<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class ResetPasswordController extends Controller
{
    public  function showResetForm($mobile){
        return view('auth.verifyResetMobile', ['mobile'=>$mobile]);
    }
    public function reset(Request $request){
        $validator = $this->validate($request, ['code'=>['required', 'numeric', 'regex:/^\d{4}$/'], 'password' => ['required', 'string', 'min:6', 'confirmed']]);
        $code = $request->code;
        $password = $request->password;
        $mobile= $request->mobile;
        $user=User::where('mobile', $mobile)->first();
        $dbcode=$user->code;

        if($validator && $code==$dbcode){
            $user->password=Hash::make($password);
            if($user->save()){
                $request->session()->flash('status', 'پسورد با موفقیت تغییر یافت');
                return redirect()->route('login');
            }else{ return redirect('password/reset/'.$mobile)->withErrors(['code'=>'خطا در بارگذاری اطلاعات.']); }
        }else{
            if ($code!=$dbcode){
                return redirect('password/reset/'.$mobile)->withErrors(['code'=>'کد وارد شده صحیح نمیباشد.']);
            }
            return redirect('password/reset/'.$mobile)->withErrors();
        }


    }
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
}
