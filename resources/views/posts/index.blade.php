@extends('layouts.public')

@section('title', $heading ?? 'Latest')

@section('content')

    {{-- Task 6: Year Archive Menu --}}
    @if (!empty($archiveYears) && count($archiveYears) > 0)
        <div class="years-bar">
            <strong>Archive:</strong>
            @foreach ($archiveYears as $y)
                <a href="{{ route('posts.archive', $y) }}" @class(['active' => isset($year) && $year == $y])>
                    {{ $y }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Search and Category Filter --}}
    <form method="GET" action="{{ route('posts.index') }}" class="card row">
        <input type="text" name="search" class="grow" value="{{ $search ?? '' }}"
               placeholder="Search by title or author">

        <select name="category">
            <option value="">All categories</option>
            @foreach ($categories as $option)
                <option value="{{ $option }}" @selected(($category ?? '') === $option)>
                    {{ $option }}
                </option>
            @endforeach
        </select>

        <button type="submit">Search</button>
        <a class="btn-light" href="{{ route('posts.index') }}">Reset</a>
    </form>

    @if (isset($heading))
        <h2 style="margin: 0 0 16px;">{{ $heading }}</h2>
    @endif

    @forelse ($posts as $post)
        <article class="card">
            <p class="meta">
                {{-- Task 2: Category is a link --}}
                <a href="{{ route('posts.category', $post->category) }}" style="color: #64748b; font-weight: 600; text-decoration: none;">
                    {{ $post->category }}
                </a> &middot;
                {{ $post->published_at ? $post->published_at->format('d M Y') : '—' }} &middot;
                {{-- Task 5: Reading time --}}
                <span style="color: #0f766e; font-weight: 500;">{{ $post->reading_time }}</span> &middot;
                {{ $post->author }}
            </p>

            <h2>
                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
            </h2>

            <p>{{ $post->excerpt }}</p>

            <a class="more" href="{{ route('posts.show', $post) }}">Read more &rarr;</a>
        </article>
    @empty
        <p class="card empty">No posts found.</p>
    @endforelse

    @include('partials.pagination', ['paginator' => $posts])

@endsection
