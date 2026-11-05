<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public const CATEGORIES = [
        'announcements',
        'research',
        'student-life',
        'events',
    ];

    protected $fillable = [
        'title',
        'author',
        'category',
        'excerpt',
        'body',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'date',
    ];

    /**
     * Task 5 (10 pts): Reading time accessor.
     * Calculates reading time assuming 200 words per minute, minimum 1 min.
     */
    public function getReadingTimeAttribute(): string
    {
        $wordCount = str_word_count(strip_tags($this->body ?? ''));
        $minutes = max(1, (int) ceil($wordCount / 200));

        return "{$minutes} min read";
    }
}
