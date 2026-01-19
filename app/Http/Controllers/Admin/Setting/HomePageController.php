<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\HomePage;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index()
    {
        $hero = HomePage::where('key', 'hero')->first();
        $customers = HomePage::where('key', 'customers')->first();
        $gather = HomePage::where('key', 'gather')->first();

        return view('backend.setting.home_page', compact('hero', 'customers', 'gather'));
    }

    public function update(Request $request)
    {
        // Validation
        $request->validate([
            'hero_slide_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'hero_slide_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'hero_slide_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'customers_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'gather_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        // ============ Hero Slides ============
        $heroSlides = [];
        for ($i = 1; $i <= 3; $i++) {
            $fieldName = 'hero_slide_' . $i;
            $oldFieldName = 'hero_slide_' . $i . '_old';

            $imagePath = $request->$oldFieldName;

            if ($request->hasFile($fieldName)) {
                if ($imagePath && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }

                $image = $request->file($fieldName);
                $imageName = 'hero_slide_' . $i . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/img/home/hero'), $imageName);
                $imagePath = 'assets/img/home/hero/' . $imageName;
            }

            $heroSlides[] = [
                'image' => $imagePath,
                'title' => $request->input('hero_title_' . $i),
                'description' => $request->input('hero_description_' . $i),
            ];
        }

        HomePage::updateOrCreate(
            ['key' => 'hero'],
            ['value' => ['slides' => $heroSlides]]
        );

        // ============ Customers Section ============
        $customersImagePath = $request->customers_image_old;
        if ($request->hasFile('customers_image')) {
            if ($customersImagePath && file_exists(public_path($customersImagePath))) {
                unlink(public_path($customersImagePath));
            }

            $customersImage = $request->file('customers_image');
            $customersImageName = 'customers_' . time() . '.' . $customersImage->getClientOriginalExtension();
            $customersImage->move(public_path('assets/img/home'), $customersImageName);
            $customersImagePath = 'assets/img/home/' . $customersImageName;
        }

        HomePage::updateOrCreate(
            ['key' => 'customers'],
            [
                'value' => [
                    'title' => $request->customers_title,
                    'description' => $request->customers_description,
                    'link_text' => $request->customers_link_text,
                    'link_url' => $request->customers_link_url,
                    'image' => $customersImagePath,
                ]
            ]
        );

        // ============ Gather Section ============
        $gatherImagePath = $request->gather_image_old;
        if ($request->hasFile('gather_image')) {
            if ($gatherImagePath && file_exists(public_path($gatherImagePath))) {
                unlink(public_path($gatherImagePath));
            }

            $gatherImage = $request->file('gather_image');
            $gatherImageName = 'gather_' . time() . '.' . $gatherImage->getClientOriginalExtension();
            $gatherImage->move(public_path('assets/img/home'), $gatherImageName);
            $gatherImagePath = 'assets/img/home/' . $gatherImageName;
        }

        HomePage::updateOrCreate(
            ['key' => 'gather'],
            [
                'value' => [
                    'top_title' => $request->gather_top_title,
                    'top_description' => $request->gather_top_description,
                    'button_text' => $request->gather_button_text,
                    'button_url' => $request->gather_button_url,
                    'overlay_title' => $request->gather_overlay_title,
                    'image' => $gatherImagePath,
                ]
            ]
        );

        return back()->with('success', 'Home Page updated successfully!');
    }
}
