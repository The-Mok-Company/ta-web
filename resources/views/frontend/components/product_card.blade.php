@php
    /** @var \App\Models\Product $product */
    $lang = $lang ?? null;
    // optional context (used on category pages to keep category_id on product urls)
    $category_id = $category_id ?? null;

    $productName = $productName ?? $product?->getTranslation('name', $lang) ?? ($product->name ?? '');
    $productImage = $productImage ?? (function () use ($product) {
        try {
            if (!empty($product->thumbnail_img)) {
                return uploaded_asset($product->thumbnail_img);
            }
        } catch (\Throwable $e) {
        }
        try {
            if (!empty($product->thumbnail)) {
                return get_image($product->thumbnail);
            }
        } catch (\Throwable $e) {
        }
        return static_asset('assets/img/placeholder.jpg');
    })();

    $categoryId = $categoryId ?? ($product->category_id ?? null);
    $categoryName = $categoryName ?? (\App\Models\Category::find($categoryId)?->getTranslation('name') ?? translate('Products'));
    $categoryUrl = $categoryUrl ?? ($categoryId ? (route('categories.level2', $categoryId) . '?open=' . $categoryId) : '#');

    $useModal = (bool) ($useModal ?? false);
    $useCategoryRedirect = (bool) ($useCategoryRedirect ?? false);
    $showBadges = (bool) ($showBadges ?? false);

    $productUrl = $productUrl ?? (function () use ($product, $category_id) {
        $url = route('product', $product->slug);
        if ($product->auction_product == 1) {
            $url = route('auction-product', $product->slug);
        }
        if (isset($category_id) && !empty($category_id)) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query(['category_id' => $category_id]);
        }
        return $url;
    })();

    $productModalUrl = $productModalUrl ?? (function () use ($product, $category_id) {
        $url = route('product.modal', $product->slug);
        if (isset($category_id) && !empty($category_id)) {
            $url .= '?' . http_build_query(['category_id' => $category_id]);
        }
        return $url;
    })();

    // Variants detection (covers different product representations across templates)
    $hasVariants = false;
    try {
        $colors = is_string($product->colors ?? null) ? json_decode($product->colors, true) : ($product->colors ?? []);
        $attributes = is_string($product->attributes ?? null) ? json_decode($product->attributes, true) : ($product->attributes ?? []);
        $choiceOptions = is_string($product->choice_options ?? null) ? json_decode($product->choice_options, true) : ($product->choice_options ?? []);
        $hasVariants = (is_array($colors) && count($colors) > 0) ||
            (is_array($attributes) && count($attributes) > 0) ||
            (is_array($choiceOptions) && count($choiceOptions) > 0);
    } catch (\Throwable $e) {
        $hasVariants = false;
    }
@endphp

<a href="{{ $productUrl }}"
    class="product-card {{ $useModal ? 'js-open-product-details' : '' }}"
    @if ($useModal)
        data-modal-url="{{ $productModalUrl }}"
        data-product-id="{{ $product->id }}"
        data-product-slug="{{ $product->slug }}"
    @endif
