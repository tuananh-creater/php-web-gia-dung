@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">HomeKit</span>
            <h2>Liên hệ với chúng tôi</h2>
            <p>Hãy gửi câu hỏi, góp ý hoặc nhu cầu tư vấn của bạn. Chúng tôi sẽ phản hồi sớm nhất.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="content-box h-100">
                    <h3 class="mb-4">Thông tin liên hệ</h3>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Địa chỉ</h6>
                        <p class="text-muted mb-0">Hà Đông, Hà Nội</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Hotline</h6>
                        <p class="text-muted mb-0">1900 6750</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Email</h6>
                        <p class="text-muted mb-0">support@gmail.com</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Thời gian làm việc</h6>
                        <p class="text-muted mb-0">Thứ 2 - Chủ nhật: 08:00 - 21:00</p>
                    </div>

                    <div class="contact-highlight-box">
                        <h5 class="mb-2">Bạn cần hỗ trợ nhanh?</h5>
                        <p class="mb-0 text-muted">
                            Gửi thông tin qua form bên cạnh, đội ngũ HomeKit sẽ liên hệ lại để tư vấn sản phẩm, đơn hàng hoặc hợp tác.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="content-box">
                    <h3 class="mb-4">Gửi liên hệ</h3>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ old('name', auth()->user()->name ?? '') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ old('email', auth()->user()->email ?? '') }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text"
                                       name="phone"
                                       class="form-control"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Chủ đề</label>
                                <input type="text"
                                       name="subject"
                                       class="form-control"
                                       value="{{ old('subject') }}"
                                       placeholder="Ví dụ: Tư vấn sản phẩm">
                                @error('subject')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Nội dung</label>
                            <textarea name="message"
                                      rows="6"
                                      class="form-control"
                                      placeholder="Nhập nội dung liên hệ...">{{ old('message') }}</textarea>
                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button class="btn btn-theme">
                            <i class="bi bi-send me-1"></i> Gửi liên hệ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection