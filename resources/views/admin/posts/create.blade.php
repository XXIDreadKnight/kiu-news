@extends('layouts.admin')

@section('title', 'New post')

@section('content')

    <p><a href="{{ route('admin.posts.index') }}">&larr; All posts</a></p>

    <h2>New post</h2>

    <form method="POST" action="{{ route('admin.posts.store') }}" class="card">
        @csrf

        @include('admin.posts._form')

        <button type="submit">Create post</button>
    </form>

@endsection
