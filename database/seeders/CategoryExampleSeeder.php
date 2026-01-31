<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Support\Str;

class CategoryExampleSeeder extends Seeder
{
    /**
     * Seed example multi-layer categories: Main categories and sub categories under Industrial & Raw Materials.
     *
     * @return void
     */
    public function run()
    {
        $defaultLang = env('DEFAULT_LANGUAGE', 'en');

        $mainNames = [
            'Food & Beverages',
            'FMCG – Household & Personal Care',
            'Textiles',
            'Packing Materials',
            'Scrap & Recycled Materials',
            'Industrial & Raw Materials',
        ];

        $mainCategories = [];
        foreach ($mainNames as $index => $name) {
            $cat = Category::withoutGlobalScope('published')->firstOrNew(['name' => $name, 'parent_id' => 0]);
            $cat->name = $name;
            $cat->parent_id = 0;
            $cat->level = 0;
            $cat->digital = 0;
            $cat->order_level = 100 - $index;
            $cat->is_published = 1;
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($name) . '-' . Str::random(5);
            }
            $cat->save();
            $cat->category_translations()->updateOrCreate(
                ['lang' => $defaultLang],
                ['name' => $name]
            );
            $mainCategories[$name] = $cat;
        }

        $industrialId = $mainCategories['Industrial & Raw Materials']->id ?? null;
        if ($industrialId) {
            $subNames = [
                'Ingredients & Additives',
                'Commodities & Bulk Trading',
            ];
            foreach ($subNames as $index => $name) {
                $cat = Category::withoutGlobalScope('published')->firstOrNew(['name' => $name, 'parent_id' => $industrialId]);
                $cat->name = $name;
                $cat->parent_id = $industrialId;
                $cat->level = 1;
                $cat->digital = 0;
                $cat->order_level = 50 - $index;
                $cat->is_published = 1;
                if (empty($cat->slug)) {
                    $cat->slug = Str::slug($name) . '-' . Str::random(5);
                }
                $cat->save();
                $cat->category_translations()->updateOrCreate(
                    ['lang' => $defaultLang],
                    ['name' => $name]
                );
            }
        }
    }
}
