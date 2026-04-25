<?php

namespace App\Exports;

use App\Models\Contact;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactsExport implements FromCollection, WithHeadings
{
    public function __construct(protected Request $request)
    {
    }

    public function collection()
    {
        $statuses = [
            'new' => 'Mới',
            'read' => 'Đã đọc',
            'replied' => 'Đã phản hồi',
        ];

        return Contact::query()
            ->when($this->request->filled('keyword'), function ($query) {
                $keyword = trim($this->request->keyword);

                $query->where(function ($subQuery) use ($keyword) {
                    if (is_numeric($keyword)) {
                        $subQuery->orWhere('id', $keyword);
                    }

                    $subQuery->orWhere('name', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%')
                        ->orWhere('phone', 'like', '%' . $keyword . '%')
                        ->orWhere('subject', 'like', '%' . $keyword . '%');
                });
            })
            ->when($this->request->filled('status'), function ($query) {
                $query->where('status', $this->request->status);
            })
            ->latest('id')
            ->get()
            ->map(function ($contact) use ($statuses) {
                return [
                    'ID' => $contact->id,
                    'Họ tên' => $contact->name,
                    'Email' => $contact->email,
                    'SĐT' => $contact->phone,
                    'Chủ đề' => $contact->subject,
                    'Nội dung' => $contact->message,
                    'Trạng thái' => $statuses[$contact->status] ?? $contact->status,
                    'Ghi chú admin' => $contact->admin_note,
                    'Ngày gửi' => $contact->created_at->format('d/m/Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Họ tên',
            'Email',
            'SĐT',
            'Chủ đề',
            'Nội dung',
            'Trạng thái',
            'Ghi chú admin',
            'Ngày gửi',
        ];
    }
}