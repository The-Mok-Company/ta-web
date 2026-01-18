@php
    $cart_added = [];
    $current_quantity = 1;
@endphp

<div class="featured-product-card">
    @php
        $product_url = route('product', $product->slug);
        if ($product->auction_product == 1) {
            $product_url = route('auction-product', $product->slug);
        }
    @endphp

    <!-- Image Container -->
    <a href="{{ $product_url }}" class="featured-image-wrapper">
        <img class="lazyload featured-product-image"
            src="{{ get_image($product->thumbnail) }}"
            alt="{{ $product->getTranslation('name') }}"
            title="{{ $product->getTranslation('name') }}"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

        @php
            $badgeIndex = 0;
        @endphp

        <!-- Badges Container -->
        <div class="featured-badges-container">
            <!-- Discount Badge -->
            @if (discount_in_percentage($product) > 0)
                <span class="featured-badge featured-badge-discount">
                    -{{ discount_in_percentage($product) }}%
                </span>
                @php $badgeIndex++; @endphp
            @endif

            <!-- Wholesale Badge -->
            @if ($product->wholesale_product)
                <span class="featured-badge featured-badge-wholesale">
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
                    <span class="featured-badge featured-badge-custom"
                        style="background-color:{{ $customLabel->background_color }};
                               color:{{ $customLabel->text_color }};">
                        {{ $customLabel->text }}
                    </span>
                    @php $badgeIndex++; @endphp
                @endforeach
            @endif
        </div>

        @if ($product->auction_product == 0)
            <!-- Action Icons (Wishlist & Compare) -->
            <div class="featured-action-icons">
                <!-- Wishlist Icon -->
                <button type="button" class="featured-action-btn featured-wishlist-btn"
                    onclick="addToWishList({{ $product->id }})"
                    data-toggle="tooltip"
                    data-title="{{ translate('Add to wishlist') }}"
                    data-placement="left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14.4">
                        <g transform="translate(-3.05 -4.178)">
                            <path d="M11.3,5.507l-.247.246L10.8,5.506A4.538,4.538,0,1,0,4.38,11.919l.247.247,6.422,6.412,6.422-6.412.247-.247A4.538,4.538,0,1,0,11.3,5.507Z"
                                transform="translate(0 0)" fill="currentColor" />
                        </g>
                    </svg>
                </button>

                <!-- Compare Icon -->
                <button type="button" class="featured-action-btn featured-compare-btn"
                    onclick="addToCompare({{ $product->id }})"
                    data-toggle="tooltip"
                    data-title="{{ translate('Add to compare') }}"
                    data-placement="left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M18.037,5.547v.8a.8.8,0,0,1-.8.8H7.221a.4.4,0,0,0-.4.4V9.216a.642.642,0,0,1-1.1.454L2.456,6.4a.643.643,0,0,1,0-.909L5.723,2.227a.642.642,0,0,1,1.1.454V4.342a.4.4,0,0,0,.4.4H17.234a.8.8,0,0,1,.8.8Zm-3.685,4.86a.642.642,0,0,0-1.1.454v1.661a.4.4,0,0,1-.4.4H2.84a.8.8,0,0,0-.8.8v.8a.8.8,0,0,0,.8.8H12.854a.4.4,0,0,1,.4.4V17.4a.642.642,0,0,0,1.1.454l3.267-3.268a.643.643,0,0,0,0-.909Z"
                            transform="translate(-2.037 -2.038)" fill="currentColor" />
                    </svg>
                </button>
            </div>

            @php
                $colors = is_string($product->colors) ? json_decode($product->colors, true) : $product->colors;
                $attributes = is_string($product->attributes) ? json_decode($product->attributes, true) : $product->attributes;
                $has_variants = (is_array($colors) && count($colors) > 0) || (is_array($attributes) && count($attributes) > 0);
            @endphp
        @endif
    </a>

    <!-- Product Info -->
    <div class="featured-product-info">
        <div class="featured-product-header">
            <h3 class="featured-product-title">
                <a href="{{ $product_url }}" title="{{ $product->getTranslation('name') }}">
                    {{ $product->getTranslation('name') }}
                </a>
            </h3>

            @if ($product->auction_product == 0)
                <!-- Action Buttons in Header -->
                <div class="featured-header-buttons">
                    <!-- Add to Cart Button -->
                    <button class="featured-header-btn"
                        onclick="event.preventDefault(); event.stopPropagation();
                        @if ($has_variants)
                            showAddToCartModal({{ $product->id }})
                        @else
                            @if(Auth::check() || get_Setting('guest_checkout_activation') == 1)
                                addToCartSingleProduct({{ $product->id }})
                            @else
                                showLoginModal()
                            @endif
                        @endif
                        ">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="featured-header-btn-text">Inquire Now</span>
                    </button>

                    <!-- Add to Inquiry Button -->
                    <button class="featured-header-btn featured-header-inquiry-btn"
                        onclick="event.preventDefault(); event.stopPropagation();
                        @if ($has_variants)
                            showAddToCartModal({{ $product->id }})
                        @else
                            @if(Auth::check() || get_Setting('guest_checkout_activation') == 1)
                                addToCartSingleProduct({{ $product->id }})
                            @else
                                showLoginModal()
                            @endif
                        @endif
                        ">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14" />
                        </svg>

                        <span class="featured-header-btn-text">Add to Inquiry</span>
                    </button>
                </div>
            @endif

            @if ($product->auction_product == 1)
                <!-- Auction Buttons in Header -->
                <div class="featured-header-buttons">
                    @php
                        $carts = get_user_cart();
                        if (count($carts) > 0) {
                            $cart_added = $carts->pluck('product_id')->toArray();
                        }
                        $highest_bid = $product->bids->max('amount');
                        $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
                        $gst_rate = gst_applicable_product_rate($product->id);
                    @endphp

                    <!-- Place Bid Button -->
                    <button class="featured-header-btn"
                        onclick="event.preventDefault(); event.stopPropagation(); bid_single_modal({{ $product->id }}, {{ $min_bid_amount }}, {{ $gst_rate }})">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="featured-header-btn-text">Place Bid</span>
                    </button>

                    <!-- Add to Inquiry Button -->
                    <button class="featured-header-btn featured-header-inquiry-btn"
                        onclick="event.preventDefault(); event.stopPropagation(); bid_single_modal({{ $product->id }}, {{ $min_bid_amount }}, {{ $gst_rate }})">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span class="featured-header-btn-text">Add to Inquiry</span>
                    </button>
                </div>
            @endif
        </div>
