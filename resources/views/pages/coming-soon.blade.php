@extends('layouts.app')

@section('title', $title)

@section('content')
<section class="section-space">
    <div class="container">
        <div class="coming-box text-center">
            <span class="section-kicker">Đang phát triển</span>
            <h1>{{ $title }}</h1>
            <p>{{ $description ?? 'Nội dung đang được cập nhật.' }}</p>
            <a href="{{ route('home') }}" class="btn btn-theme mt-3">Quay về trang chủ</a>
        </div>
    </div>
</section>
@endsection