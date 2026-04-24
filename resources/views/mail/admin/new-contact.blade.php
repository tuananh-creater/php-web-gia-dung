<x-mail::message>
# Có liên hệ mới từ website

**Người gửi:** {{ $contact->name }}  
**Email:** {{ $contact->email }}  
**Số điện thoại:** {{ $contact->phone ?: 'Không có' }}  
**Chủ đề:** {{ $contact->subject ?: 'Không có chủ đề' }}

**Nội dung:**  
{{ $contact->message }}

<x-mail::button :url="route('admin.contacts.show', $contact)">
Xem chi tiết liên hệ
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>