@php
    $categoryName = \App\Models\Category::find($product->category_id)?->getTranslation('name');
@endphp
<p class="featured-product-category">
    {{ $categoryName ?? translate('Products') }}
</p>
        <!-- Price -->

    </div>
</div>

<style>
/* ===============================================
   Featured Product Card - Fully Responsive
   =============================================== */
.featured-product-card {
    background: white;
    border-radius: clamp(12px, 2vw, 15px);
    overflow: hidden;
    cursor: pointer;
    padding: clamp(12px, 2vw, 16px);
    transition: all 0.3s ease;
    text-decoration: none;
    display: block;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    height: 100%;
}

.featured-product-card:hover {
    transform: translateY(clamp(-4px, -1vw, -8px));
    box-shadow: 0 clamp(6px, 1vw, 8px) clamp(15px, 3vw, 25px) rgba(0, 0, 0, 0.12);
}

/* ===============================================
   Image Wrapper
   =============================================== */
.featured-image-wrapper {
    position: relative;
    width: 100%;
    height: clamp(150px, 25vw, 200px);
    overflow: hidden;
    display: block;
    border-radius: clamp(8px, 1.5vw, 12px);
}

.featured-product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.featured-product-card:hover .featured-product-image {
    transform: scale(1.1);
}

/* ===============================================
   Badges Container
   =============================================== */
.featured-badges-container {
    position: absolute;
    top: clamp(8px, 1.5vw, 12px);
    left: clamp(8px, 1.5vw, 12px);
    z-index: 3;
    display: flex;
    flex-direction: column;
    gap: clamp(4px, 0.8vw, 6px);
}

.featured-badge {
    display: inline-block;
    padding: clamp(3px, 0.6vw, 4px) clamp(6px, 1vw, 8px);
    border-radius: clamp(3px, 0.6vw, 4px);
    font-size: clamp(10px, 1.5vw, 11px);
    font-weight: 600;
    line-height: 1.2;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.featured-badge-discount {
    background: #dc3545;
    color: #fff;
}

.featured-badge-wholesale {
    background: #495057;
    color: #fff;
}

/* ===============================================
   Action Icons (Wishlist & Compare)
   =============================================== */
.featured-action-icons {
    position: absolute;
    top: clamp(8px, 1.5vw, 12px);
    right: clamp(8px, 1.5vw, 12px);
    z-index: 3;
    display: flex;
    flex-direction: column;
    gap: clamp(6px, 1vw, 8px);
    opacity: 0;
    transform: translateX(10px);
    transition: all 0.3s ease;
}

.featured-product-card:hover .featured-action-icons {
    opacity: 1;
    transform: translateX(0);
}

.featured-action-btn {
    width: clamp(32px, 4vw, 36px);
    height: clamp(32px, 4vw, 36px);
    border-radius: 50%;
    border: none;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    color: #6c757d;
}

.featured-action-btn svg {
    width: clamp(14px, 2vw, 16px);
    height: clamp(14px, 2vw, 16px);
}

.featured-action-btn:hover {
    background: #007bff;
    color: #ffffff;
    transform: scale(1.1);
}

/* ===============================================
   Product Info
   =============================================== */
.featured-product-info {
    padding: clamp(12px, 2.5vw, 20px) 0 0 0;
}

/* ===============================================
   Product Header (Title + Buttons)
   =============================================== */
.featured-product-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: clamp(8px, 1.5vw, 12px);
    margin-bottom: clamp(6px, 1vw, 8px);
}

.featured-product-title {
    font-size: clamp(0.95rem, 2vw, 1.1rem);
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
    line-height: 1.4;
    flex: 1;
}

.featured-product-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.featured-product-title a:hover {
    color: #007bff;
}

/* ===============================================
   Header Buttons (Fully Responsive)
   =============================================== */
.featured-header-buttons {
    display: flex;
    gap: clamp(6px, 1vw, 8px);
    flex-shrink: 0;
    align-items: flex-start;
}

.featured-header-btn {
    width: 40px;
    height: 40px;
    min-width: 40px;

    background-color: #1a1a1a;
    border-radius: 20px;
    border: none;
    color: #fff;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    cursor: pointer;
    white-space: nowrap;
    padding: 0;

    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);

    gap: clamp(4px, 1vw, 8px);
    overflow: hidden;

    box-shadow: 0 2px clamp(6px, 1vw, 8px) rgba(0, 0, 0, 0.1);
}