>
    <div class="image-wrapper">
        <img
            src="{{ $productImage }}"
            alt="{{ $productName }}"
            title="{{ $productName }}"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
        >

        @if ($showBadges)
            <div class="featured-badges-container">
                @if (function_exists('discount_in_percentage') && discount_in_percentage($product) > 0)
                    <span class="featured-badge featured-badge-discount">
                        -{{ discount_in_percentage($product) }}%
                    </span>
                @endif
                @if (!empty($product->wholesale_product))
                    <span class="featured-badge featured-badge-wholesale">
                        {{ translate('Wholesale') }}
                    </span>
                @endif
                @php
                    $customLabels = function_exists('get_custom_labels') ? get_custom_labels($product->custom_label_id) : null;
                @endphp
                @if ($customLabels)
                    @foreach ($customLabels as $customLabel)
                        <span class="featured-badge featured-badge-custom"
                            style="background-color:{{ $customLabel->background_color }}; color:{{ $customLabel->text_color }};">
                            {{ $customLabel->text }}
                        </span>
                    @endforeach
                @endif
            </div>
        @endif

        @if (($product->auction_product ?? 0) == 0)
            <div class="featured-action-icons">
                <button type="button"
                    class="featured-action-btn featured-inquiry-btn"
                    data-product-id="{{ $product->id }}"
                    data-has-variants="{{ $hasVariants ? 1 : 0 }}"
                    onclick="event.preventDefault(); event.stopPropagation(); featuredInquiryAction(this);"
                    title="{{ translate('Add to inquiry') }}">
                    <svg class="icon-default" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    <svg class="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                    <span class="btn-text">Add to Inquiry</span>
                </button>
            </div>
        @endif
    </div>

    <div class="product-info"
        @if($useCategoryRedirect && !empty($categoryUrl) && $categoryUrl !== '#')
            data-category-url="{{ $categoryUrl }}"
            onclick="event.preventDefault(); event.stopPropagation(); window.location.href=this.getAttribute('data-category-url');"
        @endif
    >
        <h3>{{ $productName }}</h3>
        <p class="featured-product-category">{{ $categoryName }}</p>
    </div>
</a>

@once
    <style>
        /* Reusable product card (home + category listings) */
        .product-card {
            background: #fff;
            border-radius: clamp(12px, 2vw, 16px);
            overflow: hidden;
            cursor: pointer;
            padding: clamp(12px, 2vw, 16px) clamp(12px, 2vw, 16px) clamp(8px, 1.5vw, 12px);
            transition: all .3s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        .product-card .image-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            border-radius: clamp(8px, 1.5vw, 12px);
            background: #f5f5f5;
        }
        .product-card .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: inherit;
            display: block;
            transition: transform .5s ease;
        }
        .product-card:hover .image-wrapper img { transform: scale(1.1); }

        .product-card .product-info {
            padding: clamp(10px, 2vw, 14px) 0 0 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .product-card .product-info h3 {
            font-size: clamp(0.95rem, 2vw, 1.1rem);
            font-weight: 600;
            color: #1a1a1a;
            margin: 0 0 4px 0;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            white-space: normal;
            line-height: 1.35;
            min-height: calc(1.35em * 2);
        }
        .product-card .featured-product-category {
            font-size: clamp(0.65rem, 1.2vw, 0.7rem);
            color: #6c757d;
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Action icons (match category-page cards) */
        .product-card .featured-action-icons {
            position: absolute;
            top: clamp(8px, 1.5vw, 12px);
            right: clamp(8px, 1.5vw, 12px);
            z-index: 3;
            display: flex;
            flex-direction: column;
            gap: clamp(6px, 1vw, 8px);
            opacity: 0;
            transform: translateX(10px);
            transition: all .3s ease;
        }
        .product-card:hover .featured-action-icons {
            opacity: 1;
            transform: translateX(0);
        }
        .product-card .featured-action-btn {
            width: clamp(32px, 4vw, 36px);
            height: clamp(32px, 4vw, 36px);
            border-radius: 50px;
            border: none;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
            color: #6c757d;
            position: relative;
            overflow: hidden;
            white-space: nowrap;
        }
        .product-card .featured-action-btn svg {
            width: clamp(14px, 2vw, 16px);
            height: clamp(14px, 2vw, 16px);
            flex-shrink: 0;
            transition: all .3s ease;
        }
        .product-card .featured-action-btn .btn-text {
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            font-size: clamp(11px, 1.5vw, 13px);
            font-weight: 600;
            margin-left: 0;
            transition: all .3s ease;
        }
        .product-card .featured-action-btn:hover {
            width: auto;
            padding: 0 clamp(12px, 2vw, 16px);
            border-radius: 50px;
        }
        .product-card .featured-action-btn:hover .btn-text {
            max-width: 150px;
            opacity: 1;
            margin-left: clamp(6px, 1vw, 8px);
        }

        .product-card .featured-inquiry-btn {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: #fff;
            box-shadow: 0 2px 10px rgba(33, 150, 243, 0.25);
        }
        .product-card .featured-inquiry-btn:hover {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            box-shadow: 0 10px 22px rgba(33, 150, 243, 0.35);
            transform: scale(1.05);
        }
        .product-card .featured-inquiry-btn .icon-check { display: none; }
        .product-card .featured-inquiry-btn.is-added .icon-default { display: none; }
        .product-card .featured-inquiry-btn.is-added .icon-check { display: block; }
        .product-card .featured-inquiry-btn.is-loading { opacity: .92; pointer-events: none; }
        .product-card .featured-inquiry-btn.is-added::before {
            content: "";
            position: absolute;
            inset: -6px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(22,163,74,.35) 0%, rgba(22,163,74,0) 70%);
            animation: productCardRipple .6s ease-out;
            pointer-events: none;
        }
        @keyframes productCardRipple {
            0% { opacity: 0; transform: scale(.7); }
            30% { opacity: 1; }
            100% { opacity: 0; transform: scale(1.25); }
        }

        /* Badges (optional) */
        .product-card .featured-badges-container {
            position: absolute;
            top: clamp(8px, 1.5vw, 12px);
            left: clamp(8px, 1.5vw, 12px);
            z-index: 3;
            display: flex;
            flex-direction: column;
            gap: clamp(4px, .8vw, 6px);
        }
        .product-card .featured-badge {
            display: inline-block;
            padding: clamp(3px,.6vw,4px) clamp(6px,1vw,8px);
            border-radius: clamp(3px,.6vw,4px);
            font-size: clamp(10px,1.5vw,11px);
            font-weight: 600;
            line-height: 1.2;
            box-shadow: 0 2px 4px rgba(0,0,0,.15);
        }
        .product-card .featured-badge-discount { background: #dc3545; color: #fff; }
        .product-card .featured-badge-wholesale { background: #495057; color: #fff; }

        @media (max-width: 768px) {
            .product-card .featured-action-icons { opacity: 1; transform: translateX(0); }
        }
    </style>

    <script>
        // One global inquiry function (reused everywhere)
        window.featuredInquiryAction = window.featuredInquiryAction || function (btnEl) {
            try {
                const btn = btnEl;
                const productId = parseInt(btn?.dataset?.productId || '0', 10);
                const hasVariants = (btn?.dataset?.hasVariants || '0') === '1';
                if (!productId) return;

                if (hasVariants) {
                    if (typeof showAddToCartModal === 'function') {
                        showAddToCartModal(productId);
                    }
                    return;
                }

                if (btn.classList.contains('is-loading')) return;
                btn.classList.add('is-loading');

                $.ajax({
                    type: "POST",
                    url: "{{ route('cart.addToCart') }}",
                    data: { id: productId, quantity: 1, _token: "{{ csrf_token() }}" },
                    success: function (data) {
                        if (data) {
                            if (data.cart_count !== undefined) {
                                const c = (data.cart_count === undefined || data.cart_count === null) ? 0 : data.cart_count;
                                $('.cart-count').html(c).attr('data-count', c);
                            }
                            if (data.nav_cart_view) $('#cart_items').html(data.nav_cart_view);
                            if (typeof flashHeaderCartSuccess === 'function') flashHeaderCartSuccess();

                            btn.classList.remove('is-loading');
                            btn.classList.add('is-added');
                            window.setTimeout(() => btn.classList.remove('is-added'), 1200);

                            try {
                                if (typeof AIZ !== 'undefined' && AIZ.plugins && typeof AIZ.plugins.notify === 'function') {
                                    AIZ.plugins.notify('success', "{{ translate('Added to inquiry') }}");
                                }
                            } catch (e) {}
                        }
                    },
                    error: function () {
                        btn.classList.remove('is-loading');
                        try {
                            if (typeof AIZ !== 'undefined' && AIZ.plugins && typeof AIZ.plugins.notify === 'function') {
                                AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
                            }
                        } catch (e) {}
                    }
                });
            } catch (e) {}
        };
    </script>
@endonce
