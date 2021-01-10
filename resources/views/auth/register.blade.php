@extends('layouts.pikabu')

@section('content')

        <div class="row justify-content-center ">
            <div class="col-md-8 ">
                <div class="card ">
                    <div class="card-header rtl">{{ __('ثبت نام') }}</div>

                    <div class="card-body rtl">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row ">
                                <label for="name" class="col-md-4 col-form-label text-md-right ">{{ __('نام') }}</label>

                                <div class="col-md-6 ">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="نام" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback rtl" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="mobile" class="col-md-4 col-form-label text-md-right">{{ __('شماره همراه') }}</label>

                                <div class="col-md-6">
                                    <input id="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile" placeholder="09xxxxxxxxx" value="{{ old('mobile') }}" required autocomplete="tel-national">

                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('رمز عبور ') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" placeholder="حداقل شش حرف" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('تکرار رمز عبور') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-4 offset-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('ثبت نام') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