.featured-header-btn svg {
    width: clamp(16px, 2.5vw, 20px);
    height: clamp(16px, 2.5vw, 20px);
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.featured-header-btn-text {
    font-size: clamp(0.75rem, 1.5vw, 0.95rem);
    font-weight: 500;

    /* مخفي في البداية */
    opacity: 0;
    max-width: 0;
    margin-left: 0;

    transform: translateX(-10px);
    transition: all 0.3s ease;

    white-space: nowrap;
}

/* عند الـ Hover - توسيع متجاوب */
.featured-header-btn:hover {
    width: auto;
    min-width: auto;
    padding: 0 clamp(14px, 2vw, 18px) 0 clamp(10px, 1.5vw, 14px);

    box-shadow: 0 clamp(3px, 0.5vw, 4px) clamp(12px, 2vw, 16px) rgba(0, 0, 0, 0.2);

    transform: translateY(clamp(-1px, -0.3vw, -2px));

    background-color: #000000;
}

/* إظهار النص عند Hover */
.featured-header-btn:hover .featured-header-btn-text {
    opacity: 1;
    max-width: clamp(100px, 20vw, 150px);
    margin-left: clamp(4px, 0.8vw, 6px);
    transform: translateX(0);
}

/* تكبير الأيقونة عند Hover */
.featured-header-btn:hover svg {
    transform: scale(1.1);
}

/* Inquiry Button - Blue Gradient */
.featured-header-inquiry-btn {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    box-shadow: 0 2px clamp(6px, 1vw, 8px) rgba(33, 150, 243, 0.25);
}

.featured-header-inquiry-btn:hover {
    background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
    box-shadow: 0 clamp(3px, 0.5vw, 4px) clamp(12px, 2vw, 16px) rgba(33, 150, 243, 0.4);
}

/* ===============================================
   Category & Price
   =============================================== */
.featured-product-category {
    font-size: clamp(0.75rem, 1.3vw, 0.85rem);
    color: #6c757d;
    margin: 0 0 clamp(8px, 1.5vw, 12px) 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    margin-top: -16px;
}

.featured-product-price {
    display: flex;
    align-items: center;
    gap: clamp(6px, 1vw, 8px);
}

.featured-old-price {
    color: #999;
    font-size: clamp(0.8rem, 1.5vw, 0.9rem);
    text-decoration: line-through;
}

.featured-current-price {
    color: #1a1a1a;
    font-weight: 700;
    font-size: clamp(0.95rem, 2vw, 1.1rem);
}

/* ===============================================
   Responsive Media Queries (للتحسينات الإضافية)
   =============================================== */

/* Large Tablets */
@media (max-width: 1024px) {
    .featured-product-card:hover {
        transform: translateY(-6px);
    }
}

/* Tablets & Mobile */
@media (max-width: 768px) {
    /* إظهار الأيقونات دائماً على الموبايل */
    .featured-action-icons {
        opacity: 1;
        transform: translateX(0);
    }

    .featured-image-wrapper {
        height: clamp(140px, 30vw, 180px);
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .featured-product-card {
        padding: 10px;
    }

    .featured-image-wrapper {
        height: clamp(180px, 40vw, 220px);
    }

    .featured-header-buttons {
        gap: 5px;
    }

    .featured-header-btn:hover {
        padding: 0 12px 0 8px;
    }
}

/* Extra Small Mobile */
@media (max-width: 360px) {
    .featured-product-card {
        padding: 8px;
    }

    .featured-product-header {
        gap: 6px;
    }
}

/* Touch Devices - تحسين التفاعل */
@media (hover: none) and (pointer: coarse) {
    .featured-action-icons {
        opacity: 1;
        transform: translateX(0);
    }

    .featured-header-btn:active {
        transform: scale(0.95);
    }

    .featured-product-card:active {
        transform: translateY(-4px);
    }
}

/* ===============================================
   Print Styles
   =============================================== */
@media print {
    .featured-action-icons,
    .featured-header-buttons {
        display: none !important;
    }

    .featured-product-card {
        box-shadow: none;
        border: 1px solid #ddd;
    }
}

/* ===============================================
   Accessibility
   =============================================== */
.featured-header-btn:focus,
.featured-action-btn:focus {
    outline: 2px solid #007bff;
    outline-offset: 2px;
}

.featured-header-btn:focus:not(:focus-visible),
.featured-action-btn:focus:not(:focus-visible) {
    outline: none;
}

.featured-header-btn:active {
    transform: translateY(0);
}

/* ===============================================
   Loading State
   =============================================== */
.featured-product-image[data-loading] {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* ===============================================
   Dark Mode Support (Optional)
   =============================================== */
@media (prefers-color-scheme: dark) {
    .featured-product-card {
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
    }

    .featured-product-title a {
        color: rgba(37, 43, 66, 1);
    }

    .featured-product-category {
        color: #aaa;
    }

    .featured-action-btn {
        background: #2a2a2a;
        color: #fff;
    }
}
</style>

<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.featured-header-btn, .featured-action-btn');

    if (!btn) return;

    const link = btn.closest('a');

    if (link) {
        e.preventDefault();
        e.stopPropagation();
    }
});

// Lazy loading optimization
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src || img.src;
                img.classList.remove('lazyload');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('.featured-product-image.lazyload').forEach(img => {
        imageObserver.observe(img);
    });
}
</script>
