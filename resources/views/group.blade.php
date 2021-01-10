@extends('layouts.pikabu')
@section('content')
    <div class="row h-100">
        <div class="col">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header rtl">
                            {{'نام گروه: '}}  <strong>{{$selectedGroup->name}}</strong>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title rtl">عنوان گروه: </h5>
                            <p class="card-text rtl">{{$selectedGroup->info}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row h-25 align-content-center">
                <div class="col">
                    <button class="btn btn-primary w-100 h-50" data-toggle="modal" data-target="#users" style="margin-top: 10%;">{{'لیست اعضای این گروه'}}</button>
                </div>
            </div>
            <div class="row h-25 align-content-center">
                <div class="col">
                    <button class="btn btn-primary w-100 h-50" data-toggle="modal" data-target="#challenges" style="margin-top: 10%;">{{'چالشهای انجام شده در این گروه'}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_body')

    <div class="modal fade" id="users" tabindex="-1" role="dialog" aria-labelledby="usersTitle" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 85%; margin-left: 5%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usersTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary text-center">
                        <strong>{{'اعضای گروه '}}{{$selectedGroup->name}}</strong>
                    </div>
                    <ul class="list-group">

                        @if(!$users)
                            <li class="list-group-item"><span style="float: right;">{{'این گروه هنوز عضوی ندارد'}}</span></li>
                        @endif
                        @foreach($users as $user)
                            <li class="list-group-item"><span style="float: right;">{{$user['name']}}</span><span>{{$user['mobile']}}</span></li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary w-75" data-toggle="modal" data-target="#friends" data-dismiss="modal">اضافه کردن عضو</button>
                    <button type="button" class="btn btn-secondary w-75" data-dismiss="modal">برگشت</button>
                </div>
            </div>
        </div>
    </div>

    <!-- challenges list -->
    <div class="modal fade" id="challenges" tabindex="-1" role="dialog" aria-labelledby="challengesTitle" aria-hidden="true">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="challengesTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active text-center">
                            <strong>{{'چالش‌های گروه '}}{{$selectedGroup->name}}</strong>
                        </a>
                        @if(!$challenges)
                            <a href="#" class="list-group-item list-group-item-action rtl">هنوز چالشی در این گروه انجام نشده</a>
                        @endif
                        @foreach($challenges as $key=>$challenge)
                            <a href="{{route('challenge.result', $key)}}" class="list-group-item list-group-item-action text-center"> <strong style="float: right;">{{$challenge['firstUser']}}</strong> <i class="fas fa-arrows-alt-h" style="font-size: 20px; color: #0000d8" ></i>  <strong style="float: left;">{{$challenge['secondUser']}}</strong> </a>
                        @endforeach
                    </div>
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
                    <div class="alert alert-warning d-none rtl" id="userExist"><strong id="newName"></strong> از قبل در گروه بوده</div>
                    <div class="alert alert-success d-none rtl" id="userAdded"><strong id="newAddName"></strong> به گروه اضافه شد</div>
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active text-center">
                            <strong>{{'اضافه کردن عضو به  '}}{{$selectedGroup->name}}</strong>
                        </a>
                        @if(!$friends)
                            <a href="#" class="list-group-item list-group-item-action rtl">شما هنوز دوستی برای اضافه کردن به این گروه ندارید</a>
                        @endif
                        @foreach($friends as $key=>$friend)
                            <a href="#" data="{{$key}}" id="addUser" class="list-group-item list-group-item-action text-center"> <strong style="float: right;">{{$friend['name']}}</strong><strong style="float: left;">{{$friend['mobile']}}</strong> </a>
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
<script>

@section('script')

$("a#addUser").click( function() {
    $('#userExist').addClass('d-none');
        $code=(this).getAttribute("data");
    $.ajax({
        url: "{{route('add.user')}}",
        type:"POST",
        data:{
            "_token": "{{ csrf_token() }}",
            code:$code,
            GP:{{$selectedGroup->id}},
        },
        success:function(response){
            if(response.exist){
                $('#userExist').removeClass('d-none');
                $('#newName').text(response.newUser.name);
            }
            if(response.success){
                $('#userExist').addClass('d-none');
                $('#userAdded').removeClass('d-none');
                $('#newAddName').text(response.success.name);
                location.reload();
            }
        },
        error: function(response){alert('ارتباط با سرور مقدور نمیباشد');}
    });
    });



@endsection
</script>
