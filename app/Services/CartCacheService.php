<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CartCacheService
{
    /**
     * Get cart key for user or guest
     */
    private function getCartKey($userId = null, $tempUserId = null)
    {
        if ($userId) {
            return "cart_user_{$userId}";
        }
        if ($tempUserId) {
            return "cart_temp_{$tempUserId}";
        }
        return null;
    }

    /**
     * Get all cart items (raw associative array keyed by cartId)
     */
    public function getCartItems($userId = null, $tempUserId = null)
    {
        $key = $this->getCartKey($userId, $tempUserId);
        if (!$key) {
            return [];
        }

        return Cache::get($key, []);
    }

    /**
     * Add item to cart
     */
    public function addItem($data, $userId = null, $tempUserId = null)
    {
        $key = $this->getCartKey($userId, $tempUserId);
        if (!$key) {
            return false;
        }

        $cart = $this->getCartItems($userId, $tempUserId);

        // Generate unique ID for cart item (STRING)
        $cartId = uniqid('cart_', true);

        $data['id'] = $cartId;
        $data['user_id'] = $userId;
        $data['temp_user_id'] = $tempUserId;
        $data['created_at'] = now()->toDateTimeString();
        $data['updated_at'] = now()->toDateTimeString();

        // store using cartId as key
        $cart[$cartId] = $data;

        Cache::put($key, $cart, now()->addDays(30));

        return $cartId;
    }

    /**
     * Update item in cart
     */
    public function updateItem($cartId, $data, $userId = null, $tempUserId = null)
    {
        $key = $this->getCartKey($userId, $tempUserId);
        if (!$key) {
            return false;
        }

        $cart = $this->getCartItems($userId, $tempUserId);

        if (isset($cart[$cartId])) {
            $cart[$cartId] = array_merge($cart[$cartId], $data);
            $cart[$cartId]['updated_at'] = now()->toDateTimeString();

            Cache::put($key, $cart, now()->addDays(30));
            return true;
        }

        return false;
    }

    /**
     * Remove item from cart (by cartId key)
     */
    public function removeItem($cartId, $userId = null, $tempUserId = null)
    {
        $key = $this->getCartKey($userId, $tempUserId);
        if (!$key) {
            return false;
        }

        $cart = $this->getCartItems($userId, $tempUserId);

        if (isset($cart[$cartId])) {
            unset($cart[$cartId]);
            Cache::put($key, $cart, now()->addDays(30));
            return true;
        }

        return false;
    }

    /**
     * Get single cart item
     */
    public function getItem($cartId, $userId = null, $tempUserId = null)
    {
        $cart = $this->getCartItems($userId, $tempUserId);
        return $cart[$cartId] ?? null;
    }

    /**
     * Check if item exists in cart
     */
    public function itemExists($productId, $variation, $userId = null, $tempUserId = null)
    {
        $cart = $this->getCartItems($userId, $tempUserId);

        foreach ($cart as $cartId => $item) {
            if (
                isset($item['product_id']) &&
                $item['product_id'] == $productId &&
                ($item['variation'] ?? '') == $variation
            ) {
                return $cartId;
            }
        }

        return null;
    }

    /**
     * Check if category exists in cart
     */
    public function categoryExists($categoryId, $userId = null, $tempUserId = null)
    {
        $cart = $this->getCartItems($userId, $tempUserId);

        foreach ($cart as $cartId => $item) {
            if (isset($item['category_id']) && $item['category_id'] == $categoryId) {
                return $cartId;
            }
        }

        return null;
    }

    /**
     * Clear entire cart
     */
    public function clearCart($userId = null, $tempUserId = null)
    {
        $key = $this->getCartKey($userId, $tempUserId);
        if (!$key) {
            return false;
        }

        Cache::forget($key);
        return true;
    }

    /**
     * Merge temp user cart to logged in user
     */
    public function mergeTempCart($tempUserId, $userId)
    {
        $tempCart = $this->getCartItems(null, $tempUserId);
        $userCart = $this->getCartItems($userId, null);

        foreach ($tempCart as $cartId => $item) {
            $item['user_id'] = $userId;
            $item['temp_user_id'] = null;
            $userCart[$cartId] = $item;
        }

        $userKey = $this->getCartKey($userId, null);
        Cache::put($userKey, $userCart, now()->addDays(30));

        $this->clearCart(null, $tempUserId);

        return true;
    }

    /**
     * Get cart count
     */
    public function getCartCount($userId = null, $tempUserId = null)
    {
        $cart = $this->getCartItems($userId, $tempUserId);
        return count($cart);
    }

    /**
     * Update cart status (for checkout)
     */
    public function updateCartStatus($productIds, $status, $userId = null, $tempUserId = null)
    {
        $cart = $this->getCartItems($userId, $tempUserId);

        foreach ($cart as $cartId => $item) {
            if (isset($item['product_id'])) {
                if ($productIds === null) {
                    $cart[$cartId]['status'] = $status;
                } elseif (in_array($item['product_id'], $productIds)) {
                    $cart[$cartId]['status'] = $status;
                } else {
                    $cart[$cartId]['status'] = 0;
                }
            }
        }

        $key = $this->getCartKey($userId, $tempUserId);
        Cache::put($key, $cart, now()->addDays(30));

        return true;
    }

    /**
     * ✅ IMPORTANT FIX:
     * Get cart items as collection WITHOUT array_values()
     * so keys/cartId remain consistent across remove/update
     */
    public function getCartItemsAsCollection($userId = null, $tempUserId = null)
    {
        $cart = $this->getCartItems($userId, $tempUserId);
        return collect($cart); // ✅ keep keys
    }

    /**
     * Update shipping cost
     */
    public function updateShippingCost($shippingCost, $userId = null, $tempUserId = null)
    {
        $cart = $this->getCartItems($userId, $tempUserId);

        foreach ($cart as $cartId => $item) {
            $cart[$cartId]['shipping_cost'] = $shippingCost;
        }

        $key = $this->getCartKey($userId, $tempUserId);
        Cache::put($key, $cart, now()->addDays(30));

        return true;
    }
}
