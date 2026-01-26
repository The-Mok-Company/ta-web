<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Category;
use App\Models\InquiryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\InquiryUpdateRequest;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $q = Inquiry::query()
            ->with(['user:id,name,email', 'admin:id,name,email'])
            ->withCount('items')
            ->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }

        if ($request->filled('code')) {
            $q->where('code', 'like', '%' . $request->string('code') . '%');
        }

        if ($request->filled('user_id')) {
            $q->where('user_id', (int) $request->input('user_id'));
        }

        $inquiries = $q->paginate(20)->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function create()
    {
        $users = User::select('id', 'name', 'email')
            ->where('user_type', 'customer')
            ->orderBy('name')
            ->get();

        $products = Product::select('id', 'name', 'unit_price', 'thumbnail_img', 'category_id')
            ->where('published', 1)
            ->orderBy('name')
            ->get();

        // All categories for filtering
        $allCategories = Category::select('id', 'name', 'parent_id', 'level')
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        // Main categories (level 0) for category items
        $categories = $allCategories->where('level', 0);

        return view('admin.inquiries.create', compact('users', 'products', 'categories', 'allCategories'));
    }



public function store(Request $request)
{
    $request->validate([
        'user_id' => ['required', 'exists:users,id'],
        'status'  => ['nullable', Rule::in(['pending','processing','completed','cancelled'])],
        'note'    => ['nullable', 'string', 'max:5000'],

        'tax'        => ['nullable', 'numeric', 'min:0'],
        'delivery'   => ['nullable', 'numeric', 'min:0'],
        'discount'   => ['nullable', 'numeric', 'min:0'],
        'extra_fees' => ['nullable', 'numeric', 'min:0'],

        'products_total'   => ['nullable', 'numeric', 'min:0'],
        'categories_total' => ['nullable', 'numeric', 'min:0'],
        'subtotal'         => ['nullable', 'numeric', 'min:0'],
        'total'            => ['nullable', 'numeric', 'min:0'],

        'items' => ['required', 'array', 'min:1'],

        'items.*.type'      => ['required', Rule::in(['product','category'])],
        'items.*.product_id'=> ['nullable', 'integer', 'exists:products,id'],
        'items.*.category_id'=> ['nullable', 'integer', 'exists:categories,id'],
        'items.*.quantity'  => ['required', 'numeric', 'min:0.001'],
        'items.*.unit'      => ['nullable', 'string', 'max:50'],
        'items.*.note'      => ['nullable', 'string', 'max:1000'],

        // ✅ أهم تعديل: price لكل item
        'items.*.price'     => ['nullable', 'numeric', 'min:0'],
    ], [], [
        'items.*.price' => 'price',
    ]);

    // ✅ Validate cross rules (product vs category)
    $items = $request->input('items', []);
    foreach ($items as $idx => $item) {
        $type = $item['type'] ?? null;

        if ($type === 'product') {
            if (empty($item['product_id'])) {
                return back()->withErrors(["items.$idx.product_id" => "product_id is required when type=product"])->withInput();
            }
            if (!empty($item['category_id'])) {
                return back()->withErrors(["items.$idx.category_id" => "category_id must be null when type=product"])->withInput();
            }
        }

        if ($type === 'category') {
            if (empty($item['category_id'])) {
                return back()->withErrors(["items.$idx.category_id" => "category_id is required when type=category"])->withInput();
            }
            if (!empty($item['product_id'])) {
                return back()->withErrors(["items.$idx.product_id" => "product_id must be null when type=category"])->withInput();
            }
        }
    }

    $inquiry = DB::transaction(function () use ($request) {

        // Generate inquiry code
        $code = 'INQ-' . strtoupper(uniqid());

        // Create inquiry
        $inquiry = Inquiry::create([
            'user_id'          => $request->input('user_id'),
            'admin_id'         => auth()->id(),
            'code'             => $code,
            'user_note'        => $request->input('user_note'),
            'note'             => $request->input('note'),
            'status'           => $request->input('status', 'pending'),

            // totals (جاية من الـ JS)
            'products_total'   => $request->input('products_total', 0),
            'categories_total' => $request->input('categories_total', 0),
            'subtotal'         => $request->input('subtotal', 0),

            // fees
            'tax'              => $request->input('tax', 0),
            'delivery'         => $request->input('delivery', 0),
            'discount'         => $request->input('discount', 0),
            'extra_fees'       => $request->input('extra_fees', 0),

            'total'            => $request->input('total', 0),
        ]);

        // Create items
        $items = $request->input('items', []);
        foreach ($items as $payload) {
            if (empty($payload['type'])) continue;

            $item = new InquiryItem();
            $item->inquiry_id = $inquiry->id;

            $this->applyItemPayload($item, $payload); // ✅ now saves price too
            $item->save();
        }

        return $inquiry;
    });

    return redirect()
        ->route('admin.inquiries.show', $inquiry->id)
        ->with('success', 'Inquiry created successfully.');
}


    public function show(Inquiry $inquiry)
    {
        $inquiry->load([
            'user:id,name,email',
            'admin:id,name,email',
            'items.product:id,name,unit_price,thumbnail_img',
            'items.category:id,name,banner',
        ]);

        return view('admin.inquiries.show', compact('inquiry'));
    }

