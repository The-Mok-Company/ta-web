<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\ContactUsForm;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactUsForm::with('category')->latest()->paginate(9);

        return view('backend.contact.messages', compact('messages'));
    }

    public function toggleStatus($id)
    {
        $message = ContactUsForm::findOrFail($id);
        $message->update(['status' => !$message->status]);

        $statusText = $message->status ? 'Read' : 'Unread';

        return redirect()->back()->with('success', "Message marked as {$statusText}");
    }
}
