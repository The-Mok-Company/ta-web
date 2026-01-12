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
       SIDEBAR - RESPONSIVE (NEW DESIGN)
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

    .category-sidebar h6 {
        font-weight: 600;
        margin-bottom: 20px;
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .category-sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0 0 25px 0;
    }

    .category-sidebar ul li {
        padding: 0;
        border-radius: 50px;
        font-size: 15px;
        cursor: pointer;
        transition: all .3s ease;
        margin-bottom: 8px;
        color: #666;
        font-weight: 500;
        background: transparent;
        position: relative;
    }

    .category-sidebar ul li a.category-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        color: inherit;
        text-decoration: none;
        width: 100%;
        border-radius: 50px;
    }

    .category-sidebar .category-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-radius: 50px;
    }

    .category-sidebar .category-header .category-link {
        flex: 1;
        padding: 0;
    }

    .category-sidebar .toggle-icon {
        font-size: 11px;
        opacity: 0.6;
        transition: transform .3s ease;
        cursor: pointer;
        padding: 5px;
        margin-left: 10px;
    }

    .category-sidebar ul li:hover {
        background: #f5f8fa;
        color: #333;
        transform: translateX(3px);
    }

    .category-sidebar ul li.active {
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
    }

    .category-sidebar ul li.active a.category-link {
        color: #fff;
    }

    .category-sidebar ul li i {
        font-size: 11px;
        opacity: 0.6;
        transition: .3s;
    }

    .category-sidebar ul li:hover i {
        opacity: 1;
    }

    .category-sidebar ul li.active i {
        opacity: 1;
    }

    /* Sub Categories Styling */
    .sub-categories {
        margin: 0 0 15px 0 !important;
        padding-left: 20px !important;
        list-style: none;
    }

    .sub-categories li {
        font-size: 14px;
        margin-bottom: 5px;
    }

    .sub-categories li a {
        padding: 10px 15px;
    }

    .sub-categories li i {
        font-size: 9px;
    }

    /* First Item - All Categories */
    .category-sidebar ul li:first-child {
        color: #888;
        font-weight: 400;
    }

    .category-sidebar ul li:first-child:hover {
        color: #333;
    }

    .category-sidebar ul li:first-child a {
        color: inherit;
    }

    /* Parent category with children */
    .parent-category-list {
        margin-bottom: 15px !important;
    }

    /* Main category styling */
    .main-category {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px !important;
    }

    /* ========================
       CATEGORY CARD - NEW DESIGN EXACTLY LIKE IMAGE
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

    /* Category Title - Bottom Left */
    .category-card .category-title {
        position: absolute;
        bottom: 60px;
        left: 20px;
        z-index: 2;
        text-align: left;
    }

    .category-card .category-title h5 {
        margin: 0;
        font-weight: 700;
        font-size: 24px;
        color: #fff;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
        letter-spacing: 0;
    }

    /* Sub Categories - Bottom Left (Below Title) */
    .category-card .sub-categories-bottom {
        position: absolute;
        bottom: 20px;
        left: 20px;
        right: 20px;
        z-index: 2;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .category-card .sub-cat-item {
        font-size: 11px;
        color: #fff;
        background: transparent;
        padding: 0;
        border-radius: 0;
        font-weight: 400;
        transition: .3s;
        border: none;
        white-space: nowrap;
        opacity: 0.85;
        line-height: 1.4;
    }

    .category-card:hover .sub-cat-item {
        opacity: 1;
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

        .category-card .category-title h5 {
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
            height: 260px;
        }

        .category-sidebar {
            position: relative;
            top: 0;
        }

        .category-card .category-title {
            bottom: 55px;
            left: 18px;
        }

        .category-card .category-title h5 {
            font-size: 22px;
        }

        .category-card .sub-categories-bottom {
            bottom: 18px;
            left: 18px;
            right: 18px;
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

        .category-sidebar h6 {
            font-size: 10px;
            margin-bottom: 16px;
        }

        .category-sidebar ul {
            margin-bottom: 20px;
        }

        .category-sidebar ul li a.category-link,
        .category-sidebar .category-header {
            padding: 12px 18px;
            font-size: 14px;
        }

        .category-sidebar ul li {
            margin-bottom: 6px;
        }

        .category-card {
            height: 240px;
            border-radius: 12px;
        }

        .category-card .category-title {
            bottom: 50px;
            left: 16px;
        }

        .category-card .category-title h5 {
            font-size: 20px;
        }

        .category-card .sub-categories-bottom {
            bottom: 16px;
            left: 16px;
            right: 16px;
            gap: 7px;
        }

        .category-card .sub-cat-item {
            font-size: 10px;
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

        .col-md-6 {
            width: 50%;
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

        .category-sidebar h6 {
            font-size: 9px;
            margin-bottom: 14px;
        }

        .category-sidebar ul {
            margin-bottom: 18px;
        }

        .category-sidebar ul li a.category-link,
        .category-sidebar .category-header {
            padding: 11px 16px;
            font-size: 13px;
        }

        .category-sidebar ul li {
            margin-bottom: 5px;
            border-radius: 40px;
        }

        .category-sidebar ul li i {
            font-size: 10px;
        }

        .category-card {
            height: 220px;
            border-radius: 10px;
        }

        .category-card .category-title {
            bottom: 45px;
            left: 14px;
        }

        .category-card .category-title h5 {
            font-size: 18px;
        }

        .category-card .sub-categories-bottom {
            bottom: 14px;
            left: 14px;
            right: 14px;
            gap: 6px;
        }

        .category-card .sub-cat-item {
            font-size: 9px;
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

        .col-lg-6 {
            width: 100%;
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

        .category-card .category-title {
            bottom: 40px;
            left: 12px;
        }

        .category-card .category-title h5 {
            font-size: 16px;
        }

        .category-card .sub-categories-bottom {
            bottom: 12px;
            left: 12px;
            right: 12px;
        }

        .category-card .sub-cat-item {
            font-size: 8px;
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

        .back-arrow:hover {
            transform: none;
        }

        .back-arrow:active {
            transform: scale(0.95);
        }
    }
</style>

@section('content')
    <div class="category-page">

        {{-- Hero Banner with Main Category Image --}}
        <div class="category-hero"
            style="background-image: url('{{ uploaded_asset($mainCategory->banner ?? $levelOneCategories->first()->banner) }}');">
            <div class="container">
                <a href="javascript:history.back()" class="back-arrow">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>
                    <span class="explore">Explore</span>
                    {{ $mainCategory->getTranslation('name') ?? 'Food & Beverages' }}
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
                            <h6>CATEGORIES</h6>

                            {{-- All Categories Link --}}
                            <ul>
                                <li class="{{ !request('category') ? 'active' : '' }}">
                                    <a href="{{ route('categories.all') }}" class="category-link">
                                        <span>All Categories</span>
                                    </a>
                                </li>
                            </ul>

                            {{-- Main Category --}}
                            @if ($mainCategory)
                                <ul>
                                    <li class="active main-category">
                                        <a href="{{ route('products.category', $mainCategory->slug) }}"
                                            class="category-link">
                                            <span>{{ $mainCategory->getTranslation('name') }}</span>
                                            <i class="fas fa-chevron-down"></i>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            {{-- Level One Categories (Sub Categories) --}}
                            @foreach ($levelOneCategories as $category)
                                <h6>{{ $category->getTranslation('name') }}</h6>

                                @if ($category->childrenCategories && $category->childrenCategories->count() > 0)
                                    {{-- Category with children --}}
                                    <ul class="parent-category-list">
                                        <li class="parent-category" data-category-id="{{ $category->id }}">
                                            <div class="category-header">
                                                <a href="{{ route('products.level2', $category->id) }}"
                                                    class="category-link">
                                                    <span>{{ $category->getTranslation('name') }}</span>
                                                </a>
                                                <i class="fas fa-chevron-down toggle-icon"></i>
                                            </div>
                                        </li>

                                        {{-- Sub Categories (hidden by default) --}}
                                        <ul class="sub-categories" data-parent-id="{{ $category->id }}"
                                            style="display: none;">
                                            @foreach ($category->childrenCategories as $subCategory)
                                                <li>
                                                    <a href="{{ route('products.level2', $subCategory->id) }}"
                                                        class="category-link">
                                                        <span>{{ $subCategory->getTranslation('name') }}</span>
                                                        <i class="fas fa-chevron-right"></i>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </ul>
                                @else
                                    {{-- Category without children --}}
                                    <ul>
                                        <li>
                                            <a href="{{ route('products.level2', $category->id) }}" class="category-link">
                                                <span>{{ $category->getTranslation('name') }}</span>
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Cards Grid --}}
                    <div class="col-lg-9">
                        <div class="row g-4">
                            @foreach ($levelOneCategories as $category)
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <a href="{{ route('categories.level2', $category->id) }}" class="text-decoration-none">
                                        <div class="category-card">
                                            <img src="{{ uploaded_asset($category->banner) }}"
                                                alt="{{ $category->getTranslation('name') }}"
                                                onerror="this.src='{{ static_asset('assets/img/placeholder.jpg') }}'">

                                            {{-- Cart Icon - Top Left --}}
                                            <div class="cart-icon">
                                                <i class="fas fa-shopping-basket"></i>
                                            </div>

                                            {{-- Category Title - Bottom Left --}}
                                            <div class="category-title">
                                                <h5>{{ $category->getTranslation('name') }}</h5>
                                            </div>

                                            {{-- Sub Categories - Below Title --}}
                                            @if ($category->childrenCategories && $category->childrenCategories->count() > 0)
                                                <div class="sub-categories-bottom">
                                                    @foreach ($category->childrenCategories->take(5) as $subCat)
                                                        <span
                                                            class="sub-cat-item">{{ $subCat->getTranslation('name') }}</span>
                                                    @endforeach
                                                    @if ($category->childrenCategories->count() > 5)
                                                        <span
                                                            class="sub-cat-item">+{{ $category->childrenCategories->count() - 5 }}</span>
                                                    @endif
                                                </div>
                                            @endif
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

    {{-- JavaScript for Toggle Functionality --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const parentCategories = document.querySelectorAll('.parent-category');

            parentCategories.forEach(function(parent) {
                const categoryId = parent.getAttribute('data-category-id');
                const toggleIcon = parent.querySelector('.toggle-icon');
                const subCategoriesUl = document.querySelector(
                    `.sub-categories[data-parent-id="${categoryId}"]`);

                // Check if any subcategory is currently active
                const hasActiveSubcategory = subCategoriesUl && subCategoriesUl.querySelector('li.active');
                if (hasActiveSubcategory) {
                    subCategoriesUl.style.display = 'block';
                    toggleIcon.style.transform = 'rotate(180deg)';
                    parent.classList.add('active');
                }

                // Toggle icon click event
                if (toggleIcon && subCategoriesUl) {
                    toggleIcon.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (subCategoriesUl.style.display === 'none' || subCategoriesUl.style
                            .display === '') {
                            subCategoriesUl.style.display = 'block';
                            toggleIcon.style.transform = 'rotate(180deg)';
                        } else {
                            subCategoriesUl.style.display = 'none';
                            toggleIcon.style.transform = 'rotate(0deg)';
                        }
                    });
                }

                // Category header click event
                const categoryHeader = parent.querySelector('.category-header');
                if (categoryHeader && subCategoriesUl) {
                    categoryHeader.addEventListener('click', function(e) {
                        // If clicking on the link, allow navigation
                        if (e.target.closest('.category-link')) {
                            return;
                        }

                        e.preventDefault();

                        // Toggle sub-categories
                        if (subCategoriesUl.style.display === 'none' || subCategoriesUl.style
                            .display === '') {
                            subCategoriesUl.style.display = 'block';
                            toggleIcon.style.transform = 'rotate(180deg)';
                        } else {
                            subCategoriesUl.style.display = 'none';
                            toggleIcon.style.transform = 'rotate(0deg)';
                        }
                    });
                }
            });
        });
    </script>
@endsection
