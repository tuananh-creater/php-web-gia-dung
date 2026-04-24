@extends('layouts.admin')

@section('title', 'Thêm bài viết')

@section('content')
<h2 class="mb-4">Thêm bài viết</h2>

<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.posts._form')

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection