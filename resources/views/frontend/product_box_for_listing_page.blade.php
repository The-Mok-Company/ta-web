@php
    $cart_added = [];
    $current_quantity = 1;
@endphp
<div class="aiz-card-box h-auto bg-white py-3 hov-scale-img">
    <div class="position-relative img-fit overflow-hidden">
        @php
            $product_url = route('product', $product->slug);
            if ($product->auction_product == 1) {
                $product_url = route('auction-product', $product->slug);
            }
        @endphp

        <!-- Image -->
        <a href="{{ $product_url }}" class="d-block h-100 position-relative image-hover-effect">
            <img class="lazyload mx-auto img-fit has-transition product-main-image"
                src="{{ get_image($product->thumbnail) }}" alt="{{ $product->getTranslation('name') }}"
                title="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            <img class="lazyload mx-auto img-fit has-transition product-hover-image position-absolute"
                src="{{ get_first_product_image($product->thumbnail, $product->photos) }}"
                alt="{{ $product->getTranslation('name') }}" title="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>

        @php
            $badgeIndex = 0;
        @endphp

        <!-- Badges Container -->
        <div class="badges-container position-absolute">
            <!-- Discount Badge -->
            @if (discount_in_percentage($product) > 0)
                <span class="badge-item badge-discount">
                    -{{ discount_in_percentage($product) }}%
                </span>
                @php $badgeIndex++; @endphp
            @endif

            <!-- Wholesale Badge -->
            @if ($product->wholesale_product)
                <span class="badge-item badge-wholesale">
                    {{ translate('Wholesale') }}
                </span>
                @php $badgeIndex++; @endphp
            @endif

            <!-- Custom Labels -->
            @php
                $customLabels = get_custom_labels($product->custom_label_id);
            @endphp
            @if ($customLabels)
                @foreach ($customLabels as $key => $customLabel)
                    <span class="badge-item badge-custom"
                        style="background-color:{{ $customLabel->background_color }};
                                 color:{{ $customLabel->text_color }};">
                        {{ $customLabel->text }}
                    </span>
                    @php $badgeIndex++; @endphp
                @endforeach
            @endif
        </div>

        @if ($product->auction_product == 0)
            <!-- Action Icons -->
            <div class="action-icons position-absolute">
                <!-- Wishlist Icon -->
                <button type="button" class="action-btn wishlist-btn" onclick="addToWishList({{ $product->id }})"
                    data-toggle="tooltip" data-title="{{ translate('Add to wishlist') }}" data-placement="left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 16 14.4">
                        <g transform="translate(-3.05 -4.178)">
                            <path
                                d="M11.3,5.507l-.247.246L10.8,5.506A4.538,4.538,0,1,0,4.38,11.919l.247.247,6.422,6.412,6.422-6.412.247-.247A4.538,4.538,0,1,0,11.3,5.507Z"
                                transform="translate(0 0)" fill="currentColor" />
                        </g>
                    </svg>
                </button>

                <!-- Compare Icon -->
                <button type="button" class="action-btn compare-btn" onclick="addToCompare({{ $product->id }})"
                    data-toggle="tooltip" data-title="{{ translate('Add to compare') }}" data-placement="left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16">
                        <path
                            d="M18.037,5.547v.8a.8.8,0,0,1-.8.8H7.221a.4.4,0,0,0-.4.4V9.216a.642.642,0,0,1-1.1.454L2.456,6.4a.643.643,0,0,1,0-.909L5.723,2.227a.642.642,0,0,1,1.1.454V4.342a.4.4,0,0,0,.4.4H17.234a.8.8,0,0,1,.8.8Zm-3.685,4.86a.642.642,0,0,0-1.1.454v1.661a.4.4,0,0,1-.4.4H2.84a.8.8,0,0,0-.8.8v.8a.8.8,0,0,0,.8.8H12.854a.4.4,0,0,1,.4.4V17.4a.642.642,0,0,0,1.1.454l3.267-3.268a.643.643,0,0,0,0-.909Z"
                            transform="translate(-2.037 -2.038)" fill="currentColor" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <div class="product-content p-2 p-md-3 text-left">
        <!-- Product name -->
        <h3 class="fw-400 fs-13 text-truncate-2 lh-1-4 mb-2 h-35px text-center">
            <a href="{{ $product_url }}" class="d-block text-reset hov-text-primary"
                title="{{ $product->getTranslation('name') }}">{{ $product->getTranslation('name') }}</a>
        </h3>

        <!-- Category -->
        <p class="product-category mb-2 text-center">{{ translate('Spices') }}</p>

        <!-- Description (Optional) -->
        @if ($product->getTranslation('description'))
            <p class="product-description mb-2 text-center">
                {{ Str::limit(strip_tags($product->getTranslation('description')), 80) }}
            </p>
        @endif

        <!-- QUANTITY CONTROLS + Actions Row -->
        @if ($product->auction_product == 0)
            @php
                $colors = is_string($product->colors) ? json_decode($product->colors, true) : $product->colors;
                $attributes = is_string($product->attributes)
                    ? json_decode($product->attributes, true)
                    : $product->attributes;
                $has_variants =
                    (is_array($colors) && count($colors) > 0) || (is_array($attributes) && count($attributes) > 0);
            @endphp



            <!-- Actions Row - SMALLER SIZE -->
            <div class="product-actions d-flex align-items-center gap-1 mb-3" style="min-height: 38px;">
                <!-- Remove Button - SMALLER -->
                <button type="button" class="btn-remove flex-shrink-0" onclick="removeFromCart({{ $product->id }})"
                    aria-label="Remove from cart">
                    <i class="las la-minus"></i>
                </button>

                <!-- Add to Inquiry Button - SMALLER -->
                @if ((is_array($colors) && count($colors) > 0) || (is_array($attributes) && count($attributes) > 0))
                    <button type="button" class="btn-inquiry flex-grow-1 flex-shrink-0"
                        onclick="showAddToCartModal({{ $product->id }})"
                        style="height: 36px; white-space: nowrap; font-size: 13px;">
                        <i class="las la-plus-circle me-1" style="font-size: 16px;"></i>
                        <span>Add to Inquiry</span>
                    </button>
                @else
                    <button type="button" class="btn-inquiry flex-grow-1 flex-shrink-0"
                        @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartSingleProduct({{ $product->id }})"
                            @else
                                onclick="showLoginModal()" @endif
                        style="height: 36px; white-space: nowrap; font-size: 13px;">
                        <i class="las la-plus-circle me-1" style="font-size: 16px;"></i>
                        <span>Add to Inquiry</span>
                    </button>
                @endif
            </div>
        @endif

        @if (
            $product->auction_product == 1 &&
                $product->auction_start_date <= strtotime('now') &&
                $product->auction_end_date >= strtotime('now'))
            @php
                $carts = get_user_cart();
                if (count($carts) > 0) {
                    $cart_added = $carts->pluck('product_id')->toArray();
                }
                $highest_bid = $product->bids->max('amount');
                $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
                $gst_rate = gst_applicable_product_rate($product->id);
            @endphp

            <div class="product-actions d-flex align-items-center gap-1 mb-3" style="min-height: 38px;">
                <!-- Remove Button - SMALLER -->
                <button type="button" class="btn-remove flex-shrink-0" aria-label="Remove">
                    <i class="las la-minus"></i>
                </button>

                <!-- Place Bid Button - SMALLER -->
                <button type="button" class="btn-inquiry flex-grow-1 flex-shrink-0"
                    onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }}, {{ $gst_rate }})"
                    style="height: 36px; white-space: nowrap; font-size: 13px;">
                    <i class="las la-gavel me-1" style="font-size: 16px;"></i>
                    <span>Place Bid</span>
                </button>
            </div>
        @endif

        <!-- Price -->
        <div class="fs-14 d-flex justify-content-center mt-1">
            @if ($product->auction_product == 0)
                <!-- Previous price -->
                @if (home_base_price($product) != home_discounted_base_price($product))
                    <div class="disc-amount has-transition">
                        <del class="fw-400 text-secondary mr-1">{{ home_base_price($product) }}</del>
                    </div>
                @endif
                <!-- price -->
                <div class="">
                    <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
                </div>
            @endif
            @if ($product->auction_product == 1)
                <!-- Bid Amount -->
                <div class="">
                    <span class="fw-700 text-primary">{{ single_price($product->starting_bid) }}</span>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    let quantities = {};

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize quantities for simple products
        const simpleProducts = document.querySelectorAll('[id^="qty-controls-"]');
        simpleProducts.forEach(function(container) {
            const productId = container.id.split('-')[2];
            quantities[productId] = 1;
        });
    });

    function updateQuantity(productId, action) {
        if (typeof quantities[productId] === 'undefined') {
            quantities[productId] = 1;
        }

        if (action === 'increase') {
            quantities[productId]++;
        } else if (action === 'decrease' && quantities[productId] > 1) {
            quantities[productId]--;
        }

        // Update display
        const qtyDisplay = document.getElementById('qty-display-' + productId);
        if (qtyDisplay) {
            qtyDisplay.textContent = quantities[productId];
        }

        console.log('Product ' + productId + ' quantity: ' + quantities[productId]);
    }

    // Override addToCartSingleProduct to include quantity
    const originalAddToCart = window.addToCartSingleProduct;
    window.addToCartSingleProduct = function(productId, quantity = 1) {
        const actualQty = quantities[productId] || quantity;
        console.log('Adding to cart:', productId, 'Qty:', actualQty);

        // Call original function if it exists, passing quantity
        if (typeof originalAddToCart === 'function') {
            originalAddToCart(productId, actualQty);
        }
    };
</script>

<style>
    /* ============================================
   Product Card Styles (Aiz Structure)
   ============================================ */

    .aiz-card-box {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .aiz-card-box:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12) !important;
        border-color: #e8e8e8;
    }

    /* ============================================
   Image Container
   ============================================ */

    .img-fit {
        overflow: hidden;
        background: #f8f9fa;
        aspect-ratio: 1 / 1;
    }

    .image-hover-effect {
        display: block;
        position: relative;
        width: 100%;
        height: 100%;
    }

    .product-main-image,
    .product-hover-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.4s ease;
    }

    .product-hover-image {
        opacity: 0;
        z-index: 1;
    }

    .image-hover-effect:hover .product-hover-image {
        opacity: 1;
    }

    .image-hover-effect:hover .product-main-image {
        transform: scale(1.05);
    }

    /* ============================================
   Badges
   ============================================ */

    .badges-container {
        top: 12px;
        left: 12px;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .badge-item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .badge-discount {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: #fff;
    }

    .badge-wholesale {
        background: linear-gradient(135deg, #455a64 0%, #37474f 100%);
        color: #fff;
    }

    /* ============================================
   Action Icons (Top Right)
   ============================================ */

    .action-icons {
        top: 12px;
        right: 12px;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 8px;
        opacity: 0;
        transform: translateX(10px);
        transition: all 0.3s ease;
    }

    .aiz-card-box:hover .action-icons {
        opacity: 1;
        transform: translateX(0);
    }

    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: none;
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        color: #6c757d;
    }

    .action-btn:hover {
        background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%);
        color: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
    }

    /* ============================================
   Product Content - COMPACT
   ============================================ */

    .product-content {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 10px 12px 8px !important;
    }

    .product-title {
        font-size: 14px !important;
        font-weight: 600;
        line-height: 1.3;
        color: #2d3436;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 36px;
    }

    .product-title a {
        color: inherit;
        transition: color 0.3s ease;
    }

    .product-title a:hover {
        color: #4A90E2;
    }

    .product-category {
        font-size: 12px;
        color: #868e96;
        margin: 0 0 4px 0;
    }

    .product-description {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.4;
        margin-bottom: 8px !important;
    }

    /* ============================================
   QUANTITY CONTROLS - NEW
   ============================================ */

    .quantity-controls {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 4px !important;
        margin-bottom: 6px !important;
        padding: 3px 6px;
        background: #f8f9fa;
        border-radius: 16px;
        border: 1px solid #e9ecef;
        min-height: 28px;
    }

    .btn-qty {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 24px !important;
        height: 24px !important;
        border: 1px solid #dee2e6 !important;
        background: #fff !important;
        color: #495057 !important;
        border-radius: 50% !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        font-size: 11px !important;
        flex-shrink: 0 !important;
        padding: 0 !important;
    }

    .btn-qty:hover:not(:disabled) {
        background: #4A90E2 !important;
        color: #fff !important;
        border-color: #4A90E2 !important;
        transform: scale(1.05);
    }

    .btn-qty:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .qty-display {
        min-width: 20px;
        text-align: center;
        color: #2d3436;
        font-weight: 600;
        font-size: 12px;
    }

    /* ============================================
   Product Actions - SMALLER & COMPACT
   ============================================ */

    .product-actions {
        margin-top: auto !important;
        display: flex !important;
        align-items: stretch !important;
        gap: 6px !important;
        min-height: 38px !important;
    }

    .btn-remove {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 38px !important;
        height: 36px !important;
        flex-shrink: 0 !important;
        border: none !important;
        background: #2d3436 !important;
        color: #fff !important;
        border-radius: 50% !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        font-size: 16px !important;
        flex: 0 0 38px !important;
    }

    .btn-remove:hover {
        background: #1a1d1f !important;
        transform: scale(1.05) !important;
        box-shadow: 0 4px 12px rgba(45, 52, 54, 0.3) !important;
    }

    .btn-inquiry {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 4px !important;
        padding: 0 12px !important;
        border: none !important;
        background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%) !important;
        color: #fff !important;
        border-radius: 20px !important;
        cursor: pointer !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        white-space: nowrap !important;
        height: 36px !important;
        flex: 1 !important;
        flex-shrink: 0 !important;
        line-height: 1.2 !important;
    }

    .btn-inquiry:hover {
        background: linear-gradient(135deg, #357ABD 0%, #2868A8 100%) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.4) !important;
    }

    .btn-inquiry i {
        font-size: 16px !important;
        flex-shrink: 0 !important;
        margin-right: 4px !important;
    }

    /* ============================================
   Responsive - EVEN SMALLER ON MOBILE
   ============================================ */

    @media (max-width: 575.98px) {
        .product-content {
            padding: 8px 10px 6px !important;
        }

        .quantity-controls {
            gap: 3px !important;
            padding: 2px 5px;
            min-height: 26px;
        }

        .btn-qty {
            width: 22px !important;
            height: 22px !important;
            font-size: 10px !important;
        }

        .qty-display {
            font-size: 11px;
        }

        .badges-container {
            top: 6px;
            left: 6px;
            gap: 4px;
        }

        .badge-item {
            padding: 4px 8px;
            font-size: 9px;
        }

        .action-icons {
            opacity: 1 !important;
            transform: translateX(0) !important;
            top: 6px;
            right: 6px;
            gap: 4px;
        }

        .action-btn {
            width: 32px !important;
            height: 32px !important;
        }

        .product-actions {
            min-height: 34px !important;
            gap: 4px !important;
        }

        .btn-remove {
            width: 34px !important;
            height: 34px !important;
            font-size: 14px !important;
        }

        .btn-inquiry {
            height: 34px !important;
            padding: 0 10px !important;
            font-size: 12px !important;
            border-radius: 18px !important;
        }

        .btn-inquiry i {
            font-size: 14px !important;
        }

        .btn-inquiry span {
            font-size: 11px !important;
        }
    }

    @media (min-width: 576px) {
        .product-content {
            padding: 12px !important;
        }
    }

    @media (min-width: 992px) {
        .product-content {
            padding: 14px !important;
        }

        .product-actions {
            gap: 8px !important;
        }

        .btn-remove {
            width: 40px !important;
            height: 38px !important;
        }

        .btn-inquiry {
            height: 38px !important;
            padding: 0 14px !important;
            font-size: 13px !important;
        }
    }

    /* Print styles */
    @media print {

        .action-icons,
        .product-actions,
        .quantity-controls {
            display: none !important;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
