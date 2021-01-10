@extends('layouts.pikabu')

@section('content')
        <div class="row justify-content-center rtl">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('بازیابی رمز عبور') }}</div>

                    <div class="card-body">


                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('شماره همراه') }}</label>

                                <div class="col-md-6">
                                    <input id="mobile" type="mobile" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required  >

                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('ارسال کد بازیابی') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
