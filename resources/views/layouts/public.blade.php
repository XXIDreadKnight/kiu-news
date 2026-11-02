<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KIU News — @yield('title', 'Latest')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="public">

    <div class="topbar">
        <span class="brand">KIU News</span>
        <nav>
            <a href="{{ route('posts.index') }}">News</a>
            <a href="{{ route('admin.posts.index') }}">Admin</a>
        </nav>
    </div>

    <div class="wrap">
        @include('partials.flash')
        @yield('content')
    </div>

</body>
</html>
