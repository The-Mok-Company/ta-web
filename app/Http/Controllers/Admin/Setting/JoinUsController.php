<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\JoinUs;
use Illuminate\Http\Request;

class JoinUsController extends Controller
{
    public function index()
    {
        $hero = JoinUs::where('key', 'hero')->first();
        $info = JoinUs::where('key', 'info')->first();
        $contact = JoinUs::where('key', 'contact')->first();

        return view('backend.setting.join_us', compact('hero', 'info', 'contact'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // ===== Hero Section =====
        $heroImagePath = $request->hero_image_old;
        if ($request->hasFile('hero_image')) {
            if ($heroImagePath && file_exists(public_path($heroImagePath))) {
                unlink(public_path($heroImagePath));
            }

            $heroImage = $request->file('hero_image');
            $heroImageName = 'join_hero_' . time() . '.' . $heroImage->getClientOriginalExtension();
            $heroImage->move(public_path('assets/img/join'), $heroImageName);
            $heroImagePath = 'public/assets/img/join/' . $heroImageName;
        }

        JoinUs::updateOrCreate(
            ['key' => 'hero'],
            [
                'value' => [
                    'title_line1' => $request->hero_title_line1,
                    'title_line2' => $request->hero_title_line2,
                    'image' => $heroImagePath,
                ]
            ]
        );

        // ===== Info Section =====
        JoinUs::updateOrCreate(
            ['key' => 'info'],
            [
                'value' => [
                    'title' => $request->info_title,
                    'description' => $request->info_description,
                ]
            ]
        );

        // ===== Contact Section =====
        JoinUs::updateOrCreate(
            ['key' => 'contact'],
            [
                'value' => [
                    'address' => $request->contact_address,
                    'phone' => $request->contact_phone,
                    'email' => $request->contact_email,
                ]
            ]
        );

        return back()->with('success', 'Join Us page updated successfully!');
    }
}
