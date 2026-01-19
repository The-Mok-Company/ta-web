@extends('frontend.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* Fix content going under navbar */
    .container.py-4 {
        padding-top: 120px !important;
    }

    @media (max-width: 768px) {
        .container.py-4 {
            padding-top: 100px !important;
        }
    }

    /* Main color theme */
    :root {
        --primary-color: #0891B2;
        --primary-hover: #0E7490;
    }

    .inquiry-title{
        font-size:42px;
        font-weight:800;
        letter-spacing:-.5px;
        color:#1e293b;
        margin-bottom: 24px;
    }

    .product-card{
        border:0;
        border-radius:16px;
        box-shadow:0 4px 14px rgba(8,145,178,.08);
        margin-bottom:16px;
        transition:all 0.3s ease;
    }
    .product-card:hover{
        box-shadow:0 8px 24px rgba(8,145,178,.15);
    }

    .product-img{
        width:80px;
        height:80px;
        object-fit:cover;
        border-radius:14px;
    }

    .qty-btn{
        width:40px;
        height:40px;
        border-radius:50%;
        border:0;
        background:#0891B2;
        color:#fff;
        font-weight:700;
        font-size:18px;
        display:flex;
        align-items:center;
        justify-content:center;
        transition:all 0.2s ease;
    }
    .qty-btn:hover{
        background:#0E7490;
        transform:scale(1.05);
    }

    .qty-value{
        width:60px;
        border:0;
        background:transparent;
        text-align:center;
        font-weight:700;
        font-size:18px;
        color:#1e293b;
    }

    .note-input{
        border-radius:12px;
        height:44px;
        border:1px solid #e2e8f0;
        padding:0 16px;
        font-size:14px;
        transition:all 0.2s ease;
    }
    .note-input:focus{
        border-color:#0891B2;
        box-shadow:0 0 0 3px rgba(8,145,178,.1);
    }

    .trash-btn{
        width:40px;
        height:40px;
        border-radius:12px;
        border:0;
        background:#FEE2E2;
        color:#DC2626;
        transition:all 0.2s ease;
    }
    .trash-btn:hover{
        background:#DC2626;
        color:#fff;
    }

    .summary-card{
        border:0;
        border-radius:18px;
        box-shadow:0 4px 14px rgba(8,145,178,.08);
        position:sticky;
        top:120px;
    }

    .pill{
        border-radius:999px;
        padding:12px 20px;
        font-weight:700;
        font-size:14px;
    }

    .pill.bg-primary{
        background:#0891B2 !important;
    }

    .avatar{
        width:58px;
        height:58px;
        border-radius:50%;
        background:#1e293b;
        display:flex;
        align-items:center;
        justify-content:center;
    }
    .avatar i{
        color:#fff;
        font-size:26px;
    }

    .request-btn{
        height:56px;
        border-radius:14px;
        font-weight:700;
        background:#0891B2;
        border:0;
        font-size:16px;
        transition:all 0.3s ease;
    }
    .request-btn:hover{
        background:#0E7490;
        transform:translateY(-2px);
        box-shadow:0 8px 16px rgba(8,145,178,.3);
    }
    .request-btn span{
        color:#fff !important;
    }

    .product-title{
        font-weight:700;
        color:#1e293b;
        font-size:16px;
        margin-bottom:4px;
    }

    .product-category{
        color:#0891B2;
        font-weight:600;
        font-size:13px;
    }

    .product-description{
        color:#64748b;
        font-size:13px;
        line-height:1.5;
    }

    .summary-title{
        font-weight:700;
        font-size:18px;
        color:#1e293b;
        margin-bottom:20px;
    }

    .summary-row{
        display:flex;
        justify-content:space-between;
        color:#64748b;
        font-weight:600;
        font-size:14px;
        margin-bottom:12px;
    }

    .user-info-name{
        font-weight:700;
        color:#1e293b;
        font-size:15px;
    }

    .user-info-detail{
        color:#64748b;
        font-size:13px;
    }

    .edit-link{
        color:#0891B2;
        font-weight:700;
        font-size:13px;
        text-decoration:none;
    }
    .edit-link:hover{
        color:#0E7490;
        text-decoration:underline;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .product-card{
            margin-bottom:20px;
        }
        .qty-btn{
            width:36px;
            height:36px;
            font-size:16px;
        }
        .qty-value{
            width:50px;
            font-size:16px;
        }
    }

    @media (max-width: 768px) {
        .inquiry-title{
            font-size:32px;
        }
        .product-img{
            width:70px;
            height:70px;
        }
        .product-description{
            font-size:12px;
        }
        .note-input{
            width:100%;
            margin-top:8px;
        }
    }

    /* =======================
       Featured Products (Like screenshot)
       ======================= */
    .fp-header{
        display:flex; align-items:center; justify-content:space-between;
        margin-top: 48px; margin-bottom: 20px;
    }
    .fp-title{
        font-size: 32px; font-weight: 900; letter-spacing: -.4px;
        color:#1e293b;
    }
    .fp-controls{
        display:flex; align-items:center; gap:10px; margin-left: 12px;
    }
    .fp-arrow{
        width:40px; height:40px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        border:0; background:#f1f5f9;
        color:#64748b;
        transition:all 0.2s ease;
    }
    .fp-arrow:hover{
        background:#0891B2;
        color:#fff;
        transform:scale(1.05);
    }

    .fp-viewall{
        font-size: 14px; font-weight: 700;
        color:#0891B2; text-decoration:none;
        transition:all 0.2s ease;
    }
    .fp-viewall:hover{
        color:#0E7490;
        text-decoration:underline;
    }

    .fp-slider{
        position:relative;
        overflow:hidden;
        padding: 10px 0 22px;
    }
    .fp-track{
        display:flex;
        gap: 22px;
        transition: transform .6s ease;
        will-change: transform;
        user-select: none;
    }
    .fp-item{
        flex: 0 0 340px;
        transition: transform .35s ease;
        transform: scale(.92);
    }
    .fp-item:hover{
        transform: scale(1.06);
    }

    .fp-card{
        background:#fff;
        border-radius: 20px;
        box-shadow: 0 4px 14px rgba(8,145,178,.1);
        overflow:hidden;
        position:relative;
        transition: all .3s ease;
    }
    .fp-item:hover .fp-card{
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(8,145,178,.2);
    }

    .fp-img{
        width:100%;
        height: 220px;
        object-fit:cover;
        display:block;
        transition: transform .35s ease;
    }
    .fp-item:hover .fp-img{ transform: scale(1.05); }

    .fp-body{
        padding: 14px 16px 18px;
    }
    .fp-name{
        font-weight: 900;
        margin-bottom: 4px;
        font-size: 15px;
    }
    .fp-cat{
        font-size: 12px;
        color:#9aa0a6;
        font-weight: 600;
    }

    /* + button on small cards */
    .fp-plus{
        width:38px; height:38px; border-radius:999px;
        display:flex; align-items:center; justify-content:center;
        background:#1f2a37;
        color:#fff;
        border:0;
        position:absolute;
        right: 14px;
        bottom: 16px;
        box-shadow: 0 10px 20px rgba(0,0,0,.2);
    }

    /* Add to Card pill on active card */
    .fp-add{
        position:absolute;
        right: 16px;
        bottom: 16px;
        background:#1f2a37;
        color:#fff;
        border:0;
        border-radius: 999px;
        padding: 8px 14px;
        font-weight: 800;
        display:none;
        align-items:center;
        gap:8px;
        box-shadow: 0 10px 20px rgba(0,0,0,.2);
        white-space: nowrap;
    }
    .fp-item:hover .fp-add{ display:flex; }
    .fp-item:hover .fp-plus{ display:none; }

    @media (max-width: 992px){
        .fp-title{font-size:26px}
        .fp-item{ flex-basis: 280px; }
        .fp-img{ height: 190px; }
    }
    @media (max-width: 576px){
        .fp-title{ font-size: 22px; }
        .fp-item{ flex-basis: 260px; }
    }

    /* Section Styles */
    .cart-section {
        background: #f8fafc;
        padding: 60px 0;
        min-height: calc(100vh - 80px);
    }

    .featured-section {
        background: #ffffff;
        padding: 60px 0;
    }

    .items-section {
        background: #ffffff;
        padding: 60px 0;
    }

    .cart-section .container {
        padding-top: 40px !important;
    }

    @media (max-width: 768px) {
        .cart-section {
            padding: 40px 0;
        }
        .featured-section {
            padding: 40px 0;
        }
        .items-section {
            padding: 40px 0;
        }
    }

    /* Items Section Styles */
    .items-tabs {
        border-bottom: 3px solid #e2e8f0;
        margin-bottom: 32px;
    }

    .items-tab {
        background: none;
        border: 0;
        padding: 16px 32px;
        font-size: 16px;
        font-weight: 700;
        color: #64748b;
        position: relative;
        transition: all 0.3s ease;
    }

    .items-tab.active {
        color: #0891B2;
    }

    .items-tab.active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        right: 0;
        height: 3px;
        background: #0891B2;
    }

    .items-tab:hover {
        color: #0891B2;
    }

    .item-row {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .item-row:last-child {
        border-bottom: 0;
    }

    .item-image {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
    }

    .item-info {
        flex: 1;
    }

    .item-qty {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-right: 8px;
    }

    .item-unit {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
    }

    .item-price-badge {
        background: #E0F2FE;
        color: #0891B2;
        padding: 10px 20px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 14px;
    }

    .pagination-info {
        text-align: center;
        background: #f1f5f9;
        padding: 12px;
        border-radius: 12px;
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
        margin-top: 24px;
    }

    @media (max-width: 768px) {
        .item-row {
            flex-direction: column;
            align-items: flex-start;
        }
        .items-tab {
            padding: 12px 20px;
            font-size: 14px;
        }
    }

    /* Inquiry Tracking Section Styles */
    .tracking-section {
        background: #ffffff;
        padding: 60px 0;
    }

    .tracking-title {
        font-size: 42px;
        font-weight: 800;
        letter-spacing: -.5px;
        color: #1e293b;
        margin-bottom: 40px;
    }

    .tracking-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .tracking-card:hover {
        box-shadow: 0 8px 24px rgba(8,145,178,.15);
        border-color: #0891B2;
    }

    .tracking-card-content {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .tracking-image {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .tracking-info {
        flex: 1;
    }

    .tracking-inquiry-number {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .tracking-products-count {
        font-size: 14px;
        color: #0891B2;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .tracking-description {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .tracking-meta {
        display: flex;
        gap: 20px;
        font-size: 12px;
        color: #94a3b8;
    }

    .tracking-meta-item {
        display: flex;
        flex-direction: column;
    }

    .tracking-meta-label {
        color: #64748b;
        margin-bottom: 2px;
    }

    .tracking-meta-value {
        color: #475569;
        font-weight: 600;
    }

    .tracking-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 12px;
        flex-shrink: 0;
    }

    .tracking-price {
        background: #E0F2FE;
        color: #0891B2;
        padding: 10px 24px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tracking-status {
        padding: 8px 20px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 13px;
        text-transform: capitalize;
    }

    .tracking-status.ongoing {
        background: #DC2626;
        color: #fff;
    }

    .tracking-status.closed {
        background: #10B981;
        color: #fff;
    }

    .tracking-load-more {
        text-align: center;
        margin-top: 24px;
    }

    .load-more-btn {
        background: #f1f5f9;
        border: 0;
        padding: 14px 32px;
        border-radius: 12px;
        font-weight: 700;
        color: #64748b;
        transition: all 0.3s ease;
    }

    .load-more-btn:hover {
        background: #e2e8f0;
        color: #475569;
    }

    @media (max-width: 768px) {
        .tracking-title {
            font-size: 32px;
        }
        .tracking-card-content {
            flex-direction: column;
            align-items: flex-start;
        }
        .tracking-right {
            align-items: flex-start;
            width: 100%;
        }
        .tracking-meta {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<!-- Section 1: Cart/Inquiry -->
<section class="cart-section">
    <div class="container py-4">

        <!-- Title -->
        <div class="mb-4">
            <div class="inquiry-title">{{ translate('My Cart') }}</div>
        </div>

        <div class="row g-4">

        <!-- Left: Products -->
        <div class="col-lg-8" id="cart-items-container">
            @if(isset($carts) && count($carts) > 0)
                @foreach($carts as $key => $cartItem)
                    @php
                        $product = \App\Models\Product::find($cartItem['product_id']);
                    @endphp
                    @if($product != null)
                        <div class="card product-card" id="cart-item-{{ $cartItem['id'] }}">
                            <div class="card-body p-4">
                                <div class="row align-items-center g-3">

                                    <!-- Image -->
                                    <div class="col-auto">
                                        <a href="{{ route('product', $product->slug) }}">
                                            <img class="product-img" src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </a>
                                    </div>

                                    <!-- Info -->
                                    <div class="col-12 col-md">
                                        <a href="{{ route('product', $product->slug) }}" class="text-reset">
                                            <div class="product-title">{{ $product->getTranslation('name') }}</div>
                                        </a>
                                        @if($product->category)
                                            <div class="product-category mb-2">{{ $product->category->getTranslation('name') }}</div>
                                        @endif
                                        <div class="product-description" style="max-width: 400px;">
                                            {{ $product->getTranslation('description') ? Str::limit(strip_tags($product->getTranslation('description')), 150) : translate('No description available') }}
                                        </div>
                                    </div>

                                    <!-- Qty & Note -->
                                    <div class="col-12 col-md-auto">
                                        <div class="d-flex flex-column gap-2">
                                            <!-- Quantity buttons -->
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="qty-btn" onclick="updateCartQuantity({{ $cartItem['id'] }}, -1)">−</button>
                                                <input type="text" class="qty-value" id="qty-{{ $cartItem['id'] }}" value="{{ $cartItem['quantity'] }}" readonly>
                                                <button type="button" class="qty-btn" onclick="updateCartQuantity({{ $cartItem['id'] }}, 1)">+</button>
                                                <span class="ms-2 fw-semibold" style="color:#64748b">{{ $product->unit }}</span>
                                            </div>
                                            <!-- Note input -->
                                            <input type="text" class="form-control note-input" placeholder="{{ translate('Note...') }}" style="width: 220px;">
                                        </div>
                                    </div>

                                    <!-- Delete -->
                                    <div class="col-auto">
                                        <button type="button" class="trash-btn" onclick="removeCartItem({{ $cartItem['id'] }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="card product-card">
                    <div class="card-body p-5 text-center">
                        <i class="bi bi-cart-x" style="font-size: 48px; color: #94a3b8;"></i>
                        <h4 class="mt-3" style="color: #64748b;">{{ translate('Your cart is empty') }}</h4>
                        <a href="{{ route('search') }}" class="btn request-btn mt-3" style="width: auto; padding: 12px 32px;">
                            <span>{{ translate('Continue Shopping') }}</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Summary -->
        <div class="col-lg-4">
            <div class="card summary-card">
                <div class="card-body p-4">

                    <div class="summary-title">{{ translate('Cart Summary') }}</div>

                    @php
                        $totalProducts = isset($carts) ? count($carts) : 0;
                        $totalItems = 0;
                        if(isset($carts) && count($carts) > 0) {
                            foreach($carts as $cart) {
                                $totalItems += $cart['quantity'];
                            }
                        }
                    @endphp

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="pill bg-primary text-white">{{ translate('Total Products') }}</div>
                        <div class="pill bg-primary text-white" id="total-products">{{ str_pad($totalProducts, 2, '0', STR_PAD_LEFT) }}</div>
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

                    @auth
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="user-info-name">{{ Auth::user()->name }}</div>
                                <div class="user-info-detail">{{ Auth::user()->phone }}</div>
                                <div class="user-info-detail">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="{{ route('profile') }}" class="edit-link">{{ translate('Edit') }}</a>
                        </div>
                    @endauth

                    <textarea class="form-control mb-4 note-input" rows="3" placeholder="{{ translate('Note...') }}"></textarea>

                    <button type="button" class="btn w-100 request-btn" @if($totalProducts == 0) disabled @endif>
                        <span>{{ translate('Request Offer') }}</span>
                    </button>

                </div>
            </div>
        </div>

    </div>
</section>

<!-- Section 2: Inquiry Tracking -->
<section class="tracking-section">
    <div class="container">

        <div class="tracking-title">Inquiry Tracking</div>

        <!-- Tracking Card 1 -->
        <div class="tracking-card">
            <div class="tracking-card-content">
                <img class="tracking-image" src="{{ asset('uploads/all/EbM9tJYgdR2oFheJi7nfrknYHRxVfWjYtqSBy8wy.jpeg') }}" alt="Product">

                <div class="tracking-info">
                    <div class="tracking-inquiry-number">Inquiry #356</div>
                    <div class="tracking-products-count">3 Products</div>
                    <div class="tracking-description">
                        From food and beverages to raw materials and recycled goods — Trades Axis bridges global demand and supply with precision, trust, and efficiency.
                    </div>
                    <div class="tracking-meta">
                        <div class="tracking-meta-item">
                            <span class="tracking-meta-label">Date Created:</span>
                            <span class="tracking-meta-value">12 May 2025</span>
                        </div>
                        <div class="tracking-meta-item">
                            <span class="tracking-meta-label">Last Modified:</span>
                            <span class="tracking-meta-value">15 May 2025</span>
                        </div>
                        <div class="tracking-meta-item">
                            <span class="tracking-meta-label">Modified By:</span>
                            <span class="tracking-meta-value">Admin Gaser</span>
                        </div>
                    </div>
                </div>

                <div class="tracking-right">
                    <div class="tracking-price">
                        <span>Price</span>
                        <span>3,600 EGP</span>
                    </div>
                    <div class="tracking-status ongoing">Ongoing</div>
                </div>
            </div>
        </div>

        <!-- Tracking Card 2 -->
        <div class="tracking-card">
            <div class="tracking-card-content">
                <img class="tracking-image" src="{{ asset('uploads/all/EbM9tJYgdR2oFheJi7nfrknYHRxVfWjYtqSBy8wy.jpeg') }}" alt="Product">

                <div class="tracking-info">
                    <div class="tracking-inquiry-number">Inquiry #356</div>
                    <div class="tracking-products-count">3 Products</div>
                    <div class="tracking-description">
                        From food and beverages to raw materials and recycled goods — Trades Axis bridges global demand and supply with precision, trust, and efficiency.
                    </div>
                    <div class="tracking-meta">
                        <div class="tracking-meta-item">
                            <span class="tracking-meta-label">Date Created:</span>
                            <span class="tracking-meta-value">12 May 2025</span>
                        </div>
                        <div class="tracking-meta-item">
                            <span class="tracking-meta-label">Last Modified:</span>
                            <span class="tracking-meta-value">15 May 2025</span>
                        </div>
                        <div class="tracking-meta-item">
                            <span class="tracking-meta-label">Modified By:</span>
                            <span class="tracking-meta-value">Admin Gaser</span>
                        </div>
                    </div>
                </div>

                <div class="tracking-right">
                    <div class="tracking-price">
                        <span>Price</span>
                        <span>3,600 EGP</span>
                    </div>
                    <div class="tracking-status closed">Closed</div>
                </div>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="tracking-load-more">
            <button type="button" class="load-more-btn">Previously Sent</button>
        </div>

    </div>
</section>

<!-- Section 3: Items & Updates -->
<section class="items-section">
    <div class="container">

        <div class="inquiry-title mb-4">Inquiry #356</div>

        <!-- Tabs -->
        <div class="items-tabs">
            <button class="items-tab active" type="button">Items</button>
            <button class="items-tab" type="button">Updates</button>
        </div>

        <!-- Items List -->
        <div class="row">
            <div class="col-lg-8">
                @for ($i = 0; $i < 2; $i++)
                <div class="item-row">
                    <img class="item-image" src="{{ asset('uploads/all/EbM9tJYgdR2oFheJi7nfrknYHRxVfWjYtqSBy8wy.jpeg') }}" alt="Product">

                    <div class="item-info">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="item-qty">8Tons</span>
                            <span class="product-title" style="margin: 0;">Mix Fruit for Juice</span>
                        </div>
                        <div class="product-category mb-2">Vegetables & Fruit</div>
                        <div class="product-description" style="max-width: 500px;">
                            From food and beverages to raw materials and recycled goods — Trades Axis bridges
                            global demand and supply with precision, trust, and efficiency.
                        </div>
                    </div>

                    <div class="item-price-badge">
                        Price &nbsp;&nbsp; {{ $i == 0 ? '3,600 EGP' : '4,200 EGP' }}
                    </div>
                </div>
                @endfor

                <!-- Pagination -->
                <div class="pagination-info">
                    24 / 31
                </div>
            </div>

            <!-- Right: Summary (same as before) -->
            <div class="col-lg-4">
                <div class="card summary-card">
                    <div class="card-body p-4">

                        <div class="summary-title">Inquiry Summary</div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="pill bg-primary text-white">Inquiry Number</div>
                            <div class="pill bg-primary text-white">#36591</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: #1e293b; font-weight: 700;">Status</span>
                            <span class="badge" style="background: #DC2626; padding: 8px 16px; border-radius: 999px; font-weight: 700;">Ongoing</span>
                        </div>

                        <hr style="border-color:#e2e8f0;margin:24px 0">

                        <div class="summary-row">
                            <span>Available products Price</span>
                            <span style="color: #1e293b; font-weight: 700;">3600 EGP</span>
                        </div>

                        <div class="summary-row">
                            <span>Taxes</span>
                            <span style="color: #1e293b; font-weight: 700;">3600 EGP</span>
                        </div>

                        <div class="summary-row">
                            <span>Delivery</span>
                            <span style="color: #1e293b; font-weight: 700;">3600 EGP</span>
                        </div>

                        <div class="summary-row">
                            <span>Discount</span>
                            <span style="color: #1e293b; font-weight: 700;">3600 EGP</span>
                        </div>

                        <div class="summary-row mb-4">
                            <span>Extra fees</span>
                            <span style="color: #1e293b; font-weight: 700;">3600 EGP</span>
                        </div>

                        <hr style="border-color:#e2e8f0;margin:24px 0">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span style="font-size: 18px; font-weight: 700; color: #1e293b;">Total</span>
                            <span style="font-size: 18px; font-weight: 700; color: #1e293b;">3600 EGP</span>
                        </div>

                        <button type="button" class="btn w-100 request-btn" style="background: #0891B2;">
                            <span>Accept Offer</span>
                        </button>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Section 3: Featured Products -->
<section class="featured-section">
    <div class="container">
        <div class="fp-header">
        <div class="d-flex align-items-center">
            <div class="fp-title">Featured Products</div>
            <div class="fp-controls">
                <button class="fp-arrow js-fp-prev" type="button" aria-label="Prev">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="fp-arrow js-fp-next" type="button" aria-label="Next">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
        <a class="fp-viewall" href="#">View All</a>
    </div>

    <div class="fp-slider">
        <div class="fp-track js-fp-track">
            @php
                $featured = [
                    ['title'=>'Conor Chicken BBQ','cat'=>'Spices','img'=>asset('uploads/all/EbM9tJYgdR2oFheJi7nfrknYHRxVfWjYtqSBy8wy.jpeg')],
                    ['title'=>'Conor Chicken BBQ','cat'=>'Spices','img'=>asset('uploads/all/EbM9tJYgdR2oFheJi7nfrknYHRxVfWjYtqSBy8wy.jpeg')],
                    ['title'=>'Conor Chicken BBQ','cat'=>'Spices','img'=>asset('uploads/all/EbM9tJYgdR2oFheJi7nfrknYHRxVfWjYtqSBy8wy.jpeg')],
                    ['title'=>'Conor Chicken BBQ','cat'=>'Spices','img'=>asset('uploads/all/EbM9tJYgdR2oFheJi7nfrknYHRxVfWjYtqSBy8wy.jpeg')],
                    ['title'=>'Conor Chicken BBQ','cat'=>'Spices','img'=>asset('uploads/all/EbM9tJYgdR2oFheJi7nfrknYHRxVfWjYtqSBy8wy.jpeg')],
                    ['title'=>'Conor Chicken BBQ','cat'=>'Spices','img'=>asset('uploads/all/EbM9tJYgdR2oFheJi7nfrknYHRxVfWjYtqSBy8wy.jpeg')],
                ];
            @endphp

            @foreach($featured as $idx => $p)
                <div class="fp-item js-fp-item">
                    <div class="fp-card">
                        <img class="fp-img" src="{{ $p['img'] }}" alt="product">

                        <div class="fp-body">
                            <div class="fp-name">{{ $p['title'] }}</div>
                            <div class="fp-cat">{{ $p['cat'] }}</div>
                        </div>

                        <button class="fp-plus" type="button" aria-label="Add">
                            <i class="bi bi-plus-lg"></i>
                        </button>

                        <button class="fp-add" type="button">
                            <i class="bi bi-plus-lg"></i>
                            <span>Add to Card</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    </div>
</section>

@endsection

@section('script')
<script>
    // Update cart quantity
    function updateCartQuantity(cartId, change) {
        var qtyInput = document.getElementById('qty-' + cartId);
        var currentQty = parseInt(qtyInput.value);
        var newQty = currentQty + change;

        if(newQty < 1) {
            removeCartItem(cartId);
            return;
        }

        $.ajax({
            type: "POST",
            url: '{{ route("cart.updateQuantity") }}',
            data: {
                _token: '{{ csrf_token() }}',
                id: cartId,
                quantity: newQty
            },
            success: function(data) {
                qtyInput.value = newQty;
                if(typeof updateNavCart === 'function') {
                    updateNavCart(data.nav_cart_view, data.cart_count);
                }
                updateCartSummary();
            },
            error: function() {
                AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
            }
        });
    }

    // Remove cart item
    function removeCartItem(cartId) {
        $.ajax({
            type: "POST",
            url: '{{ route("cart.removeFromCart") }}',
            data: {
                _token: '{{ csrf_token() }}',
                id: cartId
            },
            success: function(data) {
                $('#cart-item-' + cartId).fadeOut(300, function() {
                    $(this).remove();
                    if(typeof updateNavCart === 'function') {
                        updateNavCart(data.nav_cart_view, data.cart_count);
                    }
                    updateCartSummary();

                    // Check if cart is empty
                    if($('#cart-items-container .product-card').length === 0) {
                        location.reload();
                    }
                });
                AIZ.plugins.notify('success', "{{ translate('Item removed from cart') }}");
            },
            error: function() {
                AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
            }
        });
    }

    // Update cart summary
    function updateCartSummary() {
        var totalProducts = $('#cart-items-container .product-card').length;
        var totalItems = 0;

        $('#cart-items-container .qty-value').each(function() {
            totalItems += parseInt($(this).val()) || 0;
        });

        $('#total-products').text(totalProducts.toString().padStart(2, '0'));
        $('#summary-products').text(totalProducts + ' {{ translate("Products") }}');
        $('#summary-items').text(totalItems + ' {{ translate("Items") }}');
    }

    // Featured Products: arrows only
    (function(){
        const track = document.querySelector('.js-fp-track');
        const items = Array.from(document.querySelectorAll('.js-fp-item'));
        const prevBtn = document.querySelector('.js-fp-prev');
        const nextBtn = document.querySelector('.js-fp-next');

        if(!track || items.length === 0) return;

        let currentIndex = 0;

        function gapPx(){
            const st = window.getComputedStyle(track);
            return parseFloat(st.gap || st.columnGap || 22) || 22;
        }
        function stepWidth(){
            return items[0].getBoundingClientRect().width + gapPx();
        }

        function render(){
            track.style.transform = `translateX(${-currentIndex * stepWidth()}px)`;
        }

        function next(){
            if(currentIndex < items.length - 1){
                currentIndex++;
                render();
            }
        }
        function prev(){
            if(currentIndex > 0){
                currentIndex--;
                render();
            }
        }

        // arrows only
        nextBtn && nextBtn.addEventListener('click', next);
        prevBtn && prevBtn.addEventListener('click', prev);

        // init
        render();
        window.addEventListener('resize', render);
    })();
</script>
@endsection
