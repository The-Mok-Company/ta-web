@extends('frontend.layouts.app')

@section('meta_title', 'Product Listing')


@php
    $form_all_preorder_page = session('preorder_all_page');
    session()->forget('preorder_all_page');
@endphp
@php
    use App\Models\Category;

    $mainCategories = Category::where('level', 0)
        ->with([
            'childrenCategories' => function ($query) {
                $query
                    ->with([
                        'childrenCategories' => function ($q) {
                            $q->with('childrenCategories')->withCount('products');
                        },
                    ])
                    ->withCount('products');
            },
        ])
        ->orderBy('order_level', 'desc')
        ->get();

    $currentCategoryId = request()->segment(2);
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
            border-radius: 8px;
            padding: 30px;
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
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn-add-inquiry {
            background: rgba(5, 133, 188, 1);
            color: #fff;
            border-radius: 50px;
            padding: 12px 30px;
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
            border-radius: 50px;
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
    <style>
        /* ========== CATEGORY SIDEBAR STYLING ========== */
        .category-sidebar {
            background: #fff;
            padding: 30px 20px;
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, .08);
            position: sticky;
            top: 20px;
            margin-bottom: 30px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }

        /* Custom Scrollbar */
        .category-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .category-sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .category-sidebar::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .category-sidebar::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        /* Header Styling */
        .category-sidebar h6 {
            font-weight: 600;
            margin-bottom: 15px;
            margin-top: 30px;
            font-size: 10px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 0 12px;
        }

        .category-sidebar h6:first-of-type {
            margin-top: 0;
        }

        /* List Base Styling */
        .category-sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        .category-sidebar ul li {
            padding: 0;
            border-radius: 12px;
            font-size: 14px;
            cursor: pointer;
            transition: all .3s ease;
            margin-bottom: 6px;
            color: #555;
            font-weight: 500;
            background: transparent;
            position: relative;
        }

        /* Category Links */
        .category-sidebar ul li a.category-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            color: inherit !important;
            text-decoration: none;
            margin: 4px;
            width: 100%;
            border-radius: 12px;
            transition: all .3s ease;
        }

        .category-sidebar .category-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-radius: 12px;
            cursor: pointer;
            transition: all .3s ease;
        }

        .category-sidebar .category-header .category-name {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
        }

        /* Toggle Icons */
        .category-sidebar .toggle-icon {
            font-size: 10px;
            opacity: 0.5;
            transition: all .3s ease;
            cursor: pointer;
            padding: 4px 8px;
            margin-left: 8px;
            flex-shrink: 0;
        }

        /* Hover States */
        .category-sidebar ul li:hover:not(.active) {
            background: #f8f9fa !important;
        }

        .category-sidebar ul li:hover:not(.active) a.category-link,
        .category-sidebar ul li:hover:not(.active) .category-header:not(.active) {
            color: #333;
        }

        /* Active States for LI */
        .category-sidebar ul li.active {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%) !important;
            color: #fff;
            box-shadow: 0 3px 10px rgba(74, 144, 226, 0.25);
        }

        .category-sidebar ul li.active a.category-link,
        .category-sidebar ul li.active .category-name {
            color: #fff;
        }

        .category-sidebar ul li.active:hover {
            filter: brightness(1.05);
        }

        /* ========== ACTIVE HEADER STYLING (الجديد) ========== */

        /* Main Category Header Active */
        .main-category-item .main-category-header.active {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%) !important;
            color: #fff !important;
            box-shadow: 0 3px 10px rgba(74, 144, 226, 0.25);
        }

        .main-category-item .main-category-header.active .main-category-name {
            color: #fff !important;
        }

        .main-category-item .main-category-header.active .main-toggle-icon {
            opacity: 1;
            color: #fff !important;
        }

        .main-category-item .main-category-header.active:hover {
            filter: brightness(1.05);
        }

        /* Level 1 & Level 2 Category Header Active */
        .category-sidebar .category-header.active {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%) !important;
            color: #fff !important;
            box-shadow: 0 3px 10px rgba(74, 144, 226, 0.25);
        }

        .category-sidebar .category-header.active .category-name,
        .category-sidebar .category-header.active .category-name span {
            color: #fff !important;
        }

        .category-sidebar .category-header.active .toggle-icon {
            opacity: 1 !important;
            color: #fff !important;
        }

        .category-sidebar .category-header.active:hover {
            filter: brightness(1.05);
        }

        /* إلغاء الـ background من الـ LI لو الـ header عنده active class */
        .category-sidebar ul li:has(> .category-header.active) {
            background: transparent !important;
            box-shadow: none !important;
        }

        /* Chevron Icons */
        .category-sidebar ul li i.fa-chevron-right {
            font-size: 9px;
            opacity: 0.5;
            transition: .3s;
            flex-shrink: 0;
        }

        .category-sidebar ul li:hover i.fa-chevron-right {
            opacity: 0.8;
        }

        .category-sidebar ul li.active i {
            opacity: 1;
            color: #fff;
        }

        /* Sub-categories Visibility */
        .sub-categories,
        .sub-sub-categories {
            display: none;
            padding-left: 10px;
            margin-top: 5px;
        }

        .sub-categories.show,
        .sub-sub-categories.show {
            display: block;
            margin-left: 16px;
        }

        /* Rotate toggle icon when expanded */
        .category-sidebar ul li.active>.category-header>.toggle-icon {
            transform: rotate(180deg);
        }

        /* All Main Categories Wrapper */
        .all-main-categories-wrapper {
            padding-left: 0;
            margin-top: 5px;
        }

        .all-main-categories-wrapper.collapsed {
            display: none;
        }

        /* Main Category Item Styling */
        .main-category-item {
            margin-bottom: 15px;
        }

        .main-category-item .main-category-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-radius: 12px;
            cursor: pointer;
            transition: all .3s ease;
            background: transparent;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        .main-category-item .main-category-header:hover:not(.active) {
            background: #f8f9fa;
            color: #333;
        }

        .main-category-item .main-category-name {
            flex: 1;
            text-decoration: none;
            color: inherit;
        }

        .main-category-item .main-toggle-icon {
            font-size: 10px;
            opacity: 0.5;
            transition: all .3s ease;
            cursor: pointer;
            padding: 4px 8px;
            margin-left: 8px;
            flex-shrink: 0;
        }

        .main-category-item .main-toggle-icon.rotated {
            transform: rotate(180deg);
        }

        .main-category-children {
            display: none;
            padding-left: 0;
            margin-top: 10px;
        }

        .main-category-children.show {
            display: block;
        }

        /* ========== RESPONSIVE DESIGN ========== */
        @media (max-width: 1024px) {
            .category-sidebar {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .category-sidebar {
                margin-bottom: 25px;
                padding: 25px 20px;
                border-radius: 14px;
            }

            .category-sidebar h6 {
                font-size: 10px;
                margin-bottom: 16px;
            }

            .category-sidebar ul li a.category-link,
            .category-sidebar .category-header {
                padding: 12px 18px;
                font-size: 14px;
            }

            .category-sidebar ul li {
                margin-bottom: 6px;
            }
        }

        @media (max-width: 576px) {
            .category-sidebar {
                padding: 22px 18px;
                margin-bottom: 20px;
                border-radius: 12px;
            }

            .category-sidebar h6 {
                font-size: 9px;
                margin-bottom: 14px;
            }

            .category-sidebar ul li a.category-link,
            .category-sidebar .category-header {
                padding: 11px 16px;
                font-size: 13px;
            }

            .category-sidebar ul li {
                margin-bottom: 5px;
            }
        }

        /* Touch Devices Optimization */
        @media (hover: none) and (pointer: coarse) {
            .category-sidebar ul li:hover {
                background: transparent !important;
            }

            .category-sidebar ul li:active:not(.active) {
                background: #f8f9fa !important;
            }
        }

        #title-filter {
            font-weight: 600;
            margin-bottom: 15px;
            margin-top: 30px;
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 2px 0px;
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
                        <div class="category-sidebar">
                            <h6>CATEGORIES</h6>

                            {{-- All Categories + Toggle --}}
                            <ul>
                                <li class="{{ !request()->segment(2) ? 'active' : '' }} parent-category" data-all-main="1">
                                    <div class="category-header">
                                        <a href="{{ route('categories.all') }}" class="category-name">
                                            <span>All Categories</span>
                                        </a>
                                        <i class="fas fa-chevron-down toggle-icon toggle-all-main"></i>
                                    </div>
                                </li>
                            </ul>

                            {{-- Main Categories Wrapper --}}
                            <div class="all-main-categories-wrapper">
                                @foreach ($mainCategories as $main)
                                    @php
                                        $isMainActive = false;
                                        $hasActiveDescendant = false;

                                        if (isset($mainCategory) && $mainCategory->id == $main->id) {
                                            $isMainActive = true;
                                        }

                                        if ($main->childrenCategories) {
                                            foreach ($main->childrenCategories as $level1) {
                                                // تشيك Level 1
                                                if ($currentCategoryId == $level1->id) {
                                                    $hasActiveDescendant = true;
                                                    break;
                                                }

                                                // تشيك Level 2
                                                if ($level1->childrenCategories) {
                                                    foreach ($level1->childrenCategories as $level2) {
                                                        if ($currentCategoryId == $level2->id) {
                                                            $hasActiveDescendant = true;
                                                            break 2;
                                                        }

                                                        // تشيك Level 3
                                                        if ($level2->childrenCategories) {
                                                            foreach ($level2->childrenCategories as $level3) {
                                                                if ($currentCategoryId == $level3->id) {
                                                                    $hasActiveDescendant = true;
                                                                    break 3;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $isMainActive = $isMainActive || $hasActiveDescendant;
                                    @endphp

                                    <div class="main-category-item {{ $isMainActive ? 'active' : '' }}"
                                        data-main-id="{{ $main->id }}">

                                        <div class="main-category-header {{ $isMainActive ? 'active' : '' }}">
                                            <a href="{{ route('products.category', $main->slug) }}"
                                                class="main-category-name">
                                                {{ $main->getTranslation('name') }}
                                            </a>
                                            @if ($main->childrenCategories && $main->childrenCategories->count() > 0)
                                                <i class="fas fa-chevron-down main-toggle-icon"></i>
                                            @endif
                                        </div>

                                        @if ($main->childrenCategories && $main->childrenCategories->count() > 0)
                                            <div class="main-category-children">
                                                <span id="title-filter">SUB Categories</span>

                                                <ul class="parent-category-list main-level0" style="margin-left: 16px;">
                                                    @foreach ($main->childrenCategories as $level1Category)
                                                        @php
                                                            $isLevel1Active = $currentCategoryId == $level1Category->id;
                                                            $hasActiveChild = false;

                                                            // تشيك لو Level 1 نفسه active
                                                            if ($isLevel1Active) {
                                                                $hasActiveChild = true;
                                                            }

                                                            // تشيك على Level 2 و 3
                                                            if ($level1Category->childrenCategories) {
                                                                foreach ($level1Category->childrenCategories as $l2) {
                                                                    if ($currentCategoryId == $l2->id) {
                                                                        $hasActiveChild = true;
                                                                        $isLevel1Active = true;
                                                                        break;
                                                                    }
                                                                    if ($l2->childrenCategories) {
                                                                        foreach ($l2->childrenCategories as $l3) {
                                                                            if ($currentCategoryId == $l3->id) {
                                                                                $hasActiveChild = true;
                                                                                $isLevel1Active = true;
                                                                                break 2;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        @endphp

                                                        <li class="parent-category {{ $isLevel1Active ? 'active' : '' }}"
                                                            data-category-id="{{ $level1Category->id }}">

                                                            @if ($level1Category->childrenCategories && $level1Category->childrenCategories->count() > 0)
                                                                <div
                                                                    class="category-header {{ $isLevel1Active ? 'active' : '' }}">
                                                                    <a href="{{ route('categories.level2', $level1Category->id) }}?open={{ $level1Category->id }}"
                                                                        class="category-name">
                                                                        <span>{{ $level1Category->getTranslation('name') }}</span>
                                                                    </a>
                                                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                                                </div>

                                                                <span id="title-filter"
                                                                    class="products-title">Products</span>

                                                                <ul class="sub-categories {{ $hasActiveChild ? 'show' : '' }}"
                                                                    data-parent-id="{{ $level1Category->id }}">
                                                                    @foreach ($level1Category->childrenCategories as $level2Category)
                                                                        @php
                                                                            $isLevel2Active =
                                                                                $currentCategoryId ==
                                                                                $level2Category->id;
                                                                            $hasActiveLevel3 = false;

                                                                            if ($level2Category->childrenCategories) {
                                                                                foreach (
                                                                                    $level2Category->childrenCategories
                                                                                    as $l3
                                                                                ) {
                                                                                    if ($currentCategoryId == $l3->id) {
                                                                                        $hasActiveLevel3 = true;
                                                                                        $isLevel2Active = true;
                                                                                        break;
                                                                                    }
                                                                                }
                                                                            }
                                                                        @endphp

                                                                        <li class="{{ $isLevel2Active ? 'active' : '' }}"
                                                                            data-category-id="{{ $level2Category->id }}">

                                                                            @if ($level2Category->childrenCategories && $level2Category->childrenCategories->count() > 0)
                                                                                <div
                                                                                    class="category-header {{ $isLevel2Active ? 'active' : '' }}">
                                                                                    <a href="{{ route('categories.level2', $level2Category->id) }}?open={{ $level2Category->id }}"
                                                                                        class="category-name">
                                                                                        <span>{{ $level2Category->getTranslation('name') }}</span>
                                                                                    </a>
                                                                                    <i
                                                                                        class="fas fa-chevron-down toggle-icon"></i>
                                                                                </div>

                                                                                <ul class="sub-sub-categories {{ $hasActiveLevel3 ? 'show' : '' }}"
                                                                                    data-parent-id="{{ $level2Category->id }}">
                                                                                    @foreach ($level2Category->childrenCategories as $level3Category)
                                                                                        <li
                                                                                            class="{{ $currentCategoryId == $level3Category->id ? 'active' : '' }}">
                                                                                            <a href="{{ route('categories.level2', $level3Category->id) }}?open={{ $level3Category->id }}"
                                                                                                class="category-link">
                                                                                                <span>{{ $level3Category->getTranslation('name') }}</span>
                                                                                                <i
                                                                                                    class="fas fa-chevron-right"></i>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @else
                                                                                <a href="{{ route('categories.level2', $level2Category->id) }}?open={{ $level2Category->id }}"
                                                                                    class="category-link">
                                                                                    <span>{{ $level2Category->getTranslation('name') }}</span>
                                                                                </a>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <a href="{{ route('categories.level2', $level1Category->id) }}?open={{ $level1Category->id }}"
                                                                    class="category-link">
                                                                    <span>{{ $level1Category->getTranslation('name') }}</span>
                                                                    <i class="fas fa-chevron-right"></i>
                                                                </a>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
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
                                    <button type="button" class="btn-add-inquiry">
                                        <i class="las la-plus"></i> Add to inquiry
                                    </button>
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
                            <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 row-cols-lg-3 g-3" id="products-row">
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

    <!-- Product Details Modal (stay on same page) -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-600">{{ translate('Product details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0" id="productDetailModalBody">
                    <div class="text-center p-4">
                        <div class="spinner-border" role="status" aria-hidden="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        let category_page_first_time = true;
        let brand_page_first_time = true;
        let session_data_first_time = true;

        const enableInlineProductDetails = {{ isset($category_id) ? 'true' : 'false' }};

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

        // Open product details in modal on category listing pages
        $(document).on('click', 'a.js-open-product-details', function(e) {
            if (!enableInlineProductDetails) {
                return true;
            }

            // allow open-in-new-tab behaviors
            if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
                return true;
            }

            const modalUrl = $(this).data('modal-url');
            if (!modalUrl) {
                return true;
            }

            e.preventDefault();

            $('#productDetailModalBody').html(
                '<div class="text-center p-4"><div class="spinner-border" role="status" aria-hidden="true"></div></div>'
            );
            $('#productDetailModal').modal('show');

            $.get(modalUrl, function(html) {
                $('#productDetailModalBody').html(html);

                try {
                    // Re-init dynamic plugins for injected content
                    if (typeof AIZ !== 'undefined' && AIZ.plugins) {
                        AIZ.plugins.slickCarousel();
                        AIZ.plugins.zoom();
                    }
                    if (typeof AIZ !== 'undefined' && AIZ.extra) {
                        AIZ.extra.plusMinus();
                    }

                    // Rebind variant price handler for newly injected form
                    if (typeof getVariantPrice === 'function') {
                        $('#option-choice-form input').off('change').on('change', function() {
                            getVariantPrice();
                        });
                    }
                } catch (err) {
                    console.warn('Product modal init error:', err);
                }
            }).fail(function() {
                $('#productDetailModalBody').html(
                    '<div class="p-4 text-center text-danger">{{ translate('Something went wrong') }}</div>'
                );
            });
        });

        // Cleanup modal content on close (avoid duplicated IDs / handlers)
        $('#productDetailModal').on('hidden.bs.modal', function() {
            $('#productDetailModalBody').html(
                '<div class="text-center p-4"><div class="spinner-border" role="status" aria-hidden="true"></div></div>'
            );
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allToggleIcons = document.querySelectorAll('.toggle-icon');
            const allMainToggle = document.querySelector('.toggle-all-main');
            const allMainWrapper = document.querySelector('.all-main-categories-wrapper');
            const mainCategoryItems = document.querySelectorAll('.main-category-item');

            // ========== MAIN CATEGORIES TOGGLE ==========
            mainCategoryItems.forEach(function(mainItem) {
                const mainHeader = mainItem.querySelector('.main-category-header');
                const mainToggleIcon = mainItem.querySelector('.main-toggle-icon');
                const mainChildren = mainItem.querySelector('.main-category-children');

                if (mainHeader && mainToggleIcon && mainChildren) {
                    mainToggleIcon.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const isVisible = mainChildren.classList.contains('show');

                        if (isVisible) {
                            mainChildren.classList.remove('show');
                            mainToggleIcon.classList.remove('rotated');
                        } else {
                            mainChildren.classList.add('show');
                            mainToggleIcon.classList.add('rotated');
                        }
                    });
                }
            });

            // ========== SUB-CATEGORIES TOGGLE (Level 1/2/3) ==========
            allToggleIcons.forEach(function(toggleIcon) {
                // استثناء الـ main toggles
                if (toggleIcon.classList.contains('toggle-all-main') ||
                    toggleIcon.classList.contains('main-toggle-icon')) {
                    return;
                }

                toggleIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const categoryHeader = this.closest('.category-header');
                    const parentLi = categoryHeader.closest('li');
                    const categoryId = parentLi.getAttribute('data-category-id');

                    // البحث عن الـ Products title
                    const productsTitle = parentLi.querySelector('.products-title');

                    let subCategoriesUl = parentLi.querySelector(
                        `.sub-categories[data-parent-id="${categoryId}"]`
                    );
                    if (!subCategoriesUl) {
                        subCategoriesUl = parentLi.querySelector(
                            `.sub-sub-categories[data-parent-id="${categoryId}"]`
                        );
                    }

                    if (subCategoriesUl) {
                        const isVisible = subCategoriesUl.classList.contains('show');

                        if (isVisible) {
                            subCategoriesUl.classList.remove('show');
                            this.style.transform = 'rotate(0deg)';
                            // إخفاء Products title
                            if (productsTitle) {
                                productsTitle.style.display = 'none';
                            }
                        } else {
                            subCategoriesUl.classList.add('show');
                            this.style.transform = 'rotate(180deg)';
                            // إظهار Products title
                            if (productsTitle) {
                                productsTitle.style.display = 'block';
                            }
                        }
                    }
                });
            });

            // ========== "ALL CATEGORIES" COLLAPSE ==========
            if (allMainToggle && allMainWrapper) {
                allMainToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const isCollapsed = allMainWrapper.classList.contains('collapsed');

                    if (isCollapsed) {
                        allMainWrapper.classList.remove('collapsed');
                        this.style.transform = 'rotate(180deg)';
                    } else {
                        allMainWrapper.classList.add('collapsed');
                        this.style.transform = 'rotate(0deg)';
                    }
                });
            }

            // ========== AUTO-EXPAND ACTIVE MAIN CATEGORIES ==========
            mainCategoryItems.forEach(function(mainItem) {
                if (mainItem.classList.contains('active')) {
                    const mainChildren = mainItem.querySelector('.main-category-children');
                    const mainToggleIcon = mainItem.querySelector('.main-toggle-icon');

                    if (mainChildren) {
                        mainChildren.classList.add('show');
                    }
                    if (mainToggleIcon) {
                        mainToggleIcon.classList.add('rotated');
                    }
                }
            });

            // ========== AUTO-EXPAND ACTIVE SUB CATEGORIES ==========
            const activeCategories = document.querySelectorAll('.category-sidebar li.active');
            activeCategories.forEach(function(activeLi) {
                let parentUl = activeLi.closest('.sub-categories, .sub-sub-categories');

                while (parentUl) {
                    parentUl.classList.add('show');

                    const parentLi = parentUl.closest('li');
                    const parentToggle = parentLi?.querySelector(
                        '.category-header > .toggle-icon:not(.toggle-all-main):not(.main-toggle-icon)');

                    if (parentToggle) {
                        parentToggle.style.transform = 'rotate(180deg)';
                    }

                    // إظهار Products title للـ active categories
                    const productsTitle = parentLi?.querySelector('.products-title');
                    if (productsTitle) {
                        productsTitle.style.display = 'block';
                    }

                    parentUl = parentLi?.closest('.sub-categories, .sub-sub-categories');
                }
            });

            // ========== إخفاء Products title في البداية ==========
            const allProductsTitles = document.querySelectorAll('.products-title');
            allProductsTitles.forEach(function(title) {
                const parentLi = title.closest('li');
                const subCategories = parentLi.querySelector('.sub-categories');

                // إخفاء إذا كانت الـ sub-categories مش ظاهرة
                if (subCategories && !subCategories.classList.contains('show')) {
                    title.style.display = 'none';
                }
            });
        });
    </script>



@endsection
