@extends('layouts.pikabu')
@section('content')
    <div class="row">
        <div class="col">
            <table class="table rtl">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">نام</th>
                    <th scope="col">شماره</th>
                    <th scope="col">کد</th>
                    <th scope="col">تایید شده</th>
                </tr>
                </thead>
                <tbody>

                @foreach($users as $user)
                    <tr>
                        <th scope="row">{{$user->id}}</th>
                        <td>{{$user->name}}</td>
                        <td>{{$user->mobile}}</td>
                        <td>{{$user->code}}</td>
                        <td>{{$user->registered_mobile}}</td>
                    </tr>
                 @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
