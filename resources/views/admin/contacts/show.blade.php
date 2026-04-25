@extends('layouts.admin')

@section('title', 'Chi tiết liên hệ #' . $contact->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1">Chi tiết liên hệ #{{ $contact->id }}</h2>
        <p class="text-muted mb-0">Xem và cập nhật trạng thái liên hệ.</p>
    </div>

    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="content-box">
            <h4 class="mb-3">Thông tin người gửi</h4>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Họ tên:</strong>
                    <div>{{ $contact->name }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email:</strong>
                    <div>{{ $contact->email }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Số điện thoại:</strong>
                    <div>{{ $contact->phone ?: 'Không có' }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Ngày gửi:</strong>
                    <div>{{ $contact->created_at->format('d/m/Y H:i') }}</div>
                </div>

                <div class="col-12 mb-3">
                    <strong>Chủ đề:</strong>
                    <div>{{ $contact->subject ?: 'Không có chủ đề' }}</div>
                </div>

                <div class="col-12">
                    <strong>Nội dung:</strong>
                    <div class="mt-2 p-3 bg-light rounded">
                        {!! nl2br(e($contact->message)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-box">
            <h4 class="mb-3">Cập nhật xử lý</h4>

            <form action="{{ route('admin.contacts.update', $contact) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $contact->status) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi chú nội bộ</label>
                    <textarea name="admin_note" rows="4" class="form-control">{{ old('admin_note', $contact->admin_note) }}</textarea>
                    @error('admin_note')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nội dung phản hồi qua email</label>
                    <textarea name="reply_message" rows="6" class="form-control">{{ old('reply_message', $contact->reply_message) }}</textarea>
                    @error('reply_message')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="send_reply" value="1" id="send_reply">
                    <label class="form-check-label" for="send_reply">
                        Gửi email phản hồi cho khách ngay bây giờ
                    </label>
                </div>

                <button class="btn btn-primary w-100">
                    <i class="bi bi-check2-circle me-1"></i> Lưu cập nhật
                </button>
            </form>

            @if($contact->admin_note)
                <hr>
                <h6 class="fw-bold">Ghi chú hiện tại</h6>
                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($contact->admin_note)) !!}
                </div>
            @endif

            @if($contact->reply_message)
                <hr>
                <h6 class="fw-bold">Phản hồi đã lưu</h6>
                <div class="p-3 bg-light rounded mb-2">
                    {!! nl2br(e($contact->reply_message)) !!}
                </div>

                @if($contact->replied_at)
                    <small class="text-muted">Đã gửi lúc: {{ $contact->replied_at->format('d/m/Y H:i') }}</small>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection