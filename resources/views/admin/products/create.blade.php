@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
<h2 class="mb-4">Thêm sản phẩm</h2>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.products._form')

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection