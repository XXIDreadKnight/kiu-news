<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — @yield('title', 'Posts')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin">

    <div class="topbar">
        <span class="brand">KIU News — Admin</span>
        <nav>
            <a href="{{ route('admin.posts.index') }}">Posts</a>
            <a href="{{ route('posts.index') }}">View site</a>
        </nav>
    </div>

    <div class="wrap">
        @include('partials.flash')
        @yield('content')
    </div>

</body>
</html>
