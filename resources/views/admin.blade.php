@extends('layouts.pikabu')
@section('content')
    <div class="row">
        <div class="col">
            <table class="table rtl">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">سوال</th>
                    <th scope="col">حذف</th>
                    <th scope="col">بروز رسانی</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th colspan="4">
                        <form method="post" name="insert" action="{{route('admin.store')}}">
                            @csrf
                            {{'چه کسی '}}<input type="text" name="question">{{' است؟'}}
                            <button type="submit" name="submit" class="btn btn-success">اضافه کردن</button>
                        </form>
                    </th>
                </tr>
                @php($i=0)
                @foreach($questions as $question)
                    @php($i++)
                    <tr>
                        <th scope="row">{{$i}}</th>
                        <form method="post" name="update" action="{{route('admin.update', $question->id)}}">
                            @method('PATCH')
                            @csrf
                        <td>{{'چه کسی '}}<input name="question" type="text" value="{{$question->question}}">{{' است؟'}}</td>
                        <td>
                            <button type="submit" class="btn btn-primary" name="submit">بروز رسانی</button>
                            </td></form>
                        <td><form method="post" name="update" action="{{route('admin.destroy', $question->id)}}">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger" name="submit">حذف</button>
                            </form></td>
                    </tr>
                 @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
