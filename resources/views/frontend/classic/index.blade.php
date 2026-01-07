@extends('frontend.layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #E91E63;
        --secondary-color: #FF4081;
        --text-dark: #2C3E50;
        --text-light: #7F8C8D;
        --bg-light: #F8F9FA;
    }

    body {
        background-color: #f5f5f5;
    }

    /* Hero Section */
    .hero-section {
        position: relative;
        height: 450px;
        background: linear-gradient(135deg, rgba(233, 30, 99, 0.95), rgba(255, 64, 129, 0.85)), url('https://images.unsplash.com/photo-1542838132-92c53300491e?w=1200');
        background-size: cover;
        background-position: center;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        align-items: center;
        color: white;
        padding: 3rem;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 1.3rem;
        font-weight: 400;
        margin-bottom: 0;
        opacity: 0.95;
    }

    /* Floating Pink Circle Icons */
    .floating-circle {
        position: absolute;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        animation: float 4s ease-in-out infinite;
    }

    .circle-m-top {
        width: 70px;
        height: 70px;
        top: 50px;
        right: 180px;
        animation-delay: 0s;
    }

    .circle-m-bottom {
        width: 70px;
        height: 70px;
        bottom: 100px;
        right: 50px;
        animation-delay: 1.5s;
    }

    .circle-m {
        background: var(--primary-color);
        color: white;
        font-size: 2rem;
        font-weight: 700;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }

    /* Customer Satisfaction Card */
    .satisfaction-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        margin-bottom: 3rem;
    }

    .satisfaction-icon {
        width: 180px;
        height: 180px;
        margin: 0 auto;
    }

    .satisfaction-text h4 {
        color: var(--primary-color);
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .satisfaction-text p {
        color: var(--text-light);
        font-size: 1.1rem;
        margin: 0;
    }

    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding: 0 5px;
    }

    .section-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .nav-arrows {
        display: flex;
        gap: 8px;
    }

    .nav-arrow {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: white;
        border: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .nav-arrow:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    /* Category Cards */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 3rem;
    }

    .category-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        height: 180px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    }

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2);
    }

    .category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .category-card:hover img {
        transform: scale(1.15);
    }

    .category-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.75), transparent);
        padding: 1.2rem 1rem;
        color: white;
    }

    .category-name {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
    }

    /* Product Cards */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 3rem;
    }

    .product-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .product-image-container {
        position: relative;
        padding-top: 100%;
        overflow: hidden;
        background: #fafafa;
    }

    .product-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.1);
    }

    .product-body {
        padding: 1rem;
    }

    .product-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.6rem;
        height: 40px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.3rem;
    }

    .add-cart-icon {
        position: absolute;
        bottom: 15px;
        right: 15px;
        width: 35px;
        height: 35px;
        background: var(--text-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .add-cart-icon:hover {
        background: var(--primary-color);
        transform: scale(1.1);
    }

    /* Blue Banner */
    .platform-banner {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        border-radius: 20px;
        padding: 2rem 3rem;
        color: white;
        margin-bottom: 3rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 25px rgba(33, 150, 243, 0.3);
    }

    .platform-content h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }

    .platform-content p {
        margin: 0;
        opacity: 0.95;
    }

    .platform-icon {
        font-size: 3rem;
    }

    /* Quality Section */
    .quality-section {
        background: linear-gradient(135deg, rgba(103, 58, 183, 0.95), rgba(81, 45, 168, 0.95)), url('https://images.unsplash.com/photo-1556740758-90de374c12ad?w=1200');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 5rem 2rem;
        text-align: center;
        color: white;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }

    .quality-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 5s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .quality-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.2);
    }

    .quality-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
        position: relative;
        z-index: 2;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .category-grid,
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-section {
            height: 350px;
        }
        
        .floating-circle {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .hero-content {
            padding: 2rem;
        }
        
        .section-title {
            font-size: 1.3rem;
        }
        
        .quality-title {
            font-size: 1.8rem;
        }
        
        .platform-banner {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
    }
</style>

@php 
    $lang = get_system_language()->code;
    
    // Get real categories from data
    $main_categories = isset($featured_categories) ? $featured_categories->where('level', 0)->take(4) : collect();
    
    // Mock products data for display
    $display_products = [
        ['name' => 'Fresh Organic Bread', 'price' => '$12.00', 'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400'],
        ['name' => 'Artisan Sourdough', 'price' => '$12.00', 'image' => 'https://images.unsplash.com/photo-1549931319-a545dcf3bc73?w=400'],
        ['name' => 'Whole Wheat Bread', 'price' => '$6.00', 'image' => 'https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=400'],
        ['name' => 'French Baguette', 'price' => '$12.00', 'image' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=400'],
    ];
@endphp

<div class="container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div>
                <h1 class="hero-title">Connecting Markets,<br>Delivering Value</h1>
                <p class="hero-subtitle">Discover amazing products at unbeatable prices</p>
            </div>
        </div>
        
        <!-- Floating M Circles -->
        <div class="floating-circle circle-m-top circle-m">
            <span>M</span>
        </div>
        <div class="floating-circle circle-m-bottom circle-m">
            <span>M</span>
        </div>
    </section>

    <!-- Customer Satisfaction Card -->
    <div class="row mb-4">
        <div class="col-lg-10 mx-auto">
            <div class="satisfaction-card">
                <div class="row align-items-center">
                    <div class="col-md-5 text-center mb-3 mb-md-0">
                        <div class="satisfaction-icon">
                            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                <!-- Laptop/Screen -->
                                <rect x="40" y="60" width="120" height="80" rx="5" fill="#E3F2FD" stroke="#2196F3" stroke-width="3"/>
                                <rect x="50" y="70" width="100" height="60" fill="#fff"/>
                                <line x1="90" y1="140" x2="90" y2="155" stroke="#2196F3" stroke-width="3"/>
                                <line x1="110" y1="140" x2="110" y2="155" stroke="#2196F3" stroke-width="3"/>
                                <rect x="70" y="155" width="60" height="5" rx="2" fill="#2196F3"/>
                                
                                <!-- People -->
                                <circle cx="70" cy="95" r="8" fill="#E91E63"/>
                                <rect x="65" y="105" width="10" height="15" rx="2" fill="#E91E63"/>
                                
                                <circle cx="100" cy="95" r="8" fill="#2196F3"/>
                                <rect x="95" y="105" width="10" height="15" rx="2" fill="#2196F3"/>
                                
                                <circle cx="130" cy="95" r="8" fill="#FFC107"/>
                                <rect x="125" y="105" width="10" height="15" rx="2" fill="#FFC107"/>
                                
                                <!-- Chart on screen -->
                                <polyline points="60,90 70,85 80,95 90,80 100,90 110,85" fill="none" stroke="#4CAF50" stroke-width="2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="col-md-7 satisfaction-text">
                        <h4>Make your customers happy</h4>
                        <p>by giving the best products.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <section>
        <div class="section-header">
            <h2 class="section-title">Our Categories</h2>
            <div class="nav-arrows">
                <div class="nav-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="nav-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="category-grid">
            <a href="#" class="text-decoration-none">
                <div class="category-card">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600" alt="Bakery & Bread">
                    <div class="category-overlay">
                        <h3 class="category-name">Bakery & Bread</h3>
                    </div>
                </div>
            </a>
            
            <a href="#" class="text-decoration-none">
                <div class="category-card">
                    <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=600" alt="Beverages">
                    <div class="category-overlay">
                        <h3 class="category-name">Beverages</h3>
                    </div>
                </div>
            </a>
            
            @if($main_categories->count() > 0)
                @foreach($main_categories->take(2) as $category)
                <a href="{{ route('products.category', $category->slug) }}" class="text-decoration-none">
                    <div class="category-card">
                        <img src="{{ isset($category->bannerImage->file_name) ? my_asset($category->bannerImage->file_name) : 'https://images.unsplash.com/photo-1556740758-90de374c12ad?w=600' }}" alt="{{ $category->getTranslation('name') }}">
                        <div class="category-overlay">
                            <h3 class="category-name">{{ $category->getTranslation('name') }}</h3>
                        </div>
                    </div>
                </a>
                @endforeach
            @else
                <a href="#" class="text-decoration-none">
                    <div class="category-card">
                        <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=600" alt="Food & Beverages">
                        <div class="category-overlay">
                            <h3 class="category-name">Food & Beverages</h3>
                        </div>
                    </div>
                </a>
                
                <a href="#" class="text-decoration-none">
                    <div class="category-card">
                        <img src="https://images.unsplash.com/photo-1597362925123-77861d3fbac7?w=600" alt="Vegetables">
                        <div class="category-overlay">
                            <h3 class="category-name">Vegetables</h3>
                        </div>
                    </div>
                </a>
            @endif
        </div>
    </section>

    <!-- Featured Products Section -->
    <section>
        <div class="section-header">
            <h2 class="section-title">Featured Products</h2>
            <div class="nav-arrows">
                <div class="nav-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="nav-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="products-grid">
            @foreach($display_products as $product)
            <div class="product-card">
                <div class="product-image-container">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="product-image">
                </div>
                <div class="product-body">
                    <h3 class="product-name">{{ $product['name'] }}</h3>
                    <div class="product-price">{{ $product['price'] }}</div>
                </div>
                <div class="add-cart-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M12 5V19M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Platform Banner -->
    <div class="platform-banner">
        <div class="platform-content">
            <h3>Enjoy More Completed Trading platform</h3>
            <p>Everything you need in one place</p>
        </div>
        <div class="platform-icon">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none">
                <rect x="3" y="3" width="7" height="7" rx="1" fill="white" opacity="0.9"/>
                <rect x="3" y="13.5" width="7" height="7" rx="1" fill="white" opacity="0.9"/>
                <rect x="13.5" y="3" width="7" height="7" rx="1" fill="white" opacity="0.9"/>
                <rect x="13.5" y="13.5" width="7" height="7" rx="1" fill="white" opacity="0.9"/>
            </svg>
        </div>
    </div>

    <!-- Quality Section -->
    <section class="quality-section">
        <h2 class="quality-title">We Gather the highest<br>Quality Products</h2>
        <p class="quality-subtitle">Carefully selected from the best suppliers worldwide</p>
    </section>
</div>

<!-- Flash Deal Section (if exists) -->
@php
    $flash_deal = get_featured_flash_deal();
@endphp
@if ($flash_deal != null)
<section class="mb-4" id="flash_deal">
    <div class="container">
        <div class="d-flex flex-wrap mb-3 align-items-baseline justify-content-between">
            <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                <span>{{ translate('Flash Sale') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 16 24" class="ml-1">
                    <path d="M30.953,13.695a.474.474,0,0,0-.424-.25h-4.9l3.917-7.81a.423.423,0,0,0-.028-.428.477.477,0,0,0-.4-.207H21.588a.473.473,0,0,0-.429.263L15.041,18.151a.423.423,0,0,0,.034.423.478.478,0,0,0,.4.2h4.593l-2.229,9.683a.438.438,0,0,0,.259.5.489.489,0,0,0,.571-.127L30.9,14.164a.425.425,0,0,0,.054-.469Z" transform="translate(-15 -5)" fill="#fcc201"/>
                </svg>
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-5 mb-3">
                <a href="{{ route('flash-deal-details', $flash_deal->slug) }}">
                    <div style="background-image: url('{{ uploaded_asset($flash_deal->banner) }}'); background-size: cover; height: 400px; border-radius: 20px;"></div>
                </a>
            </div>
            <div class="col-lg-8 col-md-7">
                @php
                    $flash_deal_products = get_flash_deal_products($flash_deal->id);
                @endphp
                <div class="products-grid">
                    @foreach ($flash_deal_products->take(4) as $flash_deal_product)
                        @if ($flash_deal_product->product != null && $flash_deal_product->product->published != 0)
                        <div class="product-card">
                            <a href="{{ route('product', $flash_deal_product->product->slug) }}" class="text-decoration-none">
                                <div class="product-image-container">
                                    <img src="{{ get_image($flash_deal_product->product->thumbnail) }}" class="product-image" alt="{{ $flash_deal_product->product->getTranslation('name') }}">
                                </div>
                                <div class="product-body">
                                    <h3 class="product-name">{{ $flash_deal_product->product->getTranslation('name') }}</h3>
                                    <div class="product-price">{{ home_discounted_base_price($flash_deal_product->product) }}</div>
                                </div>
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<div id="todays_deal"></div>
<div id="section_best_selling"></div>
<div id="section_newest"></div>
<div id="section_featured"></div>

@endsection

@section('script')
<script>
    // Add any necessary JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Homepage loaded successfully');
    });
</script>
@endsection