@extends('layouts.admin')

@section('title', 'Thêm banner')

@section('content')
<h2 class="mb-4">Thêm banner</h2>

<form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.banners._form')

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection