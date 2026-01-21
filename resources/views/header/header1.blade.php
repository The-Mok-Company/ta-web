@php
    use App\Models\Category;
    use App\Models\Product;

    $topHeaderTextColor = get_setting('top_header_text_color');
    $middleHeaderTextColor = get_setting('middle_header_text_color');
    $bottomHeaderTextColor = get_setting('bottom_header_text_color');

    $categories = Category::where('level', 0)->with('childrenCategories')->get();

    // Cart count (works with DB/session cart via helper)
    // - Guest users: often stored in Session('cart')
    // - Logged-in users: typically stored via get_user_cart()
    $header_cart_count = 0;
    if (Session::has('cart')) {
        $header_cart_count = is_countable(Session::get('cart')) ? count(Session::get('cart')) : 0;
    } elseif (function_exists('get_user_cart')) {
        $header_carts = get_user_cart();
        $header_cart_count = is_countable($header_carts) ? count($header_carts) : 0;
    }
@endphp

<style>
    body {
        margin: 0;
        padding: 0;
    }

    .header {
        background: transparent;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        padding: 20px 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .header-container {
        max-width: 1200px;
        width: 90%;
        padding: 0 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #1a1a1a;
        border-radius: 100px;
        height: 56px;
        transition: all 0.3s ease;
        /* Important: allow dropdowns to render outside header pill */
        overflow: visible;
    }

    .header-container.search-active {
        border-radius: 16px;
        height: auto;
        flex-wrap: wrap;
        padding: 12px 40px;
    }



    /* Logo */
    .logo1 {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: white;
        padding-right: 20px;
        margin-top: 0;
    }

    /* Header-only resets (avoid impacting whole site) */
    .header a,
    .header button,
    .header input,
    .header textarea,
    .header .btn,
    .header .has-transition {
        margin-top: 0 !important;
    }

    /* User menu button vertical alignment (overrides inline top:5px) */
    .header .user-menu-btn {
        top: 0 !important;
    }

    .logo1-icon {
        width: 24px;
        height: 24px;
    }

    .logo1-text {
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .logo1-text span {
        font-weight: 400;
    }

    /* Navigation */
    .nav {
        display: flex;
        align-items: center;
        gap: 39px;
        flex: 1;
        justify-content: center;
    }

    .nav-link {
        color: #fff;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: color 0.2s;
        white-space: nowrap;
    }

    .nav-link:hover {
        color: var(--blue);
    }

    .nav-dropdown {
        position: relative;
    }

    .dropdown-btn {
        background: none;
        border: none;
        color: #fff;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
        padding: 0;
    }

    .dropdown-btn:hover {
        color: var(--blue);
    }

    .dropdown-arrow {
        width: 14px;
        height: 14px;
        transition: transform 0.2s;
    }

    .dropdown-btn.active .dropdown-arrow {
        transform: rotate(180deg);
    }

    /* Actions */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-left: 20px;
        overflow: visible;
    }

    .icon-btn {
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        /* Ensure true circular hover background */
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
        position: relative;
        overflow: visible;
    }

    .icon-btn:hover {
        color: #fff;
        background: #2a2a2a;
    }

    .icon-btn > a {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: inherit;
        text-decoration: none;
    }

    .icon-btn svg {
        width: 20px;
        height: 20px;
        display: block;
    }

    /* Cart "added" success state (green + check) */
    .header-cart-btn.is-success {
        background: rgba(34, 197, 94, 0.18) !important;
        color: #22c55e !important;
    }

    .header-cart-btn .cart-icon-svg {
        transition: opacity 0.18s ease, transform 0.18s ease;
    }

    .header-cart-btn .cart-success-check {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0.8);
        transition: opacity 0.18s ease, transform 0.18s ease;
        pointer-events: none;
        color: #22c55e;
    }

    .header-cart-btn.is-success .cart-icon-svg {
        opacity: 0;
        transform: scale(0.8);
    }

    .header-cart-btn.is-success .cart-success-check {
        opacity: 1;
        transform: scale(1);
    }

    .badge-count {
        position: absolute;
        top: 0;
        right: 0;
        transform: translate(35%, -35%);
        background: var(--blue);
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 5px;
        border-radius: 10px;
        min-width: 16px;
        text-align: center;
        border: 2px solid #1a1a1a; /* matches header bg */
    }

    .badge-count[data-count="0"] {
        display: none;
    }

    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        padding: 8px;
    }

    /* Search Bar */
    .search-container {
        width: 100%;
        margin-top: 12px;
        display: none;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
    }

    .search-container.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .search-input-wrapper {
        position: relative;
        width: 100%;
    }

    .search-input {
        width: 100%;
        padding: 12px 50px 12px 20px;
        background: #2a2a2a;
        border: 1px solid #3a3a3a;
        border-radius: 100px;
        color: #fff;
        font-size: 15px;
        outline: none;
        transition: all 0.2s;
    }

    .search-input:focus {
        border-color: var(--blue);
        background: #333;
    }

    .search-input::placeholder {
        color: #666;
    }

    .search-close-btn {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .search-close-btn:hover {
        color: #fff;
        background: #3a3a3a;
    }

    /* Search Results Dropdown */
    .search-results {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        max-height: 400px;
        overflow-y: auto;
        display: none;
        z-index: 1001;
    }

    .search-results.active {
        display: block;
    }

    .search-result-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s;
        border-bottom: 1px solid #f0f0f0;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .search-result-item:hover {
        background: #f8f9fa;
    }

    .search-result-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }

    .search-result-info {
        flex: 1;
        min-width: 0;
    }

    .search-result-name {
        font-weight: 600;
        color: #333;
        margin: 0 0 4px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .search-result-price {
        color: #3b82f6;
        font-weight: 500;
        font-size: 14px;
    }

    .search-no-results {
        padding: 20px;
        text-align: center;
        color: #999;
    }

    .search-loading {
        padding: 20px;
        text-align: center;
        color: #666;
    }

    /* Dropdown Menu Styles */
    .header-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        left: 50%;
        transform: translateX(-50%);
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateX(-50%) translateY(-10px);
        transition: all 0.3s;
        z-index: 1000;
    }

    .nav-dropdown:hover .header-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .icon-dropdown {
        right: 0;
        left: auto;
        transform: none;
    }

    .icon-btn:hover .icon-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Allow click-to-open for touch + desktop */
    .icon-btn.is-open .icon-dropdown,
    .icon-btn:focus-within .icon-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-header {
        padding: 12px 16px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
        color: #333;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s;
        border-bottom: 1px solid #f0f0f0;
    }

    .dropdown-item span {
        color: black !important;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-item:hover {
        background: #fff;
        color: black !important;
    }

    .dropdown-footer {
        padding: 10px;
        text-align: center;
        border-top: 1px solid #e9ecef;
    }

    .dropdown-footer a {
        color: var(--blue);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
    }

    /* === Sub Categories Dropdown (Mega Menu Style) === */
    .category-item-wrapper {
        position: relative;
    }

    .sub-dropdown {
        position: absolute;
        top: -64;
        left: 95%;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateX(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
        margin-left: 10px;
        padding: 8px 0;
    }

    .category-item-wrapper:hover>.sub-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    .category-item-wrapper:hover>.dropdown-item {
        background: #dbdbdb;
    }

    .sub-arrow {
        margin-left: auto;
        width: 16px;
        height: 16px;
        transition: transform 0.2s ease;
    }

    .category-item-wrapper:hover .sub-arrow {
        transform: translateX(4px);
    }

    /* Mobile Styles */
    @media (max-width: 968px) {
        .header {
            padding: 12px 0;
        }

        .nav-dropdown:hover .header-dropdown {
            transform: translateX(-23%) translateY(0px);
        }

        .nav-dropdown.active .header-dropdown {
            opacity: 1;
            visibility: visible;
            position: relative;
            top: 0;
            left: 0;
            transform: none;
            box-shadow: none;
            margin-top: 8px;
            background: #ffffff;
            border-radius: 8px;
        }

        .header-container {
            border-radius: 16px;
            padding: 0 20px;
            width: 95%;
        }

        .nav {
            display: none;
            position: absolute;
            top: 68px;
            left: 50%;
            transform: translateX(-50%);
            width: 95%;
            max-width: 1200px;
            background: #1a1a1a;
            flex-direction: column;
            gap: 0;
            padding: 16px 0;
            border-radius: 16px;
        }

        .nav.active {
            display: flex;
            align-items: flex-start;
        }

        .nav-link,
        .dropdown-btn {
            padding: 12px 24px;
            width: 100%;
            text-align: left;
        }

        .mobile-menu-btn {
            display: block;
        }

        .header-actions {
            gap: 4px;
            padding-left: 0;
        }

        .search-input {
            font-size: 14px;
            padding: 10px 40px 10px 16px;
        }

        .sub-dropdown {
            display: none;
            position: static;
            opacity: 1;
            visibility: visible;
            transform: none;
            margin-left: 0;
            padding-left: 20px;
            box-shadow: none;
            background: #ffffff;
            margin-top: 4px;
            border-radius: 6px;
        }

        .category-item-wrapper.active>.sub-dropdown {
            display: block;
        }

        .sub-dropdown .sub-dropdown {
            background: #ffffff;
            padding-left: 15px;
        }

        .sub-dropdown.level-2 {
            background: #ffffff;
        }

        .nav-dropdown.active .dropdown-item {
            color: #fff;
        }

        .nav-dropdown.active .dropdown-item span {
            color: #fff !important;
        }

        .nav-dropdown.active .dropdown-item:hover {
            background: #3a3a3a;
        }
    }

    .welcome-text {
        font-size: 12px;
        color: rgba(255, 255, 255, 1);
    }

    #logo-iconstyle {
        max-height: 40px;
        max-width: 200px;
        object-fit: contain;
    }

    @media (max-width: 600px) {
        #logo-iconstyle {
            max-height: 20px;
            max-width: 150px;
            margin-top: 5px;
        }
    }
        @media (max-width: 1161px) and (min-width: 968px) {
        .header-container {
            width: 100%;
        }

        .nav {
            gap: 18px;
        }
    }
</style>


<header class="header">
    <div class="header-container" id="headerContainer">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo1">
            @php
                $header_logo = get_setting('header_logo');
            @endphp
            @if ($header_logo != null)
                <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}" class="logo-icon"
                    id="logo-iconstyle">
            @else
                <svg class="logo1-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="#3B82F6" stroke="#3B82F6" stroke-width="2"
                        stroke-linejoin="round" />
                    <path d="M2 17L12 22L22 17" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M2 12L12 17L22 12" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            @endif
        </a>

        <!-- Navigation -->
        <nav class="nav" id="mainNav">
            @if (get_setting('header_menu_labels') != null)
                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                    @if ($value == 'All Categories')
                        @if (isset($categories) && $categories->count() > 0)
                            <div class="nav-dropdown">
                                <button class="dropdown-btn">
                                    {{ translate('categories') }}
                                    <svg class="dropdown-arrow" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div class="header-dropdown">
                                    <div class="dropdown-header">{{ translate('Main Categories') }}</div>

                                    @foreach ($categories->take(10) as $category)
                                        <div class="category-item-wrapper">
                                            <a href="{{ route('products.category', $category->slug) }}"
                                                class="dropdown-item">
                                                @if ($category->icon)
                                                    <img src="{{ uploaded_asset($category->icon) }}" width="20"
                                                        height="20">
                                                @endif

                                                <span>{{ $category->getTranslation('name') }}</span>

                                                @if ($category->childrenCategories->count() > 0)
                                                    <svg class="sub-arrow" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                @endif
                                            </a>

                                            @if ($category->childrenCategories->count() > 0)
                                                <div class="sub-dropdown">
                                                    <div class="dropdown-header">{{ translate('Sub Categories') }}</div>

                                                    @foreach ($category->childrenCategories as $subCategory)
                                                        <div class="category-item-wrapper">
                                                            <a href="{{ route('products.category', $subCategory->slug) }}"
                                                                class="dropdown-item">
                                                                <span>{{ $subCategory->getTranslation('name') }}</span>

                                                                @if ($subCategory->childrenCategories->count() > 0)
                                                                    <svg class="sub-arrow" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 5l7 7-7 7" />
                                                                    </svg>
                                                                @endif
                                                            </a>

                                                            @if ($subCategory->childrenCategories->count() > 0)
                                                                <div class="sub-dropdown level-2">
                                                                    <div class="dropdown-header">
                                                                        {{ translate('Product Group') }}</div>

                                                                    @foreach ($subCategory->childrenCategories as $level2Category)
                                                                        <a href="{{ route('categories.level2', $level2Category->id) }}?open={{ $level2Category->id }}"
                                                                            class="dropdown-item">
                                                                            <span>{{ $level2Category->getTranslation('name') }}</span>
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    <div class="dropdown-footer">
                                        <a href="{{ route('categories.all') }}">
                                            {{ translate('View All Categories') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('categories.all') }}" class="nav-link">
                                {{ translate('All categories') }}
                            </a>
                        @endif
                    @elseif ($value == 'Partners' || $value == 'Our Partners')
                        <div class="nav-dropdown">
                            <button class="dropdown-btn">
                                {{ translate('Partners') }}
                                <svg class="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="header-dropdown">
                                <a href="{{ route('ourpartners') }}" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        id="ourpartners">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    <span>{{ translate('Our Partners') }}</span>
                                </a>
                                <a href="{{ route('join_us') }}" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <line x1="19" y1="8" x2="19" y2="14"></line>
                                        <line x1="22" y1="11" x2="16" y2="11"></line>
                                    </svg>
                                    <span>{{ translate('Join Us') }}</span>
                                </a>
                            </div>
                        </div>
                    @else
                        @if (!in_array($value, ['Partners', 'Our Partners', 'Join Us']))
                            <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                                class="nav-link">
                                {{ translate($value) }}
                            </a>
                        @endif
                    @endif
                @endforeach
            @endif
        </nav>

        <!-- Actions -->
        <div class="header-actions">
            <!-- Search Icon -->
            <button class="icon-btn" onclick="toggleSearch()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            @if (Auth::check() && auth()->user()->user_type == 'customer')
                <!-- Notifications -->
                <div class="icon-btn" style="position: relative;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if (count($user->unreadNotifications) > 0)
                        <span class="badge-count">{{ count($user->unreadNotifications) }}</span>
                    @endif

                    <div class="header-dropdown icon-dropdown" style="max-width: 320px;">
                        <div class="dropdown-header">{{ translate('Notifications') }}</div>
                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse($user->unreadNotifications->take(5) as $notification)
                                <a href="{{ route('notification.read-and-redirect', encrypt($notification->id)) }}"
                                    class="dropdown-item">
                                    <div style="flex: 1;">
                                        <p style="margin: 0; font-size: 13px; color: #333;">
                                            {{ Str::limit($notification->data['message'] ?? 'New notification', 50) }}
                                        </p>
                                        <small
                                            style="color: #999;">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </a>
                            @empty
                                <div style="padding: 20px; text-align: center; color: #999;">
                                    {{ translate('No notifications') }}
                                </div>
                            @endforelse
                        </div>
                        <div class="dropdown-footer">
                            <a href="{{ route('customer.all-notifications') }}">{{ translate('View All') }}</a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Cart -->
            <div class="icon-btn header-cart-btn" style="position: relative;">
                <a href="{{ route('cart') }}" aria-label="{{ translate('Cart') }}">
                    <svg class="cart-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>
                <span class="cart-success-check" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="badge-count cart-count" data-count="{{ $header_cart_count }}">{{ $header_cart_count }}</span>
            </div>

            <!-- User Icon -->
            <div class="icon-btn user-menu-btn" style="position: relative; top:5px;" tabindex="0" aria-label="{{ translate('Account') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>

                @auth
                    <div class="header-dropdown icon-dropdown">
                        <div class="dropdown-header">{{ Auth::user()->name }}</div>

                        <a href="{{ route('inquiries.index') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5" />
                                <circle cx="9" cy="20" r="1" />
                                <circle cx="17" cy="20" r="1" />
                            </svg>
                            <span>{{ translate('My Inquiries') }}</span>
                        </a>

                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            <span>{{ translate('Account Settings') }}</span>
                        </a>

                        <a href="{{ route('logout') }}" class="dropdown-item" style="color: #d43533;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M8,0a8,8,0,1,0,8,8A8,8,0,0,0,8,0ZM8,1a7,7,0,1,1-7,7A7,7,0,0,1,8,1Z"
                                    fill="#d43533" />
                                <rect width="1" height="8" rx="0.5" transform="translate(7.5 0)"
                                    fill="#d43533" />
                            </svg>
                            <span>{{ translate('Logout') }}</span>
                        </a>
                    </div>
                @else
                    <div class="header-dropdown icon-dropdown">
                        <a href="{{ route('cart') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5" />
                                <circle cx="9" cy="20" r="1" />
                                <circle cx="17" cy="20" r="1" />
                            </svg>
                            <span>{{ translate('My Inquiries') }}</span>
                        </a>
                        <a href="{{ route('user.login') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                            <span>{{ translate('Login') }}</span>
                        </a>
                        <a href="{{ route('user.registration') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <line x1="19" y1="8" x2="19" y2="14"></line>
                                <line x1="22" y1="11" x2="16" y2="11"></line>
                            </svg>
                            <span>{{ translate('Register') }}</span>
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Search Container -->
        <div class="search-container" id="searchContainer">
            <div class="search-input-wrapper">
                <input type="text" id="searchInput" class="search-input"
                    placeholder="{{ translate('I\'m Looking for...') }}" autocomplete="off" name="query">
                <button type="button" class="search-close-btn" onclick="toggleSearch()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Search Results Dropdown -->
                <div class="search-results" id="searchResults"></div>
            </div>
        </div>
    </div>
</header>

<script>
 let searchTimeout;
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
let initialProductsLoaded = false;

// Load initial products when search opens
function loadInitialProducts() {
    if (initialProductsLoaded) return;

    searchResults.innerHTML = '<div class="search-loading">جاري التحميل...</div>';
    searchResults.classList.add('active');

    fetch(`{{ route('search.ajax') }}`)
        .then(response => response.json())
        .then(data => {
            initialProductsLoaded = true;
            console.log(data);
            displayProducts(data.products);
        })
        .catch(error => {
            console.error('Error loading products:', error);
            searchResults.innerHTML = '<div class="search-no-results">حدث خطأ في التحميل</div>';
        });
}

// Display products function
function displayProducts(products) {
    if (products && products.length > 0) {
        let html = '';
        products.forEach(product => {
            const imageUrl = product.thumbnail_img ?
                `{{ asset('') }}${product.thumbnail_img}` :
                '{{ asset('public/assets/img/placeholder.jpg') }}';

            html += `
                <a href="${product.url}" class="search-result-item">
                    <img src="${imageUrl}" alt="${product.name}" class="search-result-img" onerror="this.src='{{ asset('public/assets/img/placeholder.jpg') }}'">
                    <div class="search-result-info">
                        <p class="search-result-name">${product.name}</p>
                     </div>
                </a>
            `;
        });
        searchResults.innerHTML = html;
    } else {
        searchResults.innerHTML = '<div class="search-no-results">لا توجد منتجات</div>';
    }
}

// Dynamic Search Function - Search starts from first character
searchInput?.addEventListener('input', function(e) {
    const query = e.target.value.trim();

    clearTimeout(searchTimeout);

    // If empty, show initial products
    if (query.length === 0) {
        loadInitialProducts();
        return;
    }

    // Search from first character
    if (query.length >= 1) {
        searchResults.innerHTML = '<div class="search-loading">جاري البحث...</div>';
        searchResults.classList.add('active');

        searchTimeout = setTimeout(() => {
            fetch(`{{ route('search.ajax') }}?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displayProducts(data.products);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.innerHTML = '<div class="search-no-results">حدث خطأ في البحث</div>';
                });
        }, 300);
    }
});

// Show initial products when search input is focused
searchInput?.addEventListener('focus', function() {
    if (this.value.trim().length === 0) {
        loadInitialProducts();
    }
});

// Close search results when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.search-input-wrapper')) {
        searchResults.classList.remove('active');
    }
});

// Navigate to full search on Enter
searchInput?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const query = this.value.trim();
        if (query) {
            window.location.href = `{{ route('search.ajax') }}?keyword=${encodeURIComponent(query)}`;
        }
    }
});

function toggleSearch() {
    const container = document.getElementById('headerContainer');
    const searchContainer = document.getElementById('searchContainer');
    const searchInput = searchContainer.querySelector('.search-input');

    container.classList.toggle('search-active');
    searchContainer.classList.toggle('active');

    if (searchContainer.classList.contains('active')) {
        setTimeout(() => {
            searchInput.focus();
            // Load initial products when search opens
            if (searchInput.value.trim().length === 0) {
                loadInitialProducts();
            }
        }, 300);
    } else {
        searchResults.classList.remove('active');
        searchInput.value = '';
        initialProductsLoaded = false; // Reset for next time
    }
}

function toggleMobileMenu() {
    const nav = document.getElementById('mainNav');
    nav.classList.toggle('active');
}

// Close menus when clicking outside
document.addEventListener('click', function(event) {
    const nav = document.getElementById('mainNav');
    const menuBtn = document.querySelector('.mobile-menu-btn');
    const searchContainer = document.getElementById('searchContainer');
    const headerContainer = document.getElementById('headerContainer');

    if (nav && menuBtn && !nav.contains(event.target) && !menuBtn.contains(event.target)) {
        nav.classList.remove('active');
    }

    if (searchContainer.classList.contains('active') &&
        !searchContainer.contains(event.target) &&
        !event.target.closest('.icon-btn')) {
        headerContainer.classList.remove('search-active');
        searchContainer.classList.remove('active');
        initialProductsLoaded = false;
    }
});

// Mobile dropdown handling
document.addEventListener('DOMContentLoaded', function() {
    const dropdownBtns = document.querySelectorAll('.dropdown-btn');

    dropdownBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (window.innerWidth <= 968) {
                e.preventDefault();
                const parent = this.closest('.nav-dropdown');

                document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
                    if (dropdown !== parent) {
                        dropdown.classList.remove('active');
                    }
                });

                parent.classList.toggle('active');
                this.classList.toggle('active');
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 968) {
            const clickedItem = e.target.closest('.category-item-wrapper > .dropdown-item');

            if (clickedItem) {
                const wrapper = clickedItem.parentElement;
                const hasSubDropdown = wrapper.querySelector(':scope > .sub-dropdown');

                if (hasSubDropdown) {
                    e.preventDefault();
                    e.stopPropagation();

                    const parentContainer = wrapper.parentElement;
                    const siblings = parentContainer.querySelectorAll(':scope > .category-item-wrapper');

                    siblings.forEach(sibling => {
                        if (sibling !== wrapper) {
                            sibling.classList.remove('active');
                        }
                    });

                    wrapper.classList.toggle('active');
                }
            }

            if (!e.target.closest('.nav-dropdown') && !e.target.closest('.category-item-wrapper')) {
                document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
                document.querySelectorAll('.category-item-wrapper').forEach(wrapper => {
                    wrapper.classList.remove('active');
                });
                document.querySelectorAll('.dropdown-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
            }
        }
    });
});
</script>
