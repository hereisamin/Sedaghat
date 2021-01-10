@extends('layouts.pikabu')
@section('content')
    <div class="row">
        <div class="col">
            <table class="table rtl">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">یوزر</th>
                    <th scope="col">ارور</th>
                    <th scope="col">توضیحات</th>
                </tr>
                </thead>
                <tbody>

                @foreach($errors as $error)
                    <tr>
                        <th scope="row">{{$error->id}}</th>
                        <td>{{$error->user_id}}</td>
                        <td>{{$error->error}}</td>
                        <td>{{$error->description}}</td>
                    </tr>
                 @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
