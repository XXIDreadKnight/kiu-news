# KIU News Portal - Week 7 News Lab & Assignment

A full-featured News & Editorial application built with **Laravel 12** and **MySQL** for the Kutaisi International University (KIU) Web Programming Course (Lecturer: Assoc. Prof. Lasha Iashvili).

This project implements all core lab instructions (Steps 1–16) and all extended assignment bonus tasks (**Tasks 1 through 6**, yielding the full 10 bonus points).

---

## Features & Implementation Overview

### 1. Base Lab Implementation (Steps 1–16)
- **Database Schema & Migrations (`posts` table)**:
  - Columns: `id`, `title`, `slug` (unique), `category` (enum: *academic, research, campus, student-life, events*), `excerpt`, `body`, `is_published` (boolean default false), `published_at` (nullable datetime), `created_at`, `updated_at`.
- **Eloquent Model (`App\Models\Post`)**:
  - `$fillable` fields, `$casts` for `is_published` (boolean) and `published_at` (datetime).
  - Validation constants `CATEGORIES`.
- **Database Seeder (`database/seeders/PostSeeder.php`)**:
  - 20 realistic KIU campus articles (16 published across various dates/categories, 4 draft articles).
- **Public Reader Interface (`/` & `/posts/{post}`)**:
  - Clean, modern layout (`layouts/public.blade.php`).
  - Search filter by keyword (`title`, `excerpt`, `body`) and category selector.
  - Published-only scope: drafts are never listed on the public feed.
  - Draft privacy guard: accessing an unpublished post directly via URL throws a 404 response.
  - Pagination (6 posts per page) preserving filter parameters via `withQueryString()`.
- **Admin Management Portal (`/admin/posts`)**:
  - Professional editorial dashboard (`layouts/admin.blade.php`).
  - Complete CRUD:
    - Create post (`/admin/posts/create`) with validation and automatic slug generation from title.
    - Edit post (`/admin/posts/{post}/edit`).
    - Delete post with confirmation dialog.
    - Single-click Publish / Unpublish toggle button (`PATCH /admin/posts/{post}/publish`).
  - Draft badges (`Draft` vs `Published`).
  - Status filter dropdown (All, Published, Draft).
  - Flash notifications on every editorial action.

---

### 2. Assignment Tasks (All Completed for +10 Points)

| Task | Feature | Implementation Details |
|---|---|---|
| **Task 1** | **Base Lab Complete** | All 16 steps completed, fully functional, 100% test coverage. |
| **Task 2** | **Category Archive Pages** | URL route `/category/{category}` filtered to that category. Invalid categories return a proper 404 error. Category badges across all cards are clickable links. |
| **Task 3** | **Sortable Admin Columns** | Clickable `Title` and `Published` table headers on `/admin/posts`. Clicking toggles between ascending (`▲`) and descending (`▼`), keeping current search and status filters intact. |
| **Task 4** | **Duplicate Post** | One-click "Duplicate" button in admin table (`POST /admin/posts/{post}/duplicate`). Creates an exact clone prefixed with `[Copy]`, generates a unique slug, sets status to `draft`, clears `published_at`, and redirects to edit screen with a success banner. |
| **Task 5** | **Reading Time Estimate** | Eloquent accessor `$post->reading_time` calculating estimated reading time based on 200 words per minute (e.g. `2 min read`, `1 min read`). Rendered on both index article cards and the full article page. |
| **Task 6** | **Archive by Year** | Route `/archive/{year}` showing published posts from a specific year. Includes year navigation filter bar dynamically querying all published years in the database. |

---

## Database Configuration

The application is configured to connect to MySQL running on `127.0.0.1:3306`:

- **Database Name**: `kiu_news`
- **Username**: `root`
- **Password**: *(empty by default in XAMPP)*

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kiu_news
DB_USERNAME=root
DB_PASSWORD=
```

---

## Quick Start & Verification

### 1. Requirements
- PHP 8.2+
- Composer
- MySQL (XAMPP / MariaDB)

### 2. Setup Database & Seed
```bash
# Run migrations and seed sample posts
php artisan migrate:fresh --seed
```

### 3. Run Development Server
```bash
php artisan serve
```
Open **[http://127.0.0.1:8000](http://127.0.0.1:8000)** in your web browser.

---

## Route Overview

### Public Routes
- `GET /` — Public news homepage with search, category filtering, reading times, and year archive chips.
- `GET /posts/{post}` — Full post reading view (drafts return 404).
- `GET /category/{category}` — Category archive (e.g., `/category/research`, `/category/campus`).
- `GET /archive/{year}` — Year archive (e.g., `/archive/2026`, `/archive/2025`).

### Admin Routes
- `GET /admin/posts` — Editorial dashboard with sortable columns (`?sort=title&dir=asc`), status filter, draft badges, and duplicate buttons.
- `GET /admin/posts/create` — Form to create a new article.
- `POST /admin/posts` — Store article.
- `GET /admin/posts/{post}/edit` — Edit existing article.
- `PUT /admin/posts/{post}` — Update article.
- `DELETE /admin/posts/{post}` — Delete article.
- `PATCH /admin/posts/{post}/publish` — Quick toggle published / draft state.
- `POST /admin/posts/{post}/duplicate` — Clone existing article as a draft.

---

## Running the Automated Test Suite

A comprehensive automated test suite covers every requirement:

```bash
php artisan test
```

### Test Coverage Summary:
- `✓ the application returns a successful response`
- `✓ public index shows only published posts`
- `✓ draft cannot be viewed by address (404 guard)`
- `✓ admin shows drafts with badge`
- `✓ publish and unpublish toggle`
- `✓ public search and category combined`
- `✓ task2 category page and 404 guard`
- `✓ task3 sortable admin columns`
- `✓ task4 duplicate post`
- `✓ task5 reading time accessor`
- `✓ task6 archive by year`

All **12 tests and 34 assertions** pass cleanly (100% green).
