@extends('layouts.admin')

@section('title', 'Posts')

@section('content')

    <div class="bar">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="row">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   placeholder="Title or author">

            <select name="status">
                <option value="">All</option>
                <option value="published" @selected(($status ?? '') === 'published')>Published</option>
                <option value="draft" @selected(($status ?? '') === 'draft')>Drafts</option>
            </select>

            {{-- Preserve current sort in filter form --}}
            @if (!empty($sort))
                <input type="hidden" name="sort" value="{{ $sort }}">
                <input type="hidden" name="dir" value="{{ $dir }}">
            @endif

            <button type="submit">Filter</button>
            <a class="btn-light" href="{{ route('admin.posts.index') }}">Reset</a>
        </form>

        <a class="btn" href="{{ route('admin.posts.create') }}">+ New post</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    {{-- Task 3: Sortable Title --}}
                    <th>
                        <a href="{{ route('admin.posts.index', array_merge(request()->query(), [
                            'sort' => 'title',
                            'dir' => ($sort === 'title' && $dir === 'asc') ? 'desc' : 'asc'
                        ])) }}">
                            Title
                            @if ($sort === 'title')
                                <span>{{ $dir === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>Category</th>
                    <th>Status</th>
                    {{-- Task 3: Sortable Published date --}}
                    <th>
                        <a href="{{ route('admin.posts.index', array_merge(request()->query(), [
                            'sort' => 'published_at',
                            'dir' => ($sort === 'published_at' && $dir === 'asc') ? 'desc' : 'asc'
                        ])) }}">
                            Published
                            @if ($sort === 'published_at')
                                <span>{{ $dir === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>
                            {{ $post->title }}
                            <br><small>{{ $post->author }}</small>
                        </td>
                        <td>{{ $post->category }}</td>
                        <td>
                            @if ($post->is_published)
                                <span class="badge green">Published</span>
                            @else
                                <span class="badge grey">Draft</span>
                            @endif
                        </td>
                        <td>
                            @if ($post->published_at)
                                {{ $post->published_at->format('d M Y') }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="actions">
                            {{-- Step 13: Publish/Unpublish form --}}
                            <form method="POST" action="{{ route('admin.posts.publish', $post) }}">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-light">
                                    {{ $post->is_published ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>

                            {{-- Task 4: Duplicate button --}}
                            <form method="POST" action="{{ route('admin.posts.duplicate', $post) }}">
                                @csrf
                                <button type="submit" class="btn-light">Duplicate</button>
                            </form>

                            {{-- Step 11: Edit link --}}
                            <a class="btn-light" href="{{ route('admin.posts.edit', $post) }}">Edit</a>

                            {{-- Step 12: Delete form --}}
                            <form method="POST"
                                  action="{{ route('admin.posts.destroy', $post) }}"
                                  onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">No posts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $posts])

@endsection
