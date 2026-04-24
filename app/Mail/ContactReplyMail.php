<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Contact $contact,
        public string $replyMessage
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Phản hồi liên hệ: ' . ($this->contact->subject ?: 'Liên hệ từ website')
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contacts.reply'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}