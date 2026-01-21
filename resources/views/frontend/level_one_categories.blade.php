@extends('frontend.layouts.app')

@php
    use App\Models\Category;

    $mainCategories = Category::where('level', 0)
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
        ->with('products')
        ->orderBy('order_level', 'desc')
        ->get();

    $currentCategoryId = request()->segment(2);

    // Hero category هو الـ main category المختار
    $heroCategory = $mainCategory ?? null;
@endphp

<style>
    .add-inquiry-btn .icon-check{ display:none; }
.add-inquiry-btn.added .icon-plus{ display:none; }
.add-inquiry-btn.added .icon-check{
    display:block;
    color:#fff;
    font-size:18px;
    font-weight:700;
}
.add-inquiry-btn.added .icon-check::before{ content:"✓"; }

    /* ===== Add to Inquiry button on cards ===== */
.add-inquiry-wrap{
    position:absolute;
    top: 15px;
    right: 15px;
    z-index: 6; /* أعلى من overlay */
}

.add-inquiry-btn{
    width: 40px;
    height: 40px;
    background: #0891B2;
    border-radius: 50%;
    border: none;
    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    box-shadow: 0 2px 8px rgba(0,0,0,.25);
    transition: transform .18s cubic-bezier(.2,.8,.2,1),
                box-shadow .18s ease,
                background .18s ease;
}

.add-inquiry-btn .icon{
    color:#fff;
    font-size:22px;
    font-weight:700;
    line-height:1;
    pointer-events:none;
}

/* يبان بوضوح انه button */
.add-inquiry-btn:hover{
    background:#0E7490;
    transform: scale(1.08);
    box-shadow:
        0 8px 22px rgba(8,145,178,.45),
        0 0 0 4px rgba(8,145,178,.25);
}

.add-inquiry-btn:active{
    transform: scale(0.95);
}

/* Added state */
.add-inquiry-btn.added{
    background:#16a34a;
    cursor: default;
    box-shadow: 0 2px 8px rgba(0,0,0,.25);
}

.add-inquiry-btn.added .icon{
    font-size:18px;
}

.add-inquiry-btn.added .icon::before{
    content:"✓";
}

/* لو اتعمل disable */
.add-inquiry-btn:disabled{
    opacity:.9;
    cursor: not-allowed;
}

    .category-page {
        background: #f8f9fa;
        min-height: 100vh;
    }

    /* BACK ARROW */
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

    /* HERO BANNER */
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
        inset: 0;
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

    /* CONTENT */
    .category-content {
        background: #fff;
        padding: 40px 0 80px;
    }

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

    /* Active section header styling */
    .category-sidebar h6.active-section {
        color: #4a90e2;
        font-weight: 700;
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
        margin-bottom: 10px;
        color: #555;
        font-weight: 500;
        background: transparent;
        position: relative;
        z-index: 1;
    }

    .category-sidebar ul li a.category-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
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
        padding: 14px 18px;
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
        padding: 6px 10px;
        margin-left: 8px;
        flex-shrink: 0;
    }

    /* Extra spacing for the expanded "main-level0" list (the one in your screenshot) */
    .main-category-children {
        margin-top: 12px;
        padding: 6px 6px 2px;
    }

    .main-category-children .parent-category-list.main-level0 {
        margin-top: 10px;
        padding-left: 0;
    }

    .main-category-children .parent-category-list.main-level0 > li {
        margin-bottom: 14px;
    }

    .main-category-children .parent-category-list.main-level0 > li:last-child {
        margin-bottom: 0;
    }

    /* Roomier rows specifically for main-level0 items */
    .main-category-children .parent-category-list.main-level0 > li > .category-header,
    .main-category-children .parent-category-list.main-level0 > li > a.category-link {
        padding: 16px 18px;
    }

    /* Give nested lists more indentation + breathing room */
    .main-category-children .parent-category-list.main-level0 .sub-categories,
    .main-category-children .parent-category-list.main-level0 .sub-sub-categories {
        margin-top: 10px;
        padding-left: 18px;
    }

    /* HOVER / ACTIVE STATES */
    .category-sidebar ul li:hover:not(.active) {
        background: #f8f9fa !important;
    }

    .category-sidebar ul li:hover:not(.active) a.category-link,
    .category-sidebar ul li:hover:not(.active) .category-header {
        color: #333;
    }

    .category-sidebar ul li.active {
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%) !important;
        color: #fff;
        box-shadow: 0 3px 10px rgba(74, 144, 226, 0.25);
    }

    .category-sidebar ul li.active a.category-link,
    .category-sidebar ul li.active .category-header,
    .category-sidebar ul li.active .category-name {
        color: #fff;
    }

    .category-sidebar ul li.active:hover {
        filter: brightness(1.05);
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
        color: #fff;
    }

    /* ========================
       SIDEBAR ITEM SPACING + OUTLINE
    ======================== */
    .category-sidebar ul li {
        margin-bottom: 10px;
        border-radius: 12px;
    }

    .category-sidebar ul li:last-child {
        margin-bottom: 0;
    }

    .category-sidebar ul li a.category-link,
    .category-sidebar ul li .category-header,
    .main-category-item .main-category-header {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
    }

    .category-sidebar ul li:hover:not(.active) a.category-link,
    .category-sidebar ul li:hover:not(.active) .category-header {
        border-color: #cbd5e1;
        background: #f8fafc;
    }

    .category-sidebar ul li.active a.category-link,
    .category-sidebar ul li.active .category-header,
    .category-sidebar ul li.active .category-name {
        border-color: rgba(255, 255, 255, 0.35);
        background: transparent;
    }

    /* SUBMENUS VISIBILITY */
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

    /* ALL MAIN CATEGORIES WRAPPER */
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

    .main-category-item .main-category-header:hover {
        background: #f8f9fa;
        color: #333;
    }

    .main-category-item.active .main-category-header {
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
        color: #fff;
        box-shadow: 0 3px 10px rgba(74, 144, 226, 0.25);
    }

    .main-category-item.active .main-category-header:hover {
        filter: brightness(1.05);
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

    .main-category-item.active .main-toggle-icon {
        opacity: 1;
        color: #fff;
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
        margin-left: 16px;
    }

    /* CATEGORY CARD */
    .category-card {
        position: relative;
        height: 280px;
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        margin: 0;
        transition: .4s;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .1);
        background: #fff;
    }

    /* Real vertical spacing between rows (Bootstrap gx/gy not available here) */
    .category-cards-grid > [class*="col-"] {
        margin-bottom: 28px;
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
    }

    /* Small child-categories line under title */
    .category-card .category-subline {
        margin-top: 10px;
        font-size: 12px;
        line-height: 1.35;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.65);
        max-width: 320px;
        white-space: normal;       /* allow wrapping */
        overflow: visible;
        text-overflow: clip;
        word-break: break-word;
    }

    /* Make the subline clickable (each segment as a link) */
    .category-card .category-subline a.category-subline-link {
        color: inherit;
        text-decoration: none;
        cursor: pointer;
    }
    .category-card .category-subline a.category-subline-link:hover {
        text-decoration: underline;
        color: #fff;
    }

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

    .category-card::before {
        content: "";
        position: absolute;
        inset: 0;
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

    /* RESPONSIVE */

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

    .sub-categories-bottom a.sub-cat-item {
        text-decoration: none;
        color: #fff;
        cursor: pointer;
    }

    .sub-categories-bottom a.sub-cat-item:hover {
        opacity: 1;
        text-decoration: underline;
    }

    .category-card-wrapper {
        position: relative;
    }

    .category-main-link {
        display: block;
    }

    .main-category-link {
        color: inherit;
        text-decoration: none;
        display: block;
    }

    .main-category-link:hover {
        color: #28a745;
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

@section('content')
    <div class="category-page">

        {{-- Hero Banner --}}
        <div class="category-hero" style="background-image: url('{{ uploaded_asset($heroCategory?->banner ?? null) }}');">
            <div class="container">
                <a href="javascript:history.back()" class="back-arrow">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>
                    <span class="explore">Explore</span>
                    {{ $heroCategory?->getTranslation('name') ?? 'Categories' }}
                </h1>
            </div>
        </div>

        {{-- Content --}}
        <div class="category-content">
            <div class="container">
                <div class="row">

                    {{-- Sidebar --}}
                    <div class="col-lg-3 mb-4">
                        <div class="category-sidebar">
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

                            {{-- كل الـ Main Level 0 تحت All Categories --}}
                            <div class="all-main-categories-wrapper">
                                @foreach ($mainCategories as $main)
                                    @php
                                        $isMainActive = false;
                                        if (isset($mainCategory) && $mainCategory->id == $main->id) {
                                            $isMainActive = true;
                                        } elseif (isset($levelOneCategories)) {
                                            foreach ($levelOneCategories as $l1) {
                                                if ($l1->parent_id == $main->id) {
                                                    $isMainActive = true;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp

                                    {{-- Main Category Item with Header --}}
                                    <div class="main-category-item {{ $isMainActive ? 'active' : '' }}"
                                        data-main-id="{{ $main->id }}">

                                        <div class="main-category-header">
                                            <a href="{{ route('products.category', $main->slug) }}"
                                                class="main-category-name">
                                                {{ $main->getTranslation('name') }}
                                            </a>
                                            @if ($main->childrenCategories && $main->childrenCategories->count() > 0)
                                                <i class="fas fa-chevron-down main-toggle-icon"></i>
                                            @endif
                                        </div>

                                        {{-- Main Category Children --}}
                                        @if ($main->childrenCategories && $main->childrenCategories->count() > 0)
                                            <div class="main-category-children">
                                                <ul class="parent-category-list main-level0">
                                                    @foreach ($main->childrenCategories as $level1Category)
                                                        @php
                                                            $isLevel1Active = $currentCategoryId == $level1Category->id;
                                                            $hasActiveChild = false;

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
                                                                <div class="category-header">
                                                                    <a href="{{ route('categories.level2', $level1Category->id) }}"
                                                                        class="category-name">
                                                                        <span>{{ $level1Category->getTranslation('name') }}</span>
                                                                    </a>
                                                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                                                </div>

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
                                                                                <div class="category-header">
                                                                                    <a href="{{ route('categories.level2', $level2Category->id) }}"
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
                                                                                            <a href="{{ route('categories.level2', $level3Category->id) }}"
                                                                                                class="category-link">
                                                                                                <span>{{ $level3Category->getTranslation('name') }}</span>
                                                                                                <i
                                                                                                    class="fas fa-chevron-right"></i>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @else
                                                                                <a href="{{ route('categories.level2', $level2Category->id) }}"
                                                                                    class="category-link">
                                                                                    <span>{{ $level2Category->getTranslation('name') }}</span>
                                                                                </a>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <a href="{{ route('categories.level2', $level1Category->id) }}"
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


                    {{-- Cards Grid - عرض الـ Sub Categories (Level 1) بتاعت الـ Main Category المختار --}}
                    <div class="col-lg-9">
                        <div class="row gx-3 gy-5 category-cards-grid">
                            @if ($levelOneCategories && $levelOneCategories->count() > 0)
                                @foreach ($levelOneCategories as $subCategory)
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="category-card-wrapper"
                                            onclick="window.location='{{ route('categories.level2', $subCategory->id) }}'">

                                            <div class="category-card">

                                                <img src="{{ uploaded_asset($subCategory->banner) }}"
                                                    alt="{{ $subCategory->getTranslation('name') }}"
                                                    onerror="this.src='{{ static_asset('assets/img/placeholder.jpg') }}'">

                                                <div class="cart-icon">
                                                    <i class="fas fa-shopping-basket"></i>
                                                </div>
<div class="add-inquiry-wrap">
    <button type="button"
            class="add-inquiry-btn js-add-category"
            data-id="{{ $subCategory->id }}"
            data-name="{{ $subCategory->getTranslation('name') }}"
            title="Add to Inquiry">
        <span class="icon icon-plus">+</span>
<span class="icon icon-check"></span>

    </button>
</div>

                                                <div class="category-title">
                                                    <h5>{{ $subCategory->getTranslation('name') }}</h5>
                                                    @php
                                                        $cardChildren = $subCategory->childrenCategories ?? ($subCategory->categories ?? collect());
                                                        $cardChildren = $cardChildren instanceof \Illuminate\Support\Collection
                                                            ? $cardChildren->take(4)
                                                            : collect($cardChildren)->take(4);
                                                    @endphp
                                                    @if ($cardChildren->count() > 0)
                                                        <div class="category-subline">
                                                            @foreach ($cardChildren as $child)
                                                                <a class="category-subline-link"
                                                                    href="{{ route('categories.level2', $child->id) }}"
                                                                    onclick="event.stopPropagation();">
                                                                    {{ method_exists($child, 'getTranslation') ? $child->getTranslation('name') : ($child->name ?? '') }}
                                                                </a>@if(!$loop->last)<span class="category-subline-sep"> / </span>@endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No subcategories available for this category.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Toggle Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allToggleIcons = document.querySelectorAll('.toggle-icon');
            const allMainToggle = document.querySelector('.toggle-all-main');
            const allMainWrapper = document.querySelector('.all-main-categories-wrapper');
            const mainCategoryItems = document.querySelectorAll('.main-category-item');

            // Make the whole category header clickable (not just the text),
            // but keep the toggle icon only for expanding/collapsing.
            document.addEventListener('click', function(e) {
                const header = e.target.closest('.category-sidebar .category-header');
                if (!header) return;

                // If user clicked the toggle icon, let the toggle handler run.
                if (e.target.closest('.toggle-icon')) return;

                const link = header.querySelector('a.category-name');
                if (link && link.getAttribute('href')) {
                    window.location.href = link.getAttribute('href');
                }
            });

            // Toggle للـ Main Categories
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

            // Toggle للـ Sub-Categories الداخلية (Level 1/2)
            allToggleIcons.forEach(function(toggleIcon) {
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

            // Toggle لـ "All Categories"
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

            // Auto-expand active main categories
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

            // Auto-expand active sub categories
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

            // إخفاء Products title في البداية
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





        // Add category to Inquiry (Ajax) - prevent card navigation
document.addEventListener('click', function(e){
    const btn = e.target.closest('.js-add-category');
    if(!btn) return;

    e.preventDefault();
    e.stopPropagation();   // يمنع onclick بتاع الكارد
    e.stopImmediatePropagation();

    if(btn.classList.contains('added') || btn.dataset.loading === "1") return;

    const categoryId = btn.getAttribute('data-id');
    const categoryName = btn.getAttribute('data-name');

    btn.dataset.loading = "1";

    $.ajax({
        type: "POST",
        url: "{{ route('cart.addCategoryToCart') }}",
        data: {
            _token: "{{ csrf_token() }}",
            category_id: categoryId
        },
        success: function (data) {
            if (data && data.status === 1) {

                if (data.cart_count !== undefined) {
                    const c = (data.cart_count === undefined || data.cart_count === null) ? 0 : data.cart_count;
                    $('.cart-count').html(c).attr('data-count', c);
                }
                if (typeof flashHeaderCartSuccess === 'function') {
                    flashHeaderCartSuccess();
                }

                btn.classList.add('added');
                btn.setAttribute('disabled', 'disabled');

                if (data.message === 'Category already in cart') {
                    AIZ.plugins.notify('warning', categoryName + " {{ translate('is already in cart') }}");
                } else {
                    AIZ.plugins.notify('success', categoryName + " {{ translate('added to inquiry') }}");
                }

            } else {
                AIZ.plugins.notify('danger', (data && data.message) ? data.message : "{{ translate('Something went wrong') }}");
            }
        },
        error: function () {
            AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
        },
        complete: function () {
            btn.dataset.loading = "0";
        }
    });
}, true);

    </script>
@endsection
