<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Mail\ContactReplyMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::query()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = trim($request->keyword);

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
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $statuses = [
            'new' => 'Mới',
            'read' => 'Đã đọc',
            'replied' => 'Đã phản hồi',
        ];

        return view('admin.contacts.index', compact('contacts', 'statuses'));
    }

    public function show(Contact $contact)
    {
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
            $contact->refresh();
        }

        $statuses = [
            'new' => 'Mới',
            'read' => 'Đã đọc',
            'replied' => 'Đã phản hồi',
        ];

        return view('admin.contacts.show', compact('contact', 'statuses'));
    }

    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,read,replied'],
            'admin_note' => ['nullable', 'string'],
            'reply_message' => ['nullable', 'string'],
            'send_reply' => ['nullable', 'boolean'],
        ], [
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $sendReply = $request->boolean('send_reply');
        $replyMessage = trim((string) ($data['reply_message'] ?? ''));

        if ($sendReply && $replyMessage === '') {
            return back()
                ->withErrors(['reply_message' => 'Vui lòng nhập nội dung phản hồi trước khi gửi email.'])
                ->withInput();
        }

        $contact->admin_note = $data['admin_note'] ?? null;
        $contact->reply_message = $replyMessage !== '' ? $replyMessage : $contact->reply_message;
        $contact->status = $data['status'];

        if ($sendReply) {
            try {
                Mail::to($contact->email)->send(new ContactReplyMail($contact, $replyMessage));

                $contact->status = 'replied';
                $contact->reply_message = $replyMessage;
                $contact->replied_at = now();
            } catch (\Throwable $mailException) {
                report($mailException);

                return back()
                    ->with('error', 'Đã lưu dữ liệu nhưng gửi email phản hồi thất bại.')
                    ->withInput();
            }
        }

        $contact->save();

        return redirect()
            ->route('admin.contacts.show', $contact)
            ->with('success', $sendReply ? 'Đã gửi email phản hồi thành công.' : 'Cập nhật liên hệ thành công.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Xóa liên hệ thành công.');
    }
}