@extends('frontend.layouts.app')

<style>
    .category-page {
        background: #f8f9fa;
        min-height: 100vh;
    }

    /* ========================
       BACK ARROW - RESPONSIVE
    ======================== */
    .back-arrow {
        position: absolute;
        top: -60px;
        left: 20px;
        width: 52px;
        height: 52px;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        z-index: 3;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .back-arrow:hover {
        background: rgba(0, 0, 0, 0.7);
        transform: translateX(-3px);
        color: #fff;
    }

    .back-arrow i {
        font-size: 16px;
    }

    /* ========================
       HERO BANNER - RESPONSIVE
    ======================== */
    .category-hero {
        position: relative;
        height: 450px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: flex-end;
        margin-bottom: 0;
        padding: 20px;
        padding-bottom: 50px;
        overflow: hidden;
    }

    .category-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #000;
        opacity: 0.7;
        z-index: 1;
    }

    .category-hero .container {
        position: relative;
        z-index: 2;
        width: 100%;
    }

    .category-hero h1 {
        color: #fff;
        font-size: 52px;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.9);
        line-height: 1.2;
    }

    .category-hero h1 .explore {
        color: #5fb3f6;
        display: block;
        font-size: 46px;
        margin-bottom: 5px;
    }

    /* ========================
       CONTENT SECTION
    ======================== */
    .category-content {
        background: #fff;
        padding: 40px 0 80px;
    }

    /* ========================
       PAGE TITLE
    ======================== */
    .page-title {
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #333;
    }

    .page-title .explore {
        color: #5fb3f6;
    }

    /* ========================
       SIDEBAR - NEW DESIGN
    ======================== */
    .category-sidebar {
        background: #fff;
        padding: 30px 25px;
        border-radius: 16px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, .08);
        position: sticky;
        top: 20px;
        margin-bottom: 30px;
    }

    .category-sidebar a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        color: #666;
        text-decoration: none;
        width: 100%;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 500;
        transition: all .3s ease;
        background: transparent;
    }

    .category-sidebar a:hover {
        background: #f5f8fa;
        color: #333;
        transform: translateX(3px);
    }

    /* ========================
       CATEGORY CARD - NEW DESIGN
    ======================== */
    .category-card {
        position: relative;
        height: 280px;
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        transition: .4s;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .1);
        background: #fff;
    }

    .category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: .4s;
    }

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, .18);
    }

    .category-card:hover img {
        transform: scale(1.08);
    }

    /* Cart Icon - Top Left with Dark Circle */
    .category-card .cart-icon {
        position: absolute;
        top: 15px;
        left: 15px;
        width: 35px;
        height: 35px;
        background: rgba(0, 0, 0, 0.7);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 3;
        cursor: pointer;
        transition: .3s;
    }

    .category-card .cart-icon:hover {
        background: rgba(0, 0, 0, 0.85);
        transform: scale(1.1);
    }

    .category-card .cart-icon i {
        color: #fff;
        font-size: 14px;
    }

    /* Overlay Content - Bottom Left */
    .category-card .overlay {
        position: absolute;
        bottom: 20px;
        left: 20px;
        z-index: 2;
        text-align: left;
        background: transparent !important;
    }

    .category-card .overlay h5 {
        margin: 0 0 5px 0;
        font-weight: 700;
        font-size: 24px;
        color: #fff;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
        letter-spacing: 0;
    }

    .category-card .overlay p {
        margin: 0;
        font-size: 13px;
        color: #fff;
        opacity: 0.9;
    }

    /* Dark overlay gradient for better text visibility */
    .category-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom,
                rgba(0, 0, 0, 0) 0%,
                rgba(0, 0, 0, 0.1) 40%,
                rgba(0, 0, 0, 0.5) 70%,
                rgba(0, 0, 0, 0.7) 100%);
        z-index: 1;
        transition: .4s;
    }

    .category-card:hover::before {
        background: linear-gradient(to bottom,
                rgba(0, 0, 0, 0.1) 0%,
                rgba(0, 0, 0, 0.2) 40%,
                rgba(0, 0, 0, 0.6) 70%,
                rgba(0, 0, 0, 0.75) 100%);
    }

    /* ========================
       RESPONSIVE BREAKPOINTS
    ======================== */

    /* Large Desktop */
    @media (min-width: 1400px) {
        .category-hero {
            height: 500px;
        }

        .category-hero h1 {
            font-size: 60px;
        }

        .category-hero h1 .explore {
            font-size: 52px;
        }

        .category-card {
            height: 320px;
        }

        .category-card .overlay h5 {
            font-size: 26px;
        }
    }

    /* Tablet Landscape */
    @media (max-width: 1024px) {
        .category-hero {
            height: 380px;
            padding-bottom: 40px;
        }

        .category-hero h1 {
            font-size: 44px;
        }

        .category-hero h1 .explore {
            font-size: 38px;
        }

        .category-card {
            height: 320px;
        }

        .category-card .overlay h5 {
            font-size: 26px;
        }
    }

    /* Tablet Landscape */
    @media (max-width: 1024px) {
        .page-title {
            font-size: 38px;
        }

        .category-card {
            height: 260px;
        }

        .category-sidebar {
            position: relative;
            top: 0;
        }

        .category-card .overlay {
            bottom: 18px;
            left: 18px;
        }

        .category-card .overlay h5 {
            font-size: 22px;
        }
    }

    /* Tablet Portrait & Mobile Landscape */
    @media (max-width: 768px) {
        .back-arrow {
            width: 40px;
            height: 40px;
            top: 15px;
            left: 15px;
        }

        .back-arrow i {
            font-size: 14px;
        }

        .category-hero {
            height: 320px;
            padding: 15px;
            padding-bottom: 35px;
        }

        .category-hero h1 {
            font-size: 36px;
        }

        .category-hero h1 .explore {
            font-size: 32px;
        }

        .category-content {
            padding: 30px 0 60px;
        }

        .category-sidebar {
            margin-bottom: 25px;
            padding: 25px 20px;
            border-radius: 14px;
        }

        .category-sidebar a {
            padding: 12px 18px;
            font-size: 14px;
        }

        .category-card {
            height: 240px;
            border-radius: 12px;
        }

        .category-card .overlay {
            bottom: 16px;
            left: 16px;
        }

        .category-card .overlay h5 {
            font-size: 20px;
        }

        .category-card .overlay p {
            font-size: 12px;
        }

        .category-card .cart-icon {
            width: 32px;
            height: 32px;
            top: 12px;
            left: 12px;
        }

        .category-card .cart-icon i {
            font-size: 13px;
        }
    }

    /* Mobile Portrait */
    @media (max-width: 576px) {
        .back-arrow {
            width: 38px;
            height: 38px;
            top: 12px;
            left: 12px;
        }

        .back-arrow i {
            font-size: 13px;
        }

        .category-hero {
            height: 280px;
            padding: 12px;
            padding-bottom: 30px;
        }

        .category-hero h1 {
            font-size: 30px;
        }

        .category-hero h1 .explore {
            font-size: 26px;
            margin-bottom: 3px;
        }

        .category-content {
            padding: 25px 0 50px;
        }

        .category-sidebar {
            padding: 22px 18px;
            margin-bottom: 20px;
            border-radius: 12px;
        }

        .category-sidebar a {
            padding: 11px 16px;
            font-size: 13px;
            border-radius: 40px;
        }

        .category-card {
            height: 220px;
            border-radius: 10px;
        }

        .category-card .overlay {
            bottom: 14px;
            left: 14px;
        }

        .category-card .overlay h5 {
            font-size: 18px;
        }

        .category-card .overlay p {
            font-size: 11px;
        }

        .category-card .cart-icon {
            width: 30px;
            height: 30px;
            top: 10px;
            left: 10px;
        }

        .category-card .cart-icon i {
            font-size: 12px;
        }

        .row.g-4 {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }
    }

    /* Extra Small Mobile */
    @media (max-width: 400px) {
        .category-hero {
            height: 250px;
            padding-bottom: 25px;
        }

        .category-hero h1 {
            font-size: 26px;
        }

        .category-hero h1 .explore {
            font-size: 23px;
        }

        .category-card {
            height: 200px;
        }

        .category-card .overlay {
            bottom: 12px;
            left: 12px;
        }

        .category-card .overlay h5 {
            font-size: 16px;
        }

        .category-card .overlay p {
            font-size: 10px;
        }
    }

    /* Landscape orientation fixes */
    @media (max-height: 500px) and (orientation: landscape) {
        .category-hero {
            height: 250px;
            padding-bottom: 20px;
        }

        .category-hero h1 {
            font-size: 28px;
        }

        .category-hero h1 .explore {
            font-size: 24px;
        }
    }

    /* Touch device optimizations */
    @media (hover: none) and (pointer: coarse) {
        .category-card:hover {
            transform: none;
        }

        .category-card:active {
            transform: translateY(-4px);
            transition: transform 0.1s;
        }
    }
