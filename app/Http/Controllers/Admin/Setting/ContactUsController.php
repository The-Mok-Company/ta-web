<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index()
    {
        $header = ContactUs::where('key', 'header')->first();
        $contactInfo = ContactUs::where('key', 'contact_info')->first();
        $form = ContactUs::where('key', 'form')->first();

        return view('backend.setting.contact_us', compact(
            'header',
            'contactInfo'
        ));
    }

    public function update(Request $request)
    {
        ContactUs::updateOrCreate(
            ['key' => 'header'],
            [
                'value' => [
                    'title' => $request->header_title,
                    'description' => $request->header_description,
                ]
            ]
        );

        ContactUs::updateOrCreate(
            ['key' => 'contact_info'],
            [
                'value' => [
                    'address_label' => $request->address_label,
                    'address_value' => $request->address_value,
                    'phone_label' => $request->phone_label,
                    'phone_value' => $request->phone_value,
                    'email_label' => $request->email_label,
                    'email_value' => $request->email_value,
                ]
            ]
        );

        return back()->with('success', 'Contact Us page updated successfully!');
    }
}
