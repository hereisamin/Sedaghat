@extends('layouts.pikabu')
@section('content')
<div class="row">
    <div class="col">
        <div class="alert alert-warning d-none rtl" id="topError"></div>
    </div>
</div>
<div id="selectGroup" class="">
    <div class="row">
        <div class="col">
            <div class="alert alert-primary text-center">
                {{'  شروع چالش در گروه  '}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group rtl">
                <label for="formControlSelectGroup">نام گروه را انتخاب کنید</label>
                <select class="form-control form-control-lg rtl" id="formControlSelectGroup">
                    <option value="0" selected disabled >انتخاب گروه</option>
                    @foreach($groups as $group)
                        <option value="{{$group->id}}">{{$group->name}}</option>
                        @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row mb-3 mt-3">
            <div class="col rtl">
                <label class="rtl"><strong>یا گروهی جدید بسازید</strong></label>
            </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100" id="newGroup">ساخت گروه جدید</button>
        </div>
    </div>
</div>

<div id="selectUserDiv" class="d-none">
    <div class="card">
        <div class="card-header rtl text-muted">
            <label>نام شخصی از گروه <strong id="groupName"></strong> انتخاب کنید</label>
        </div>
        <div class="card-body">
    <div class="row">
        <div class="col">
            <div class="form-group rtl">
                <select class="form-control form-control-lg rtl" id="formControlSelectUser">
                    <option selected disabled>انتخاب شخص</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row mb-3 mt-3">
        <div class="col rtl">
            <label class="rtl text-muted"><strong>یا شخصی به گروه دعوت کنید</strong></label>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100" id="inviteNewUser">دعوت شخص جدید</button>
        </div>
    </div>
        </div>
        <div class="card-footer text-muted">
            <button class="btn btn-secondary" id="backToChose"><i class="far fa-arrow-alt-circle-left" style="font-size: 20px;"></i></button>
        </div>
    </div>
</div>

<div id="createNewGroup" class="d-none">
    <div class="row mt-3 ">
        <div class="col">
            <div class="card text-center">
                <div class="card-header">
                    {{'ساخت گروه جدید'}}
                </div>
                <div class="card-body">
                    <div class="form-group rtl">
                        <label for="inputGroupName">نام گروه: </label>
                        <input type="text" class="form-control" id="inputGroupName" placeholder="نام گروه">
                    </div>
                    <div class="form-group rtl">
                        <label for="inputGroupdec">شرح گروه: </label>
                        <input type="text" class="form-control" required id="inputGroupdec" placeholder="" aria-describedby="sharhHelp">
                        <small id="sharhHelp" class="text-muted rtl">
                            در صورت نیاز برای توضیح کوتاهی از گروه.
                        </small>
                    </div>
                    <button class="btn btn-primary w-75" id="submitGroup">ثبت گروه و ادامه</button>
                </div>
                <div class="card-footer text-muted">
                    <button class="btn btn-secondary" id="backToChose"><i class="far fa-arrow-alt-circle-left" style="font-size: 20px;"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="addUserToGroup" class="d-none">
    <div class="row justify-content-center rtl  ">
        <div class="col-md-12">
            <div class="alert alert-danger text-center d-none" id="quizError" role="alert">
                {{__('چالش با این شخص راانجام داده اید و این چالش به گروه اضافه شد. برای دیدن نتایج به لیست چالشهای تمام شده مراجعه کنید.')}}
            </div>
        </div>
        <div class="col-md-8 ">
            <div class="card">
                <div class="card-header " style="text-align: right">{{'با چه کسی قصد چالش در گروه '}}<strong id="groupName2"></strong>{{' را دارید؟'}}</div>
                <div class="card-body">
                    <form id="subForm" >
                        @csrf
                        <input type="hidden" id="groupCode">
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
                                <button id="submit" type="button" name="submit" class="btn btn-primary w-75">
                                    {{ __('شروع چالش') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-muted">
                    <button class="btn btn-secondary float-left" id="backToChoseName"><i class="far fa-arrow-alt-circle-left" style="font-size: 20px;"></i></button>
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

$('#backToChoseName').click(function(event) {
    $('#topError').addClass('d-none');
    $('#addUserToGroup').addClass('d-none');
    $('#selectUserDiv').removeClass('d-none');
});

$('#inviteNewUser').click(function(event) {
    $('#topError').addClass('d-none');
    $('#groupName2').text($('#groupName').text());
    $('#selectUserDiv').addClass('d-none');
    $('#addUserToGroup').removeClass('d-none');
});
$('#newGroup').click(function(event) {
    $('#topError').addClass('d-none');
    $('#selectGroup').addClass('d-none');
    $('#createNewGroup').removeClass('d-none');
});
$('#backToChose, #backToChose').click(function(event) {
    $('#topError').addClass('d-none');
    $("#formControlSelectGroup option[value=0]").prop("selected", "selected");
    $('#createNewGroup').addClass('d-none');
    $('#selectUserDiv').addClass('d-none');
    $('#confirmChallenge').addClass('d-none');
    $('#addUserToGroup').addClass('d-none');
    $('#selectGroup').removeClass('d-none');
});

$('#submitGroup').click(function(event) {
    $('#topError').addClass('d-none');
    $gName=$('#inputGroupName').val().trim();
    $gDes= $('#inputGroupdec').val().trim();
if (!$gName){
    $('#inputGroupName').addClass('is-invalid');
}else {
    event.preventDefault();
    $.ajax({
        url: "{{route('group.create')}}",
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            createGroup:true,
            name:$gName,
            des:$gDes
        },
        success:function (response) {
            if(response.success){
                $('#createNewGroup').addClass('d-none');
                $groupName=response.groupName;
                $groupCode=response.GP;
                $('#groupName2').text($groupName);
                $('#groupCode').val($groupCode);
                $('#addUserToGroup').removeClass('d-none');
            }
        }
    });
}
});

$('#formControlSelectUser').change(function (event) {
    $('#topError').addClass('d-none');
    $('#topError').addClass('d-none');
    $group = $('#groupCode').val();
    $user = $('#formControlSelectUser').val();
    $.ajax({
        url: "{{route('group.create')}}",
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            selectUser:true,
            validation:true,
            group:$group,
            user:$user,
        },
        success:function (response) {//console.log(response.name);
            //console.log(response);
            if (response.status){
                $('#topError').text(response.errors.mobile);
                $('#topError').removeClass('d-none');
            }
            if (response.quiz){
                $('#topError').text('چالش با این شخص راانجام داده اید و این چالش به گروه اضافه شد. برای دیدن نتایج به لیست چالشهای تمام شده مراجعه کنید.');
                $('#topError').removeClass('d-none');
            }
            if (response.validated){
                $('#GroupNameConfirm').text(response.info.GPname);
                $('#GroupCodeConfirm').val(response.info.GPcode);
                $('#NameConfirm').text(response.info.name);
                $('#MobileConfirm').text(response.info.mobile);
                $('#selectUserDiv').addClass('d-none');
                $('#confirmChallenge').removeClass('d-none');
            }else{
                //alert('no response');
            }
        }
    });
});

$('#formControlSelectGroup').change(function (event) {
    $('#topError').addClass('d-none');
    $("#formControlSelectUser option").remove();
    $("#formControlSelectUser").append('<option selected disabled>انتخاب شخص </option>');
    $group = $('#formControlSelectGroup').val();
    $.ajax({
        url: "{{route('group.create')}}",
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            selectGroup:true,
            group:$group,
        },
        success:function (response) {//console.log(response.name);
            if (response.name){
                $('#selectGroup').addClass('d-none');
                $('#groupName').text(response.group.name);
                $('#groupCode').val(response.group.id);
            $val=response.name;

            $.each($val, function (index, value) {
                $("#formControlSelectUser").append('<option '+value.disabled+' value='+value.id+'>'+value.name+'</option>');
            });
                $('#selectUserDiv').removeClass('d-none');
        }}
    });
});

$('#submit').click(function(event){
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
            //for (var index in response.errors) {
            //    alert( index+' : '+response.errors[index] );
            //}
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
                    $('#addUserToGroup').addClass('d-none');
                    $('#confirmChallenge').removeClass('d-none');
                }

            }
        },

        error: function(response) {
        }

    });
});

$('#FinalConfirm').click(function(event){
    $('#topError').addClass('d-none');
    event.preventDefault();
    $name = $('#NameConfirm').text();
    $mobile = $('#MobileConfirm').text();
    $group=$('#GroupCodeConfirm').text();
    $.ajax({
        url: "{{route('challenge.invite')}}",
        type:"POST",
        data:{
            "_token": "{{ csrf_token() }}",
            name:$name,
            mobile:$mobile,
            group:$group,
        },
        success:function(response){
            if(response.status) {
                $('#topError').removeClass('d-none');
                $.each(response.errors, function(index, el) {
                    $('#topError').text(el);
                });
            }else {
                if (response.quiz) {
                    if(response.code){
                        window.location="{{route('challenge.load', "")}}/"+response.code;
                    }else{
                        alert('خطا در ارسال ارسال اطلاعات. لطفا اندکی دیگر تلاش کنید');
                    }
                } else {
                    if(response.StartQuiz){
                        window.location="{{route('challenge.start')}}";
                    }else  alert('خطا در ارسال ارسال اطلاعات. لطفا اندکی دیگر تلاش کنید');
                }
            }
        },
    });
});

@endsection
</script>
