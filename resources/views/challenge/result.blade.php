@extends('layouts.result')
@section('content')
    <div class="row d-none" id="urlLinkRow">
        <div class="col">
            <div class="alert alert-success">
                <input type="hidden" id="shareLink">
                <button class="btn btn-primary w-100" id="copyUrl">کپی کردن لینک اشتراک</button>
            </div>
        </div>
    </div>

    <div class="row text-center">
        <div class="col">
            <h4 style="color: #0000d8;">{{$info['user2']->name}}</h4>
        </div>
        <div class="col">
            <div class="chart" data-percent="{{$percentage}}" data-scale-color="" style="color: #1aa502"> {{$percentage}}</div>
        </div>
        <div class="col">
            <h4 style="color: #0000d8;">{{$info['user1']->name}}</h4>
        </div>
    </div>
    @if($info['done'])
        <div class="row text-center">
            <div class="col">
                @foreach($rights as $right)
                    @if($right['name']==$info['user2']->name)
                        <span style="color: #1aa502; font-size: 15px;">{{$info['fname2']}}  {{$right['question']}}{{__('ه')}}</span><br/>
                    @endif
                @endforeach
            </div>
            <div class="col">
                @foreach($rights as $right)
                    @if($right['name']==$info['user1']->name)
                        <span style="color: #1aa502; font-size: 15px;">{{$info['fname1']}}  {{$right['question']}}{{__('ه')}}</span><br/>
                    @endif
                @endforeach
            </div>
        </div>
    @else
        <div class="alert alert-warning rtl">این چالش در انتظار پاسخ {{ $info['fname2'] }} میباشد </div>
    @endif

    <div class="row text-center">
        <table class="table table-striped text-center">
            <tbody>
            @foreach($answers as $key=>$answer)
                <tr>
                    <td colspan="4">{{__('کی')}} {{$answer['question']}}{{'ه؟'}} </td>
                </tr>
                <tr>
                    <td style="color: #260192;" colspan="2">
                        {{$info['fname2']}} :
                        @if($info['done']) @if($answer['answer2']==1) {{'خودم'}} @else {{$info['fname1']}} @endif @else
                            {{'در انتظار پاسخ '}}
                        @endif
                        <br/>
                        @if($info['done'])
                            <i class="@if($answer['myLike2']) fa @else far @endif fa-thumbs-up"  data="{{$key}}" style="font-size: 15px;" @guest id="like" @else id="like2" @endguest > {{$answer['likes2']}} </i>
                        @endif
                    </td>
                    <td style="color: #260192;" colspan="2">
                        {{$info['fname1']}} :
                        @if($answer['answer1']==1) خودم @else {{$info['fname2']}} @endif
                        <br/>
                        @if($info['done'])
                            <i class="@if($answer['myLike1']) fa @else far @endif fa-thumbs-up" @guest id="like" @else id="like2" @endguest data="{{$key}}" style="font-size: 15px;"> {{$answer['likes1']}} </i>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4">
                    <a class="btn btn-primary w-100" href="{{route('home')}}">برگشت به پیشخوان</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>



@endsection

<script>
    @section('script')


    $('#like1, #like1').click(function(event){
        if($(this).hasClass('far')){
            var text=(parseInt($(this).text())+1);
            $(this).text(text);
            $(this).removeClass('far');
            $(this).addClass('fa');
            event.preventDefault();
            $.ajax({
                url: "{{route('challenge.likes')}}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    section:'like1',
                    code:$(this).attr('data'),
                },
                success:function(response){
                    if(response.liked){
                        //console.log(response);
                    }
                },
                error: function(response){alert('ارتباط با سرور مقدور نمیباشد');}
            });
        }else{
            var text=(parseInt($(this).text())-1);
            $(this).text(text);
            $(this).removeClass('fa');
            $(this).addClass('far');
            event.preventDefault();
            $.ajax({
                url: "{{route('challenge.likes')}}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    section:'unlike1',
                    code:$(this).attr('data'),
                },
                success:function(response){
                    if(response.liked){
                        //console.log(response);
                    }
                },
                error: function(response){alert('ارتباط با سرور مقدور نمیباشد');}
            });
        }
    });



    $('#like2, #like2').click(function(event){
        if($(this).hasClass('far')){
            var text=(parseInt($(this).text())+1);
            $(this).text(text);
            $(this).removeClass('far');
            $(this).addClass('fa');
            event.preventDefault();
            $.ajax({
                url: "{{route('challenge.likes')}}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    section:'like2',
                    code:$(this).attr('data'),
                },
                success:function(response){
                    if(response.liked){
                        //console.log(response);
                    }
                },
                error: function(response){alert('ارتباط با سرور مقدور نمیباشد');}
            });
        }else{
            var text=(parseInt($(this).text())-1);
            $(this).text(text);
            $(this).removeClass('fa');
            $(this).addClass('far');
            event.preventDefault();
            $.ajax({
                url: "{{route('challenge.likes')}}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    section:'unlike2',
                    code:$(this).attr('data'),
                },
                success:function(response){
                    if(response.liked){
                        //console.log(response);
                    }
                },
                error: function(response){alert('ارتباط با سرور مقدور نمیباشد');}
            });
        }
    });

    $("a#shareIt").click( function() {
        $first="{{$info['user1']->id}}";
        $second="{{$info['user2']->id}}";
        $user1="{{$info['user1']->name}}";
        $user2="{{$info['user2']->name}}";
        $challenge="{{$info['challenge']}}"
        //alert($user1+' و '+$user2);
        $.ajax({
            url: "{{route('setUrl')}}",
            type:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                share:true,
                code1:$user1,
                code2:$user2,
                challenge:$challenge,
            },
            success:function(response){
                if(response.name){
                    $('#urlLinkRow').removeClass('d-none');
                    $('#shareLink').val(response.name);
                    //console.log(response.name);
                }
            },
            error: function(response){alert('ارتباط با سرور مقدور نمیباشد');}
        });
    });

    $('#like, #like').click(function(event){
        $(this).text('ابتدا وارد شوید');
    });
    $('#copyUrl').click(function(event){
        //$link=$('#shareLink').val();
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($('#shareLink').val()).select();
        document.execCommand("copy");
        $temp.remove();
        $('#copyUrl').text('کپی شد');
    });


    @endsection
</script>


