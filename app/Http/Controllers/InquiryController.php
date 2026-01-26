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

    public function index()
    {
        // (1) Ensure user is logged in
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        // (2) Fetch user inquiries (latest first)
        $inquiries = Inquiry::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        // (3) Return view
        return view('frontend.user.inquiries.index', compact('inquiries'));
    }
 /**
     * Show one inquiry details page
     * ✅ this is what fixes "Undefined variable $inquiry"
     */
    public function show($id)
    {
        $user = Auth::user();

        $inquiry = Inquiry::with([
                'items.product',
                'items.category',
            ])
            ->where('id', $id)
            ->where('user_id', $user->id) // security: user only sees his inquiries
            ->firstOrFail();

        return view('frontend.inquiries.show', compact('inquiry'));
    }

    public function requestOffer(Request $request)
    {
        // (1) Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // (2) Get current user
        $user = Auth::user();

        // (3) Get inquiry note (optional)
        $note = (string) $request->input('note', '');

        // (4) Get items (may come as JSON string)
        $items = $request->input('items');
        $items = is_string($items) ? json_decode($items, true) : $items;

        // (5) Validate items not empty
        if (!is_array($items) || count($items) === 0) {
            return response()->json(['message' => 'Items are empty'], 422);
        }

        // (6) Normalize + validate items structure
        $normalized      = [];
        $productsTotal   = 0;
        $categoriesTotal = 0;

        foreach ($items as $it) {
            // (6.1) Validate type
            $type = $it['type'] ?? null;
            if (!in_array($type, ['product', 'category'], true)) {
                continue;
            }

            // (6.2) Read IDs
            $cartId     = isset($it['cart_id']) ? (string) $it['cart_id'] : null;
            $productId  = isset($it['product_id']) ? (int) $it['product_id'] : null;
            $categoryId = isset($it['category_id']) ? (int) $it['category_id'] : null;

            // (6.3) Validate required ID based on type
            if ($type === 'product' && (!$productId || $productId < 1)) {
                continue;
            }
            if ($type === 'category' && (!$categoryId || $categoryId < 1)) {
                continue;
            }

            // (6.4) Quantity default + safety
            $qty = isset($it['quantity']) ? (int) $it['quantity'] : 1;
            if ($qty < 1) $qty = 1;

            // (6.5) Item note (optional)
            $itemNote = isset($it['note']) ? (string) $it['note'] : null;

            // (6.6) Totals counters
            if ($type === 'product')  $productsTotal++;
            if ($type === 'category') $categoriesTotal++;

            // (6.7) Build normalized array
            $normalized[] = [
                'type'       => $type,
                'cart_id'     => $cartId,
                'product_id'  => $productId,
                'category_id' => $categoryId,
                'quantity'    => $qty,
                'note'        => $itemNote,
            ];
        }

        // (7) Validate normalized not empty
        if (count($normalized) === 0) {
            return response()->json(['message' => 'No valid items'], 422);
        }

        // (8) Start DB transaction
        DB::beginTransaction();
        try {
            // (8.1) Create inquiry (status = pending)
            $inquiry = Inquiry::create([
                'user_id'          => $user->id,
                'admin_id'         => null,
                'user_note'        => $note,  // User's note (read-only for admin)
                'note'             => null,   // Admin's note (editable by admin)
                'status'           => 'pending',

                'products_total'   => $productsTotal,
                'categories_total' => $categoriesTotal,

                'subtotal'         => 0,
                'tax'              => 0,
                'delivery'         => 0,
                'discount'         => 0,
                'extra_fees'       => 0,
                'total'            => 0,
            ]);

            // (8.2) Create inquiry items
            foreach ($normalized as $it) {
                InquiryItem::create([
                    'inquiry_id'  => $inquiry->id,
                    'type'        => $it['type'],
                    'product_id'  => $it['type'] === 'product' ? $it['product_id'] : null,
                    'category_id' => $it['type'] === 'category' ? $it['category_id'] : null,
                    'quantity'    => $it['quantity'],
                    'unit'        => null,
                    'user_note'   => $it['note'],  // User's note (read-only for admin)
                    'note'        => null,         // Admin's note (editable by admin)
                ]);
            }

            DB::commit();

            $this->cartCacheService->clearCart($user->id, null);

            return response()->json([
                'ok'         => true,
                'message'    => 'Inquiry created with items',
                'inquiry_id' => $inquiry->id,
            ]);
        } catch (\Throwable $e) {
            // (12) Rollback on error
            DB::rollBack();

            return response()->json([
                'ok'      => false,
                'message' => 'Failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function acceptOffer($id)
    {
        $inquiry = Inquiry::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // User can only accept when status is 'processing'
        if ($inquiry->status !== 'processing') {
            return redirect()->back()->with('error', 'Offer not ready yet');
        }

        $inquiry->update([
            'status' => 'completed',
        ]);

        return redirect()
            ->route('cart.inquiry')
            ->with('success', 'Offer accepted successfully');
    }

    public function cancelOffer($id)
    {
        $inquiry = Inquiry::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // User can only cancel when status is 'processing'
        if ($inquiry->status !== 'processing') {
            return redirect()->back()->with('error', 'Cannot cancel this inquiry');
        }

        $inquiry->update([
            'status' => 'cancelled',
        ]);

        return redirect()
            ->route('cart.inquiry')
            ->with('success', 'Inquiry cancelled successfully');
    }
}
