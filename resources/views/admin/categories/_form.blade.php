<div class="mb-3">
    <label class="form-label">Tên danh mục</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}">
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Ảnh danh mục</label>
    <input type="file" name="image" class="form-control">
    @error('image')
        <small class="text-danger">{{ $message }}</small>
    @enderror

    @if(!empty($category?->image))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $category->image) }}" width="120" alt="{{ $category->name }}">
        </div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="description" rows="4" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Trạng thái</label>
    <select name="status" class="form-select">
        <option value="1" {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
        <option value="0" {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
    </select>
    @error('status')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>