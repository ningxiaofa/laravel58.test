<!-- 存放在 resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', 'Laravel学院')

@section('sidebar')
@parent
<p>登录系统之后, 更新数据...</p>
@endsection

@section('content')
<p>
<form action="/redis/update" method="post">
    @csrf {{-- 或者 {{csrf_field()}} --}}
    <lable>id</lable>
    <input type="text" name="id">
    <lable>content</lable>
    <input type="text" name="content">
    <input type="submit" value="submit">
</form>
</p>
@endsection