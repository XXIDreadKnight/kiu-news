# KIU News Lab & Assignment - Verification & Grading Guide

**Course**: Web Programming with Laravel  
**University**: Kutaisi International University (KIU)  
**Lecturer**: Assoc. Prof. Lasha Iashvili  
**Target Score**: Base Lab (Steps 1–16) + Bonus Tasks 1–6 (+10 Points) = **100% Full Score**

---

## 1. Quick Verification URLs (When Running `php artisan serve`)

Ensure XAMPP MySQL is running, then run `php artisan serve` inside `kiu-news`:

| Feature / Task | URL to Visit | What to Look For |
|---|---|---|
| **Public Homepage** | `http://127.0.0.1:8000/` | - Shows published news posts only.<br>- Search bar + Category filter dropdown.<br>- Reading time badge on each card (e.g. `1 min read`).<br>- Dynamic year archive bar at the top (e.g. `2026`, `2025`).<br>- Pagination with query string preservation. |
| **Full Article View** | `http://127.0.0.1:8000/posts/1` | - Clean reading typography.<br>- Category badge, publication date, reading time.<br>- Back button returning to feed. |
| **Draft Security Guard** | `http://127.0.0.1:8000/posts/17` | - Returns **404 Not Found** because post #17 is an unpublished draft. |
| **Task 2: Category Filter** | `http://127.0.0.1:8000/category/research` | - Shows only Research articles.<br>- Banner: *"Category: Research"*.<br>- Invalid category like `/category/foobar` returns 404. |
| **Task 6: Year Archive** | `http://127.0.0.1:8000/archive/2026` | - Shows only articles published in 2026.<br>- Banner: *"Archive: 2026"*. |
| **Admin Dashboard** | `http://127.0.0.1:8000/admin/posts` | - Dark-themed admin navbar with "View Public Site" link.<br>- Displays all posts (both published and drafts).<br>- Distinct **Draft** (gray) vs **Published** (green) badges.<br>- Status filter dropdown (All / Published / Draft). |
| **Task 3: Sortable Columns** | `http://127.0.0.1:8000/admin/posts?sort=title&dir=asc` | - Click **Title** or **Published** header to sort.<br>- Shows ascending (`▲`) or descending (`▼`) arrows. |
| **Task 4: Duplicate Post** | In Admin Table, click **Duplicate** button | - Clones the article with title `[Copy] ...`.<br>- Slug automatically adjusted with random hash.<br>- Status set to `Draft` and published date cleared.<br>- Redirects directly to edit view with a flash alert. |
| **Toggle Publish** | In Admin Table, click **Unpublish** / **Publish** | - Instantly changes post status without form navigation.<br>- Sets/clears `published_at` timestamp. |
| **Create Article** | `http://127.0.0.1:8000/admin/posts/create` | - Fill title, category, excerpt, body, and publish checkbox.<br>- Slug automatically generated from title. |
| **Delete Article** | In Admin Table, click **Delete** | - Confirmation modal/prompt, permanently removes post. |

---

## 2. Automated Test Verification

To demonstrate automated test coverage to the lecturer:

```bash
php artisan test
```

Expected result:
```text
PASS  Tests\Unit\ExampleTest
PASS  Tests\Feature\ExampleTest
PASS  Tests\Feature\NewsLabTest
  ✓ public index shows only published posts
  ✓ draft cannot be viewed by address
  ✓ admin shows drafts with badge
  ✓ publish and unpublish toggle
  ✓ public search and category combined
  ✓ task2 category page and 404 guard
  ✓ task3 sortable admin columns
  ✓ task4 duplicate post
  ✓ task5 reading time accessor
  ✓ task6 archive by year

Tests:  12 passed (34 assertions)
```

---

## 3. Database Schema in phpMyAdmin

1. Open `http://localhost/phpmyadmin` in your browser.
2. Select database `kiu_news`.
3. Check table `posts`:
   - Contains 20 seeded records.
   - 16 rows have `is_published = 1` with timestamps.
   - 4 rows have `is_published = 0` with `published_at = NULL`.
