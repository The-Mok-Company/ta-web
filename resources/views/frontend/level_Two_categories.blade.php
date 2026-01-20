@extends('frontend.layouts.app')

@php
    $mainCategories = App\Models\Category::where('level', 0)
        ->with([
            'childrenCategories' => function ($query) {
                $query
                    ->with([
                        'childrenCategories' => function ($q) {
                            $q->withCount('products');
                        },
                    ])
                    ->withCount('products');
            },
        ])
        ->withCount('products')
        ->orderBy('order_level', 'desc')
        ->get();

    $currentCategoryId = request()->segment(2);

    // Active trail + active main category (level 0) for sidebar highlighting
    $activeTrailIds = [];
    $activeMainCategoryId = null;
    if (!empty($currentCategoryId)) {
        $cat = App\Models\Category::find($currentCategoryId);
        while ($cat) {
            $activeTrailIds[] = (int) $cat->id;
            if ((int) $cat->level === 0) {
                $activeMainCategoryId = (int) $cat->id;
            }
            $cat = $cat->parentCategory;
        }
    }
@endphp

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
        padding: 30px 20px;
        border-radius: 16px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, .08);
        position: sticky;
        top: 20px;
        margin-bottom: 30px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
    }

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

    .category-sidebar ul li a.category-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        color: inherit;
        text-decoration: none;
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

    .category-sidebar .toggle-icon {
        font-size: 10px;
        opacity: 0.5;
        transition: all .3s ease;
        cursor: pointer;
        padding: 4px 8px;
        margin-left: 8px;
        flex-shrink: 0;
    }

    .category-sidebar ul li:hover:not(.active) {
        background: #f8f9fa;
    }

    .category-sidebar ul li:hover:not(.active) a.category-link,
    .category-sidebar ul li:hover:not(.active) .category-header {
        color: #333;
    }

    .category-sidebar ul li.active {
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
        color: #fff;
        box-shadow: 0 3px 10px rgba(74, 144, 226, 0.25);
    }

    .category-sidebar ul li.active a.category-link {
        color: #fff;
    }

    .category-sidebar ul li.active .category-header {
        color: #fff;
    }

    .category-sidebar ul li.active .category-name {
        color: #fff;
    }

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
    }

    /* ========================
       MAIN CATEGORY SECTIONS (CLEARER STRUCTURE)
    ======================== */
    .sidebar-section {
        margin: 10px 0 14px;
    }

    .sidebar-section-header {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8f9fa;
        border: 1px solid #eef0f3;
        border-radius: 14px;
        padding: 12px 14px;
        cursor: pointer;
        transition: all .2s ease;
        color: #111827;
        font-weight: 700;
        font-size: 13px;
        text-align: left;
    }

    .sidebar-section-header:hover {
        background: #f3f4f6;
    }

    .sidebar-section.is-main-active .sidebar-section-header {
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 3px 10px rgba(74, 144, 226, 0.25);
    }

    .sidebar-section-toggle {
        font-size: 10px;
        opacity: 0.8;
        transition: transform .2s ease;
        margin-left: 10px;
        flex-shrink: 0;
    }

    .sidebar-section.is-open .sidebar-section-toggle {
        transform: rotate(180deg);
    }

    .sidebar-section-body {
        display: none;
        padding-top: 10px;
    }

    .sidebar-section.is-open .sidebar-section-body {
        display: block;
    }

    /* Subtle marker for the active trail (parents) */
    .category-sidebar li.is-active-trail:not(.active) {
        background: #f8f9fa;
        border: 1px solid #eef0f3;
    }

    .category-sidebar li.is-active-trail:not(.active) a,
    .category-sidebar li.is-active-trail:not(.active) .category-header {
        color: #111827;
        font-weight: 600;
    }

    /* Sub Categories Styling - Level 2 */
    .sub-categories {
        margin: 8px 0 0 0 !important;
        padding-left: 16px !important;
        list-style: none;
        display: none;
    }

    .sub-categories.show {
        display: block;
        padding-top: 6px;
    }

    .sub-categories li {
        font-size: 13px;
        margin-bottom: 5px;
    }

    .sub-categories li a.category-link {
        padding: 10px 14px;
        border-radius: 10px;
    }

    .sub-categories li .category-header {
        padding: 10px 14px;
        font-size: 13px;
        border-radius: 10px;
    }

    .sub-categories li i.fa-chevron-right,
    .sub-categories li i.fa-chevron-down {
        font-size: 8px;
    }

    /* Sub Sub Categories Styling - Level 3 */
    .sub-sub-categories {
        margin: 8px 0 0 0 !important;
        padding-left: 16px !important;
        list-style: none;
        display: none;
    }

    .sub-sub-categories.show {
        display: block;
        padding-top: 6px;
    }

    .sub-sub-categories li {
        font-size: 12px;
        margin-bottom: 4px;
    }

    .sub-sub-categories li a.category-link {
        padding: 8px 12px;
        border-radius: 8px;
    }

    .sub-sub-categories li i {
        font-size: 7px;
    }

    /* Product Count */
    .product-count {
        font-size: 11px;
        opacity: 0.65;
        margin-left: 6px;
        font-weight: 400;
        flex-shrink: 0;
    }

    .sub-categories .product-count {
        font-size: 10px;
    }

    .sub-sub-categories .product-count {
        font-size: 9px;
    }

    /* Parent category with children */
    .parent-category-list {
        margin-bottom: 0 !important;
    }

    /* Spacing between category items */
    .parent-category-list>li {
        margin-bottom: 8px;
    }

    .parent-category-list>li:last-child {
        margin-bottom: 0;
    }

    /* ========================
       CATEGORY CARD - NEW DESIGN
    ======================== */
    .category-card {
        position: relative;
        height: 300px;
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        margin: 0;
        transition: .4s;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .1);
        background: #fff;
    }

    /* Ensure consistent vertical spacing between tile rows */
    #level2-categories-grid .category-card {
        margin-bottom: 24px;
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
        bottom: 30px; //important to change to be like the figma
        left: 16px;
        right: 16px;
        z-index: 2;
        text-align: left;
    }

    .category-card .category-title h5 {
        margin: 0;
        font-weight: 700;
        font-size: 28px;
        color: #fff;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
        letter-spacing: 0;
    }

    /* Small white "slash" accent under headline (---) */
    .category-card .category-title-accent {
        width: 44px;
        height: 3px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 3px;
        margin-top: 8px;
        margin-bottom: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.35);
    }

    /* Subcategory list under headline (Figma-style) */
    .category-card .category-sublist {
        margin-top: 0;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        column-gap: 16px;
        row-gap: 6px;
        max-width: 320px;
    }

    .category-card .category-subitem {
        font-size: 12px;
        line-height: 1.2;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.65);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
            height: 340px;
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
            height: 300px;
        }

        .category-sidebar {
            position: relative;
            top: 0;
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
            padding: 25px 18px;
            border-radius: 14px;
        }

        .category-sidebar ul li a.category-link {
            padding: 11px 14px;
            font-size: 13px;
        }

        .category-sidebar .category-header {
            padding: 11px 14px;
            font-size: 13px;
        }

        .sub-categories li a.category-link {
            padding: 9px 12px;
            font-size: 12px;
        }

        .sub-categories li .category-header {
            padding: 9px 12px;
            font-size: 12px;
        }

        .category-card {
            height: 280px;
        }

        .category-card .category-title h5 {
            font-size: 22px;
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
            padding: 22px 16px;
            margin-bottom: 20px;
            border-radius: 12px;
        }

        .category-sidebar ul li a.category-link {
            padding: 10px 12px;
            font-size: 12px;
            border-radius: 10px;
        }

        .category-sidebar .category-header {
            padding: 10px 12px;
            font-size: 12px;
        }

        .sub-categories li a.category-link {
            padding: 8px 10px;
            font-size: 11px;
        }

        .sub-categories li .category-header {
            padding: 8px 10px;
            font-size: 11px;
        }

        .category-card {
            height: 260px;
        }

        .category-card .category-title h5 {
            font-size: 20px;
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
            height: 240px;
        }

        .category-card .category-title h5 {
            font-size: 18px;
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

    /* ========================
       INLINE PRODUCTS (UI IMPROVEMENTS)
    ======================== */
    #products-row .col.border-right.border-bottom {
        border: none !important;
    }

    #products-row .aiz-card-box {
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    #products-row .aiz-card-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }

    #products-row .product-content {
        padding-bottom: 14px !important;
    }

    #products-row .product-category {
        color: #6b7280;
        font-size: 12px;
        margin-bottom: 10px !important;
    }

    /* Make inquiry button comfortable and full-width */
    #products-row .btn-inquiry {
        width: 100%;
        min-width: 170px;
        height: 40px !important;
        border-radius: 12px;
        font-size: 14px !important;
        font-weight: 600;
        padding: 10px 12px !important;
        white-space: nowrap;
    }

    #products-row .product-actions,
    #products-row .product-action {
        gap: 10px !important;
    }

    /* Sidebar products list */
    .sidebar-products {
        margin-top: 10px;
    }

    .sidebar-products ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-products li a {
        display: block;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 13px;
        text-decoration: none;
        color: inherit;
        transition: background .2s ease, color .2s ease;
    }

    .sidebar-products li a:hover {
        background: #f8f9fa;
        color: #111827;
    }

    .sidebar-products li.active a {
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
        color: #fff;
        box-shadow: 0 3px 10px rgba(74, 144, 226, 0.25);
    }
</style>

@section('content')
    <div class="category-page">
        {{-- Hero Banner with Category Image --}}
        <div class="category-hero"
            style="background-image: url('{{ uploaded_asset($levelTwoCategories->first()->banner ?? '') }}');">
            <div class="container">
                <a href="javascript:history.back()" class="back-arrow">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>
                    <span class="explore">Explore</span>

                    {{ $levelTwoCategories->first()->name ?? 'Categories' }}
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
                                <li class="{{ !request()->segment(2) ? 'active' : '' }}">
                                    <a href="{{ route('categories.all') }}" class="category-link">
                                        <span>All Categories</span>
                                    </a>
                                </li>
                            </ul>

                            {{-- Loop through Main Categories (Level 0) --}}
                            @foreach ($mainCategories as $mainCategory)
                                @php
                                    $isMainActive = !empty($activeMainCategoryId) && (int) $mainCategory->id === (int) $activeMainCategoryId;
                                @endphp

                                <div class="sidebar-section {{ $isMainActive ? 'is-main-active is-open' : '' }}"
                                    data-main-category-id="{{ $mainCategory->id }}">
                                    <button type="button" class="sidebar-section-header">
                                        <span>{{ $mainCategory->getTranslation('name') }}</span>
                                        <i class="fas fa-chevron-down sidebar-section-toggle"></i>
                                    </button>

                                    <div class="sidebar-section-body">
                                        <ul class="parent-category-list">
                                            @if ($mainCategory->childrenCategories && $mainCategory->childrenCategories->count() > 0)
                                                {{-- Main Category with Sub-categories (Level 1) --}}
                                                @foreach ($mainCategory->childrenCategories as $level1Category)
                                                    @php
                                                        $isTrail = in_array((int) $level1Category->id, $activeTrailIds ?? [], true);
                                                    @endphp
                                                    <li class="parent-category {{ $currentCategoryId == $level1Category->id ? 'active' : '' }} {{ $isTrail ? 'is-active-trail' : '' }}"
                                                        data-category-id="{{ $level1Category->id }}">

                                                        @if ($level1Category->childrenCategories && $level1Category->childrenCategories->count() > 0)
                                                            {{-- Level 1 has children (Level 2) --}}
                                                            <div class="category-header">
                                                                <a href="{{ route('categories.level2', $level1Category->id) }}"
                                                                    class="category-name">
                                                                    <span>{{ $level1Category->getTranslation('name') }}</span>
                                                                </a>
                                                                <i class="fas fa-chevron-down toggle-icon"></i>
                                                            </div>

                                                            {{-- Sub Categories (Level 2) --}}
                                                            <ul class="sub-categories" data-parent-id="{{ $level1Category->id }}">
                                                                @foreach ($level1Category->childrenCategories as $level2Category)
                                                                    @php
                                                                        $isTrail2 = in_array((int) $level2Category->id, $activeTrailIds ?? [], true);
                                                                    @endphp
                                                                    <li class="{{ $currentCategoryId == $level2Category->id ? 'active' : '' }} {{ $isTrail2 ? 'is-active-trail' : '' }}"
                                                                        data-category-id="{{ $level2Category->id }}">

                                                                        @if ($level2Category->childrenCategories && $level2Category->childrenCategories->count() > 0)
                                                                            {{-- Level 2 has children (Level 3) --}}
                                                                            <div class="category-header">
                                                                                <a href="{{ route('products.level2', $level2Category->id) }}"
                                                                                    class="category-name">
                                                                                    <span>{{ $level2Category->getTranslation('name') }}</span>
                                                                                </a>
                                                                                <i class="fas fa-chevron-down toggle-icon"></i>
                                                                            </div>

                                                                            {{-- Sub Sub Categories (Level 3) --}}
                                                                            <ul class="sub-sub-categories" data-parent-id="{{ $level2Category->id }}">
                                                                                @foreach ($level2Category->childrenCategories as $level3Category)
                                                                                    @php
                                                                                        $isTrail3 = in_array((int) $level3Category->id, $activeTrailIds ?? [], true);
                                                                                    @endphp
                                                                                    <li class="{{ $currentCategoryId == $level3Category->id ? 'active' : '' }} {{ $isTrail3 ? 'is-active-trail' : '' }}">
                                                                                        <a href="{{ route('products.level2', $level3Category->id) }}"
                                                                                            class="category-link">
                                                                                            <span>{{ $level3Category->getTranslation('name') }}</span>
                                                                                            <i class="fas fa-chevron-right"></i>
                                                                                        </a>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @else
                                                                            {{-- Level 2 without children --}}
                                                                            <a href="{{ route('products.level2', $level2Category->id) }}"
                                                                                class="category-link">
                                                                                <span>{{ $level2Category->getTranslation('name') }}</span>
                                                                                <i class="fas fa-chevron-right"></i>
                                                                            </a>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            {{-- Level 1 without children --}}
                                                            <a href="{{ route('products.level2', $level1Category->id) }}"
                                                                class="category-link">
                                                                <span>{{ $level1Category->getTranslation('name') }}</span>
                                                                <i class="fas fa-chevron-right"></i>
                                                            </a>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="{{ $currentCategoryId == $mainCategory->id ? 'active' : '' }}">
                                                    <a href="#" class="category-link">
                                                        <span class="product-count">There are no sub categories available.</span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Products (populated when a subcategory is opened) --}}
                            <div class="sidebar-products d-none" id="sidebar-products-section">
                                <h6>PRODUCTS</h6>
                                <ul id="sidebar-products-list"></ul>
                            </div>
                        </div>
                    </div>

                    {{-- Cards Section --}}
                    <div class="col-lg-9">
                        {{-- Products view (loaded inline) --}}
                        <div id="inline-products-wrapper" class="d-none">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h4 class="mb-0 fw-700" id="inline-products-title"></h4>
                                <button type="button" class="btn btn-sm btn-light" id="inline-products-back">
                                    <i class="las la-arrow-left"></i> {{ translate('Back') }}
                                </button>
                            </div>

                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-3" id="products-row"></div>
                            <div class="pagination-wrapper mt-3" id="pagination"></div>
                        </div>

                        {{-- Categories grid (default) --}}
                        <div class="row g-4" id="level2-categories-grid">
                            @foreach ($levelTwoCategories as $category)
                                <div class="col-lg-6 col-md-6">
                                    <a href="{{ route('products.level2', $category->id) }}" style="text-decoration: none;"
                                        class="js-open-category-products"
                                        data-category-id="{{ $category->id }}"
                                        data-category-name="{{ $category->getTranslation('name') }}">
                                        <div class="category-card">
                                            <img src="{{ uploaded_asset($category->banner) }}"
                                                alt="{{ $category->getTranslation('name') }}">

                                            {{-- Cart Icon - Top Left --}}
                                            <div class="cart-icon">
                                                <i class="fas fa-shopping-basket"></i>
                                            </div>

                                            {{-- Category Title - Bottom Left --}}
                                            <div class="category-title">
                                                <h5>{{ $category->getTranslation('name') }}</h5>
                                                <div class="category-title-accent"></div>
                                                <div class="category-sublist">
                                                    @if ($category->childrenCategories && $category->childrenCategories->count() > 0)
                                                        @foreach ($category->childrenCategories->take(5) as $subCat)
                                                            <span class="category-subitem">{{ $subCat->getTranslation('name') }}</span>
                                                        @endforeach
                                                    @elseif ($category->products && $category->products->count() > 0)
                                                        @foreach ($category->products->take(5) as $p)
                                                            <span class="category-subitem">{{ $p->name }}</span>
                                                        @endforeach
                                                    @endif
                                                </div>
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

    {{-- JavaScript for Toggle Functionality --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle all toggle icons
            const allToggleIcons = document.querySelectorAll('.toggle-icon');

            allToggleIcons.forEach(function(toggleIcon) {
                toggleIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const categoryHeader = this.closest('.category-header');
                    const parentLi = categoryHeader.closest('li');
                    const categoryId = parentLi.getAttribute('data-category-id');

                    // Find the sub-categories ul
                    let subCategoriesUl = parentLi.querySelector(
                        `.sub-categories[data-parent-id="${categoryId}"]`);
                    if (!subCategoriesUl) {
                        subCategoriesUl = parentLi.querySelector(
                            `.sub-sub-categories[data-parent-id="${categoryId}"]`);
                    }

                    if (subCategoriesUl) {
                        const isVisible = subCategoriesUl.classList.contains('show');

                        if (isVisible) {
                            subCategoriesUl.classList.remove('show');
                            this.style.transform = 'rotate(0deg)';
                        } else {
                            subCategoriesUl.classList.add('show');
                            this.style.transform = 'rotate(180deg)';
                        }
                    }
                });
            });

            // Auto-expand active category's parents
            const activeCategories = document.querySelectorAll('.category-sidebar li.active');
            activeCategories.forEach(function(activeLi) {
                // Find parent ul and show it
                let parentUl = activeLi.closest('.sub-categories, .sub-sub-categories');
                while (parentUl) {
                    parentUl.classList.add('show');

                    // Rotate the toggle icon
                    const parentLi = parentUl.previousElementSibling?.querySelector('.toggle-icon') ||
                        parentUl.closest('li')?.querySelector('.toggle-icon');
                    if (parentLi) {
                        parentLi.style.transform = 'rotate(180deg)';
                    }

                    // Move up to next parent
                    parentUl = parentUl.closest('li')?.closest('.sub-categories, .sub-sub-categories');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let activeCategoryId = null;

            function renderSidebarProducts(productList, categoryId) {
                const $section = $('#sidebar-products-section');
                const $list = $('#sidebar-products-list');

                $list.html('');

                if (!productList || !Array.isArray(productList) || productList.length === 0) {
                    $section.addClass('d-none');
                    return;
                }

                productList.forEach(function(p) {
                    const name = p.name || '';
                    const slug = p.slug || '';
                    if (!slug) return;

                    const modalUrl = `{{ route('product.modal', '___SLUG___') }}`.replace('___SLUG___', slug) +
                        (categoryId ? `?category_id=${categoryId}` : '');

                    $list.append(
                        `<li data-product-slug="${slug}">
                            <a href="/product/${slug}" class="js-open-product-details" data-modal-url="${modalUrl}" data-product-slug="${slug}">
                                ${$('<div>').text(name).html()}
                            </a>
                        </li>`
                    );
                });

                $section.removeClass('d-none');
            }

            function setActiveCategory(categoryId) {
                activeCategoryId = categoryId;

                $('.category-sidebar li').removeClass('active');
                $(`.category-sidebar li[data-category-id="${categoryId}"]`).addClass('active');

                // Open and highlight the correct main section
                const $activeLi = $(`.category-sidebar li[data-category-id="${categoryId}"]`);
                const $section = $activeLi.closest('.sidebar-section');
                if ($section.length) {
                    $('.sidebar-section').removeClass('is-open is-main-active');
                    $section.addClass('is-open is-main-active');
                }

                // Expand parents for the active li
                const activeLi = document.querySelector(`.category-sidebar li[data-category-id="${categoryId}"]`);
                if (activeLi) {
                    let parentUl = activeLi.closest('.sub-categories, .sub-sub-categories');
                    while (parentUl) {
                        parentUl.classList.add('show');
                        const parentToggle = parentUl.previousElementSibling?.querySelector('.toggle-icon') ||
                            parentUl.closest('li')?.querySelector('.toggle-icon');
                        if (parentToggle) {
                            parentToggle.style.transform = 'rotate(180deg)';
                        }
                        parentUl = parentUl.closest('li')?.closest('.sub-categories, .sub-sub-categories');
                    }
                }
            }

            // Toggle main sections (accordion)
            $(document).on('click', '.sidebar-section-header', function() {
                const $section = $(this).closest('.sidebar-section');
                if (!$section.length) return;

                // If clicking the already-open section, collapse it; otherwise open it and collapse others
                const isOpen = $section.hasClass('is-open');
                $('.sidebar-section').removeClass('is-open');
                if (!isOpen) {
                    $section.addClass('is-open');
                }
            });

            function setActiveProduct(productSlug) {
                if (!productSlug) return;

                $('#sidebar-products-list li').removeClass('active');
                const $active = $(`#sidebar-products-list li[data-product-slug="${productSlug}"]`);
                $active.addClass('active');

                if ($active.length) {
                    $active[0].scrollIntoView({
                        block: 'nearest'
                    });
                }
            }

            function openCategoryProducts(categoryId, categoryName, page = 1) {
                if (!categoryId) return;

                $('#inline-products-title').text(categoryName || '');
                $('#products-row').html(
                    '<div class="col-12 text-center p-4"><div class="spinner-border" role="status" aria-hidden="true"></div></div>'
                );
                $('#pagination').html('');

                $('#level2-categories-grid').addClass('d-none');
                $('#inline-products-wrapper').removeClass('d-none');

                setActiveCategory(categoryId);

                // reset products list UI while loading
                renderSidebarProducts([], categoryId);

                $.ajax({
                    url: "{{ route('suggestion.search2') }}",
                    type: 'get',
                    data: {
                        'categories[]': categoryId,
                        page: page,
                        include_product_list: 1
                    },
                    success: function(response) {
                        $('#products-row').html(response.product_html);
                        $('#pagination').html(response.pagination_html);

                        // Populate sidebar products list + keep it in sync
                        renderSidebarProducts(response.product_list, categoryId);

                        try {
                            if (typeof AIZ !== 'undefined' && AIZ.plugins) {
                                AIZ.plugins.slickCarousel();
                            }
                        } catch (e) {}
                    },
                    error: function() {
                        $('#products-row').html(
                            '<div class="col-12 text-center p-4 text-danger">{{ translate('Something went wrong') }}</div>'
                        );
                    }
                });
            }

            // Load products into the same page (no redirect)
            $(document).on('click', 'a.js-open-category-products', function(e) {
                // allow open-in-new-tab behaviors
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
                    return true;
                }

                e.preventDefault();

                const categoryId = $(this).data('category-id');
                const categoryName = $(this).data('category-name') || $(this).find('span').first().text() || '';

                if (!categoryId) {
                    return true;
                }

                openCategoryProducts(categoryId, categoryName, 1);
            });

            // Sidebar click: intercept /products-level2/{id} to keep same page
            $(document).on('click', '.category-sidebar a.category-link, .category-sidebar a.category-name', function(e) {
                // allow open-in-new-tab behaviors
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
                    return true;
                }

                const href = $(this).attr('href') || '';
                if (!href.includes('/products-level2/')) {
                    return true; // let category-level2 links navigate normally
                }

                const $li = $(this).closest('li[data-category-id]');
                const categoryId = $li.data('category-id');
                const categoryName = $(this).find('span').first().text() || $(this).text().trim();

                if (!categoryId) return true;

                e.preventDefault();
                openCategoryProducts(categoryId, categoryName, 1);
            });

            // Pagination inside inline products
            $(document).on('click', '#pagination .page-btn', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (!page || !activeCategoryId) return;
                const categoryName = $('#inline-products-title').text() || '';
                openCategoryProducts(activeCategoryId, categoryName, page);
            });

            $('#inline-products-back').on('click', function() {
                $('#inline-products-wrapper').addClass('d-none');
                $('#level2-categories-grid').removeClass('d-none');
                $('#products-row').html('');
                $('#pagination').html('');
                $('#sidebar-products-section').addClass('d-none');
                $('#sidebar-products-list').html('');
            });

            // Open product details in modal (stay on same page)
            $(document).on('click', 'a.js-open-product-details', function(e) {
                // allow open-in-new-tab behaviors
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
                    return true;
                }

                const modalUrl = $(this).data('modal-url');
                if (!modalUrl) {
                    return true;
                }

                e.preventDefault();

                // Highlight the clicked product in sidebar list
                setActiveProduct($(this).data('product-slug'));

                $('#productDetailModalBody').html(
                    '<div class="text-center p-4"><div class="spinner-border" role="status" aria-hidden="true"></div></div>'
                );
                $('#productDetailModal').modal('show');

                $.get(modalUrl, function(html) {
                    $('#productDetailModalBody').html(html);

                    try {
                        if (typeof AIZ !== 'undefined' && AIZ.plugins) {
                            AIZ.plugins.slickCarousel();
                            AIZ.plugins.zoom();
                        }
                        if (typeof AIZ !== 'undefined' && AIZ.extra) {
                            AIZ.extra.plusMinus();
                        }

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

            $('#productDetailModal').on('hidden.bs.modal', function() {
                $('#productDetailModalBody').html(
                    '<div class="text-center p-4"><div class="spinner-border" role="status" aria-hidden="true"></div></div>'
                );
            });
        });
    </script>
@endsection
