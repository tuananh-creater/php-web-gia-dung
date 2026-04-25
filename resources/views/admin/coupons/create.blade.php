@extends('layouts.admin')

@section('title', 'Thêm mã giảm giá')

@section('content')
<h2 class="mb-4">Thêm mã giảm giá</h2>

<form action="{{ route('admin.coupons.store') }}" method="POST">
    @csrf
    @include('admin.coupons._form')

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection