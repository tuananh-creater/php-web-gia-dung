<div class="mb-3">
    <label class="form-label">Tiêu đề bài viết</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? '') }}">
    @error('title')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Ảnh bài viết</label>
    <input type="file" name="image" class="form-control">
    @error('image')
        <small class="text-danger">{{ $message }}</small>
    @enderror

    @if(!empty($post?->image))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $post->image) }}" width="160" alt="{{ $post->title }}">
        </div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Tóm tắt</label>
    <textarea name="summary" rows="4" class="form-control">{{ old('summary', $post->summary ?? '') }}</textarea>
    @error('summary')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Nội dung</label>
    <textarea name="content" rows="8" class="form-control">{{ old('content', $post->content ?? '') }}</textarea>
    @error('content')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Trạng thái</label>
    <select name="status" class="form-select">
        <option value="1" {{ old('status', $post->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
        <option value="0" {{ old('status', $post->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
    </select>
    @error('status')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>