</style>

@section('content')
    <div class="category-page">
        {{-- Hero Banner with Category Image --}}
        {{-- http://127.0.0.1:8000/uploads/all/EbM9tJYgdR2oFheJi7nfrknYHRxVfWjYtqSBy8wy.jpeg --}}
        <div class="category-hero"
            style="background-image: url('{{ uploaded_asset($levelTwoCategories->first()->banner ?? '') }}');">
            <div class="container">
                <a href="javascript:history.back()" class="back-arrow">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>
                    <span class="explore">Explore</span>
                    {{$levelTwoCategories->first()->name}}
                </h1>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="category-content">
            <div class="container">

        <div class="row">

            {{-- Sidebar --}}
            <div class="col-lg-3 mb-4">
                <div class="category-sidebar">
                    <a href="/categories" class="mb-3">Categories</a>
                </div>
            </div>

            {{-- Cards --}}
            <div class="col-lg-9">
                <div class="row g-4">

                    @foreach ($levelTwoCategories as $category)
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <a href="{{ route('products.level2', $category->id) }}" class="category-link">
                                <div class="category-card">
                                    <img src="{{ uploaded_asset($category->banner) }}" alt="">

                                    {{-- Cart Icon - Top Left --}}
                                    <div class="cart-icon">
                                        <i class="fas fa-shopping-basket"></i>
                                    </div>

                                    <div class="overlay" style="background:transparent;">
                                        <h5>{{ $category->getTranslation('name') }}</h5>
                                        <p>{{ $category->products_count ?? 0 }} Products</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
            </div>
        </div>
    </div>
@endsection
