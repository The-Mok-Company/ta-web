<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InquiryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['pending','processing','completed','cancelled'])],
            'note'   => ['nullable', 'string', 'max:5000'],
            'admin_id' => ['nullable', 'integer', 'exists:users,id'],

            'tax'        => ['nullable', 'numeric', 'min:0'],
            'delivery'   => ['nullable', 'numeric', 'min:0'],
            'discount'   => ['nullable', 'numeric', 'min:0'],
            'extra_fees' => ['nullable', 'numeric', 'min:0'],

            // Totals (manual entry by admin)
            'products_total'   => ['nullable', 'numeric', 'min:0'],
            'categories_total' => ['nullable', 'numeric', 'min:0'],
            'subtotal'         => ['nullable', 'numeric', 'min:0'],
            'total'            => ['nullable', 'numeric', 'min:0'],

            // items payload
            'items' => ['nullable', 'array'],

            // existing items updates
            'items.*.id' => ['nullable', 'integer', 'exists:inquiry_items,id'],

            'items.*.type' => ['required_with:items', Rule::in(['product','category'])],
            'items.*.product_id'  => ['nullable', 'integer', 'exists:products,id'],
            'items.*.category_id' => ['nullable', 'integer', 'exists:categories,id'],

            'items.*.quantity' => ['required_with:items', 'numeric', 'min:0.001'],
            'items.*.unit'     => ['nullable', 'string', 'max:50'],
            'items.*.note'     => ['nullable', 'string', 'max:1000'],
            'items.*._delete'  => ['nullable', 'boolean'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $items = $this->input('items', []);
            foreach ($items as $idx => $item) {
                $type = $item['type'] ?? null;
                $productId = $item['product_id'] ?? null;
                $categoryId = $item['category_id'] ?? null;

                if ($type === 'product') {
                    if (empty($productId)) {
                        $validator->errors()->add("items.$idx.product_id", 'product_id is required when type=product');
                    }
                    if (!empty($categoryId)) {
                        $validator->errors()->add("items.$idx.category_id", 'category_id must be null when type=product');
                    }
                }

                if ($type === 'category') {
                    if (empty($categoryId)) {
                        $validator->errors()->add("items.$idx.category_id", 'category_id is required when type=category');
                    }
                    if (!empty($productId)) {
                        $validator->errors()->add("items.$idx.product_id", 'product_id must be null when type=category');
                    }
                }
            }
        });
    }
}
