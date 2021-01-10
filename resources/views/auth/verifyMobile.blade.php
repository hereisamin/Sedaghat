@extends('layouts.pikabu')
@section('content')

        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header rtl">تایید شماره همراه</div>
                    <div class="card-body rtl " >
                        <p>با تشکر از ثبت نام شما. لطفا کد ارسال شده به شماره {{$mobile}} را وارد کنید.</p>

                        <div class="d-flex justify-content-center">
                            <div class="col-8">
                                <form method="post" action="{{ route('login.verify') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="code">کد را وارد کنید</label>
                                        <input id="code" style="text-align:center" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{ $status ?? '' }}" name="code" type="text" placeholder="کد فعال سازی" required autofocus>

                                        @if ($errors->has('code'))
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('code') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary">تایید کن</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