public function edit(Inquiry $inquiry)
{
    $inquiry->load(['user', 'items.product', 'items.category']);

    $products = Product::select('id','name','unit_price','thumbnail_img','category_id')
        ->where('published', 1)
        ->orderBy('name')
        ->get();

    $categories = Category::select('id','name','parent_id','level')
        ->orderBy('level')->orderBy('name')
        ->get();

    return view('admin.inquiries.edit', compact('inquiry', 'products', 'categories'));
}


    public function update(InquiryUpdateRequest $request, Inquiry $inquiry)
    {
        DB::transaction(function () use ($request, $inquiry) {

            // 1) Update inquiry basic fields
            $inquiry->fill([
                'status'   => $request->input('status'),
                'note'     => $request->input('note'),
                'admin_id' => auth()->id(), // Always update to current admin
            ]);

            // optional manual fees fields
            foreach (['tax','delivery','discount','extra_fees'] as $f) {
                if ($request->has($f)) {
                    $inquiry->{$f} = $request->input($f);
                }
            }

            // manual totals fields (admin enters these values)
            foreach (['products_total','categories_total','subtotal','total'] as $f) {
                if ($request->has($f)) {
                    $inquiry->{$f} = $request->input($f);
                }
            }

            $inquiry->save();

            // 2) Handle items (update/create/delete)
            $items = $request->input('items', []);

            foreach ($items as $payload) {

                $delete = !empty($payload['_delete']);

                // Existing item
                if (!empty($payload['id'])) {
                    $item = InquiryItem::where('inquiry_id', $inquiry->id)
                        ->where('id', $payload['id'])
                        ->firstOrFail();

                    if ($delete) {
                        $item->delete();
                        continue;
                    }

                    $this->applyItemPayload($item, $payload);
                    $item->save();
                    continue;
                }

                // New item
                if ($delete) {
                    continue; // ignore
                }

                $item = new InquiryItem();
                $item->inquiry_id = $inquiry->id;
                $this->applyItemPayload($item, $payload);
                $item->save();
            }

        });

        return redirect()
            ->route('admin.inquiries.show', $inquiry->id)
            ->with('success', 'Inquiry updated successfully.');
    }

    public function destroy(Inquiry $inquiry)
    {
        DB::transaction(function () use ($inquiry) {
            $inquiry->items()->delete();
            $inquiry->delete();
        });

        return redirect()
            ->route('admin.inquiries.index')
            ->with('success', 'Inquiry deleted successfully.');
    }

    // ================= Helpers =================

private function applyItemPayload(InquiryItem $item, array $payload): void
{
    $type = $payload['type'] ?? 'product';

    $item->type = $type;
    $item->quantity = (float) ($payload['quantity'] ?? 1);
    $item->unit = $payload['unit'] ?? null;
    $item->note = $payload['note'] ?? null;

    // ✅ price (with fallback)
    $price = $payload['price'] ?? null;
    $price = is_null($price) ? null : (float)$price;

    if ($type === 'product') {
        $item->product_id = (int) ($payload['product_id'] ?? 0);
        $item->category_id = null;

        if (is_null($price) || $price <= 0) {
            $price = optional($item->product)->unit_price ?? 0;
        }
    } else {
        $item->category_id = (int) ($payload['category_id'] ?? 0);
        $item->product_id = null;

        // لو مش عندك unit_price في Category سيبها 0
        if (is_null($price) || $price <= 0) {
            $price = optional($item->category)->unit_price ?? 0;
        }
    }

    $item->price = (float) $price;
}


    private function recalculateTotals(Inquiry $inquiry): void
    {
        $inquiry->load(['items.product', 'items.category']);

        $productsTotal = 0.0;
        $categoriesTotal = 0.0;

        foreach ($inquiry->items as $item) {
            $qty = (float) $item->quantity;

            if ($item->type === 'product' && $item->product) {
                $unitPrice = (float) ($item->product->unit_price ?? 0);
                $productsTotal += ($unitPrice * $qty);
            }

            if ($item->type === 'category' && $item->category) {
                $categoriesTotal += 0;
            }
        }

        $subtotal = $productsTotal + $categoriesTotal;

        $tax        = (float) ($inquiry->tax ?? 0);
        $delivery   = (float) ($inquiry->delivery ?? 0);
        $discount   = (float) ($inquiry->discount ?? 0);
        $extraFees  = (float) ($inquiry->extra_fees ?? 0);

        $total = $subtotal + $tax + $delivery + $extraFees - $discount;

        $inquiry->updateQuietly([
            'products_total'   => round($productsTotal, 2),
            'categories_total' => round($categoriesTotal, 2),
            'subtotal'         => round($subtotal, 2),
            'total'            => round($total, 2),
        ]);
    }
}
