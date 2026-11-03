@extends('layouts.public')

@section('title', $post->title)

@section('content')

    <p><a href="{{ route('posts.index') }}">&larr; All news</a></p>

    <article class="card">
        <p class="meta">
            {{-- Task 2: Category link --}}
            <a href="{{ route('posts.category', $post->category) }}" style="color: #64748b; font-weight: 600; text-decoration: none;">
                {{ $post->category }}
            </a> &middot;
            {{ $post->published_at ? $post->published_at->format('d M Y') : '—' }} &middot;
            {{-- Task 5: Reading time --}}
            <span style="color: #0f766e; font-weight: 500;">{{ $post->reading_time }}</span> &middot;
            {{ $post->author }}
        </p>

        <h1>{{ $post->title }}</h1>

        <p class="lead">{{ $post->excerpt }}</p>

        <div class="body">{!! nl2br(e($post->body)) !!}</div>
    </article>

@endsection
