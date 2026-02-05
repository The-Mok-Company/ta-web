<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left">
                @if (get_setting('system_logo_black') != null)
                <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_black')) }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @else
                <img class="mw-100" src="{{ static_asset('assets/img/logo.png') }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-3 mb-3 position-relative">
                <input class="form-control bg-transparent rounded-2 form-control-sm text-white fs-14" type="text" name="" placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">
                <span class="absolute-top-right pr-3 mr-3" style="margin-top: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M176.921-769.231l6.255-6.255a5.99,5.99,0,0,0,1.733.949,5.687,5.687,0,0,0,1.885.329,5.317,5.317,0,0,0,3.9-1.608,5.31,5.31,0,0,0,1.609-3.9,5.322,5.322,0,0,0-1.608-3.9,5.306,5.306,0,0,0-3.9-1.611,5.321,5.321,0,0,0-3.9,1.609,5.312,5.312,0,0,0-1.611,3.9,5.554,5.554,0,0,0,.35,1.946,6.043,6.043,0,0,0,.929,1.672l-6.255,6.255Zm9.874-5.82a4.51,4.51,0,0,1-3.317-1.352,4.51,4.51,0,0,1-1.352-3.317,4.51,4.51,0,0,1,1.352-3.317,4.51,4.51,0,0,1,3.317-1.352,4.51,4.51,0,0,1,3.317,1.352,4.51,4.51,0,0,1,1.352,3.317,4.51,4.51,0,0,1-1.352,3.317A4.51,4.51,0,0,1,186.8-775.051Z" transform="translate(-176.307 785.231)" fill="#4e5767" />
                    </svg>
                </span>
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">

                {{-- ======================================== --}}
                {{-- 1. Dashboard --}}
                {{-- ======================================== --}}
                @can('admin_dashboard')
                <li class="aiz-side-nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="aiz-side-nav-link {{ areActiveRoutes(['admin.dashboard']) }}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M18,12.286a1.715,1.715,0,0,0-1.714-1.714h-4a1.715,1.715,0,0,0-1.714,1.714v4A1.715,1.715,0,0,0,12.286,18h4A1.715,1.715,0,0,0,18,16.286Zm-8.571,0a1.715,1.715,0,0,0-1.714-1.714h-4A1.715,1.715,0,0,0,2,12.286v4A1.715,1.715,0,0,0,3.714,18h4a1.715,1.715,0,0,0,1.714-1.714Zm7.429,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm-8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571ZM9.429,3.714A1.715,1.715,0,0,0,7.714,2h-4A1.715,1.715,0,0,0,2,3.714v4A1.715,1.715,0,0,0,3.714,9.429h4A1.715,1.715,0,0,0,9.429,7.714Zm8.571,0A1.715,1.715,0,0,0,16.286,2h-4a1.715,1.715,0,0,0-1.714,1.714v4a1.715,1.715,0,0,0,1.714,1.714h4A1.715,1.715,0,0,0,18,7.714Zm-9.714,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Z" transform="translate(-2 -2)" fill="#575b6a" fill-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>
                @endcan

                {{-- ======================================== --}}
                {{-- 2. Catalog --}}
                {{-- ======================================== --}}
                @canany(['add_new_product', 'show_all_products', 'view_product_categories', 'view_all_brands', 'view_custom_label', 'product_bulk_import', 'product_bulk_export'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="13.714" viewBox="0 0 16 13.714">
                                <g id="Layer_2" data-name="Layer 2" transform="translate(-2 -4)">
                                    <path d="M17.429,4H2.571A.571.571,0,0,0,2,4.571V8a.571.571,0,0,0,.571.571h.571v8.571a.571.571,0,0,0,.571.571H16.286a.571.571,0,0,0,.571-.571V8.571h.571A.571.571,0,0,0,18,8V4.571A.571.571,0,0,0,17.429,4ZM15.714,16.571H4.286v-8H15.714Zm1.143-9.143H3.143V5.143H16.857Z" fill="#575b6a" />
                                    <path d="M12.571,15.143H16A.571.571,0,0,0,16,14H12.571a.571.571,0,0,0,0,1.143Z" transform="translate(-4.286 -4.286)" fill="#575b6a" />
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Catalog') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @can('add_new_product')
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['products.create']) }}" href="{{ route('products.create') }}">
                                <span class="aiz-side-nav-text">{{ translate('Add New Product') }}</span>
                            </a>
                        </li>
                        @endcan

                        @can('show_all_products')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('products.all') }}" class="aiz-side-nav-link {{ areActiveRoutes(['products.all', 'products.admin', 'products.admin.edit']) }}">
                                <span class="aiz-side-nav-text">{{ translate('All Products') }}</span>
                            </a>
                        </li>
                        @endcan

                        @can('view_product_categories')
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Categories') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('categories.index') }}?level=0" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Main Categories') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('categories.index') }}?level=1" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Sub Categories') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('categories.index') }}?level=2" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Product Groups') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan

                        @can('view_all_brands')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('brands.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Brands') }}</span>
                            </a>
                        </li>
                        @endcan

                        @can('view_custom_label')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('custom_label.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['custom_label.index', 'custom_label.edit', 'custom_label.create']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Custom Label') }}</span>
                            </a>
                        </li>
                        @endcan

                        @can('product_bulk_import')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('product_bulk_upload.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Bulk Import') }}</span>
                            </a>
                        </li>
                        @endcan

                        @can('product_bulk_export')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('product_bulk_export.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Bulk Export') }}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- ======================================== --}}
                {{-- 3. Inquiries --}}
                {{-- ======================================== --}}
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M14,1H2A2,2,0,0,0,0,3V11a2,2,0,0,0,2,2H4v2.5a.5.5,0,0,0,.854.354L7.707,13H14a2,2,0,0,0,2-2V3A2,2,0,0,0,14,1ZM15,11a1,1,0,0,1-1,1H7.5a.5.5,0,0,0-.354.146L5,14.293V12.5a.5.5,0,0,0-.5-.5H2a1,1,0,0,1-1-1V3A1,1,0,0,1,2,2H14a1,1,0,0,1,1,1Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Inquiries') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        {{-- All Inquiries --}}
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['admin.inquiries.index', 'admin.inquiries.create', 'admin.inquiries.show', 'admin.inquiries.edit']) }}" href="{{ route('admin.inquiries.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('All Inquiries') }}</span>
                            </a>
                        </li>

                        {{-- Status Filters --}}
                        @php
                            $inquiryStatuses = [
                                'new' => ['label' => 'New', 'color' => '', 'icon' => '├─'],
                                'pending' => ['label' => 'Pending', 'color' => '', 'icon' => '├─'],
                                'responded' => ['label' => 'Responded', 'color' => '', 'icon' => '├─'],
                                'offer_sent' => ['label' => 'Offer Sent', 'color' => '', 'icon' => '├─'],
                                'accepted' => ['label' => 'Accepted', 'color' => '', 'icon' => '├─'],
                                'rejected' => ['label' => 'Rejected', 'color' => '', 'icon' => '├─'],
                                'deal_closed' => ['label' => 'Deal Closed', 'color' => '', 'icon' => '├─'],
                                'cancelled' => ['label' => 'Cancelled', 'color' => 'text-danger', 'icon' => '└─'],
                                'on_hold' => ['label' => 'On Hold', 'color' => 'text-warning', 'icon' => '└─'],
                                'expired' => ['label' => 'Expired', 'color' => 'text-muted', 'icon' => '└─'],
                            ];
                        @endphp

                        @foreach($inquiryStatuses as $status => $info)
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('admin.inquiries.index', ['status' => $status]) }}">
                                <span class="aiz-side-nav-text {{ $info['color'] }}">
                                    <small class="mr-1">{{ $info['icon'] }}</small> {{ translate($info['label']) }}
                                </span>
                            </a>
                        </li>
                        @endforeach

                        {{-- Filter by Type --}}
                        <li class="aiz-side-nav-item mt-2">
                            <a class="aiz-side-nav-link" href="{{ route('admin.inquiries.index', ['type' => 'category']) }}">
                                <span class="aiz-side-nav-text text-info">
                                    <i class="las la-folder mr-1"></i> {{ translate('Category Inquiries') }}
                                </span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('admin.inquiries.index', ['type' => 'product']) }}">
                                <span class="aiz-side-nav-text text-info">
                                    <i class="las la-box mr-1"></i> {{ translate('Product Inquiries') }}
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ======================================== --}}
                {{-- 4. Customers --}}
                {{-- ======================================== --}}
                @can('view_all_customers')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M8,8A4,4,0,1,0,4,4,4,4,0,0,0,8,8ZM8,1A3,3,0,1,1,5,4,3,3,0,0,1,8,1Zm0,9a6.006,6.006,0,0,0-6,6,.5.5,0,0,0,1,0,5,5,0,0,1,10,0,.5.5,0,0,0,1,0A6.006,6.006,0,0,0,8,10Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Customers') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('customers.index', ['type' => 'new']) }}">
                                <span class="aiz-side-nav-text">{{ translate('New') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('customers.index', ['type' => 'inquired']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Inquired') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- ======================================== --}}
                {{-- 5. Reports --}}
                {{-- ======================================== --}}
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M0,1.5A1.5,1.5,0,0,1,1.5,0h13A1.5,1.5,0,0,1,16,1.5v13a1.5,1.5,0,0,1-1.5,1.5H1.5A1.5,1.5,0,0,1,0,14.5ZM1.5,1a.5.5,0,0,0-.5.5v13a.5.5,0,0,0,.5.5h13a.5.5,0,0,0,.5-.5v-13a.5.5,0,0,0-.5-.5ZM4,4.5a.5.5,0,0,1,.5-.5h7a.5.5,0,0,1,0,1h-7A.5.5,0,0,1,4,4.5Zm0,3a.5.5,0,0,1,.5-.5h7a.5.5,0,0,1,0,1h-7A.5.5,0,0,1,4,7.5Zm0,3a.5.5,0,0,1,.5-.5h4a.5.5,0,0,1,0,1h-4A.5.5,0,0,1,4,10.5Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Reports') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('user_search_report.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('Search Reports') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('admin.inquiries.reports') }}">
                                <span class="aiz-side-nav-text">{{ translate('Inquiries Report') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('commission-log.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('Earnings / Finance') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ======================================== --}}
                {{-- 6. Marketing --}}
                {{-- ======================================== --}}
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M13,2.5a1.5,1.5,0,0,1,3,0V11a1.5,1.5,0,0,1-3,0ZM14.5,2a.5.5,0,0,0-.5.5V11a.5.5,0,0,0,1,0V2.5A.5.5,0,0,0,14.5,2ZM0,8A6,6,0,0,1,11.992,5.984l.008.022V11h-.008A6,6,0,0,1,0,8Zm1,0a5,5,0,0,0,10,0A5,5,0,0,0,1,8Zm2.5,8a.5.5,0,0,1,0-1h5a.5.5,0,0,1,0,1Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Marketing') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('dynamic-popups.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('Dynamic Popup') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('custom-alerts.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('Custom Alerts') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('newsletters.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('Newsletters') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('subscribers.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('Subscribers') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ======================================== --}}
                {{-- 7. Messages (from contact us) --}}
                {{-- ======================================== --}}
                <li class="aiz-side-nav-item">
                    <a href="{{ route('contacts') }}" class="aiz-side-nav-link {{ areActiveRoutes(['contacts']) }}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M0,4A2,2,0,0,1,2,2H14a2,2,0,0,1,2,2V12a2,2,0,0,1-2,2H2a2,2,0,0,1-2-2ZM2,3A1,1,0,0,0,1,4V4.217l7,4.2,7-4.2V4a1,1,0,0,0-1-1Zm13,2.383-4.758,2.855L15,11.114Zm-.034,6.878L9.271,8.82,8,9.583,6.729,8.82.034,12.261A1,1,0,0,0,2,13H14A1,1,0,0,0,14.966,12.261ZM1,11.114l4.758-2.876L1,5.383Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Messages') }}</span>
                    </a>
                </li>

                {{-- ======================================== --}}
                {{-- 8. Partners (Join Us / Partner Applications) --}}
                {{-- ======================================== --}}
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M7,14s-1,0-1-1,1-4,5-4,5,3,5,4-1,1-1,1Zm4-6a3,3,0,1,0-3-3A3,3,0,0,0,11,8ZM5.216,14A2.238,2.238,0,0,1,5,13c0-1.355.68-2.75,1.936-3.72A6.325,6.325,0,0,0,5,9c-4,0-5,3-5,4s1,1,1,1ZM4.5,8A2.5,2.5,0,1,0,2,5.5,2.5,2.5,0,0,0,4.5,8Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Partners') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('settings.join-us') }}">
                                <span class="aiz-side-nav-text">{{ translate('Join Us Settings') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('settings.join-us') }}">
                                <span class="aiz-side-nav-text">{{ translate('Partner Applications') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ======================================== --}}
                {{-- 9. Pages Setup --}}
                {{-- ======================================== --}}
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M2.5,0A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11a2.5,2.5,0,0,0,2.5-2.5v-11A2.5,2.5,0,0,0,13.5,0ZM1,2.5A1.5,1.5,0,0,1,2.5,1H6v5.5a.5.5,0,0,0,.5.5h3a.5.5,0,0,0,.5-.5V1h3.5A1.5,1.5,0,0,1,15,2.5v11a1.5,1.5,0,0,1-1.5,1.5h-11A1.5,1.5,0,0,1,1,13.5ZM7,1h2v5H7Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Pages Setup') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('settings.home-page.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('Homepage') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('settings.about-us') }}">
                                <span class="aiz-side-nav-text">{{ translate('About Us') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('settings.our-partners') }}">
                                <span class="aiz-side-nav-text">{{ translate('Our Partners') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('settings.our-services') }}">
                                <span class="aiz-side-nav-text">{{ translate('Our Services') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('settings.contact-us') }}">
                                <span class="aiz-side-nav-text">{{ translate('Contact Us') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('settings.footer') }}">
                                <span class="aiz-side-nav-text">{{ translate('Footer') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ======================================== --}}
                {{-- 10. System Settings --}}
                {{-- ======================================== --}}
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M9.405,1.05c-.413-1.4-2.397-1.4-2.81,0l-.1.34a1.464,1.464,0,0,1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987,1.987l.169.311a1.464,1.464,0,0,1-.872,2.105l-.34.1c-1.4.413-1.4,2.397,0,2.81l.34.1a1.464,1.464,0,0,1,.872,2.105l-.17.31c-.698,1.283.705,2.686,1.987,1.987l.311-.169a1.464,1.464,0,0,1,2.105.872l.1.34c.413,1.4,2.397,1.4,2.81,0l.1-.34a1.464,1.464,0,0,1,2.105-.872l.31.17c1.283.698,2.686-.705,1.987-1.987l-.169-.311a1.464,1.464,0,0,1,.872-2.105l.34-.1c1.4-.413,1.4-2.397,0-2.81l-.34-.1a1.464,1.464,0,0,1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464,1.464,0,0,1-2.105-.872ZM8,10.93a2.929,2.929,0,1,1,2.929-2.929A2.929,2.929,0,0,1,8,10.93Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('System Settings') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('uploaded-files.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('Uploaded Files') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('system_server') }}">
                                <span class="aiz-side-nav-text">{{ translate('Server Status') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('sitemap_generator') }}">
                                <span class="aiz-side-nav-text">{{ translate('Sitemap Generator') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ======================================== --}}
                {{-- 11. Staff & Roles (Hidden for now) --}}
                {{-- ======================================== --}}
                {{--
                @canany(['view_staff', 'view_roles'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path d="M15,14s1,0,1-1-1-4-6-4-6,3-6,4,1,1,1,1Zm-5-6a3,3,0,1,0-3-3A3,3,0,0,0,10,8ZM5.216,14A2.238,2.238,0,0,1,5,13c0-1.355.68-2.75,1.936-3.72A6.325,6.325,0,0,0,5,9c-5,0-6,3-6,4s1,1,1,1ZM4.5,8A2.5,2.5,0,1,0,2,5.5,2.5,2.5,0,0,0,4.5,8Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Staff & Roles') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('staffs.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('All Staff') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link" href="{{ route('roles.index') }}">
                                <span class="aiz-side-nav-text">{{ translate('Staff Permissions') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany
                --}}

            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    function menuSearch() {
        var filter = document.getElementById('menu-search').value.toUpperCase();
        var mainMenu = document.getElementById("main-menu");
        var searchMenu = document.getElementById("search-menu");
        var li = mainMenu.getElementsByClassName('aiz-side-nav-item');
        var counter = 0;

        searchMenu.innerHTML = '';

        if (filter === '') {
            mainMenu.style.display = '';
            searchMenu.style.display = 'none';
            return;
        }

        mainMenu.style.display = 'none';
        searchMenu.style.display = '';

        for (var i = 0; i < li.length; i++) {
            var a = li[i].getElementsByTagName("a")[0];
            if (a) {
                var txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    var cloned = li[i].cloneNode(true);
                    // Remove any nested submenus from cloned item
                    var nestedMenus = cloned.getElementsByClassName('level-2');
                    while(nestedMenus.length > 0){
                        nestedMenus[0].parentNode.removeChild(nestedMenus[0]);
                    }
                    nestedMenus = cloned.getElementsByClassName('level-3');
                    while(nestedMenus.length > 0){
                        nestedMenus[0].parentNode.removeChild(nestedMenus[0]);
                    }
                    searchMenu.appendChild(cloned);
                    counter++;
                }
            }
        }

        if (counter === 0) {
            searchMenu.innerHTML = '<li class="aiz-side-nav-item"><span class="aiz-side-nav-link text-muted">{{ translate("No results found") }}</span></li>';
        }
    }
</script>
