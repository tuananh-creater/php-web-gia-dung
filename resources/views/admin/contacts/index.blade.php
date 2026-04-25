@extends('layouts.admin')

@section('title', 'Quản lý liên hệ')

@section('content')

<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.exports.contacts.excel', request()->query()) }}" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-1"></i> Xuất Excel
    </a>

    <a href="{{ route('admin.exports.contacts.pdf', request()->query()) }}" class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf me-1"></i> Xuất PDF
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1">Quản lý liên hệ</h2>
        <p class="text-muted mb-0">Danh sách liên hệ gửi từ trang người dùng.</p>
    </div>
</div>

<form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-3 mb-4">
    <div class="col-md-6">
        <input type="text"
               name="keyword"
               class="form-control"
               value="{{ request('keyword') }}"
               placeholder="ID / họ tên / email / số điện thoại / chủ đề">
    </div>

    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">-- Tất cả trạng thái --</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-primary w-100">Lọc</button>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th width="80">ID</th>
                <th>Người gửi</th>
                <th>Liên hệ</th>
                <th>Chủ đề</th>
                <th>Trạng thái</th>
                <th>Ngày gửi</th>
                <th width="180">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse($contacts as $contact)
            <tr>
                <td>#{{ $contact->id }}</td>
                <td>{{ $contact->name }}</td>
                <td>
                    <div>{{ $contact->email }}</div>
                    <small class="text-muted">{{ $contact->phone ?: 'Không có SĐT' }}</small>
                </td>
                <td>{{ $contact->subject ?: 'Không có chủ đề' }}</td>
                <td>
                    @if($contact->status === 'new')
                        <span class="badge bg-danger">Mới</span>
                    @elseif($contact->status === 'read')
                        <span class="badge bg-warning text-dark">Đã đọc</span>
                    @else
                        <span class="badge bg-success">Đã phản hồi</span>
                    @endif
                </td>
                <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-dark btn-sm">
                        <i class="bi bi-eye"></i> Xem
                    </a>

                    <form action="{{ route('admin.contacts.destroy', $contact) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Chưa có liên hệ nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $contacts->links() }}
</div>
@endsection