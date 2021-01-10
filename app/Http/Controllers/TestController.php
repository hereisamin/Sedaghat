<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Friend;
use App\Like_quiz;
use App\Question;
use App\Question_like;
use App\Quiz;
use App\To_quiz;
use App\User;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Ipecompany\Smsirlaravel\Smsirlaravel;
use Illuminate\Support\Arr;
use PhpParser\Node\Expr\Array_;
use Illuminate\Database\Eloquent\Builder;

class TestController extends Controller
{
    public function index()
    {
        abort(404);
    }

}
