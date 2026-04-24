@extends('layouts.admin')

@section('title', 'Thêm danh mục')

@section('content')
<h2 class="mb-4">Thêm danh mục</h2>

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.categories._form')

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection