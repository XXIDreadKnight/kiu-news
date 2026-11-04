<div class="field">
    <label for="title">Title</label>
    <input type="text" id="title" name="title"
           value="{{ old('title', $post->title) }}">
    @error('title') <span class="error">{{ $message }}</span> @enderror
</div>

<div class="field">
    <label for="author">Author</label>
    <input type="text" id="author" name="author"
           value="{{ old('author', $post->author) }}">
    @error('author') <span class="error">{{ $message }}</span> @enderror
</div>

<div class="field">
    <label for="category">Category</label>
    <select id="category" name="category">
        @foreach ($categories as $option)
            <option value="{{ $option }}"
                    @selected(old('category', $post->category) === $option)>
                {{ $option }}
            </option>
        @endforeach
    </select>
    @error('category') <span class="error">{{ $message }}</span> @enderror
</div>

<div class="field">
    <label for="excerpt">Excerpt — the short text on the list page</label>
    <input type="text" id="excerpt" name="excerpt"
           value="{{ old('excerpt', $post->excerpt) }}">
    @error('excerpt') <span class="error">{{ $message }}</span> @enderror
</div>

<div class="field">
    <label for="body">Body</label>
    <textarea id="body" name="body" rows="8">{{ old('body', $post->body) }}</textarea>
    @error('body') <span class="error">{{ $message }}</span> @enderror
</div>

<div class="field check">
    <input type="checkbox" id="is_published" name="is_published" value="1"
           @checked($errors->any() ? old('is_published') : $post->is_published)>
    <label for="is_published">Publish immediately</label>
</div>
