<!-- 存放在 resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', 'Laravel学院')

@section('sidebar')
    @parent
    <p>登录系统之后, 更新数据...</p>
@endsection

@section('content')
    <p>
    <form action="/flight/save" method="post">
        @csrf {{-- 或者 {{csrf_field()}} --}}
        <lable>id</lable>
        <input type="text" name="id">
        <lable>username</lable>
        <input type="text" name="name">
        <lable>delayed</lable>
        <input type="text" name="delayed">
        <input type="submit" value="submit">
    </form>
    </p>
@endsection