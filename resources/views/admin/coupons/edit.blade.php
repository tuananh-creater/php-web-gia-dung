@extends('layouts.admin')

@section('title', 'Sửa mã giảm giá')

@section('content')
<h2 class="mb-4">Sửa mã giảm giá</h2>

<form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
    @csrf
    @method('PUT')
    @include('admin.coupons._form')

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection