@extends('frontend.layouts.app')

@php
    $form_all_preorder_page = session('preorder_all_page');
    session()->forget('preorder_all_page');
@endphp

@if (isset($category_id))
    @php
        $category_search = $category;
        $meta_title = $category->meta_title;
        $meta_description = $category->meta_description;
        $meta_keywords = $category->meta_keywords;
    @endphp
@elseif (isset($brand_id))
    @php
        $brand_name = get_single_brand($brand_id)->name;
        $meta_title = get_single_brand($brand_id)->meta_title;
        $meta_description = get_single_brand($brand_id)->meta_description;
        $meta_keywords = get_single_brand($brand_id)->meta_keywords;
    @endphp
@else
    @php
        $meta_title = get_setting('meta_title');
        $meta_description = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop
@section('meta_keywords'){{ $meta_keywords ?? '' }}@stop

@section('meta')
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')
    <style>
        /* Hero Header with Background Image */
        .hero-header {
            min-height: 450px;
            display: flex;
            align-items: center;
            position: relative;
            margin-bottom: 0;
            padding: 60px 0;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            width: 100%;
        }

        .back-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .back-arrow:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
        }

        .hero-title-small {
            color: rgba(46, 136, 214, 1);
            font-size: 50px;
            margin: 0 0 10px 0;
            letter-spacing: 1px;
        }

        .hero-title-large {
            color: #fff;
            font-size: 50px;
            margin: 0;
            line-height: 1.2;
        }

        /* Main Content Container */
        .main-content {
            background: #f8f9fa;
            padding: 30px 0;
            min-height: 100vh;
        }

        /* Sidebar Styling - NEW DESIGN */
        .sidebar-wrapper {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            padding: 0;
        }

        .sidebar-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
        }

        .sidebar-title {
            font-size: 11px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 0;
            font-weight: 600;
        }

        /* =======================
                           CATEGORY SIDEBAR (LIKE IMAGE)
                        ======================= */

        .category-list {
            list-style: none;
            padding: 16px;
            margin: 0;
        }

        .category-item {
            margin-bottom: 6px;
        }

        /* Base link */
        .category-item-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            font-size: 15px;
            color: #6b7280;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        /* Hover */
        .category-item-link:hover {
            background: #f3f4f6;
            color: #111827;
        }

        /* Active parent */
        .category-item.active>.category-item-link,
        .category-item.parent-active>.category-item-link {
            background: #2f80ed;
            color: #fff;
            font-weight: 500;
        }

        /* Arrow */
        .category-toggle {
            font-size: 16px;
            transition: transform 0.3s ease;
            color: inherit;
        }

        .category-item.expanded .category-toggle {
            transform: rotate(180deg);
        }

        /* =======================
                           CHILDREN
                        ======================= */

        .category-children {
            list-style: none;
            padding-left: 16px;
            margin-top: 6px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        .category-item.expanded>.category-children {
            max-height: 2000px;
        }

        /* Child item */
        .category-children .category-item-link {
            font-size: 14px;
            padding: 10px 16px;
            margin-left: 12px;
        }

        /* Active child */
        .category-children .category-item.active>.category-item-link {
            background: #2f80ed;
            color: #fff;
            font-weight: 500;
        }

        /* =======================
                           SUB CHILD
                        ======================= */

        .category-children .category-children {
            padding-left: 12px;
        }

        .category-children .category-children .category-item-link {
            font-size: 13px;
            padding: 9px 14px;
            margin-left: 20px;
        }


        /* All Categories special styling */
        .all-categories-item {
            border-bottom: 1px solid #f3f4f6;
            margin-bottom: 8px;
            padding-bottom: 8px;
        }

        .all-categories-item .category-item-link {
            color: #374151;
            font-weight: 400;
        }

        .all-categories-item:hover .category-item-link {
            background: transparent;
            color: #111827;
        }

        /* Product Grid Container */
        .products-container {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        /* Breadcrumb */
        .breadcrumb-modern {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 25px;
            padding: 0;
            background: transparent;
            list-style: none;
        }

        .breadcrumb-modern a {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-modern a:hover {
            color: #007bff;
        }

        .breadcrumb-modern .active {
            color: #212529;
            font-weight: 500;
        }

        .breadcrumb-separator {
            color: #dee2e6;
        }

        /* Page Title Section */
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 15px;
        }

        .page-description {
            color: #6c757d;
            line-height: 1.7;
            font-size: 14px;
            margin-bottom: 30px;
            max-width: 900px;
        }

        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn-add-inquiry {
            background: #007bff;
            color: #fff;
            padding: 12px 30px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add-inquiry:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sort-dropdown {
            padding: 10px 18px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 13px;
            color: #495057;
            cursor: pointer;
            background: #fff;
            min-width: 200px;
            transition: border-color 0.2s;
        }

        .sort-dropdown:hover,
        .sort-dropdown:focus {
            border-color: #007bff;
            outline: none;
        }

        /* View Toggle Buttons */
        .view-toggle {
            display: flex;
            gap: 8px;
            background: #f8f9fa;
            padding: 4px;
            border-radius: 6px;
        }

        .view-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            transition: all 0.2s;
        }

        .view-btn.active {
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .view-btn .grid-dot {
            width: 4px;
            height: 4px;
            background: #6c757d;
            border-radius: 1px;
        }

        .view-btn.active .grid-dot {
            background: #007bff;
        }

        /* Product Card */
        .product-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            border: 1px solid #e9ecef;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            border-color: #dee2e6;
        }

        .product-image-wrapper {
            position: relative;
            overflow: hidden;
            background: #f8f9fa;
            padding-top: 75%;
            /* 4:3 aspect ratio */
        }

        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .product-card:hover .product-image {
            transform: scale(1.08);
        }

        .product-info {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 12px;
        }

        .product-description {
            font-size: 13px;
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .product-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-top: auto;
        }

        .btn-product {
            flex: 1;
            padding: 10px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-product-dark {
            background: #212529;
            color: #fff;
        }

        .btn-product-dark:hover {
            background: #000;
            transform: translateY(-1px);
        }

        .btn-product-primary {
            background: #007bff;
            color: #fff;
        }

        .btn-product-primary:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            flex-shrink: 0;
        }

        .icon-btn-dark {
            background: #212529;
            color: #fff;
        }

        .icon-btn-primary {
            background: #007bff;
            color: #fff;
        }

        .icon-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Results Count */
        .results-info {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .results-count {
            font-weight: 600;
            color: #212529;
        }

        /* Filter Sections */
        .filter-section-wrapper {
            background: #fff;
            border-bottom: 1px solid #f3f4f6;
        }

        .filter-section-header {
            padding: 18px 24px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }

        .filter-section-header:hover {
            background: #fafafa;
        }

        .filter-section-title {
            font-size: 14px;
            font-weight: 600;
            color: #212529;
            margin: 0;
        }

        .filter-section-content {
            padding: 0 24px 20px;
        }

        .filter-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            cursor: pointer;
        }

        .filter-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
        }

        .filter-checkbox label {
            font-size: 14px;
            color: #495057;
            cursor: pointer;
            margin: 0;
        }

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-header {
                min-height: 220px;
                padding: 40px 0;
            }

            .hero-title-small {
                font-size: 18px;
            }

            .hero-title-large {
                font-size: 36px;
            }

            .products-container {
                padding: 20px;
            }

            .page-title {
                font-size: 22px;
            }

            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .sort-dropdown {
                width: 100%;
            }
        }

        /* Product Type Tabs */
        .product-type-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .tab-badge {
            padding: 10px 24px;
            border: 2px dashed #dee2e6;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s;
            background: transparent;
        }

        .tab-badge.active {
            background: #212529;
            color: #fff;
            border-color: #212529;
            border-style: solid;
        }

        .tab-badge:hover:not(.active) {
            border-color: #007bff;
            color: #007bff;
        }

        /* Price Range Slider */
        .price-range-wrapper {
            padding: 20px;
        }

        .price-values {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .price-value {
            font-size: 13px;
            font-weight: 600;
            color: #495057;
        }

        /* Loading State */
        .loading-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state-icon {
            font-size: 64px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-state-title {
            font-size: 20px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
        }

        .empty-state-text {
            color: #6c757d;
            font-size: 14px;
        }
    </style>

    <!-- Hero Header -->
    @php
        $banner = App\Models\Category::where('id', $category_id)->get()->first()->banner;
    @endphp


    <section class="hero-header"
        style="
        background:
        linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
        url('{{ uploaded_asset($banner) ?? asset('images/default-hero.jpg') }}')
        center / cover;
    ">

        <div class="hero-content">
            <a href="{{ route('home') }}" class="back-arrow">
                <i class="las la-arrow-left"></i>
            </a>
            <h1 class="hero-title-small">Explore</h1>
            <h2 class="hero-title-large">
                @if (isset($category_id))
                    {{ $category_search->getTranslation('name') }}
                @elseif (isset($brand_id))
                    {{ $brand_name }}
                @else
                    Frozen Food
                @endif
            </h2>
        </div>
    </section>

    <!-- Main Content -->
    <section class="main-content">
        <div class="container">
            <form id="search-form" method="GET">
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-lg-3 mb-4">
                        <div class="sidebar-wrapper">
                            <!-- Mobile Filter Toggle -->
                            <div class="d-lg-none">
                                <button type="button" class="btn btn-block btn-light mb-3" data-toggle="collapse"
                                    data-target="#filterSidebar">
                                    <i class="las la-filter"></i> {{ translate('Show Filters') }}
                                </button>
                            </div>

                            <div id="filterSidebar" class="collapse d-lg-block">
                                <!-- Categories -->
                                <div class="sidebar-header">
                                    <h3 class="sidebar-title">{{ translate('CATEGORIES') }}</h3>
                                </div>

                                <div class="display-none" id="general_cagegories_box">
                                    <ul class="category-list" id="category_filter">
                                        <!-- All Categories -->
                                        <li class="category-item all-categories-item">
                                            <a href="{{ route('search') }}" class="category-item-link">
                                                <span class="category-name">{{ translate('All Categories') }}</span>
                                            </a>
                                        </li>

                                        @foreach ($categories as $category)
                                            @if ($category->products_count > 0 || count($category->childrenCategories) > 0)
                                                <li class="category-item @if (count($category->childrenCategories) > 0) has-children @endif @if (isset($category_id) && $category_id == $category->id) active @endif"
                                                    data-id="{{ $category->id }}">
                                                    <div class="category-item-link"
                                                        onclick="handleCategoryClick(event, {{ $category->id }}, '{{ route('products.category', $category->slug) }}', {{ count($category->childrenCategories) > 0 ? 'true' : 'false' }})">
                                                        <span
                                                            class="category-name">{{ $category->getTranslation('name') }}</span>
                                                        @if (count($category->childrenCategories) > 0)
                                                            <i class="las la-angle-down category-toggle"></i>
                                                        @endif
                                                    </div>

                                                    @if (count($category->childrenCategories) > 0)
                                                        <ul class="category-children">
                                                            @foreach ($category->childrenCategories as $childCategory)
                                                                <li class="category-item @if (isset($category_id) && $category_id == $childCategory->id) active @endif"
                                                                    data-id="{{ $childCategory->id }}">
                                                                    <div class="category-item-link"
                                                                        onclick="handleCategoryClick(event, {{ $childCategory->id }}, '{{ route('products.category', $childCategory->slug) }}', {{ count($childCategory->childrenCategories) > 0 ? 'true' : 'false' }})">
                                                                        <span
                                                                            class="category-name">{{ $childCategory->getTranslation('name') }}</span>
                                                                        @if (count($childCategory->childrenCategories) > 0)
                                                                            <i
                                                                                class="las la-angle-down category-toggle"></i>
                                                                        @endif
                                                                    </div>

                                                                    @if (count($childCategory->childrenCategories) > 0)
                                                                        <ul class="category-children">
                                                                            @foreach ($childCategory->childrenCategories as $subChildCategory)
                                                                                <li class="category-item @if (isset($category_id) && $category_id == $subChildCategory->id) active @endif"
                                                                                    data-id="{{ $subChildCategory->id }}">
                                                                                    <a href="{{ route('products.category', $subChildCategory->slug) }}"
                                                                                        class="category-item-link">
                                                                                        <span
                                                                                            class="category-name">{{ $subChildCategory->getTranslation('name') }}</span>
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="display-none" id="preorder_cagegories_box">
                                    <ul class="category-list" id="category_filter_preorder">
                                        <!-- All Categories -->
                                        <li class="category-item all-categories-item">
                                            <a href="{{ route('search') }}" class="category-item-link">
                                                <span class="category-name">{{ translate('All Categories') }}</span>
                                            </a>
                                        </li>

                                        @foreach ($preorder_categories as $category)
                                            @if ($category->products_count > 0 || count($category->childrenCategories) > 0)
                                                <li class="category-item @if (count($category->childrenCategories) > 0) has-children @endif @if (isset($category_id) && $category_id == $category->id) active @endif"
                                                    data-id="{{ $category->id }}">
                                                    <div class="category-item-link"
                                                        onclick="handleCategoryClick(event, {{ $category->id }}, '{{ route('products.category', $category->slug) }}', {{ count($category->childrenCategories) > 0 ? 'true' : 'false' }})">
                                                        <span
                                                            class="category-name">{{ $category->getTranslation('name') }}</span>
                                                        @if (count($category->childrenCategories) > 0)
                                                            <i class="las la-angle-down category-toggle"></i>
                                                        @endif
                                                    </div>

                                                    @if (count($category->childrenCategories) > 0)
                                                        <ul class="category-children">
                                                            @foreach ($category->childrenCategories as $childCategory)
                                                                <li class="category-item @if (isset($category_id) && $category_id == $childCategory->id) active @endif"
                                                                    data-id="{{ $childCategory->id }}">
                                                                    <div class="category-item-link"
                                                                        onclick="handleCategoryClick(event, {{ $childCategory->id }}, '{{ route('products.category', $childCategory->slug) }}', {{ count($childCategory->childrenCategories) > 0 ? 'true' : 'false' }})">
                                                                        <span
                                                                            class="category-name">{{ $childCategory->getTranslation('name') }}</span>
                                                                        @if (count($childCategory->childrenCategories) > 0)
                                                                            <i
                                                                                class="las la-angle-down category-toggle"></i>
                                                                        @endif
                                                                    </div>

                                                                    @if (count($childCategory->childrenCategories) > 0)
                                                                        <ul class="category-children">
                                                                            @foreach ($childCategory->childrenCategories as $subChildCategory)
                                                                                <li class="category-item @if (isset($category_id) && $category_id == $subChildCategory->id) active @endif"
                                                                                    data-id="{{ $subChildCategory->id }}">
                                                                                    <a href="{{ route('products.category', $subChildCategory->slug) }}"
                                                                                        class="category-item-link">
                                                                                        <span
                                                                                            class="category-name">{{ $subChildCategory->getTranslation('name') }}</span>
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Price Range Filter -->
                                <div class="filter-section-wrapper">
                                    <div class="filter-section-header" data-toggle="collapse" data-target="#priceFilter">
                                        <h4 class="filter-section-title">{{ translate('Price range') }}</h4>
                                        <i class="las la-angle-down"></i>
                                    </div>
                                    <div id="priceFilter" class="collapse">
                                        <div class="price-range-wrapper">
                                            @php
                                                $product_count = get_products_count();
                                            @endphp
                                            <div class="aiz-range-slider">
                                                <div id="input-slider-range" data-range-value-min="0"
                                                    data-range-value-max="@if ($product_count < 1) 0 @else {{ get_product_max_unit_price() }} @endif">
                                                </div>
                                                <div class="price-values">
                                                    <span class="price-value" id="input-slider-range-value-low">0</span>
                                                    <span class="price-value" id="input-slider-range-value-high"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="min_price" value="">
                                        <input type="hidden" name="max_price" value="">
                                    </div>
                                </div>

                                <!-- Attribute Filters -->
                                @foreach ($attributes as $attribute)
                                    @if ($attribute->product_count > 0)
                                        <div class="filter-section-wrapper preorder-time-hide">
                                            <div class="filter-section-header" data-toggle="collapse"
                                                data-target="#filter_{{ str_replace(' ', '_', preg_replace('/[^a-zA-Z]/', '', $attribute->name)) }}">
                                                <h4 class="filter-section-title">{{ $attribute->getTranslation('name') }}
                                                </h4>
                                                <i class="las la-angle-down"></i>
                                            </div>
                                            @php
                                                $show = '';
                                                foreach ($attribute->attribute_values as $attribute_value) {
                                                    if (in_array($attribute_value->value, $selected_attribute_values)) {
                                                        $show = 'show';
                                                    }
                                                }
                                            @endphp
                                            <div id="filter_{{ str_replace(' ', '_', preg_replace('/[^a-zA-Z]/', '', $attribute->name)) }}"
                                                class="collapse {{ $show }} filter-section-content">
                                                @foreach ($attribute->attribute_values as $attribute_value)
                                                    @if ($attribute_value->product_count > 0)
                                                        <div class="filter-checkbox">
                                                            <input type="checkbox" name="selected_attribute_values[]"
                                                                value="{{ $attribute_value->value }}"
                                                                id="attr_{{ $attribute_value->id }}"
                                                                @if (in_array($attribute_value->value, $selected_attribute_values)) checked @endif
                                                                onchange="filter(event)">
                                                            <label for="attr_{{ $attribute_value->id }}">
                                                                {{ $attribute_value->value }}
                                                                ({{ $attribute_value->product_count }})
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Color Filter -->
                                @if (get_setting('color_filter_activation'))
                                    <div class="filter-section-wrapper preorder-time-hide">
                                        <div class="filter-section-header" data-toggle="collapse"
                                            data-target="#colorFilter">
                                            <h4 class="filter-section-title">{{ translate('Filter by color') }}</h4>
                                            <i class="las la-angle-down"></i>
                                        </div>
                                        @php
                                            $show = '';
                                            foreach ($colors as $key => $color) {
                                                if (isset($selected_color) && $selected_color == $color->code) {
                                                    $show = 'show';
                                                }
                                            }
                                        @endphp
                                        <div id="colorFilter"
                                            class="collapse {{ $show }} filter-section-content">
                                            @foreach ($colors as $key => $color)
                                                @if ($color->product_count > 0)
                                                    <div class="filter-checkbox">
                                                        <input type="checkbox" name="colors[]"
                                                            value="{{ $color->code }}" id="color_{{ $color->id }}"
                                                            @if (isset($selected_color) && $selected_color == $color->code) checked @endif
                                                            onchange="filter(event)">
                                                        <label for="color_{{ $color->id }}"
                                                            class="d-flex align-items-center">
                                                            <span
                                                                style="width: 20px; height: 20px; background-color: {{ $color->code }}; border-radius: 50%; margin-right: 8px; display: inline-block;"></span>
                                                            {{ $color->name }} ({{ $color->product_count }})
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Availability Filter (for preorder) -->
                                <div class="filter-section-wrapper preorder-time-show display-none">
                                    <div class="filter-section-header" data-toggle="collapse"
                                        data-target="#availabilityFilter">
                                        <h4 class="filter-section-title">{{ translate('Filter by Availability') }}</h4>
                                        <i class="las la-angle-down"></i>
                                    </div>
                                    @php
                                        $show = $is_available !== null ? 'show' : '';
                                    @endphp
                                    <div id="availabilityFilter"
                                        class="collapse {{ $show }} filter-section-content">
                                        <div class="filter-checkbox">
                                            <input type="radio" name="is_available" value="1" id="available_now"
                                                @if ($is_available == 1) checked @endif onchange="filter(event)">
                                            <label for="available_now">{{ translate('Available Now') }}</label>
                                        </div>
                                        <div class="filter-checkbox">
                                            <input type="radio" name="is_available" value="0" id="upcoming"
                                                @if ($is_available === '0') checked @endif
                                                onchange="filter(event)">
                                            <label for="upcoming">{{ translate('Upcoming') }}</label>
                                        </div>
                                        <div class="filter-checkbox">
                                            <input type="radio" name="is_available" value=""
                                                id="all_availability" @if ($is_available === null) checked @endif
                                                onchange="filter(event)">
                                            <label for="all_availability">{{ translate('All') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Section -->
                    <div class="col-lg-9">
                        <!-- Product Type Tabs -->
                        @if (addon_is_activated('preorder') && Route::currentRouteName() == 'search')
                            <div class="product-type-tabs">
                                <label class="mb-0">
                                    <input type="radio" name="product_type" value="general_product"
                                        onchange="filter(event)" style="display: none;">
                                    <span id="product_type_badge_general" class="tab-badge">
                                        {{ translate('General Products') }}
                                    </span>
                                </label>
                                <label class="mb-0">
                                    <input type="radio" name="product_type" value="preorder_product"
                                        onchange="filter(event)" style="display: none;">
                                    <span id="product_type_badge_preorder" class="tab-badge">
                                        {{ translate('Preorder Products') }}
                                    </span>
                                </label>
                            </div>
                        @endif

                        <div class="products-container">
                            <!-- Breadcrumb -->
                            <ul class="breadcrumb-modern">
                                <li><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                                <span class="breadcrumb-separator">/</span>
                                @if (!isset($category_id) && !isset($brand_id))
                                    <li class="active">"{{ translate('All Categories') }}"</li>
                                @else
                                    <li class="show_cat1 d-none active">"{{ translate('All Categories') }}"</li>
                                    @if (!isset($brand_id))
                                        <li class="hide_cat1">
                                            <a href="{{ route('search') }}">{{ translate('All Categories') }}</a>
                                        </li>
                                        <span class="hide_cat1 breadcrumb-separator">/</span>
                                    @endif
                                @endif
                                @if (isset($brand_id))
                                    <li class="hide_cat1">{{ translate('Brand') }}</li>
                                    <span class="hide_cat1 breadcrumb-separator">/</span>
                                    <li class="hide_cat1 active">"{{ $brand_name }}"</li>
                                @endif
                                @if (isset($category_id))
                                    <li class="hide_cat1 active">"{{ $category_search->getTranslation('name') }}"</li>
                                @endif
                            </ul>

                            <!-- Page Title -->
                            <h1 class="page-title">
                                @if (isset($category_id))
                                    {{ $category_search->getTranslation('name') }}
                                @elseif(isset($query))
                                    {{ translate('Search result for ') }} "{{ $query }}"
                                @else
                                    {{ translate('Showing results') }}
                                @endif
                            </h1>

                            <!-- Description -->
                            <p class="page-description">
                                To connect global markets efficiently and ethically by providing exceptional sourcing and
                                trade solutions that enhance business value and foster sustainable growth.
                            </p>

                            <!-- Action Bar -->
                            <div class="action-bar">
                                <button type="button" class="btn-add-inquiry">
                                    <i class="las la-plus"></i> Add to inquiry
                                </button>

                                <div class="toolbar">
                                    <select id="select_option" class="sort-dropdown" name="sort_by"
                                        onchange="filter(event)">
                                        <option value="">{{ translate('Sort by') }}</option>
                                        <option value="newest"
                                            @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>
                                            {{ translate('Newest') }}</option>
                                        <option value="oldest"
                                            @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>
                                            {{ translate('Oldest') }}</option>
                                        <option value="price-asc"
                                            @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>
                                            {{ translate('Price low to high') }}</option>
                                        <option value="price-desc"
                                            @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>
                                            {{ translate('Price high to low') }}</option>
                                    </select>

                                    <div class="view-toggle">
                                        <button type="button" class="view-btn view-2-hide" data-cols="2">
                                            <span class="grid-dot"></span>
                                            <span class="grid-dot"></span>
                                        </button>
                                        <button type="button" class="view-btn view-3-hide" data-cols="3">
                                            <span class="grid-dot"></span>
                                            <span class="grid-dot"></span>
                                            <span class="grid-dot"></span>
                                        </button>
                                        <button type="button" class="view-btn view-4-hide active" data-cols="4">
                                            <span class="grid-dot"></span>
                                            <span class="grid-dot"></span>
                                            <span class="grid-dot"></span>
                                            <span class="grid-dot"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Results Info -->
                            <div class="results-info display-none" id="search_product_count">
                                <span class="results-count" id="total_product_count">{{ $products->total() }}</span>
                                Products Found
                            </div>
                            <div class="display-none" id="searching_product">
                                <div class="loading-spinner"></div>
                            </div>

                            <input type="hidden" name="keyword" value="{{ $query ?? '' }}">

                            <!-- Products Grid -->
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3" id="products-row">
                                <!-- Products will be loaded here via AJAX -->
                            </div>

                            <!-- Pagination -->
                            <div class="pagination-wrapper" id="pagination"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        let category_page_first_time = true;
        let brand_page_first_time = true;
        let session_data_first_time = true;

        // Toggle category expansion
        function toggleCategory(element, categoryId) {
            event.stopPropagation();

            const $item = $(element);
            const hasChildren = $item.find('.category-children').length > 0;

            if (hasChildren) {
                $item.toggleClass('expanded');
            }

            // Set active state
            $('.category-item').removeClass('active parent-active');
            $item.addClass('active');

            // Mark parent as active if child is clicked
            $item.parents('.category-item').addClass('parent-active');

            filter();
        }

        function filter(e) {
            if (e) e.preventDefault();

            const target = e ? e.target : null;

            if (target && target.type === 'checkbox') {
                const parent = target.parentElement;
                if (parent) {
                    const label = parent.querySelector('label');
                    if (label) {
                        if (target.checked) {
                            label.style.fontWeight = '600';
                        } else {
                            label.style.fontWeight = '400';
                        }
                    }
                }
            }

            filter_data();
        }

        function rangefilter(arg) {
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter_data();
        }

        function filter_data(page = 1) {
            $("#search_product_count").hide();
            $("#searching_product").show();

            var formData = $('#search-form').serialize();
            formData += '&page=' + page;

            if (session_data_first_time) {
                const form_all_preorder_page = @json($form_all_preorder_page);
                if (form_all_preorder_page && form_all_preorder_page === 'preorder_product') {
                    formData = formData.replace(/(&|^)product_type=[^&]*/g, '');
                    formData += '&product_type=' + 'preorder_product';
                    $('input[name="product_type"][value="preorder_product"]').prop('checked', true);
                    $('#product_type_badge_preorder').addClass('active');
                    session_data_first_time = false;
                }
            }

            let category_id = <?php echo $category_id ?? 'null'; ?>;
            let brand_id = <?php echo $brand_id ?? 'null'; ?>;

            if (category_page_first_time && category_id !== null && category_id !== 0 && category_id !== undefined) {
                formData += '&categories[]=' + category_id;
                category_page_first_time = false;
            } else if (brand_page_first_time && brand_id !== null && brand_id !== 0 && brand_id !== undefined) {
                formData += "&brand_id=" + brand_id;
                brand_page_first_time = false;
            } else {
                $('.hide_cat1').hide();
                $('.show_cat1').removeClass('d-none');
            }

            if (formData.includes('product_type=preorder_product')) {
                $('#product_type_badge_preorder').addClass('active');
                $('#product_type_badge_general').removeClass('active');
                $('#preorder_cagegories_box').slideDown(300);
                $('#general_cagegories_box').slideUp(300);
                $('.preorder-time-hide').fadeOut(400);
                $('.preorder-time-show').slideDown(400);
            } else {
                $('#product_type_badge_general').addClass('active');
                $('#product_type_badge_preorder').removeClass('active');
                $('#preorder_cagegories_box').slideUp(300);
                $('#general_cagegories_box').slideDown(300);
                $('.preorder-time-hide').fadeIn(400);
                $('.preorder-time-show').slideUp(400);
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('suggestion.search2') }}",
                type: 'get',
                data: formData,
                success: function(response) {
                    $("#search_product_count").show();
                    $("#searching_product").hide();
                    $('#products-row').html(response.product_html);
                    $('#pagination').html(response.pagination_html);
                    $('#total_product_count').text(response.total_product_count);

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        $(document).on('click', '.page-btn', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            filter_data(page);
        });

        // View toggle buttons
        $('.view-btn').on('click', function() {
            $('.view-btn').removeClass('active');
            $(this).addClass('active');

            var colValue = $(this).data('cols');
            var $row = $('#products-row');

            $row.removeClass(function(index, className) {
                return (className.match(/(^|\s)row-cols-\S+/g) || []).join(' ');
            });

            $row.addClass('row-cols-1 row-cols-sm-2 row-cols-md-3');

            if (colValue == 2) {
                $row.addClass('row-cols-lg-2');
            } else if (colValue == 3) {
                $row.addClass('row-cols-lg-3');
            } else {
                $row.addClass('row-cols-lg-4');
            }
        });

        $(document).ready(function() {
            const path = window.location.pathname;
            filter_data();

            // Initialize general categories as visible
            $('#general_cagegories_box').show();
            $('#product_type_badge_general').addClass('active');

            // Auto-expand active category
            const activeCategoryId = <?php echo $category_id ?? 'null'; ?>;
            if (activeCategoryId) {
                const $activeItem = $(`.category-item[data-id="${activeCategoryId}"]`);
                $activeItem.addClass('active expanded');
                $activeItem.parents('.category-item').addClass('parent-active expanded');
            }
        });
    </script>

    <script src="{{ static_asset('assets/js/hummingbird-treeview2.js') }}"></script>

    <script>
        $(document).ready(function() {
            var $tree = $('#treeview2');

            if ($tree.length) {
                $tree.hummingbird();

                var selected_ids = '{{ implode(',', $old_categories ?? []) }}';
                if (selected_ids != '') {
                    const myArray = selected_ids.split(",");
                    for (let i = 0; i < myArray.length; i++) {
                        const element = myArray[i];
                        $('#category_checkidgenerel_' + element).prop('checked', true);
                        $('#category_checkid_textgenerel_' + element).css('font-weight', '600');
                        $('#category_checkidgenerel_' + element).parents("ul").css("display", "block");
                    }
                }
            }
        });

        window.onload = function() {
            setTimeout(function() {
                // Clean up empty categories
                const mainUl = $('#category_filter div ul');
                const mainUlPreorder = $('#category_filter_preorder div ul');

                function processUl($ul) {
                    $ul.addClass('ul_is_empty');

                    $ul.children('li').each(function() {
                        const $li = $(this);
                        const $nestedUl = $li.children('ul');

                        if ($nestedUl.length > 0) {
                            processUl($nestedUl);

                            if ($nestedUl.children('li').length === 0) {
                                $nestedUl.prev('i').remove();
                                $nestedUl.remove();
                            }
                        } else {
                            const countAttr = $li.attr('count');
                            if (countAttr === "0") {
                                $li.remove();
                            }
                        }
                    });
                }

                if (mainUl.length > 0) {
                    processUl(mainUl);
                    $('.ul_is_empty').each(function() {
                        const $ul = $(this);
                        if ($ul.children('li').length === 0) {
                            $ul.prev('i').remove();
                            $ul.remove();
                        }
                    });
                }

                if (mainUlPreorder.length > 0) {
                    processUl(mainUlPreorder);
                    $('.ul_is_empty').each(function() {
                        const $ul = $(this);
                        if ($ul.children('li').length === 0) {
                            $ul.prev('i').remove();
                            $ul.remove();
                        }
                    });
                }
            }, 0);
        };
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* =========================
               TOGGLE ARROWS CLICK
            ========================= */
            document.querySelectorAll('.category-toggle').forEach(function(toggle) {

                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const parentItem = this.closest('.category-item');

                    if (!parentItem) return;

                    parentItem.classList.toggle('expanded');
                });

            });

            /* =========================
               AUTO EXPAND ACTIVE ITEMS
            ========================= */
            document.querySelectorAll('.category-item.active').forEach(function(activeItem) {

                let currentItem = activeItem;

                while (currentItem) {
                    currentItem.classList.add('expanded');

                    const parentUl = currentItem.closest('.category-children');
                    if (!parentUl) break;

                    currentItem = parentUl.closest('.category-item');
                }

            });

        });
    </script>


@endsection
