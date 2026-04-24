@extends('layouts.admin')

@section('title', 'Sửa banner')

@section('content')
<h2 class="mb-4">Sửa banner</h2>

<form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.banners._form')

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection