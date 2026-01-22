<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Carrier;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Country;
use Auth;
use App\Utility\CartUtility;
use App\Services\CartCacheService;
use Session;
use Cookie;

class CartController extends Controller
{
    protected $cartCacheService;

    public function __construct(CartCacheService $cartCacheService)
    {
        $this->cartCacheService = $cartCacheService;
    }

    public function index(Request $request)
    {
        $user_id = null;
        $temp_user_id = null;
        $Category = Category::get();


        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            if ($request->session()->get('temp_user_id')) {
                $this->cartCacheService->mergeTempCart($request->session()->get('temp_user_id'), $user_id);
                Session::forget('temp_user_id');
            }
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
        }


        $carts = $this->cartCacheService->getCartItemsAsCollection($user_id, $temp_user_id);

    if ($request->session()->get('temp_user_id')) {
        $this->cartCacheService->mergeTempCart($request->session()->get('temp_user_id'), $user_id);
        Session::forget('temp_user_id');
    }

 
    $carts = $this->cartCacheService->getCartItemsAsCollection($user_id, $temp_user_id);

    // Update shipping cost to 0
    if (count($carts) > 0) {
        $this->cartCacheService->updateShippingCost(0, $user_id, $temp_user_id);
        $carts = $this->cartCacheService->getCartItemsAsCollection($user_id, $temp_user_id);
     }

        return view('frontend.view_cart', compact('carts', 'Category'));
}

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);

        return view('frontend.partials.cart.addToCart', compact('product'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

  public function addToCart(Request $request)
{
    $authUser = auth()->user();
    $user_id = null;
    $temp_user_id = null;

    if ($authUser != null) {
        $user_id = $authUser->id;
    } else {
        if ($request->session()->get('temp_user_id')) {
            $temp_user_id = $request->session()->get('temp_user_id');
        } else {
            $temp_user_id = bin2hex(random_bytes(10));
            $request->session()->put('temp_user_id', $temp_user_id);
        }
    }

    $product = Product::find($request->id);
    if (!$product) {
        return response()->json([
            'status' => 0,
            'message' => 'Product not found',
            'cart_count' => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
        ], 404);
    }

    $carts = $this->cartCacheService->getCartItemsAsCollection($user_id, $temp_user_id);
    $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);

    if ($check_auction_in_cart && $product->auction_product == 0) {
        return response()->json([
            'status' => 0,
            'message' => 'Remove auction product first',
            'cart_count' => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
        ]);
    }

    $quantity = (int) ($request->quantity ?? 1);
    if ($quantity < 1) $quantity = 1;

    if ($quantity < (int)$product->min_qty) {
        return response()->json([
            'status' => 0,
            'message' => 'Min qty not satisfied',
            'cart_count' => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
        ]);
    }

    $str = CartUtility::create_cart_variant($product, $request->all());

    $product_stock = $product->stocks->where('variant', $str)->first();
    if (!$product_stock) {
        $product_stock = $product->stocks->first();
    }

    if (!$product_stock) {
        return response()->json([
            'status' => 0,
            'message' => 'Out of stock',
            'cart_count' => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
        ]);
    }

    $existingCartId = $this->cartCacheService->itemExists($product->id, $str, $user_id, $temp_user_id);

    if ($existingCartId) {
        $existingCart = $this->cartCacheService->getItem($existingCartId, $user_id, $temp_user_id);

        if ($product->digital == 0) {
            if ($product->auction_product == 1) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Auction product already added',
                    'cart_count' => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
                ]);
            }

            if ((int)$product_stock->qty < ((int)$existingCart['quantity'] + (int)$quantity)) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Out of stock',
                    'cart_count' => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
                ]);
            }

            $quantity = (int)$existingCart['quantity'] + (int)$quantity;
        }

        $price = CartUtility::get_price($product, $product_stock, $quantity);
        $tax = CartUtility::tax_calculation($product, $price);

        $this->cartCacheService->updateItem($existingCartId, [
            'quantity' => $quantity,
            'price' => $price,
            'tax' => $tax,
        ], $user_id, $temp_user_id);

    } else {

        $price = CartUtility::get_price($product, $product_stock, $quantity);
        $tax = CartUtility::tax_calculation($product, $price);

        $cartData = [
            'product_id' => $product->id,
            'owner_id' => $product->user_id,
            'variation' => $str,
            'quantity' => $quantity,
            'price' => $price,
            'tax' => $tax,
            'shipping_cost' => 0,
            'discount' => 0,
            'coupon_code' => '',
            'coupon_applied' => 0,
            'product_referral_code' => null,
            'status' => 1,
        ];

        $this->cartCacheService->addItem($cartData, $user_id, $temp_user_id);
    }

    return response()->json([
        'status' => 1,
        'message' => 'Added to cart',
        'cart_count' => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
    ]);
}


    // Add Category to Cart
    public function addCategoryToCart(Request $request)
    {
        $authUser = auth()->user();
        $user_id = null;
        $temp_user_id = null;

        if($authUser != null) {
            $user_id = $authUser->id;
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            } else {
                $temp_user_id = bin2hex(random_bytes(10));
                $request->session()->put('temp_user_id', $temp_user_id);
            }
        }

        $category = Category::find($request->category_id);
        if(!$category) {
            return response()->json([
                'status' => 0,
                'message' => 'Category not found'
            ]);
        }

        // Check if category already in cart
        $existingCartId = $this->cartCacheService->categoryExists($request->category_id, $user_id, $temp_user_id);

        if($existingCartId) {
            return response()->json([
                'status' => 1,
                'cart_count' => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
                'message' => 'Category already in cart'
            ]);
        }

        // Add category to cart
        $cartData = [
            'category_id' => $request->category_id,
            'quantity' => 1,
            'price' => 0,
            'tax' => 0,
            'shipping_cost' => 0,
            'discount' => 0,
            'status' => 1,
        ];

        $this->cartCacheService->addItem($cartData, $user_id, $temp_user_id);

        return response()->json([
            'status' => 1,
            'cart_count' => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
        ]);
    }



public function removeFromCart(Request $request)
{
    $authUser = auth()->user();
    $user_id = $authUser ? $authUser->id : null;
    $temp_user_id = $authUser ? null : $request->session()->get('temp_user_id');

    $cartId = (string) $request->id;

    // ✅ remove from cache by exact cartId
    $this->cartCacheService->removeItem($cartId, $user_id, $temp_user_id);

    // reload carts after deletion
    $carts = $this->cartCacheService->getCartItemsAsCollection($user_id, $temp_user_id);

    return response()->json([
        'cart_count'    => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
        'cart_view'     => view('frontend.partials.cart.cart_details', compact('carts'))->render(),
        'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
    ]);
}



    //updated the quantity for a cart item
public function updateQuantity(Request $request)
{
    $authUser = auth()->user();
    $user_id = $authUser ? $authUser->id : null;
    $temp_user_id = $authUser ? null : $request->session()->get('temp_user_id');

    $cartId = (string) $request->id;

    $cartItem = $this->cartCacheService->getItem($cartId, $user_id, $temp_user_id);

    if (!$cartItem || !isset($cartItem['product_id'])) {
        return response()->json([
            'cart_count'    => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
            'cart_view'     => view('frontend.partials.cart.cart_details', ['carts' => $this->cartCacheService->getCartItemsAsCollection($user_id, $temp_user_id)])->render(),
        ]);
    }

    $product = Product::find($cartItem['product_id']);
    if (!$product) {
        $this->cartCacheService->removeItem($cartId, $user_id, $temp_user_id);
        $carts = $this->cartCacheService->getCartItemsAsCollection($user_id, $temp_user_id);

        return response()->json([
            'cart_count'    => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
            'cart_view'     => view('frontend.partials.cart.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        ]);
    }

    $newQuantity = (int) $request->quantity;
    if ($newQuantity < 1) $newQuantity = 1;
    if ($newQuantity < (int)$product->min_qty) $newQuantity = (int)$product->min_qty;

    $variant = $cartItem['variation'] ?? '';
    $product_stock = $product->stocks->where('variant', $variant)->first();
    if (!$product_stock) $product_stock = $product->stocks->first();

    if (!$product_stock) {
        return response()->json([
            'cart_count'    => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
            'status'        => 0,
            'message'       => 'Out of stock',
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        ], 400);
    }

    $maxQty = (int) $product_stock->qty;
    if ($maxQty > 0 && $newQuantity > $maxQty) $newQuantity = $maxQty;

    $price = CartUtility::get_price($product, $product_stock, $newQuantity);
    $tax   = CartUtility::tax_calculation($product, $price);

    $this->cartCacheService->updateItem($cartId, [
        'quantity' => $newQuantity,
        'price'    => $price,
        'tax'      => $tax,
    ], $user_id, $temp_user_id);

    $carts = $this->cartCacheService->getCartItemsAsCollection($user_id, $temp_user_id);

    return response()->json([
        'cart_count'    => $this->cartCacheService->getCartCount($user_id, $temp_user_id),
        'cart_view'     => view('frontend.partials.cart.cart_details', compact('carts'))->render(),
        'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
    ]);
}

    public function updateCartStatus(Request $request)
    {
        $product_ids = $request->product_id;
        $authUser = auth()->user();
        $user_id = null;
        $temp_user_id = null;

        if ($authUser != null) {
            $user_id = $authUser->id;
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
        }

        $carts = $this->cartCacheService->getCartItemsAsCollection($user_id, $temp_user_id);

        // Handle coupon logic
        $coupon_applied = $carts->where('coupon_applied', 1)->first();
        if($coupon_applied != null){
            $owner_id = $coupon_applied['owner_id'];
            $coupon_code = $coupon_applied['coupon_code'];
            $user_carts = $carts->where('owner_id', $owner_id);
            $coupon_discount = $user_carts->sum('discount');

            // Reset coupon for all items
            foreach($user_carts as $cart) {
                $this->cartCacheService->updateItem($cart['id'], [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ], $user_id, $temp_user_id);
            }
        }

        // Update status for all items
        $this->cartCacheService->updateCartStatus($product_ids, 1, $user_id, $temp_user_id);

        // Reapply coupon if needed
        if($product_ids != null && $coupon_applied != null){
            $active_user_carts = $user_carts->whereIn('product_id', $product_ids);
            if (count($active_user_carts) > 0) {
                foreach($active_user_carts as $cart) {
                    $this->cartCacheService->updateItem($cart['id'], [
                        'discount' => $coupon_discount / count($active_user_carts),
                        'coupon_code' => $coupon_code,
                        'coupon_applied' => 1
                    ], $user_id, $temp_user_id);
                }
            }
        }

        $carts = $this->cartCacheService->getCartItemsAsCollection($user_id, $temp_user_id);

        return view('frontend.partials.cart.cart_details', compact('carts'))->render();
    }
    public function tracking(Request $request)
    {
        return view('frontend.tracking');
    }
    public function inquiry(Request $request)
    {
        return view('frontend.inquiry');
    }
}
