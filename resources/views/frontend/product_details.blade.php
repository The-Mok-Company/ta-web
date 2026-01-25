@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }},{{ $detailedProduct->meta_keywords }}@stop

@section('meta')
    @php
        $availability = "out of stock";
        $qty = 0;
        if($detailedProduct->variant_product) {
            foreach ($detailedProduct->stocks as $key => $stock) {
                $qty += $stock->qty;
            }
        }
        else {
            $qty = optional($detailedProduct->stocks->first())->qty;
        }
        if($qty > 0){
            $availability = "in stock";
        }
    @endphp
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:brand" content="{{ $detailedProduct->brand ? $detailedProduct->brand->name : env('APP_NAME') }}">
    <meta property="product:availability" content="{{ $availability }}">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format($detailedProduct->unit_price, 2) }}">
    <meta property="product:retailer_item_id" content="{{ $detailedProduct->slug }}">
    <meta property="product:price:currency"
        content="{{ get_system_default_currency()->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@section('content')
    @if (!empty($sidebar_category_id))
        @php
            $mainCategories = App\Models\Category::where('level', 0)
                ->with([
                    'childrenCategories' => function ($query) {
                        $query->with([
                            'childrenCategories' => function ($q) {
                                $q->withCount('products');
                            },
                        ])->withCount('products');
                    },
                ])
                ->withCount('products')
                ->orderBy('order_level', 'desc')
                ->get();

            $currentCategoryId = $sidebar_category_id;
        @endphp

        <style>
            /* Sidebar styles (aligned with category pages) */
            .category-sidebar {
                background: #fff;
                padding: 20px 16px;
                border-radius: 16px;
                box-shadow: 0 2px 15px rgba(0, 0, 0, .08);
                position: sticky;
                top: 20px;
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

            .category-sidebar h6 {
                font-weight: 600;
                margin: 18px 0 12px;
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
                margin: 0 0 12px 0;
            }

            .category-sidebar ul li {
                border-radius: 12px;
                font-size: 14px;
                cursor: pointer;
                transition: all .3s ease;
                margin-bottom: 6px;
                color: #555;
                font-weight: 500;
                background: transparent;
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
                opacity: 0.6;
                transition: all .3s ease;
                cursor: pointer;
                padding: 4px 8px;
                margin-left: 8px;
                flex-shrink: 0;
            }

            .category-sidebar ul li:hover:not(.active) {
                background: #f8f9fa;
            }

            .category-sidebar ul li.active {
                background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
                color: #fff;
                box-shadow: 0 3px 10px rgba(74, 144, 226, 0.25);
            }

            .category-sidebar ul li.active a.category-link,
            .category-sidebar ul li.active .category-header,
            .category-sidebar ul li.active .category-name {
                color: #fff;
            }

            .sub-categories,
            .sub-sub-categories {
                margin: 8px 0 0 0 !important;
                padding-left: 16px !important;
                list-style: none;
                display: none;
            }

            .sub-categories.show,
            .sub-sub-categories.show {
                display: block;
                padding-top: 6px;
            }

            .sub-categories li {
                font-size: 13px;
                margin-bottom: 5px;
            }

            .sub-sub-categories li {
                font-size: 12px;
                margin-bottom: 4px;
            }

            /* Product list inside sidebar */
            .sidebar-product-list li {
                cursor: default;
            }

            .sidebar-product-list a {
                font-size: 13px;
                padding: 10px 14px;
            }
        </style>

        <section class="mb-4 pt-3">
            <div class="container">
                <div class="row gutters-16">
                    <!-- Left side (keep category menu) -->
                    <div class="col-lg-3">
                        <div class="category-sidebar">
                            <h6>Categories</h6>

                            <ul>
                                <li>
                                    <a href="{{ route('categories.all') }}" class="category-link">
                                        <span>All Categories</span>
                                    </a>
                                </li>
                            </ul>

                            @foreach ($mainCategories as $mainCategory)
                                <h6>{{ $mainCategory->getTranslation('name') }}</h6>

                                <ul class="parent-category-list">
                                    @if ($mainCategory->childrenCategories && $mainCategory->childrenCategories->count() > 0)
                                        @foreach ($mainCategory->childrenCategories as $level1Category)
                                            <li class="parent-category {{ $currentCategoryId == $level1Category->id ? 'active' : '' }}"
                                                data-category-id="{{ $level1Category->id }}">

                                                @if ($level1Category->childrenCategories && $level1Category->childrenCategories->count() > 0)
                                                    <div class="category-header">
                                                        <a href="{{ route('categories.level2', $level1Category->id) }}"
                                                            class="category-name">
                                                            <span>{{ $level1Category->getTranslation('name') }}</span>
                                                        </a>
                                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                                    </div>

                                                    <ul class="sub-categories" data-parent-id="{{ $level1Category->id }}">
                                                        @foreach ($level1Category->childrenCategories as $level2Category)
                                                            <li class="{{ $currentCategoryId == $level2Category->id ? 'active' : '' }}"
                                                                data-category-id="{{ $level2Category->id }}">

                                                                @if ($level2Category->childrenCategories && $level2Category->childrenCategories->count() > 0)
                                                                    <div class="category-header">
                                                                        <a href="{{ route('categories.level2', $level2Category->id) }}?open={{ $level2Category->id }}"
                                                                            class="category-name">
                                                                            <span>{{ $level2Category->getTranslation('name') }}</span>
                                                                        </a>
                                                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                                                    </div>

                                                                    <ul class="sub-sub-categories"
                                                                        data-parent-id="{{ $level2Category->id }}">
                                                                        @foreach ($level2Category->childrenCategories as $level3Category)
                                                                            <li
                                                                                class="{{ $currentCategoryId == $level3Category->id ? 'active' : '' }}">
                                                                                    <a href="{{ route('categories.level2', $level3Category->id) }}?open={{ $level3Category->id }}"
                                                                                    class="category-link">
                                                                                    <span>{{ $level3Category->getTranslation('name') }}</span>
                                                                                    <i class="fas fa-chevron-right"></i>
                                                                                </a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
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
                                                    <a href="{{ route('categories.level2', $level1Category->id) }}?open={{ $level1Category->id }}"
                                                        class="category-link">
                                                        <span>{{ $level1Category->getTranslation('name') }}</span>
                                                        <i class="fas fa-chevron-right"></i>
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach
                                    @else
                                        <li>
                                            <a href="#" class="category-link">
                                                <span class="product-count">There are no sub categories available.</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            @endforeach

                            @if (!empty($sidebar_products) && $sidebar_products->count() > 0)
                                <h6>Products</h6>
                                <ul class="sidebar-product-list">
                                    @foreach ($sidebar_products as $p)
                                        <li class="{{ $p->id == $detailedProduct->id ? 'active' : '' }}">
                                            <a class="category-link"
                                                href="{{ route('product', $p->slug) }}">
                                                <span>{{ $p->getTranslation('name') }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Right side (product details) -->
                    <div class="col-lg-9">
                        <div class="bg-white py-3 mb-4">
                            <div class="row">
                                <!-- Product Image Gallery -->
                                <div class="col-xl-5 col-lg-6 mb-4">
                                    @include('frontend.product_details.image_gallery')
                                </div>

                                <!-- Product Details -->
                                <div class="col-xl-7 col-lg-6">
                                    @include('frontend.product_details.details')
                                </div>
                            </div>
                        </div>

                        @if ($detailedProduct->auction_product)
                            <!-- Description, Video, Downloads -->
                            @include('frontend.product_details.description')

                            <!-- Product Query -->
                            @include('frontend.product_details.product_queries')
                        @else
                            <!-- Description, Video, Downloads -->
                            @include('frontend.product_details.description')

                            <!-- Product Query -->
                            @include('frontend.product_details.product_queries')

                            <!-- Top Selling Products (optional, now inside main column) -->
                            <div class="mt-3">
                                @include('frontend.product_details.top_selling_products')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle toggle icons
                const allToggleIcons = document.querySelectorAll('.toggle-icon');
                allToggleIcons.forEach(function(toggleIcon) {
                    toggleIcon.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const categoryHeader = this.closest('.category-header');
                        const parentLi = categoryHeader.closest('li');
                        const categoryId = parentLi.getAttribute('data-category-id');

                        let subCategoriesUl = parentLi.querySelector(`.sub-categories[data-parent-id="${categoryId}"]`);
                        if (!subCategoriesUl) {
                            subCategoriesUl = parentLi.querySelector(`.sub-sub-categories[data-parent-id="${categoryId}"]`);
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
                    let parentUl = activeLi.closest('.sub-categories, .sub-sub-categories');
                    while (parentUl) {
                        parentUl.classList.add('show');
                        const parentToggle =
                            parentUl.previousElementSibling?.querySelector('.toggle-icon') ||
                            parentUl.closest('li')?.querySelector('.toggle-icon');
                        if (parentToggle) {
                            parentToggle.style.transform = 'rotate(180deg)';
                        }
                        parentUl = parentUl.closest('li')?.closest('.sub-categories, .sub-sub-categories');
                    }
                });

                // Ensure the active product is visible in the sidebar product list
                const activeProduct = document.querySelector('.sidebar-product-list li.active');
                if (activeProduct) {
                    activeProduct.scrollIntoView({
                        block: 'nearest'
                    });
                }
            });
        </script>
    @else
        <section class="mb-4 pt-3" style="padding-top:140px !important">
            <div class="container">
                <div class="bg-white py-3">
                    <div class="row">
                        <!-- Product Image Gallery -->
                        <div class="col-xl-5 col-lg-6 mb-4">
                            @include('frontend.product_details.image_gallery')
                        </div>

                        <!-- Product Details -->
                        <div class="col-xl-7 col-lg-6">
                            @include('frontend.product_details.details')
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <div class="container" >
                @if ($detailedProduct->auction_product)
                    <!-- Description, Video, Downloads -->
                    @include('frontend.product_details.description')

                    <!-- Product Query -->
                    @include('frontend.product_details.product_queries')
                @else
                    <div class="row gutters-16">
                        <!-- Left side -->
                        <div class="col-lg-3">
                            <!-- Seller Info -->
                            @include('frontend.product_details.seller_info')


                        </div>

                        <!-- Right side -->
                        <div class="col-lg-9">

                            <!-- Description, Video, Downloads -->
                            @include('frontend.product_details.description')

                            <!-- Product Query -->
                            @include('frontend.product_details.product_queries')

                            <!-- Top Selling Products -->
                            <div class="d-lg-none">
                                @include('frontend.product_details.top_selling_products')
                            </div>

                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @include('frontend.smart_bar')

@endsection

@section('modal')
    <!-- Image Modal -->
    <div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="p-4">
                    <div class="size-300px size-lg-450px">
                        <img class="img-fit h-100 lazyload"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src=""
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3 rounded-0" name="title"
                                value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control rounded-0" rows="8" name="message" required
                                placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600 rounded-0"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600 rounded-0 w-100px">{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bid Modal -->
    @if($detailedProduct->auction_product == 1)
        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $detailedProduct->starting_bid;
            $gst_rate = gst_applicable_product_rate($detailedProduct->id);
        @endphp
        <div class="modal fade" id="bid_for_detail_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('Bid For Product') }} <small>({{ translate('Min Bid Amount: ').$min_bid_amount }})</small> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ route('auction_product_bids.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                            <div class="form-group">
                                <label class="form-label">
                                    {{translate('Place Bid Price')}}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group">
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="amount" min="{{ $min_bid_amount }}" placeholder="{{ translate('Enter Amount') }}" required>
                                    @if($gst_rate != null)
                                        <small class="text-danger">{{ translate('An') }} {{ $gst_rate }}% {{ translate('GST will be applied if you win the bid and proceed with the purchase') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>

    <!-- Size chart show Modal -->
    @include('modals.size_chart_show_modal')

    <!-- Product Warranty Modal -->
    <div class="modal fade" id="warranty-note-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Warranty Note') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    @if($detailedProduct->warranty_note_id != null)
                        <p>{{ $detailedProduct->warrantyNote->getTranslation('description') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product Refund Modal -->
    <div class="modal fade" id="refund-note-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Refund Note') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    @if($detailedProduct->refund_note_id != null)
                        <p>{{ $detailedProduct->refundNote->getTranslation('description') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        // Pagination using ajax
        $(window).on('hashchange', function() {
            if(window.history.pushState) {
                window.history.pushState('', '/', window.location.pathname);
            } else {
                window.location.hash = '';
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.product-queries-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'query', 'queries-area');
                e.preventDefault();
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.product-reviews-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'review', 'reviews-area');
                e.preventDefault();
            });
        });

        function getPaginateData(page, type, section) {
            $.ajax({
                url: '?page=' + page,
                dataType: 'json',
                data: {type: type},
            }).done(function(data) {
                $('.'+section).html(data);
                location.hash = page;
            }).fail(function() {
                alert('Something went worng! Data could not be loaded.');
            });
        }
        // Pagination end

        function showImage(photo) {
            $('#image_modal img').attr('src', photo);
            $('#image_modal img').attr('data-src', photo);
            $('#image_modal').modal('show');
        }

        function bid_modal(){
            @if (isCustomer() || isSeller())
                $('#bid_for_detail_product').modal('show');
          	@elseif (isAdmin())
                AIZ.plugins.notify('warning', '{{ translate("Sorry, Only customers & Sellers can Bid.") }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function product_review(product_id,order_id) {
            @if (isCustomer())
                @if ($review_status == 1)
                    $.post('{{ route('product_review_modal') }}', {
                        _token: '{{ @csrf_token() }}',
                        product_id: product_id,
                        order_id: order_id
                    }, function(data) {
                        $('#product-review-modal-content').html(data);
                        $('#product-review-modal').modal('show', {
                            backdrop: 'static'
                        });
                        AIZ.extra.inputRating();
                    });
                @else
                    AIZ.plugins.notify('warning', '{{ translate("Sorry, You need to buy this product to give review.") }}');
                @endif
            @elseif (Auth::check() && !isCustomer())
                AIZ.plugins.notify('warning', '{{ translate("Sorry, Only customers can give review.") }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function showSizeChartDetail(id, name){
            $('#size-chart-show-modal .modal-title').html('');
            $('#size-chart-show-modal .modal-body').html('');
            if (id == 0) {
                AIZ.plugins.notify('warning', '{{ translate("Sorry, There is no size guide found for this product.") }}');
                return false;
            }
            $.ajax({
                type: "GET",
                url: "{{ route('size-charts-show', '') }}/"+id,
                data: {},
                success: function(data) {
                    $('#size-chart-show-modal .modal-title').html(name);
                    $('#size-chart-show-modal .modal-body').html(data);
                    $('#size-chart-show-modal').modal('show');
                }
            });
        }

        function getRandomNumber(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function updateViewerCount() {
            const countElement = document.querySelector('#live-product-viewing-visitors .count');
            const min = parseInt(`{{ get_setting('min_custom_product_visitors') }}`);
            const max = parseInt(`{{ get_setting('max_custom_product_visitors') }}`);
            const randomNumber = getRandomNumber(min, max);
            countElement.textContent = randomNumber;
            const randomTime = getRandomNumber(5000, 10000);
            setTimeout(updateViewerCount, randomTime);
        }

    </script>
    @if(get_setting('show_custom_product_visitors')==1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updateViewerCount();
        });
    </script>
    @endif

@endsection
