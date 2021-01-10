<?php

namespace App\Http\Controllers;

use App\Question;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ipecompany\Smsirlaravel\Smsirlaravel;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        if (Auth::user()->mobile != '09133433320'){
            abort(404);
        }
        $questions=Question::all();
        return view('admin', ['questions'=>$questions]);
    }
    public function users()
    {
        if (Auth::user()->mobile != '09133433320'){
            abort(404);
        }
        $users=User::all();
        return view('adminUsers', ['users'=>$users]);
    }
    public function errors()
    {
        if (Auth::user()->mobile != '09133433320'){
            abort(404);
        }
        $errors=DB::table('errors')->get();
        return view('adminErrors', ['errors'=>$errors]);
    }
    public function sms()
    {
        if (Auth::user()->mobile != '09133433320'){
            abort(404);
        }
        print_r(Smsirlaravel::sendVerification('123','09133433320')['Message']);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        if (Auth::user()->mobile != '09133433320'){
            abort(404);
        }
        $question = Question::create([
            'question'=> $request->question,
            'type'=>0,
            'challenge_name_id'=>1
        ]);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->mobile != '09133433320'){
            abort(404);
        }
        Question::where('id', $id)
            ->update(['question' => $request->question]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->mobile != '09133433320'){
            abort(404);
        }
        Question::where('id', $id)->delete();
        return back();
    }
}
