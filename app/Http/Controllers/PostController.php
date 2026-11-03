<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Public news list with search and category filters.
     */
    public function index(Request $request): View
    {
        $search   = trim($request->query('search', ''));
        $category = $request->query('category', '');

        $posts = Post::where('is_published', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('author', 'like', '%' . $search . '%');
                });
            })
            ->when($category !== '', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->orderByDesc('published_at')
            ->paginate(5)
            ->withQueryString();

        return view('posts.index', [
            'posts'        => $posts,
            'search'       => $search,
            'category'     => $category,
            'categories'   => Post::CATEGORIES,
            'archiveYears' => $this->getArchiveYears(),
        ]);
    }

    /**
     * Show single article page (drafts return 404).
     */
    public function show(Post $post): View
    {
        abort_unless($post->is_published, 404);

        return view('posts.show', ['post' => $post]);
    }

    /**
     * Task 2: Dedicated category page with 404 guard and pagination.
     */
    public function category(string $category): View
    {
        abort_unless(in_array($category, Post::CATEGORIES, true), 404);

        $posts = Post::where('is_published', true)
            ->where('category', $category)
            ->orderByDesc('published_at')
            ->paginate(5)
            ->withQueryString();

        return view('posts.index', [
            'posts'        => $posts,
            'heading'      => 'Category: ' . ucfirst($category),
            'category'     => $category,
            'categories'   => Post::CATEGORIES,
            'archiveYears' => $this->getArchiveYears(),
        ]);
    }

    /**
     * Task 6 (Bonus): Year archive page.
     */
    public function archive(int $year): View
    {
        $posts = Post::where('is_published', true)
            ->whereYear('published_at', $year)
            ->orderByDesc('published_at')
            ->paginate(5)
            ->withQueryString();

        return view('posts.index', [
            'posts'        => $posts,
            'heading'      => "Archive: Year {$year}",
            'year'         => $year,
            'categories'   => Post::CATEGORIES,
            'archiveYears' => $this->getArchiveYears(),
        ]);
    }

    /**
     * Task 6: Dynamically retrieve years that have published posts.
     */
    private function getArchiveYears(): array
    {
        return Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->pluck('published_at')
            ->map(fn ($date) => is_string($date) ? date('Y', strtotime($date)) : $date->format('Y'))
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();
    }
}
