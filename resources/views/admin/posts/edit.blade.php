@extends('layouts.admin')

@section('title', 'Edit post')

@section('content')

    <p><a href="{{ route('admin.posts.index') }}">&larr; All posts</a></p>

    <h2>Edit post #{{ $post->id }}</h2>

    <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="card">
        @csrf
        @method('PUT')

        @include('admin.posts._form')

        <button type="submit">Save changes</button>
    </form>

@endsection
