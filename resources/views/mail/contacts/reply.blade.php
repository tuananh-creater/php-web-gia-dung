<x-mail::message>
# Phản hồi từ {{ config('app.name') }}

Xin chào **{{ $contact->name }}**,

Chúng tôi đã nhận được liên hệ của bạn với chủ đề:

<x-mail::panel>
{{ $contact->subject ?: 'Liên hệ từ website' }}
</x-mail::panel>

**Nội dung phản hồi:**

{{ $replyMessage }}

Cảm ơn bạn đã liên hệ với {{ config('app.name') }}.

Trân trọng,  
{{ config('app.name') }}
</x-mail::message>