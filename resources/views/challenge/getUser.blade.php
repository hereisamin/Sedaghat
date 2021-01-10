@extends('layouts.pikabu')
@section('content')
    <div id="inviteUser" class="">
        <div class="row justify-content-center rtl  ">
            <div class="col-md-12">
                <div class="alert alert-primary text-center" id="wellcomeNew" role="alert">
                    {{__('شروع چالش جدید')}}
                </div>
            </div>
            <div class="col-md-12">
                <div class="alert alert-danger text-center d-none" id="topError" role="alert">
                </div>
                <div class="alert alert-danger text-center d-none" id="quizError" role="alert">
                    {{__('چالش با این شخص راانجام داده اید برای دیدن نتایج به لیست چالشهای تمام شده مراجعه کنید.')}}
                </div>
            </div>
            <div class="col-md-8 ">
                <div class="card">
                    <div class="card-header " style="text-align: right">{{ __('با چه کسی قصد چالش دارید؟') }}</div>
                    <div class="card-body">
                        <form id="subForm" >
                            <input type="hidden" id="groupCode" value="0">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('نام دعوت شونده:') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control   " name="name" placeholder="نام دعوت شونده" value="{{ old('name') }}" required  autofocus>
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="nameError"></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mobile" class="col-md-4 col-form-label text-md-right">{{ __('شماره همراه دعوت شونده:') }}</label>
                                <div class="col-md-6">
                                    <input id="mobile" type="mobile" class="form-control" name="mobile" placeholder="09xxxxxxxxx" required>
                                    <span class="invalid-feedback" role="alert">
                                            <strong id="mobileError"></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <div class="  col-md-12 text-center mt-3">
                                    <button id="invite" name="invite" class="btn btn-primary w-75">
                                        {{'دعوت شخص'}}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="form-group row mb-2" style="margin-top: 20px; text-align: right">
                            <div class="col-md-12 d-flex justify-content-center">
                                <a></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="confirmChallenge" class="d-none">
        <div class="row h-85">
            <div class="col">
                <div class="card h-85">
                    <div class="card-header text-center">
                        {{'چالش در گروه :  '}}<strong id="GroupNameConfirm"></strong>
                        <input type="hidden" id="GroupCodeConfirm">
                    </div>
                    <div class="card-body rtl h-75">
                        <div class="row h-50 mt-3">
                            <div class="col-md-6">
                                <h5>{{'با شخص :  '}}<strong id="NameConfirm"></strong></h5><br/>
                            </div>
                            <div class="col-md-6">
                                <h5>{{'شماره همراه :  '}}<strong id="MobileConfirm"></strong></h5>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button class="btn btn-primary w-75 mt-4 " style="height: 80px;" id="FinalConfirm"><strong>تائید و شروع</strong></button>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-secondary float-left" id="backToChose"><i class="far fa-arrow-alt-circle-left" style="font-size: 20px;"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
<script>
@section('script')

    $('#backToChose').click(function () {
        $('#inviteUser').removeClass('d-none');
        $('#confirmChallenge').addClass('d-none');
    });

$('#invite').click(function(event){
    $('#quizError').addClass('d-none');
    $('#topError').addClass('d-none');
    event.preventDefault();
    $name = $('#name').val();
    $mobile = $('#mobile').val();
    $groupCode= $('#groupCode').val();
    $.ajax({
        url: "{{route('group.create')}}",
        type:"POST",
        data:{
            "_token": "{{ csrf_token() }}",
            validation:true,
            name:$name,
            mobile:$mobile,
            group:$groupCode,
        },
        success:function(response){
            $('#name').removeClass('is-invalid');
            $('#mobile').removeClass('is-invalid');
            if(response.status) {
                $.each(response.errors, function(index, el) {
                    $('#'+index+'Error').text(el);
                    $('#'+index).addClass('is-invalid');
                });
            }else {
                if (response.quiz){
                    $('#topError').text('چالش با این شخص راانجام داده اید و این چالش به گروه اضافه شد. برای دیدن نتایج به لیست چالشهای تمام شده در خانه مراجعه کنید.');
                    $('#topError').removeClass('d-none');
                }
                if (response.validated){
                    $('#GroupNameConfirm').text(response.info.GPname);
                    $('#GroupCodeConfirm').val(response.info.GPcode);
                    $('#NameConfirm').text(response.info.name);
                    $('#MobileConfirm').text(response.info.mobile);
                    $('#inviteUser').addClass('d-none');
                    $('#confirmChallenge').removeClass('d-none');
                }

            }
        },

        error: function(response) {
        }

    });
});


$('#FinalConfirm').click(function(event){
    $('#wellcomeNew').addClass('d-none');
    $('#quizError').addClass('d-none');
    event.preventDefault();

    $name = $('#name').val();
    $mobile = $('#mobile').val();

    $.ajax({
        url: "{{route('challenge.invite')}}",
        type:"POST",
        data:{
            "_token": "{{ csrf_token() }}",
            name:$name,
            mobile:$mobile
        },
        success:function(response){
            $('#name').removeClass('is-invalid');
            $('#mobile').removeClass('is-invalid');
            //for (var index in response.errors) {
            //    alert( index+' : '+response.errors[index] );
            //}
            if(response.status) {
                $.each(response.errors, function(index, el) {
                    $('#'+index+'Error').text(el);
                    $('#'+index).addClass('is-invalid');
                });
            }else {
                if (response.quiz) {
                    if(response.code){
                        window.location="{{route('challenge.load', "")}}/"+response.code;
                    }else{
                        $('#quizError').removeClass('d-none');
                    }

                } else {
                    if(response.StartQuiz){
                        window.location="{{route('challenge.start')}}";
                    }else  alert('خطا در ارسال ارسال اطلاعات. لطفا اندکی دیگر تلاش کنید');

                }
            }
        },

        error: function(response) {
        }

    });
});

@endsection
    </script>
