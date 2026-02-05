@extends('frontend.layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);;
            --primary-hover: #5FB3F6;
        }

        /* ✅ IMPORTANT: do NOT target all .container.py-4 globally (it breaks other sections) */
        .cart-section {
            background: #f8fafc;
            padding: 60px 0;
            min-height: calc(100vh - 80px);
        }

        .cart-section .container {
            padding-top: 40px !important;
        }

        .inquiry-title {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -.5px;
            color: #1e293b;
            margin-bottom: 24px;
        }

        .product-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(8, 145, 178, .08);
            margin-bottom: 16px;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            box-shadow: 0 8px 24px rgba(8, 145, 178, .15);
        }

        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 14px;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 0;
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);;
            color: #fff;
            font-weight: 700;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            background: #5FB3F6;
            transform: scale(1.05);
        }

        .qty-value {
            width: 60px;
            border: 0;
            background: transparent;
            text-align: center;
            font-weight: 700;
            font-size: 18px;
            color: #1e293b;
        }

        .note-input {
            border-radius: 12px;
            height: 44px;
            border: 1px solid #e2e8f0;
            padding: 0 16px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .note-input:focus {
            border-color: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);;
            box-shadow: 0 0 0 3px rgba(8, 145, 178, .1);
        }

        .trash-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 0;
            background: #FEE2E2;
            color: #DC2626;
            transition: all 0.2s ease;
        }

        .trash-btn:hover {
            background: #DC2626;
            color: #fff;
        }

        .summary-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 4px 14px rgba(8, 145, 178, .08);
            position: sticky;
            top: 120px;
        }

        .pill {
            border-radius: 999px;
            padding: 12px 20px;
            font-weight: 700;
            font-size: 14px;
        }

        .pill.bg-primary {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); !important;
        }

        .avatar {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar i {
            color: #fff;
            font-size: 26px;
        }

        .request-btn {
            height: 56px;
            border-radius: 14px;
            font-weight: 700;
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);;
            border: 0;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .request-btn:hover {
            background: #5FB3F6;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(8, 145, 178, .3);
        }

        .request-btn span {
            color: #fff !important;
        }

        .product-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .product-category {
            color: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);;
            font-weight: 600;
            font-size: 13px;
        }

        .product-description {
            color: #64748b;
            font-size: 13px;
            line-height: 1.5;
        }

        .summary-title {
            font-weight: 700;
            font-size: 18px;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            color: #64748b;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .user-info-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 15px;
        }

        .user-info-detail {
            color: #64748b;
            font-size: 13px;
        }

        .edit-link {
            color: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
        }

        .edit-link:hover {
            color: #5FB3F6;
            text-decoration: underline;
        }

        /* ✅ Mobile: stop sticky (prevents broken look) */
        @media (max-width: 992px) {
            .summary-card {
                position: static;
                top: auto;
            }

            .inquiry-title {
                font-size: 32px;
            }
        }

        /* ============== Section 3: Items & Updates ============== */
        .items-section {
            background: #f8fafc;
            padding: 60px 0;
        }

        .items-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }

        .items-tab {
            border: 1px solid #e2e8f0;
            background: #fff;
            padding: 10px 14px;
            border-radius: 14px;
            font-weight: 800;
            color: #1e293b;
            transition: .2s;
        }

        .items-tab.active {
            border-color: transparent;
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 8px 16px rgba(8, 145, 178, .22);
        }

        .item-row {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 16px;
            display: flex;
            gap: 16px;
            align-items: center;
            margin-bottom: 14px;
            box-shadow: 0 4px 14px rgba(8, 145, 178, .06);
        }

        .item-image {
            width: 96px;
            height: 96px;
            object-fit: cover;
            border-radius: 14px;
            flex: 0 0 auto;
        }

        .item-info {
            flex: 1 1 auto;
            min-width: 0;
        }

        .item-qty {
            background: rgba(8, 145, 178, .12);
            color: var(--primary-color);
            font-weight: 900;
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 999px;
        }

        .item-price-badge {
            flex: 0 0 auto;
            background: #0f172a;
            color: #fff;
            font-weight: 900;
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 13px;
            white-space: nowrap;
        }

        .pagination-info {
            margin-top: 18px;
            color: #64748b;
            font-weight: 800;
            text-align: center;
        }

        @media (max-width: 992px) {
            .item-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .item-price-badge {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <style>
        /* ============== Categories Dropdown Section ============== */
        .categories-dropdown-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            padding: 130px 0;
            margin-bottom: 0;
        }

        .section-header-wrapper {
            text-align: center;
            margin-bottom: 50px;
        }


        .categories-dropdown-subtitle {
            font-size: 16px;
            color: #64748b;
            font-weight: 600;
            margin: 0;
        }

        /* Category Dropdown Card */
        .category-dropdown-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(8, 145, 178, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .category-dropdown-card:hover {
            box-shadow: 0 8px 30px rgba(8, 145, 178, 0.15);
        }

        /* Dropdown Header */
        .category-dropdown-header {
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .category-dropdown-header:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            transition: all 0.3s ease;
        }

        .header-icon.main {
            background: linear-gradient(135deg, linear-gradient(135deg, #1976D2 0%, #1565C0 100%); 0%, #5FB3F6 100%);
        }

        .header-icon.sub {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        }

        .header-icon.sub-sub {
            background: linear-gradient(135deg, #EC4899 0%, #DB2777 100%);
        }

        .header-icon.products {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .header-text {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .header-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .header-count {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
        }

        .dropdown-arrow {
            font-size: 20px;
            color: #64748b;
            transition: transform 0.3s ease;
        }

        .category-dropdown-header.active .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Dropdown List */
        .category-dropdown-list {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .category-dropdown-list.open {
            max-height: 600px;
            overflow-y: auto;
        }

        /* Custom Scrollbar */
        .category-dropdown-list::-webkit-scrollbar {
            width: 6px;
        }

        .category-dropdown-list::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .category-dropdown-list::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        /* Category Item */
        .category-item {
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .category-item:last-child {
            border-bottom: none;
        }

        .category-item:hover {
            background: #f8fafc;
        }

        .category-item.in-cart {
            background: #f0fdf4;
        }

        .category-item-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
            cursor: pointer;
        }

        /* Category Bullets */
        .category-bullet {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .main-bullet {
            background: linear-gradient(135deg, linear-gradient(135deg, #1976D2 0%, #1565C0 100%); 0%, #5FB3F6 100%);
            box-shadow: 0 2px 8px rgba(8, 145, 178, 0.3);
        }

        .sub-bullet {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
            box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
        }

        .sub-sub-bullet {
            background: linear-gradient(135deg, #EC4899 0%, #DB2777 100%);
            box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3);
        }

        .category-name-wrapper {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }

        .category-name {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .category-parent {
            font-size: 12px;
            font-weight: 500;
            color: #94a3b8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Category Badge */
        .category-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .in-cart-badge {
            background: #dcfce7;
            color: #16a34a;
        }

        .in-cart-badge i {
            font-size: 14px;
        }

        /* Add Button */
        .category-add-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .category-add-btn:hover {
            background: linear-gradient(135deg, var(--primary-hover) 0%, #075985 100%);
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3);
        }

        .category-add-btn i {
            font-size: 16px;
            font-weight: 700;
        }

        .category-add-btn.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .category-add-btn.loading i {
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Product Item Styles */
        .product-item {
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-item:hover {
            background: #f0fdf4;
            transform: translateX(4px);
        }

        .product-thumbnail {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid #e2e8f0;
        }

        .product-info {
            flex: 1;
            min-width: 0;
        }

        .product-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-price {
            font-size: 13px;
            font-weight: 700;
            color: #10B981;
        }

        .product-stock {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 6px;
            background: #dbeafe;
            color: #1e40af;
        }

        .product-add-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }


        .product-add-btn:hover {
            background: linear-gradient(135deg, var(--primary-hover) 0%, #075985 100%);
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3);
        }

        .product-add-btn i {
            font-size: 16px;
            font-weight: 700;
        }

        .product-add-btn.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .product-add-btn.loading i {
            animation: spin 0.6s linear infinite;
        }

        .product-stock.out-of-stock {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Empty State */
        .empty-state {
            padding: 40px 24px;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 12px;
            display: block;
            opacity: 0.5;
        }

        .empty-state span {
            font-size: 14px;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .categories-dropdown-section {
                padding: 50px 0;
            }

            .categories-dropdown-title {
                font-size: 28px;
            }

            .category-dropdown-list.open {
                max-height: 400px;
            }
        }

        @media (max-width: 768px) {
            .section-header-wrapper {
                margin-bottom: 30px;
            }

            .category-dropdown-header {
                padding: 20px;
            }

            .header-icon {
                width: 44px;
                height: 44px;
                font-size: 20px;
            }

            .header-title {
                font-size: 16px;
            }

            .category-item {
                padding: 14px 20px;
            }

            .category-name {
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            .categories-dropdown-title {
                font-size: 24px;
                flex-direction: column;
                gap: 8px;
            }

            .header-left {
                gap: 12px;
            }

            .category-dropdown-header {
                padding: 16px;
            }

            .header-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .category-item {
                padding: 12px 16px;
            }
        }
    </style>



    <section class="cart-section">
        <div class="container py-4">

            <div class="mb-4">
                <div class="inquiry-title">{{ translate('Inquiry') }}</div>
                <p class="text-muted fs-16" style="color: #64748b; font-weight: 500;">{{ translate('Want to inquire about anything else?') }} <a href="{{ route('categories.all') }}" class="text-primary fw-600" style="text-decoration: underline;">{{ translate('Browse Categories') }}</a></p>
            </div>

            <div class="row g-4">

                <!-- Left: Products -->
                <div class="col-lg-8" id="cart-items-container">
                    @include('frontend.partials.cart.cart_details', ['carts' => $carts])
                </div>

                <!-- Right: Summary -->
                <div class="col-lg-4">
                    <div class="card summary-card">
                        <div class="card-body p-4">

                            <div class="summary-title">{{ translate('Cart Summary') }}</div>

                            @php
                                $totalProducts = isset($carts) ? count($carts) : 0;
                                $totalItems = 0;
                                if (isset($carts) && count($carts) > 0) {
                                    foreach ($carts as $cart) {
                                        $totalItems += $cart['quantity'] ?? 1;
                                    }
                                }
                            @endphp

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="pill bg-primary text-white">{{ translate('Total Products') }}</div>
                                <div class="pill bg-primary text-white" id="total-products">
                                    {{ str_pad($totalProducts, 2, '0', STR_PAD_LEFT) }}</div>
                            </div>

                            <div class="summary-row">
                                <span>{{ translate('Products') }}</span>
                                <span id="summary-products">{{ $totalProducts }} {{ translate('Products') }}</span>
                            </div>

                            <div class="summary-row mb-4">
                                <span>{{ translate('Items') }}</span>
                                <span id="summary-items">{{ $totalItems }} {{ translate('Items') }}</span>
                            </div>

                            <hr style="border-color:#e2e8f0;margin:24px 0">

                            <textarea class="form-control mb-4 note-input" rows="3" id="inquiry-note"
                                placeholder="{{ translate('Note...') }}"></textarea>

                            <div id="inquiry-sent-msg" class="mb-4" style="display:none;">
                                <div class="alert alert-success mb-0" style="border-radius:12px; font-weight:700;">
                                    Your request has been sent.
                                </div>
                            </div>

                            <button type="button" id="request-offer-btn" class="btn w-100 request-btn d-flex align-items-center justify-content-center"
                                onclick="submitInquiryRequest()"
                                @if ($totalProducts == 0) disabled style="opacity: 0.6;" @endif>
                                <span>{{ translate('Request Offer') }}</span>
                            </button>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ✅ Categories & Products Dropdown List Section --}}
    <section class="categories-dropdown-section">
        <div class="container">
            <div class="section-header-wrapper">
                <p class="categories-dropdown-subtitle">
                    {{ translate('Select categories to add to your inquiry or browse products') }}
                </p>
            </div>

            <div class="row g-4">
                @php
                    // Group categories by level
                    $mainCategories = $Category->where('level', 0);
                    $subCategories = $Category->where('level', 1);
                    $subSubCategories = $Category->where('level', 2);

                    // Check which categories are already in cart
                    $cartCategoryIds = [];
                    if (isset($carts) && count($carts) > 0) {
                        foreach ($carts as $cart) {
                            if (isset($cart['category_id'])) {
                                $cartCategoryIds[] = $cart['category_id'];
                            }
                        }
                    }

                    $allProducts = \App\Models\Product::where('published', 1)
                        ->where('approved', 1)
                        ->select('id', 'name', 'slug', 'thumbnail_img', 'unit_price', 'current_stock', 'category_id')
                        ->orderBy('num_of_sale', 'desc')
                        ->get()
                        ->groupBy('category_id');
                @endphp

                {{-- Main Categories (Level 0) --}}
                <div class="col-lg-3 col-md-6">
                    <div class="category-dropdown-card">
                        <div class="category-dropdown-header" onclick="toggleCategoryList('main-categories')">
                            <div class="header-left">
                                <div class="header-icon main">
                                    <i class="bi bi-folder-fill"></i>
                                </div>
                                <div class="header-text">
                                    <h3 class="header-title">{{ translate('Main Categories') }}</h3>
                                    <span class="header-count">{{ $mainCategories->count() }}
                                        {{ translate('Categories') }}</span>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                        </div>

                        <div class="category-dropdown-list" id="main-categories">
                            @if ($mainCategories->count() > 0)
                                @foreach ($mainCategories as $category)
                                    @php
                                        $categoryName = $category->getTranslation('name', $lang ?? 'en');
                                        $inCart = in_array($category->id, $cartCategoryIds);
                                    @endphp

                                    <div class="category-item {{ $inCart ? 'in-cart' : '' }}"
                                        data-category-id="{{ $category->id }}">
                                        <div class="category-item-left"
                                            onclick="showCategoryProducts({{ $category->id }}, '{{ $categoryName }}')">
                                            <div class="category-bullet main-bullet"></div>
                                            <span class="category-name">{{ $categoryName }}</span>
                                        </div>

                                        @if ($inCart)
                                            <span class="category-badge in-cart-badge">
                                                <i class="bi bi-check-circle-fill"></i>
                                                {{ translate('In Cart') }}
                                            </span>
                                        @else
                                            <button class="category-add-btn" data-id="{{ $category->id }}"
                                                data-name="{{ $categoryName }}" data-level="main"
                                                title="{{ translate('Add to Cart') }}">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <span>{{ translate('No main categories found') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sub Categories (Level 1) --}}
                <div class="col-lg-3 col-md-6">
                    <div class="category-dropdown-card">
                        <div class="category-dropdown-header" onclick="toggleCategoryList('sub-categories')">
                            <div class="header-left">
                                <div class="header-icon sub">
                                    <i class="bi bi-folder2-open"></i>
                                </div>
                                <div class="header-text">
                                    <h3 class="header-title">{{ translate('Sub Categories') }}</h3>
                                    <span class="header-count">{{ $subCategories->count() }}
                                        {{ translate('Categories') }}</span>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                        </div>

                        <div class="category-dropdown-list" id="sub-categories">
                            @if ($subCategories->count() > 0)
                                @foreach ($subCategories as $category)
                                    @php
                                        $categoryName = $category->getTranslation('name', $lang ?? 'en');
                                        $inCart = in_array($category->id, $cartCategoryIds);

                                        // Get parent category name
                                        $parentCategory = $Category->where('id', $category->parent_id)->first();
                                        $parentName = $parentCategory
                                            ? $parentCategory->getTranslation('name', $lang ?? 'en')
                                            : '';
                                    @endphp

                                    <div class="category-item {{ $inCart ? 'in-cart' : '' }}"
                                        data-category-id="{{ $category->id }}">
                                        <div class="category-item-left"
                                            onclick="showCategoryProducts({{ $category->id }}, '{{ $categoryName }}')">
                                            <div class="category-bullet sub-bullet"></div>
                                            <div class="category-name-wrapper">
                                                <span class="category-name">{{ $categoryName }}</span>
                                                @if ($parentName)
                                                    <span class="category-parent">{{ $parentName }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($inCart)
                                            <span class="category-badge in-cart-badge">
                                                <i class="bi bi-check-circle-fill"></i>
                                                {{ translate('In Cart') }}
                                            </span>
                                        @else
                                            <button class="category-add-btn" data-id="{{ $category->id }}"
                                                data-name="{{ $categoryName }}" data-level="sub"
                                                title="{{ translate('Add to Cart') }}">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <span>{{ translate('No sub categories found') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sub-Sub Categories (Level 2) --}}
                <div class="col-lg-3 col-md-6">
                    <div class="category-dropdown-card">
                        <div class="category-dropdown-header" onclick="toggleCategoryList('sub-sub-categories')">
                            <div class="header-left">
                                <div class="header-icon sub-sub">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div class="header-text">
                                    <h3 class="header-title">{{ translate('Sub-Sub Categories') }}</h3>
                                    <span class="header-count">{{ $subSubCategories->count() }}
                                        {{ translate('Categories') }}</span>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                        </div>

                        <div class="category-dropdown-list" id="sub-sub-categories">
                            @if ($subSubCategories->count() > 0)
                                @foreach ($subSubCategories as $category)
                                    @php
                                        $categoryName = $category->getTranslation('name', $lang ?? 'en');
                                        $inCart = in_array($category->id, $cartCategoryIds);

                                        // Get parent category name
                                        $parentCategory = $Category->where('id', $category->parent_id)->first();
                                        $parentName = $parentCategory
                                            ? $parentCategory->getTranslation('name', $lang ?? 'en')
                                            : '';
                                    @endphp

                                    <div class="category-item {{ $inCart ? 'in-cart' : '' }}"
                                        data-category-id="{{ $category->id }}">
                                        <div class="category-item-left"
                                            onclick="showCategoryProducts({{ $category->id }}, '{{ $categoryName }}')">
                                            <div class="category-bullet sub-sub-bullet"></div>
                                            <div class="category-name-wrapper">
                                                <span class="category-name">{{ $categoryName }}</span>
                                                @if ($parentName)
                                                    <span class="category-parent">{{ $parentName }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($inCart)
                                            <span class="category-badge in-cart-badge">
                                                <i class="bi bi-check-circle-fill"></i>
                                                {{ translate('In Cart') }}
                                            </span>
                                        @else
                                            <button class="category-add-btn" data-id="{{ $category->id }}"
                                                data-name="{{ $categoryName }}" data-level="sub-sub"
                                                title="{{ translate('Add to Cart') }}">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <span>{{ translate('No sub-sub categories found') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Products List (4th Column) --}}
                <div class="col-lg-3 col-md-6">
                    <div class="category-dropdown-card">
                        <div class="category-dropdown-header active" onclick="toggleProductsList()">
                            <div class="header-left">
                                <div class="header-icon products">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div class="header-text">
                                    <h3 class="header-title" id="products-title">{{ translate('Products') }}</h3>
                                    <span class="header-count"
                                        id="products-count">{{ translate('Select a category') }}</span>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                        </div>

                        <div class="category-dropdown-list open" id="products-list">
                            <div class="empty-state">
                                <i class="bi bi-box-seam"></i>
                                <span>{{ translate('Click on any category to view products') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Hidden Products Data --}}
    <script id="products-data" type="application/json">
{!! json_encode($allProducts) !!}
</script>

@endsection

@section('script')
    <script>
        // Update cart quantity
        function updateCartQuantity(cartId, change) {
            var qtyInput = document.getElementById('qty-' + cartId);

            // ✅ safe parse
            var currentQty = parseInt(qtyInput ? qtyInput.value : 1, 10);
            if (isNaN(currentQty) || currentQty < 1) currentQty = 1;

            var newQty = currentQty + change;

            // لو نزلت عن 1 احذف
            if (newQty < 1) {
                removeCartItem(cartId);
                return;
            }

            $.ajax({
                type: "POST",
                url: '{{ route('cart.updateQuantity') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: String(cartId),
                    quantity: parseInt(newQty, 10)
                },
                success: function(data) {

                    if (data && data.cart_view !== undefined) {
                        $('#cart-items-container').html(data.cart_view);
                    } else {
                        // fallback: حدّث input مباشرة
                        if (qtyInput) qtyInput.value = newQty;
                    }

                    if (typeof updateNavCart === 'function' && data && data.nav_cart_view !== undefined) {
                        updateNavCart(data.nav_cart_view, data.cart_count);
                    }

                    updateCartSummary();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
                    }
                }
            });
        }

        // Remove cart item
        function removeCartItem(cartId) {
            $.ajax({
                type: "POST",
                url: '{{ route('cart.removeFromCart') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: String(cartId)
                },
                success: function(data) {

                    // ✅ replace cart details
                    if (data && data.cart_view !== undefined) {
                        $('#cart-items-container').html(data.cart_view);
                    }

                    // ✅ update nav cart
                    if (typeof updateNavCart === 'function' && data && data.nav_cart_view !== undefined) {
                        updateNavCart(data.nav_cart_view, data.cart_count);
                    }

                    updateCartSummary();

                    if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', "{{ translate('Item removed from cart') }}");
                    }
                },
                error: function() {
                    if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
                    }
                }
            });
        }

        // Update cart summary
        function updateCartSummary() {
            var totalProducts = $('#cart-items-container .cart-row').length;
            var totalItems = 0;

            $('#cart-items-container .qty-value').each(function() {
                var v = parseInt($(this).val(), 10);
                totalItems += isNaN(v) ? 0 : v;
            });

            $('#cart-items-container .cart-row[data-type="category"]').each(function() {
                var $row = $(this);
                var cartId = $row.data('cart-id');

                var q = 1;
                var el = document.getElementById('qty-' + cartId);
                if (el) {
                    var vv = parseInt(el.value, 10);
                    if (!isNaN(vv) && vv > 0) q = vv;
                }

                if (!el) totalItems += q;
            });

            $('#total-products').text(totalProducts.toString().padStart(2, '0'));
            $('#summary-products').text(totalProducts + ' {{ translate('Products') }}');
            $('#summary-items').text(totalItems + ' {{ translate('Items') }}');

            // disable button if empty
            $('#request-offer-btn').prop('disabled', totalProducts === 0);
        }

        // Collect items from cart_details and send to InquiryController
        function submitInquiryRequest() {
            var $btn = $('#request-offer-btn');

            // prevent double click
            if ($btn.prop('disabled')) return;

            var items = [];

            $('#cart-items-container .cart-row').each(function() {
                var $row = $(this);

                var cartId = $row.data('cart-id');
                var type = $row.data('type'); // product / category
                var productId = $row.data('product-id') || null;
                var categoryId = $row.data('category-id') || null;

                // quantity
                var qtyEl = document.getElementById('qty-' + cartId);
                var qty = 1;
                if (qtyEl) {
                    var parsed = parseInt(qtyEl.value, 10);
                    qty = (isNaN(parsed) || parsed < 1) ? 1 : parsed;
                }

                // note for this item
                var noteEl = document.getElementById('note-' + cartId);
                var itemNote = noteEl ? noteEl.value.trim() : '';

                items.push({
                    cart_id: String(cartId),
                    type: String(type || ''),
                    product_id: productId ? parseInt(productId, 10) : null,
                    category_id: categoryId ? parseInt(categoryId, 10) : null,
                    quantity: qty,
                    note: itemNote
                });
            });

            if (!items.length) {
                if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('warning', "{{ translate('Cart is empty') }}");
                }
                return;
            }

            var note = $('#inquiry-note').val();

            // Debug: log items being sent
            console.log('Sending items:', items);
            console.log('Items JSON:', JSON.stringify(items));

            // UI
            $btn.prop('disabled', true);
            $btn.find('span').text("{{ translate('Sending...') }}");

            $.ajax({
                type: "POST",
                url: "{{ route('inquiry.requestOffer') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    note: note,
                    items: JSON.stringify(items)
                },
                success: function(res) {
                    if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', "{{ translate('Your request has been sent') }}");
                    }

                    // ✅ redirect to inquiries page
                    window.location.href = "{{ route('cart.inquiry') }}";
                },
                error: function(xhr) {
                    console.log('Error:', xhr.status, xhr.responseText);
                    var errorMsg = "{{ translate('Something went wrong') }}";
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMsg = response.message;
                        }
                    } catch(e) {}
                    if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', errorMsg);
                    }
                    $btn.prop('disabled', false);
                    $btn.find('span').text("{{ translate('Request Offer') }}");
                }
            });
        }

        // run once on load
        $(document).ready(function() {
            updateCartSummary();
        });
    </script>
    <script>
        // Global products data
        var productsData = {};

        $(document).ready(function() {
            // Parse products data from JSON
            try {
                var jsonData = $('#products-data').text();
                productsData = JSON.parse(jsonData);
                console.log('Products Data Loaded:', productsData);
            } catch (e) {
                console.error('Error parsing products data:', e);
            }

            // Open Main Categories by default
            var firstList = document.getElementById('main-categories');
            if (firstList) {
                firstList.classList.add('open');
                firstList.previousElementSibling.classList.add('active');
            }
        });

        // ✅ Toggle Category Dropdown Lists
        function toggleCategoryList(listId) {
            var list = document.getElementById(listId);
            var header = list.previousElementSibling;

            if (list.classList.contains('open')) {
                list.classList.remove('open');
                header.classList.remove('active');
            } else {
                // Close all other dropdowns (except products)
                document.querySelectorAll('.category-dropdown-list').forEach(function(el) {
                    if (el.id !== 'products-list') {
                        el.classList.remove('open');
                    }
                });
                document.querySelectorAll('.category-dropdown-header').forEach(function(el) {
                    if (el.querySelector('#products-title') === null) {
                        el.classList.remove('active');
                    }
                });

                // Open clicked dropdown
                list.classList.add('open');
                header.classList.add('active');
            }
        }

        // ✅ Toggle Products List (يفتح ويقفل)
        function toggleProductsList() {
            var list = document.getElementById('products-list');
            var header = list.previousElementSibling;

            if (list.classList.contains('open')) {
                list.classList.remove('open');
                header.classList.remove('active');
            } else {
                list.classList.add('open');
                header.classList.add('active');
            }
        }

        // ✅ Show Products by Category ID
        function showCategoryProducts(categoryId, categoryName) {
            var productsList = $('#products-list');
            var productsTitle = $('#products-title');
            var productsCount = $('#products-count');

            console.log('Looking for category:', categoryId);
            console.log('Available categories:', Object.keys(productsData));

            // Update header
            productsTitle.text(categoryName);

            // Get products for this category - تأكد من التحويل لـ string لأن المفاتيح في JSON دايماً strings
            var categoryProducts = productsData[String(categoryId)] || productsData[categoryId] || [];

            console.log('Products found:', categoryProducts.length);

            if (categoryProducts.length > 0) {
                var productsHtml = '';

                // Limit to first 20 products
                var limitedProducts = categoryProducts.slice(0, 20);

                limitedProducts.forEach(function(product) {
                    var thumbnailUrl = product.thumbnail_img ?
                        '{{ asset('') }}' + product.thumbnail_img :
                        '{{ static_asset('assets/img/placeholder.jpg') }}';

                    var productUrl = '{{ url('product') }}/' + product.slug;
                    var price = parseFloat(product.unit_price).toFixed(2);
                    var stockStatus = product.current_stock > 0 ?
                        'in-stock' : 'out-of-stock';
                    var stockText = product.current_stock > 0 ?
                        '{{ translate('In Stock') }}' : '{{ translate('Out of Stock') }}';

                    productsHtml += `
                    <div class="product-item-wrapper" data-product-id="${product.id}">
                        <div class="product-item">
                            <img src="${thumbnailUrl}" alt="${product.name}" class="product-thumbnail" onerror="this.src='{{ static_asset('assets/img/placeholder.jpg') }}'">
                            <div class="product-info">
                                <div class="product-name">${product.name}</div>
                            </div>
                            ${product.current_stock > 0 ? `
                                                    <button class="product-add-btn"
                                                        data-product-id="${product.id}"
                                                        data-product-name="${product.name}"
                                                        title="{{ translate('Add to Cart') }}">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                ` : ''}
                        </div>
                    </div>
                `;
                });

                productsList.html(productsHtml);
                productsCount.text(categoryProducts.length + ' {{ translate('Products') }}');

                // فتح الـ products list تلقائياً
                if (!productsList.hasClass('open')) {
                    productsList.addClass('open');
                    productsList.prev('.category-dropdown-header').addClass('active');
                }
            } else {
                // No products found
                productsList.html(`
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <span>{{ translate('No products found in this category') }}</span>
                </div>
            `);
                productsCount.text('0 {{ translate('Products') }}');
            }
        }

        // ✅ Add Product to Cart
        $(document).on('click', '.product-add-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $btn = $(this);
            var productId = $btn.data('product-id');
            var productName = $btn.data('product-name');

            // Prevent double click
            if ($btn.hasClass('loading')) return;

            // Add loading state
            $btn.addClass('loading');
            var originalIcon = $btn.find('i').attr('class');
            $btn.find('i').attr('class', 'bi bi-hourglass-split');

            $.ajax({
                type: "POST",
                url: '{{ route('cart.addToCart') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: productId,
                    quantity: 1
                },
                success: function(data) {
                    if (data && data.status == 1) {
                        // Update cart count in navbar
                        if (data.cart_count !== undefined) {
                            $('.cart-count').html(data.cart_count);
                        }

                        // Show success message
                        if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                            AIZ.plugins.notify('success', productName +
                                ' {{ translate('added to cart successfully') }}');
                        }

                        // Change button to success state
                        $btn.removeClass('loading');
                        $btn.find('i').attr('class', 'bi bi-check-circle-fill');
                        $btn.css('background', 'linear-gradient(135deg, #10B981 0%, #059669 100%)');

                        // Reset button after 2 seconds
                        setTimeout(function() {
                            $btn.find('i').attr('class', originalIcon);
                            $btn.css('background', '');
                        }, 2000);

                    } else {
                        // Error
                        $btn.removeClass('loading');
                        $btn.find('i').attr('class', originalIcon);

                        if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                            AIZ.plugins.notify('danger', data.message ||
                                '{{ translate('Something went wrong') }}');
                        }
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    $btn.removeClass('loading');
                    $btn.find('i').attr('class', originalIcon);

                    if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                }
            });
        });

        // ✅ Add Category to Cart from Dropdown
        $(document).on('click', '.category-add-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $btn = $(this);
            var categoryId = $btn.data('id');
            var categoryName = $btn.data('name');
            var categoryLevel = $btn.data('level');

            // Prevent double click
            if ($btn.hasClass('loading')) return;

            // Add loading state
            $btn.addClass('loading');
            var originalIcon = $btn.find('i').attr('class');
            $btn.find('i').attr('class', 'bi bi-hourglass-split');

            $.ajax({
                type: "POST",
                url: '{{ route('cart.addCategoryToCart') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: categoryId
                },
                success: function(data) {
                    if (data && data.status == 1) {
                        // Update cart count in navbar
                        if (data.cart_count !== undefined) {
                            $('.cart-count').html(data.cart_count);
                        }

                        // Show success message
                        if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                            if (data.message === 'Category already in cart') {
                                AIZ.plugins.notify('warning', categoryName +
                                    ' {{ translate('is already in cart') }}');
                            } else {
                                AIZ.plugins.notify('success', categoryName +
                                    ' {{ translate('added to cart successfully') }}');
                            }
                        }

                        // Change button to "In Cart" badge
                        var $categoryItem = $btn.closest('.category-item');
                        $categoryItem.addClass('in-cart');

                        $btn.replaceWith(
                            '<span class="category-badge in-cart-badge">' +
                            '<i class="bi bi-check-circle-fill"></i>' +
                            '{{ translate('In Cart') }}' +
                            '</span>'
                        );

                        // Update cart summary if on same page
                        if (typeof updateCartSummary === 'function') {
                            setTimeout(function() {
                                location.reload();
                            }, 800);
                        }

                    } else {
                        // Error
                        $btn.removeClass('loading');
                        $btn.find('i').attr('class', originalIcon);

                        if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                            AIZ.plugins.notify('danger', data.message ||
                                '{{ translate('Something went wrong') }}');
                        }
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    $btn.removeClass('loading');
                    $btn.find('i').attr('class', originalIcon);

                    if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                }
            });
        });
    </script>
@endsection
