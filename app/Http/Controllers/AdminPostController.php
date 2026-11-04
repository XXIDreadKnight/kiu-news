<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPostController extends Controller
{
    /**
     * Display a listing of posts in the admin panel with search, status filter, and Task 3 sortable columns.
     */
    public function index(Request $request): View
    {
        $search = trim($request->query('search', ''));
        $status = $request->query('status', '');

        // Task 3: Sortable admin columns with allow-list validation
        $allowedSorts = ['id', 'title', 'published_at'];
        $sort = in_array($request->query('sort'), $allowedSorts, true) ? $request->query('sort') : 'id';
        $dir = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $posts = Post::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('author', 'like', '%' . $search . '%');
                });
            })
            ->when($status === 'published', function ($query) {
                $query->where('is_published', true);
            })
            ->when($status === 'draft', function ($query) {
                $query->where('is_published', false);
            })
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts'  => $posts,
            'search' => $search,
            'status' => $status,
            'sort'   => $sort,
            'dir'    => $dir,
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'post'       => new Post(),
            'categories' => Post::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Post::create($this->validated($request));

        return redirect()->route('admin.posts.index')
                         ->with('message', 'Post created.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'post'       => $post,
            'categories' => Post::CATEGORIES,
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $post->update($this->validated($request));

        return redirect()->route('admin.posts.index')
                         ->with('message', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')
                         ->with('message', 'Post deleted.');
    }

    public function publish(Post $post): RedirectResponse
    {
        $post->update([
            'is_published' => ! $post->is_published,
            'published_at' => $post->is_published ? null : now()->toDateString(),
        ]);

        return back()->with('message',
            $post->is_published ? 'Post published.' : 'Post moved to drafts.');
    }

    /**
     * Task 4: Duplicate a post as a draft with (copy) appended to title.
     */
    public function duplicate(Post $post): RedirectResponse
    {
        $copy = $post->replicate();
        $copy->title = $post->title . ' (copy)';
        $copy->is_published = false;
        $copy->published_at = null;
        $copy->save();

        return redirect()->route('admin.posts.edit', $copy)
                         ->with('message', 'Post duplicated as a draft.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'max:150'],
            'author'   => ['required', 'string', 'max:80'],
            'category' => ['required', 'string', 'max:40'],
            'excerpt'  => ['required', 'string', 'max:255'],
            'body'     => ['required', 'string'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now()->toDateString() : null;

        return $data;
    }
}
