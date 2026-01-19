<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\OurService;
use Illuminate\Http\Request;

class OurServiceController extends Controller
{
    public function index()
    {
        $valueServices = OurService::where('key', 'value_services')->first();
        $sourcing = OurService::where('key', 'sourcing')->first();
        $branding = OurService::where('key', 'branding')->first();
        $logistics = OurService::where('key', 'logistics')->first();
        $legal = OurService::where('key', 'legal')->first();
        $whyWork = OurService::where('key', 'why_work')->first();

        return view('backend.setting.our_services', compact(
            'valueServices',
            'sourcing',
            'branding',
            'logistics',
            'legal',
            'whyWork'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'value_services_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'sourcing_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'branding_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'branding_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'branding_image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'why_work_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        // ========== 1. Value Services Section ==========
        $valueServicesImagePath = $request->value_services_image_old;
        if ($request->hasFile('value_services_image')) {
            if ($valueServicesImagePath && file_exists(public_path($valueServicesImagePath))) {
                unlink(public_path($valueServicesImagePath));
            }
            $image = $request->file('value_services_image');
            $imageName = 'value_services_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img/ourservices'), $imageName);
            $valueServicesImagePath = 'public/assets/img/ourservices/' . $imageName;
        }

        OurService::updateOrCreate(
            ['key' => 'value_services'],
            ['value' => [
                'title' => $request->value_services_title,
                'description' => $request->value_services_description,
                'image' => $valueServicesImagePath,
            ]]
        );

        // ========== 2. Sourcing Section ==========
        $sourcingImagePath = $request->sourcing_image_old;
        if ($request->hasFile('sourcing_image')) {
            if ($sourcingImagePath && file_exists(public_path($sourcingImagePath))) {
                unlink(public_path($sourcingImagePath));
            }
            $image = $request->file('sourcing_image');
            $imageName = 'sourcing_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img/ourservices'), $imageName);
            $sourcingImagePath = 'public/assets/img/ourservices/' . $imageName;
        }

        OurService::updateOrCreate(
            ['key' => 'sourcing'],
            ['value' => [
                'title' => $request->sourcing_title,
                'items' => [
                    [
                        'title' => $request->sourcing_item_1_title,
                        'description' => $request->sourcing_item_1_description,
                    ],
                    [
                        'title' => $request->sourcing_item_2_title,
                        'description' => $request->sourcing_item_2_description,
                    ],
                    [
                        'title' => $request->sourcing_item_3_title,
                        'description' => $request->sourcing_item_3_description,
                    ],
                ],
                'image' => $sourcingImagePath,
            ]]
        );

        // ========== 3. Branding Section (3 Cards) ==========
        $brandingImages = [];
        for ($i = 1; $i <= 3; $i++) {
            $fieldName = 'branding_image_' . $i;
            $oldFieldName = 'branding_image_' . $i . '_old';

            $imagePath = $request->$oldFieldName;

            if ($request->hasFile($fieldName)) {
                if ($imagePath && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $image = $request->file($fieldName);
                $imageName = 'branding_' . $i . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/img/ourservices/branding'), $imageName);
                $imagePath = 'public/assets/img/ourservices/branding/' . $imageName;
            }

            $brandingImages[] = $imagePath;
        }

        OurService::updateOrCreate(
            ['key' => 'branding'],
            ['value' => [
                'title' => $request->branding_title,
                'cards' => [
                    [
                        'title' => $request->branding_card_1_title,
                        'description' => $request->branding_card_1_description,
                        'image' => $brandingImages[0],
                    ],
                    [
                        'title' => $request->branding_card_2_title,
                        'description' => $request->branding_card_2_description,
                        'image' => $brandingImages[1],
                    ],
                    [
                        'title' => $request->branding_card_3_title,
                        'description' => $request->branding_card_3_description,
                        'image' => $brandingImages[2],
                    ],
                ],
            ]]
        );

        // ========== 4. Logistics Section (4 Items) ==========
        OurService::updateOrCreate(
            ['key' => 'logistics'],
            ['value' => [
                'title' => $request->logistics_title,
                'items' => [
                    [
                        'title' => $request->logistics_item_1_title,
                        'description' => $request->logistics_item_1_description,
                    ],
                    [
                        'title' => $request->logistics_item_2_title,
                        'description' => $request->logistics_item_2_description,
                    ],
                    [
                        'title' => $request->logistics_item_3_title,
                        'description' => $request->logistics_item_3_description,
                    ],
                    [
                        'title' => $request->logistics_item_4_title,
                        'description' => $request->logistics_item_4_description,
                    ],
                ],
            ]]
        );

        // ========== 5. Legal Section ==========
        OurService::updateOrCreate(
            ['key' => 'legal'],
            ['value' => [
                'title' => $request->legal_title,
                'description' => $request->legal_description,
            ]]
        );

        // ========== 6. Why Work With Us ==========
        $whyWorkImagePath = $request->why_work_image_old;
        if ($request->hasFile('why_work_image')) {
            if ($whyWorkImagePath && file_exists(public_path($whyWorkImagePath))) {
                unlink(public_path($whyWorkImagePath));
            }
            $image = $request->file('why_work_image');
            $imageName = 'why_work_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img/ourservices'), $imageName);
            $whyWorkImagePath = 'public/assets/img/ourservices/' . $imageName;
        }

        OurService::updateOrCreate(
            ['key' => 'why_work'],
            ['value' => [
                'title' => $request->why_work_title,
                'description' => $request->why_work_description,
                'image' => $whyWorkImagePath,
            ]]
        );

        return back()->with('success', 'Our Services updated successfully!');
    }
}
