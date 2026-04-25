<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\Contact;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Mail\AdminNewContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact.index');
    }

    public function store(ContactFormRequest $request): RedirectResponse
    {
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'new',
        ]);

        try {
            $adminEmail = config('mail.admin.address');

            if (!empty($adminEmail)) {
                Mail::to($adminEmail)->send(new AdminNewContactMail($contact));
            }
        } catch (\Throwable $mailException) {
            report($mailException);
        }

        return back()->with('success', 'Gửi liên hệ thành công. Chúng tôi sẽ phản hồi sớm nhất.');
    }
}