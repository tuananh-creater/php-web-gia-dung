<div class="mb-3">
    <label class="form-label">Tiêu đề</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title ?? '') }}">
    @error('title')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Phụ đề</label>
    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $banner->subtitle ?? '') }}">
    @error('subtitle')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Ảnh banner</label>
    <input type="file" name="image" class="form-control">
    @error('image')
        <small class="text-danger">{{ $message }}</small>
    @enderror

    @if(!empty($banner?->image))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $banner->image) }}" width="180" alt="{{ $banner->title }}">
        </div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Link</label>
    <input type="text" name="link" class="form-control" value="{{ old('link', $banner->link ?? '') }}">
    @error('link')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Thứ tự hiển thị</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order ?? 0) }}">
        @error('sort_order')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
            <option value="1" {{ old('status', $banner->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ old('status', $banner->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
        </select>
        @error('status')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>