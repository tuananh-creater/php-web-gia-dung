@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')
<h2 class="mb-4">Sửa sản phẩm</h2>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.products._form')

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection