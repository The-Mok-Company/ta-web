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

    // If a specific category is requested via ?open=, use it as the active context
    // for sidebar highlighting (active trail).
    $currentCategoryId = request('open') ?? request()->segment(2);

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
.add-inquiry-btn .icon-check{ display:none; }
.add-inquiry-btn.added .icon-plus{ display:none; }
.add-inquiry-btn.added .icon-check{
    display:block;
    color:#fff;
    font-size:18px;
    font-weight:700;
}
.add-inquiry-btn.added .icon-check::before{ content:"✓"; }


    /* ===== Add to Inquiry button on cards (Top Right) ===== */
.add-inquiry-wrap {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 6;
}

.add-inquiry-btn {
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

.add-inquiry-btn .icon {
    color: #fff;
    font-size: 22px;
    font-weight: 700;
    line-height: 1;
    pointer-events: none;
}

/* clearly looks clickable */
.add-inquiry-btn:hover {
    background: #0E7490;
    transform: scale(1.08);
    box-shadow:
        0 8px 22px rgba(8,145,178,.45),
        0 0 0 4px rgba(8,145,178,.25);
}

.add-inquiry-btn:active {
    transform: scale(0.95);
}

/* Added state -> check */
.add-inquiry-btn.added {
    background: #16a34a;
    cursor: default;
}

.add-inquiry-btn.added .icon {
    font-size: 18px;
}

.add-inquiry-btn.added .icon::before {
    content: "✓";
}

/* disabled */
.add-inquiry-btn:disabled {
    opacity: .9;
    cursor: not-allowed;
}

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

    /* ========================
       SIDEBAR ITEM SPACING + OUTLINE
    ======================== */
    .category-sidebar ul li {
        margin-bottom: 10px;
    }

    .category-sidebar ul li:last-child {
        margin-bottom: 0;
    }

    .category-sidebar a.category-link,
    .category-sidebar .category-header {
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
        padding-left: 0 !important;
        list-style: none;
        display: none;
    }

    .sub-categories.show {
        display: block;
        padding-top: 10px;
    }

    .sub-categories li {
        font-size: 13px;
        margin-bottom: 10px;
    }

    /* Indent without using UL padding */
    .sub-categories > li {
        margin-left: 16px;
    }

    .sub-categories li a.category-link {
        padding: 12px 16px;
        border-radius: 10px;
        line-height: 1.25;
    }

    .sub-categories li .category-header {
        padding: 12px 16px;
        font-size: 13px;
        border-radius: 10px;
        line-height: 1.25;
    }

    /* Center nested rows (text centered, icons stay right) */
    .sub-categories li a.category-link,
    .sub-categories li .category-header,
    .sub-sub-categories li a.category-link,
    .sub-sub-categories li .category-header {
        justify-content: center;
        text-align: center;
        position: relative;
    }

    .sub-categories li a.category-link i.fa-chevron-right,
    .sub-sub-categories li a.category-link i.fa-chevron-right {
        position: absolute;
        right: 12px;
    }

    .sub-categories li .toggle-icon,
    .sub-sub-categories li .toggle-icon {
        position: absolute;
        right: 10px;
    }

    .sub-categories li .category-name,
    .sub-sub-categories li .category-name {
        width: 100%;
        justify-content: center;
    }

    .sub-categories li i.fa-chevron-right,
    .sub-categories li i.fa-chevron-down {
        font-size: 8px;
    }

    /* Sub Sub Categories Styling - Level 3 */
    .sub-sub-categories {
        margin: 8px 0 0 0 !important;
        padding-left: 0 !important;
        list-style: none;
        display: none;
    }

    .sub-sub-categories.show {
        display: block;
        padding-top: 10px;
    }

    .sub-sub-categories li {
        font-size: 12px;
        margin-bottom: 8px;
    }

    /* Indent without using UL padding */
    .sub-sub-categories > li {
        margin-left: 16px;
    }

    .sub-sub-categories li a.category-link {
        padding: 10px 14px;
        border-radius: 8px;
        line-height: 1.25;
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
        margin-bottom: 10px;
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
        margin-bottom: 0;
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

    /* ========================
       PRODUCT HEADER (match product listing UI)
    ======================== */
    .products-container {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, .08);
        padding: 24px;
    }

    .breadcrumb-modern {
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin: 0 0 16px 0;
        padding: 0;
        font-size: 13px;
        color: #6c757d;
    }

    .breadcrumb-modern a {
        color: #6c757d;
        text-decoration: none;
        transition: color .2s;
    }

    .breadcrumb-modern a:hover {
        color: #212529;
    }

    .breadcrumb-modern .active {
        color: #212529;
        font-weight: 600;
    }

    .breadcrumb-separator {
        color: #adb5bd;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #212529;
        margin: 0 0 10px 0;
    }

    .page-description {
        font-size: 13px;
        color: #6c757d;
        line-height: 1.6;
        margin: 0 0 18px 0;
    }

    .action-bar {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 18px;
    }

    .toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
    }

    .sort-dropdown {
        min-width: 220px;
        height: 40px;
        padding: 0 12px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        font-size: 13px;
        color: #212529;
        outline: none;
    }

    .btn-add-inquiry {
        height: 40px;
        padding: 0 14px;
        border-radius: 8px;
        border: none;
        background: #0891B2;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        white-space: nowrap;
    }

    .btn-add-inquiry:hover {
        background: #0E7490;
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(8,145,178,.25);
    }

    .btn-add-inquiry:disabled {
        opacity: .8;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .inline-back-btn {
        height: 40px;
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        .products-container { padding: 18px; }
        .page-title { font-size: 22px; }
        .toolbar { justify-content: flex-start; }
        .sort-dropdown { width: 100%; }
        .btn-add-inquiry { width: 100%; justify-content: center; }
    }
</style>

@section('content')
    <div class="category-page">
        {{-- Hero Banner with Category Image --}}
        <div class="category-hero"
            style="background-image: url('{{ uploaded_asset($currentCategory->banner ?? '') }}');">
            <div class="container">
                <a href="javascript:history.back()" class="back-arrow">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>
                    <span class="explore">Explore</span>

                    {{ $currentCategory->getTranslation('name') ?? 'Categories' }}
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
                                                                                <a href="{{ route('categories.level2', $level2Category->id) }}?open={{ $level2Category->id }}"
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
                                                                                        <a href="{{ route('categories.level2', $level3Category->id) }}?open={{ $level3Category->id }}"
                                                                                            class="category-link">
                                                                                            <span>{{ $level3Category->getTranslation('name') }}</span>
                                                                                            <i class="fas fa-chevron-right"></i>
                                                                                        </a>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @else
                                                                            {{-- Level 2 without children --}}
                                                                            <a href="{{ route('categories.level2', $level2Category->id) }}?open={{ $level2Category->id }}"
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
                                                            <a href="{{ route('categories.level2', $level1Category->id) }}?open={{ $level1Category->id }}"
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
                                <ul id="sidebar-products-list"></ul>
                            </div>
                        </div>
                    </div>

                    {{-- Cards Section --}}
                    <div class="col-lg-9">
                        {{-- Products view (loaded inline) --}}
                        <div id="inline-products-wrapper" class="d-none">
                            <div class="products-container">
                                <ul class="breadcrumb-modern" id="inline-breadcrumb"></ul>

                                <h1 class="page-title" id="inline-page-title"></h1>

                                <p class="page-description" id="inline-page-description">
                                    To connect global markets efficiently and ethically by providing exceptional sourcing and
                                    trade solutions that enhance business value and foster sustainable growth.
                                </p>

                                <div class="action-bar">
                                    <div class="toolbar">
                                        <select id="inline-sort-by" class="sort-dropdown" name="sort_by">
                                            <option value="">{{ translate('Sort by') }}</option>
                                            <option value="newest">{{ translate('Newest') }}</option>
                                            <option value="oldest">{{ translate('Oldest') }}</option>
                                            <option value="price-asc">{{ translate('Price low to high') }}</option>
                                            <option value="price-desc">{{ translate('Price high to low') }}</option>
                                        </select>

                                        <button type="button" class="btn-add-inquiry" id="inline-add-inquiry-btn">
                                            <i class="las la-plus"></i> {{ translate('Add to inquiry') }}
                                        </button>

                                        <button type="button" class="btn btn-sm btn-light inline-back-btn" id="inline-products-back">
                                            <i class="las la-arrow-left"></i> {{ translate('Back') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 row-cols-lg-3 g-3" id="products-row"></div>
                                <div class="pagination-wrapper mt-3" id="pagination"></div>
                            </div>
                        </div>

                        {{-- Categories grid (default) --}}
                        <div class="row gx-3 gy-5 category-cards-grid" id="level2-categories-grid">
                          @foreach ($levelTwoCategories as $category)
    <div class="col-lg-6 col-md-6">
        <a href="{{ route('categories.level2', $category->id) }}?open={{ $category->id }}" style="text-decoration: none;"
           class="js-open-category-products"
           data-category-id="{{ $category->id }}"
           data-category-name="{{ $category->getTranslation('name') }}">

            <div class="category-card">
                <img src="{{ uploaded_asset($category->banner) }}"
                     alt="{{ $category->getTranslation('name') }}">

                {{-- Add to Inquiry Button - Top Right --}}
                <div class="add-inquiry-wrap">
                    <button type="button"
                            class="add-inquiry-btn js-add-category"
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->getTranslation('name') }}"
                            title="Add to Inquiry">
<span class="icon icon-plus">+</span>
<span class="icon icon-check"></span>
                    </button>
                </div>

                {{-- Category Title - Bottom Left --}}
                <div class="category-title">
                    <h5>{{ $category->getTranslation('name') }}</h5>
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
            let activeCategoryName = '';

            function escapeHtml(str) {
                return $('<div>').text(str || '').html();
            }

            function getSidebarCategoryName(categoryId) {
                const $li = $(`.category-sidebar li[data-category-id="${categoryId}"]`).first();
                if (!$li.length) return '';

                // Prefer the visible "header" label if present
                const headerName = $li.find('> .category-header a.category-name span').first().text().trim();
                if (headerName) return headerName;

                // Fallback to leaf link label
                const linkName = $li.find('> a.category-link span').first().text().trim();
                if (linkName) return linkName;

                // Last fallback: any span text
                return ($li.find('span').first().text() || '').trim();
            }

            function buildInlineBreadcrumb(categoryId, categoryName) {
                const homeUrl = @json(route('home'));
                const allUrl = @json(route('categories.all'));

                const parts = [];

                // Always start with Home / All Categories
                parts.push(`<li><a href="${homeUrl}">{{ translate('Home') }}</a></li>`);
                parts.push(`<span class="breadcrumb-separator">/</span>`);
                parts.push(`<li><a href="${allUrl}">{{ translate('All Categories') }}</a></li>`);

                const $activeLi = $(`.category-sidebar li[data-category-id="${categoryId}"]`).first();

                // Try to add Main Category (level 0) from the accordion section
                if ($activeLi.length) {
                    const $section = $activeLi.closest('.sidebar-section');
                    const mainName = ($section.find('> .sidebar-section-header span').first().text() || '').trim();
                    if (mainName) {
                        parts.push(`<span class="breadcrumb-separator">/</span>`);
                        parts.push(`<li class="active">${escapeHtml(mainName)}</li>`);
                    }

                    // Build parent chain using the nested <ul data-parent-id="...">
                    const chain = [];
                    let currentId = categoryId;
                    let $currentLi = $activeLi;

                    // Ensure the active name is present
                    const resolvedActiveName = (categoryName || getSidebarCategoryName(categoryId) || '').trim();
                    if (resolvedActiveName) {
                        chain.push(resolvedActiveName);
                    }

                    while (true) {
                        const $parentUl = $currentLi.closest('ul.sub-categories, ul.sub-sub-categories');
                        if (!$parentUl.length) break;

                        const parentId = parseInt($parentUl.attr('data-parent-id'), 10);
                        if (!parentId) break;

                        // Stop if we can't find the parent li in the sidebar
                        const $parentLi = $(`.category-sidebar li[data-category-id="${parentId}"]`).first();
                        if (!$parentLi.length) break;

                        const parentName = (
                            $parentLi.find('> .category-header a.category-name span').first().text() ||
                            $parentLi.find('> a.category-link span').first().text() ||
                            $parentLi.find('span').first().text() ||
                            ''
                        ).trim();

                        if (parentName) {
                            chain.push(parentName);
                        }

                        currentId = parentId;
                        $currentLi = $parentLi;
                    }

                    // Render chain from top (closest to main) to active
                    const trail = chain.reverse().filter(Boolean);
                    trail.forEach(function(name) {
                        parts.push(`<span class="breadcrumb-separator">/</span>`);
                        parts.push(`<li class="active">${escapeHtml(name)}</li>`);
                    });
                } else {
                    // Fallback: just show the active category name
                    const resolvedActiveName = (categoryName || '').trim();
                    if (resolvedActiveName) {
                        parts.push(`<span class="breadcrumb-separator">/</span>`);
                        parts.push(`<li class="active">${escapeHtml(resolvedActiveName)}</li>`);
                    }
                }

                $('#inline-breadcrumb').html(parts.join(''));
            }

            function setOpenParamInUrl(openCategoryId) {
                try {
                    const url = new URL(window.location.href);
                    if (openCategoryId) {
                        url.searchParams.set('open', String(openCategoryId));
                    } else {
                        url.searchParams.delete('open');
                    }
                    window.history.replaceState({}, '', url.toString());
                } catch (e) {}
            }

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

                activeCategoryId = categoryId;
                activeCategoryName = (categoryName || getSidebarCategoryName(categoryId) || '').trim();

                $('#inline-page-title').text(activeCategoryName);
                buildInlineBreadcrumb(categoryId, activeCategoryName);
                // Persist the opened category so refresh stays on the same products view
                setOpenParamInUrl(categoryId);
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
                        sort_by: ($('#inline-sort-by').val() || ''),
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

            // Sidebar click: intercept product-opening links (those carrying ?open=...) to keep same page
            $(document).on('click', '.category-sidebar a.category-link, .category-sidebar a.category-name', function(e) {
                // allow open-in-new-tab behaviors
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
                    return true;
                }

                const href = $(this).attr('href') || '';
                // Only intercept links that explicitly ask to open products inline.
                // Plain /category-level2/{id} (without open=) should navigate normally.
                if (!href.includes('open=')) {
                    return true;
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
                openCategoryProducts(activeCategoryId, activeCategoryName, page);
            });

            $('#inline-products-back').on('click', function() {
                $('#inline-products-wrapper').addClass('d-none');
                $('#level2-categories-grid').removeClass('d-none');
                $('#products-row').html('');
                $('#pagination').html('');
                $('#sidebar-products-section').addClass('d-none');
                $('#sidebar-products-list').html('');
                // Clear open param so refresh returns to categories grid
                setOpenParamInUrl(null);
            });

            // Sort change (reload current category products)
            $(document).on('change', '#inline-sort-by', function() {
                if (!activeCategoryId) return;
                openCategoryProducts(activeCategoryId, activeCategoryName, 1);
            });

            // Header "Add to inquiry" should add the active category
            $(document).on('click', '#inline-add-inquiry-btn', function(e) {
                e.preventDefault();
                if (!activeCategoryId) return;

                const $btn = $(this);
                if ($btn.data('loading') === 1) return;
                $btn.data('loading', 1).prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: "{{ route('cart.addCategoryToCart') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        category_id: activeCategoryId
                    },
                    success: function(data) {
                        if (data && data.status === 1) {
                            if (data.cart_count !== undefined) {
                                const c = (data.cart_count === undefined || data.cart_count === null) ? 0 : data.cart_count;
                                $('.cart-count').html(c).attr('data-count', c);
                            }
                            if (typeof flashHeaderCartSuccess === 'function') {
                                flashHeaderCartSuccess();
                            }
                            try {
                                if (typeof AIZ !== 'undefined' && AIZ.plugins && typeof AIZ.plugins.notify === 'function') {
                                    AIZ.plugins.notify('success', (activeCategoryName || '') + " {{ translate('added to inquiry') }}");
                                }
                            } catch (err) {}
                        }
                    },
                    complete: function() {
                        $btn.data('loading', 0).prop('disabled', false);
                    }
                });
            });

            // Auto-open products when requested by controller (?open=... or category has no children)
            const initialOpenCategoryId = @json($initialOpenCategoryId ?? null);
            const initialOpenCategoryName = @json($initialOpenCategoryName ?? null);
            if (initialOpenCategoryId) {
                openCategoryProducts(initialOpenCategoryId, initialOpenCategoryName || '', 1);
            }

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
    <script>// Add category to Inquiry (Ajax) - prevent navigation
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.js-add-category');
    if (!btn) return;

    // stop the <a> click and any handlers that open category products
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    if (btn.classList.contains('added') || btn.dataset.loading === "1") return;

    const categoryId = btn.getAttribute('data-id');
    const categoryName = btn.getAttribute('data-name') || '';

    btn.dataset.loading = "1";

    $.ajax({
        type: "POST",
        url: "{{ route('cart.addCategoryToCart') }}",
        data: {
            _token: "{{ csrf_token() }}",
            category_id: categoryId
        },
        success: function(data) {
            if (data && data.status === 1) {

                if (data.cart_count !== undefined) {
                    const c = (data.cart_count === undefined || data.cart_count === null) ? 0 : data.cart_count;
                    $('.cart-count').html(c).attr('data-count', c);
                }
                if (typeof flashHeaderCartSuccess === 'function') {
                    flashHeaderCartSuccess();
                }

                // mark as added
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
        error: function() {
            AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
        },
        complete: function() {
            btn.dataset.loading = "0";
        }
    });
}, true);
</script>
@endsection
