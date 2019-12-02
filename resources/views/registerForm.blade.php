<!-- 存放在 resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', 'Laravel学院')

@section('sidebar')
    @parent
    <p>Laravel学院致力于提供优质Laravel中文学习资源</p>
@endsection

@section('content')
    <p>
    <form action="/register" method="post">
        @csrf {{-- 或者 {{csrf_field()}} --}}
        <lable>username</lable>
        <input type="text" name="name">
        <lable>password</lable>
        <input type="password" name="password">
        <lable>confirm password</lable>
        <input type="password" name="confirm_password">
        <input type="submit" value="submit">
    </form>
    </p>
@endsection