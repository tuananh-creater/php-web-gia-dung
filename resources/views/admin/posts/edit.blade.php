@extends('layouts.admin')

@section('title', 'Sửa bài viết')

@section('content')
<h2 class="mb-4">Sửa bài viết</h2>

<form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.posts._form')

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection