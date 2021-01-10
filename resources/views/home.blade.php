@extends('layouts.pikabu')
@section('content')

    <div class="row rtl">
        <div class="col">
            <div class="card">
                <div class="card-header">
                     {{Auth::user()->name}}  خوش امدی.
                </div>
                @if (session('status'))
                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div class="row mt-3 h-15" style="min-height: 70px;">
        <div class="col" >
            <div class="btn-group btn-block align-self-center h-100 " role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-lg  btn-outline-primary dropdown-toggle h-85" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    شروع چالش جدید
                </button>
                <div class="dropdown-menu w-100 " aria-labelledby="btnGroupDrop1">
                    <div class="row ">
                        <div class="d-flex col-sm-6 justify-content-center mt-3 mb-3">
                            <a class="btn btn-lg btn-primary w-75" href="{{route('challenge.invite')}}"> {{ __("شروع چالش خصوصی")}}</a>
                        </div>
                        <div class="d-flex col-sm-6 justify-content-center mt-3 mb-3">
                            <a class="btn btn-lg btn-primary w-75" href="{{route('group.prep')}}"> {{ __("شروع چالش در گروه")}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="row h-65 mb-3" style="min-height: 295px;">
    <div class="col">
        <div class="card w-100 h-100">
           <div class="card-header rtl">
               <h5>{{'چالش‌های من : '}}</h5>
           </div>
           <div class="card-body h-100">
               <div class="row h-100">
                    <div class=" col-lg-4 justify-content-center mb-3 " >
                        <button type="button" @if(!$invited_challenges) disabled @endif  class="btn btn-primary  @if(!$invited_challenges) disabled @endif  w-100 h-85" data-toggle="modal" data-target="#invited_challenges">
                            <span style="float: left;" class="badge @if(!count($invited_challenges) == 0) badge-danger @else badge-light @endif ">{{count($invited_challenges)}}</span>{{ __("منتظر پاسخ من")}}
                        </button>
                    </div>
                    <div class=" col-lg-4 justify-content-center mb-3 " >
                        <button type="button" @if(!$started_challenges) disabled @endif  class="btn btn-primary  @if(!$started_challenges) disabled @endif  w-100 h-85 " data-toggle="modal" data-target="#started_challenges">
                            <span style="float: left;" class="badge @if(!count($started_challenges) == 0) badge-danger @else badge-light @endif ">{{count($started_challenges)}}</span>{{ __(" به من پاسخ نداده‌اند")}}
                        </button>
                    </div>
                    <div class=" col-lg-4 justify-content-center mb-3 " >
                        <button type="button" @if(!$done_started_challenges && !$done_invited_challenges) disabled @endif class="btn btn-primary h-85  @if(!$done_started_challenges && !$done_invited_challenges) disabled @endif w-100" data-toggle="modal" data-target="#done_invited_challenges">
                            <span style="float: left;" class="badge @if(!(count($done_started_challenges)+count($done_invited_challenges)) == 0) badge-danger @else badge-light @endif">{{count($done_started_challenges)+count($done_invited_challenges)}}</span>{{ __("چالشهای تمام شده")}}
                        </button>
                    </div>
               </div>
           </div>
        </div>
    </div>
</div>

    <div class="row h-15" style="min-height: 50px;">
        <div class="col h-100">
            <button type="button" class="btn btn-outline-primary  w-100 h-85 " data-toggle="modal" data-target="#friends" aria-haspopup="true" aria-expanded="false">
                 دوستان من
            </button>
        </div>
        <div class="col h-100">
            <button type="button" class="btn btn-outline-primary  w-100 h-85" data-toggle="modal" data-target="#groups" aria-haspopup="true" aria-expanded="false">
                 گروه‌های من
            </button>
        </div>
    </div>






@endsection
@section('after_body')
    <!-- invited_challenges -->
    <div class="modal fade" id="invited_challenges" tabindex="-1" role="dialog" aria-labelledby="invited_challengesTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invited_challengesTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary text-center">
                        <strong>{{__('چالش های که دعوت شده‌اید و هنوز پاسخ نداده‌اید')}}</strong>
                    </div>
                    <table class="table table-striped rtl">
                        <tbody>
                        @php($i=1)
                        @foreach($invited_challenges as $key=>$invited_challenge)
                            <tr>
                                <td><a href="{{route('challenge.load', $key)}}" style="color: #008dff; font-size: 19px;"> {{$i}} - {{$invited_challenge['name']}} </a></td>
                            </tr>
                            @php($i++)
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">برگشت</button>
                </div>
            </div>
        </div>
    </div>

    <!-- started_challenges -->
    <div class="modal fade" id="started_challenges" tabindex="-1" role="dialog" aria-labelledby="started_challengesTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="started_challengesTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary text-center">
                        <strong>{{__('چالش های که شروع کرده‌اید و هنوز پاسخ نگرفته‌اید')}}</strong>
                    </div>
                    <table class="table table-striped rtl">
                        <tbody>
                        @php($i=1)
                        @foreach($started_challenges as $key=>$started_challenge)
                            <tr>
                                <td><a href="{{route('challenge.result', $key)}}" style="color: #008dff; font-size: 19px;">{{$i}} - {{$started_challenge['name']}}</a></td>

                            </tr>
                            @php($i++)
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">برگشت</button>
                </div>
            </div>
        </div>
    </div>


    <!-- done_started_challenges -->
    <div class="modal fade" id="done_invited_challenges" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary text-center">
                        <strong>{{__('چالش های به پایان رسیده')}}</strong>
                    </div>
                    <table class="table table-striped rtl">
                        <tbody>
                        @php($i=1)
                        @foreach($done_invited_challenges as $key=>$done_invited_challenge)
                            <tr>
                                <td><a href="{{route('challenge.result', $key)}}" style="color: #008dff; font-size: 19px;">{{$i}} - {{$done_invited_challenge['name']}}</a></td>
                            </tr>
                            @php($i++)
                        @endforeach
                        @foreach($done_started_challenges as $key=>$done_started_challenge)
                            <tr>
                                <td><a href="{{route('challenge.result', $key)}}" style="color: #008dff; font-size: 19px;">{{$i}} - {{$done_started_challenge['name']}}</a></td>
                            </tr>
                            @php($i++)
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">برگشت</button>
                </div>
            </div>
        </div>
    </div>


    <!-- friends list -->
    <div class="modal fade" id="friends" tabindex="-1" role="dialog" aria-labelledby="friendsTitle" aria-hidden="true">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="friendsTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary text-center">
                        <strong>{{__('لیست دوستان من')}}</strong>
                    </div>
                    <ul class="list-group">
                            @if(!$friends_list)
                                <li class="list-group-item"><span style="float: right;">{{'شما هنوز دوستی در چالش ندارید'}}</span></li>
                            @endif
                            @foreach($friends_list as $friend)
                                <li class="list-group-item"><span style="float: right;">{{$friend['name']}}</span><span>{{$friend['mobile']}}</span></li>
                            @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">برگشت</button>
                </div>
            </div>
        </div>
    </div>


    <!-- groups list -->
    <div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="groupsTitle" aria-hidden="true">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupsTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active text-center">
                            لیست گروه‌های من
                        </a>
                        @if(!$groups)
                            <a href="#" class="list-group-item list-group-item-action rtl">شما هنوز گروهی ندارید</a>
                        @endif
                        @foreach($groups as $group)
                            <a href="{{route('gruop.show', $group->id)}}" class="list-group-item list-group-item-action rtl">{{$group->name}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">برگشت</button>
                </div>
            </div>
        </div>
    </div>
@endsection
