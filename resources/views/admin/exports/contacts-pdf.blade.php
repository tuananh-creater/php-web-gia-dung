<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách liên hệ</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 8px; }
        .meta { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; vertical-align: top; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>DANH SÁCH LIÊN HỆ</h2>
    <div class="meta">Ngày xuất: {{ now()->format('d/m/Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Người gửi</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Chủ đề</th>
                <th>Trạng thái</th>
                <th>Ngày gửi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($contacts as $contact)
            <tr>
                <td>{{ $contact->id }}</td>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone }}</td>
                <td>{{ $contact->subject }}</td>
                <td>{{ $statuses[$contact->status] ?? $contact->status }}</td>
                <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>