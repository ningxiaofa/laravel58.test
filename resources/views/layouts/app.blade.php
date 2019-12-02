<!-- 存放在 resources/views/layouts/app.blade.php -->

<html>
<head>
    <title>Roast - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
@section('sidebar')
    这里是侧边栏
@show

<div class="container">
    @yield('content')
</div>
</body>
</html>