<?php

use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (KIU News)
|--------------------------------------------------------------------------
*/
Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Task 2: Category page
Route::get('/category/{category}', [PostController::class, 'category'])->name('posts.category');

// Task 6 (Bonus): Archive by year
Route::get('/archive/{year}', [PostController::class, 'archive'])->name('posts.archive');

/*
|--------------------------------------------------------------------------
| Admin Routes (KIU News Dashboard)
|--------------------------------------------------------------------------
*/
Route::get('/admin/posts', [AdminPostController::class, 'index'])->name('admin.posts.index');
Route::get('/admin/posts/create', [AdminPostController::class, 'create'])->name('admin.posts.create');
Route::post('/admin/posts', [AdminPostController::class, 'store'])->name('admin.posts.store');
Route::get('/admin/posts/{post}/edit', [AdminPostController::class, 'edit'])->name('admin.posts.edit');
Route::put('/admin/posts/{post}', [AdminPostController::class, 'update'])->name('admin.posts.update');
Route::delete('/admin/posts/{post}', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');
Route::put('/admin/posts/{post}/publish', [AdminPostController::class, 'publish'])->name('admin.posts.publish');

// Task 4: Duplicate post
Route::post('/admin/posts/{post}/duplicate', [AdminPostController::class, 'duplicate'])->name('admin.posts.duplicate');
