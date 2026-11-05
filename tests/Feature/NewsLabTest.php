<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsLabTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1 & 14 checks: Public home lists published posts, 5 per page.
     */
    public function test_public_index_shows_only_published_posts(): void
    {
        Post::create([
            'title'        => 'Published Article',
            'author'       => 'Reporter',
            'category'     => 'research',
            'excerpt'      => 'Published excerpt...',
            'body'         => 'This is the full text of the article with enough words.',
            'is_published' => true,
            'published_at' => now()->toDateString(),
        ]);

        Post::create([
            'title'        => 'Draft Article',
            'author'       => 'Reporter',
            'category'     => 'research',
            'excerpt'      => 'Draft excerpt...',
            'body'         => 'Unpublished draft body.',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertSee('Published Article');
        $response->assertDontSee('Draft Article');
    }

    /**
     * Test 3: Opening draft by URL returns 404.
     */
    public function test_draft_cannot_be_viewed_by_address(): void
    {
        $draft = Post::create([
            'title'        => 'Secret Upcoming Announcement',
            'author'       => 'Admin',
            'category'     => 'announcements',
            'excerpt'      => 'Secret excerpt...',
            'body'         => 'Secret body...',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get(route('posts.show', $draft));
        $response->assertStatus(404);
    }

    /**
     * Test 4: Admin shows all posts including drafts.
     */
    public function test_admin_shows_drafts_with_badge(): void
    {
        $draft = Post::create([
            'title'        => 'Winter concert in the main auditorium',
            'author'       => 'Salome Kapanadze',
            'category'     => 'events',
            'excerpt'      => 'Winter excerpt...',
            'body'         => 'Winter body...',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get(route('admin.posts.index'));

        $response->assertStatus(200);
        $response->assertSee('Winter concert in the main auditorium');
        $response->assertSee('Draft');
    }

    /**
     * Test 6 & 7: Publish and Unpublish toggle.
     */
    public function test_publish_and_unpublish_toggle(): void
    {
        $post = Post::create([
            'title'        => 'Hackathon 2026',
            'author'       => 'Organizer',
            'category'     => 'events',
            'excerpt'      => 'Hackathon excerpt...',
            'body'         => 'Hackathon body...',
            'is_published' => false,
            'published_at' => null,
        ]);

        // Publish
        $this->put(route('admin.posts.publish', $post));
        $post->refresh();
        $this->assertTrue($post->is_published);
        $this->assertNotNull($post->published_at);

        // Unpublish
        $this->put(route('admin.posts.publish', $post));
        $post->refresh();
        $this->assertFalse($post->is_published);
        $this->assertNull($post->published_at);
    }

    /**
     * Test 11 & 12: Public search by keyword and category combined.
     */
    public function test_public_search_and_category_combined(): void
    {
        Post::create([
            'title'        => 'Robotics team publishes a paper',
            'author'       => 'Nika Beridze',
            'category'     => 'research',
            'excerpt'      => 'Excerpt...',
            'body'         => 'Body...',
            'is_published' => true,
            'published_at' => '2026-09-15',
        ]);

        Post::create([
            'title'        => 'Robotics competition hosted at KIU',
            'author'       => 'Nika Beridze',
            'category'     => 'events',
            'excerpt'      => 'Excerpt...',
            'body'         => 'Body...',
            'is_published' => true,
            'published_at' => '2026-10-24',
        ]);

        // Search robotics only -> 2 results
        $resAll = $this->get(route('posts.index', ['search' => 'robotics']));
        $resAll->assertSee('Robotics team publishes a paper');
        $resAll->assertSee('Robotics competition hosted at KIU');

        // Search robotics + category events -> 1 result
        $resEvents = $this->get(route('posts.index', ['search' => 'robotics', 'category' => 'events']));
        $resEvents->assertDontSee('Robotics team publishes a paper');
        $resEvents->assertSee('Robotics competition hosted at KIU');
    }

    /**
     * Task 2: Category page with 404 for invalid category.
     */
    public function test_task2_category_page_and_404_guard(): void
    {
        Post::create([
            'title'        => 'Research Discovery',
            'author'       => 'Researcher',
            'category'     => 'research',
            'excerpt'      => 'Discovery excerpt...',
            'body'         => 'Discovery body...',
            'is_published' => true,
            'published_at' => now()->toDateString(),
        ]);

        $responseValid = $this->get(route('posts.category', 'research'));
        $responseValid->assertStatus(200);
        $responseValid->assertSee('Category: Research');
        $responseValid->assertSee('Research Discovery');

        $responseInvalid = $this->get('/category/fake-category');
        $responseInvalid->assertStatus(404);
    }

    /**
     * Task 3: Sortable admin columns with allow-list.
     */
    public function test_task3_sortable_admin_columns(): void
    {
        Post::create([
            'title'        => 'Zebra Announcement',
            'author'       => 'Staff',
            'category'     => 'announcements',
            'excerpt'      => 'Zebra...',
            'body'         => 'Body...',
            'is_published' => true,
            'published_at' => now()->toDateString(),
        ]);

        Post::create([
            'title'        => 'Alpha Announcement',
            'author'       => 'Staff',
            'category'     => 'announcements',
            'excerpt'      => 'Alpha...',
            'body'         => 'Body...',
            'is_published' => true,
            'published_at' => now()->toDateString(),
        ]);

        $resAsc = $this->get(route('admin.posts.index', ['sort' => 'title', 'dir' => 'asc']));
        $resAsc->assertStatus(200);
        // Alpha should appear before Zebra in HTML
        $this->assertTrue(strpos($resAsc->getContent(), 'Alpha Announcement') < strpos($resAsc->getContent(), 'Zebra Announcement'));
    }

    /**
     * Task 4: Duplicate post clones as draft with (copy) appended.
     */
    public function test_task4_duplicate_post(): void
    {
        $original = Post::create([
            'title'        => 'Original Masterpiece',
            'author'       => 'Editor',
            'category'     => 'research',
            'excerpt'      => 'Excerpt...',
            'body'         => 'Body content...',
            'is_published' => true,
            'published_at' => '2026-10-10',
        ]);

        $response = $this->post(route('admin.posts.duplicate', $original));

        $copy = Post::where('title', 'Original Masterpiece (copy)')->first();
        $this->assertNotNull($copy);
        $this->assertFalse($copy->is_published);
        $this->assertNull($copy->published_at);
        $response->assertRedirect(route('admin.posts.edit', $copy));
    }

    /**
     * Task 5: Reading time accessor on model.
     */
    public function test_task5_reading_time_accessor(): void
    {
        // 450 words = 3 min read (ceil(450/200) = 3)
        $longBody = implode(' ', array_fill(0, 450, 'word'));

        $post = new Post(['body' => $longBody]);
        $this->assertEquals('3 min read', $post->reading_time);

        // Short body (10 words) = min 1 min read
        $shortPost = new Post(['body' => 'Short text.']);
        $this->assertEquals('1 min read', $shortPost->reading_time);
    }

    /**
     * Task 6: Archive by year filters published posts of that year.
     */
    public function test_task6_archive_by_year(): void
    {
        Post::create([
            'title'        => 'Article from 2025',
            'author'       => 'Historian',
            'category'     => 'events',
            'excerpt'      => 'Old excerpt...',
            'body'         => 'Old body...',
            'is_published' => true,
            'published_at' => '2025-05-12',
        ]);

        Post::create([
            'title'        => 'Article from 2026',
            'author'       => 'Reporter',
            'category'     => 'events',
            'excerpt'      => 'New excerpt...',
            'body'         => 'New body...',
            'is_published' => true,
            'published_at' => '2026-05-12',
        ]);

        $response = $this->get(route('posts.archive', 2025));
        $response->assertStatus(200);
        $response->assertSee('Archive: Year 2025');
        $response->assertSee('Article from 2025');
        $response->assertDontSee('Article from 2026');
    }
}
