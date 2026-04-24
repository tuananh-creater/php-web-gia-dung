<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Danh mục</label>
        <select name="category_id" class="form-select">
            <option value="">-- Chọn danh mục --</option>
            @foreach($categories as $item)
                <option value="{{ $item->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}">
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Giá nhập</label>
        <input type="number" step="0.01" name="cost_price" class="form-control"
            value="{{ old('cost_price', $product->cost_price ?? '') }}">
        @error('cost_price')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Giá bán</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}">
        @error('price')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Giá khuyến mãi</label>
        <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price ?? '') }}">
        @error('sale_price')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Số lượng</label>
        <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity ?? 0) }}">
        @error('quantity')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Ảnh sản phẩm</label>
        <input type="file" name="image" class="form-control">
        @error('image')
            <small class="text-danger">{{ $message }}</small>
        @enderror

        @if(!empty($product?->image))
            <div class="mt-2">
                <img src="{{ asset('storage/' . $product->image) }}" width="120" alt="{{ $product->name }}">
            </div>
        @endif
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Nổi bật</label>
        <select name="is_featured" class="form-select">
            <option value="1" {{ old('is_featured', $product->is_featured ?? 0) == 1 ? 'selected' : '' }}>Có</option>
            <option value="0" {{ old('is_featured', $product->is_featured ?? 0) == 0 ? 'selected' : '' }}>Không</option>
        </select>
        @error('is_featured')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
            <option value="1" {{ old('status', $product->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ old('status', $product->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
        </select>
        @error('status')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Mô tả ngắn</label>
        <textarea name="short_description" rows="3" class="form-control">{{ old('short_description', $product->short_description ?? '') }}</textarea>
        @error('short_description')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Mô tả chi tiết</label>
        <textarea name="description" rows="6" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>