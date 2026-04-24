@extends('layouts.admin')

@section('title', 'Sửa danh mục')

@section('content')
<h2 class="mb-4">Sửa danh mục</h2>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.categories._form')

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection