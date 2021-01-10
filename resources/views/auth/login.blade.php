@extends('layouts.pikabu')

@section('content')

        <div class="row justify-content-center rtl">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align: right">{{ __('ورود به چالش') }}</div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="mobile" class="col-md-4 col-form-label text-md-right">{{ __('موبایل') }}</label>

                                <div class="col-md-6">
                                    <input id="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile" placeholder="09xxxxxxxxx" value="{{ old('mobile') }}" required autocomplete="tel-national" autofocus>

                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('پسورد') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input mr-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('مرا بخاطر بسپار') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class=" col-md-6 mt-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('ورود') }}
                                    </button>
                                </div>
                                <div class="col-md-6 d-flex justify-content-center mt-3">
                                    <a class="btn btn-link" href="{{ route('password.request') }}"><strong>
                                        {{ __('پسورد خود را فراموش کردم') }}
                                        </strong>
                                    </a>
                                </div>
                            </div>
                        </form>
                        <div class="form-group row mb-2" style="margin-top: 20px; text-align: right">
                            <div class="col-md-12 d-flex justify-content-center">

                                <a class="btn btn-link" href="{{ route('register') }}">
                                    <strong>
                                    {{ __('در صورت نداشتن اکانت ثبت‌ نام کنید') }}
                                    </strong>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
