<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Mã giảm giá</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code ?? '') }}">
        @error('code')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Tiêu đề</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $coupon->title ?? '') }}">
        @error('title')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Loại giảm giá</label>
        <select name="discount_type" class="form-select">
            <option value="fixed" {{ old('discount_type', $coupon->discount_type ?? 'fixed') == 'fixed' ? 'selected' : '' }}>Tiền cố định</option>
            <option value="percent" {{ old('discount_type', $coupon->discount_type ?? 'fixed') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
        </select>
        @error('discount_type')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Giá trị giảm</label>
        <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value ?? '') }}">
        @error('discount_value')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Đơn tối thiểu</label>
        <input type="number" step="0.01" name="min_order_value" class="form-control" value="{{ old('min_order_value', $coupon->min_order_value ?? 0) }}">
        @error('min_order_value')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Ngày hết hạn</label>
        <input type="date" name="expired_at" class="form-control"
               value="{{ old('expired_at', !empty($coupon?->expired_at) ? $coupon->expired_at->format('Y-m-d') : '') }}">
        @error('expired_at')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
            <option value="1" {{ old('status', $coupon->status ?? 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ old('status', $coupon->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
        </select>
        @error('status')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>