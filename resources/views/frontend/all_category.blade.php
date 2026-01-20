@extends('frontend.layouts.app')

@section('content')
    <style>
        .categories-hero {
            position: relative;
            padding: 140px 20px;
            text-align: left;
            overflow: hidden;
            margin-bottom: 60px;
            background-image: url("{{ asset('assets/img/eaf877854196422d963fe04e58d086e83a98ac67.png') }}");
            background-size: cover;
            background-position: center;
        }

        /* Black overlay using opacity */
        .categories-hero::before {
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

        /* Keep content above overlay */
        .categories-hero>* {
            position: relative;
            z-index: 2;
        }

        .categories-hero-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px;
        }

        .categories-hero h1 {
            color: #60a5fa;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
            letter-spacing: 1px;
        }

        .categories-hero .subtitle {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
            margin: 0;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 60px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px;
        }

        .category-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            height: auto;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            display: block;
        }

        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.6) 100%);
            z-index: 1;
            transition: all 0.3s ease;
        }

        .category-card:hover::before {
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.5) 100%);
        }

        .category-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-image {
            transform: scale(1.03);
        }

        .category-content {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .category-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .icon-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .category-card:hover .icon-badge {
            transform: scale(1.05);
        }

        .icon-badge i {
            font-size: 18px;
            color: #000;
        }

        /* Add to Cart Button (same idea as your other page) */
        .category-add-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            background: #0891B2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            opacity: 0;
        }

        .category-card:hover .category-add-btn {
            opacity: 1;
        }

        .category-add-btn:hover {
            transform: scale(1.1);
            background: #0E7490;
        }

        .category-add-btn i {
            font-size: 18px;
            color: #fff;
        }

        @media (max-width: 768px) {
            .categories-hero {
                padding: 60px 20px;
            }

            .categories-hero h1 {
                font-size: 1.75rem;
            }

            .categories-hero .subtitle {
                font-size: 1.75rem;
            }

            .categories-grid {
                grid-template-columns: 1fr;
                padding: 0 20px;
            }

            .category-card {
                height: 180px;
            }

            .category-name {
                font-size: 1.1rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1025px) {
            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <!-- Hero Section -->
    <section class="categories-hero">
        <div class="categories-hero-content">
            <h1>{{ translate('Explore') }}</h1>
            <p class="subtitle">{{ translate('Our Categories') }}</p>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="pb-5">
        <div class="container">
            <div class="categories-grid">
                @foreach ($categories as $key => $category)
                    <a href="{{ route('products.category', $category->slug) }}" class="category-card">

                        <img src="{{ $category->banner ? uploaded_asset($category->banner) : asset('assets/img/eaf877854196422d963fe04e58d086e83a98ac67.png') }}"
                             class="category-image" alt="{{ $category->name }}">

                        <div class="icon-badge">
                            <i class="fas fa-shopping-basket"></i>
                        </div>

                        <div class="category-content">
                            <h3 class="category-name">{{ $category->getTranslation('name') }}</h3>
                        </div>

                        <!-- Add to Cart Button -->
                        <button type="button"
                                class="category-add-btn js-add-category"
                                data-id="{{ $category->id }}"
                                data-name="{{ $category->getTranslation('name') }}"
                                title="{{ translate('Add to Cart') }}">
                            <i class="las la-plus"></i>
                        </button>

                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $('.show-hide-cetegoty').on('click', function() {
            var el = $(this).siblings('ul');
            if (el.hasClass('less')) {
                el.removeClass('less');
                $(this).html('{{ translate('Less') }} <i class="las la-angle-up"></i>');
            } else {
                el.addClass('less');
                $(this).html('{{ translate('More') }} <i class="las la-angle-down"></i>');
            }
        });

        // prevent opening category page when clicking (+)
        $(document).on('click', '.js-add-category', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const categoryId = $(this).data('id');
            const categoryName = $(this).data('name');

            addCategoryToCart(categoryId, categoryName);
        });

        // Add Category to Cart (same as your other page)
        function addCategoryToCart(categoryId, categoryName) {
            $.ajax({
                type: "POST",
                url: '{{ route("cart.addCategoryToCart") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: categoryId
                },
                success: function(data) {
                    if (data && data.status == 1) {

                        if (data.cart_count !== undefined) {
                            $('.cart-count').html(data.cart_count);
                        }

                        if (data.message == 'Category already in cart') {
                            AIZ.plugins.notify('warning', categoryName + " {{ translate('is already in cart') }}");
                        } else {
                            AIZ.plugins.notify('success', categoryName + " {{ translate('added to cart successfully') }}");
                        }
                    } else {
                        AIZ.plugins.notify('danger', data.message || "{{ translate('Something went wrong') }}");
                    }
                },
                error: function(xhr) {
                    AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
                }
            });
        }
    </script>
@endsection
