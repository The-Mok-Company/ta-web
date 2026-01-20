<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\InquiryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\CartCacheService;

class InquiryController extends Controller
{
    protected CartCacheService $cartCacheService;

    public function __construct(CartCacheService $cartCacheService)
    {
        $this->cartCacheService = $cartCacheService;
    }

    public function requestOffer(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();

        // note
        $note = (string) $request->input('note', '');

        // ✅ items جايه string (JSON.stringify) => نفكها
        $items = $request->input('items');
        $items = is_string($items) ? json_decode($items, true) : $items;

        if (!is_array($items) || count($items) === 0) {
            return response()->json(['message' => 'Items are empty'], 422);
        }

        // ✅ Validate basic structure
        // كل item لازم يبقى فيه type + quantity (اختياري) + product_id/category_id
        $normalized = [];
        $productsTotal = 0;
        $categoriesTotal = 0;

        foreach ($items as $it) {
            $type = $it['type'] ?? null;
            if (!in_array($type, ['product', 'category'], true)) {
                continue;
            }

            $cartId = isset($it['cart_id']) ? (string) $it['cart_id'] : null;

            $productId  = isset($it['product_id']) ? (int) $it['product_id'] : null;
            $categoryId = isset($it['category_id']) ? (int) $it['category_id'] : null;

            // type validation
            if ($type === 'product' && (!$productId || $productId < 1)) {
                continue;
            }
            if ($type === 'category' && (!$categoryId || $categoryId < 1)) {
                continue;
            }

            $qty = isset($it['quantity']) ? (int) $it['quantity'] : 1;
            if ($qty < 1) $qty = 1;

            $itemNote = isset($it['note']) ? (string) $it['note'] : null;

            if ($type === 'product') $productsTotal++;
            if ($type === 'category') $categoriesTotal++;

            $normalized[] = [
                'type'        => $type,
                'cart_id'      => $cartId,
                'product_id'   => $productId,
                'category_id'  => $categoryId,
                'quantity'     => $qty,
                'note'         => $itemNote,
            ];
        }

        if (count($normalized) === 0) {
            return response()->json(['message' => 'No valid items'], 422);
        }

        DB::beginTransaction();
        try {
            $inquiry = Inquiry::create([
                'user_id'          => $user->id,
                'admin_id'         => null,
                'note'             => $note,
                'status'           => 'submitted',
                'products_total'   => $productsTotal,
                'categories_total' => $categoriesTotal,
                'subtotal'         => 0,
                'tax'              => 0,
                'delivery'         => 0,
                'discount'         => 0,
                'extra_fees'       => 0,
                'total'            => 0,
            ]);

            foreach ($normalized as $it) {
                InquiryItem::create([
                    'inquiry_id' => $inquiry->id,
                    'type'       => $it['type'],
                    'product_id' => $it['type'] === 'product' ? $it['product_id'] : null,
                    'category_id'=> $it['type'] === 'category' ? $it['category_id'] : null,
                    'quantity'   => $it['quantity'],
                    'unit'       => null, // لو عندك unit في InquiryItem سيبها أو املاها
                    'note'       => $it['note'],

                    // ✅ لو عندك عمود cart_id في جدول inquiry_items (اختياري)
                    // 'cart_id' => $it['cart_id'],
                ]);
            }

            DB::commit();

            // ✅✅✅ تفريغ السلة بعد نجاح إنشاء الـ Inquiry
            // بما إن السلة عندك في الكاش (CartCacheService)
            $this->cartCacheService->clearCart($user->id, null);

            return response()->json([
                'ok' => true,
                'message' => 'Inquiry created with items',
                'inquiry_id' => $inquiry->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'ok' => false,
                'message' => 'Failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
