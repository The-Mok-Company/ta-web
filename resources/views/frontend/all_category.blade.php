@extends('frontend.layouts.app')

@section('meta_title', 'Main Categories')


@section('content')
    <style>
        .add-inquiry-btn .icon-check {
            display: none;
        }

        .add-inquiry-btn.added .icon-plus {
            display: none;
        }

        .add-inquiry-btn.added .icon-check {
            display: block;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
        }

        .add-inquiry-btn.added .icon-check::before {
            content: "✓";
        }

        /* ================= HERO ================= */
        .categories-hero {
            position: relative;
            padding: 140px 20px;
            margin-bottom: 60px;
            background-image: url("{{ asset('assets/img/eaf877854196422d963fe04e58d086e83a98ac67.png') }}");
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        .categories-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: #000;
            opacity: .7;
            z-index: 1;
        }

        .categories-hero>* {
            position: relative;
            z-index: 2;
        }

        .categories-hero-content {
            max-width: 1200px;
            margin: auto;
            padding: 0 30px;
        }

        .categories-hero h1 {
            color: #60a5fa;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, .5);
        }

        .categories-hero .subtitle {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, .5);
        }

        /* ================= GRID ================= */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            max-width: 1200px;
            margin: auto;
            padding: 0 30px 60px;
        }

        .category-card-wrap {
            position: relative;
        }

        .category-card {
            position: relative;
            display: block;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .25);
        }

        .category-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, .6) 100%);
            z-index: 1;
        }

        .category-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Big screens - min-height for category images */
        @media (min-width: 769px) {
            .category-image {
                min-height: 380px;
            }
        }

        .category-content {
            position: absolute;
            bottom: 20px;
            left: 20px;
            z-index: 2;
        }

        .category-name {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        /* ================= ADD BUTTON ================= */
        .add-inquiry-wrap {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 5;
        }

        .add-inquiry-btn {
            width: 40px;
            height: 40px;
            background: #0891B2;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .25);
            transition: all .3s ease;
            overflow: hidden;
            white-space: nowrap;
            padding: 0;
        }

        /* Icons and Text */
        .add-inquiry-btn .icon {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
            transition: all .3s ease;
            flex-shrink: 0;
        }

        .add-inquiry-btn .btn-text {
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            font-size: 13px;
            font-weight: 600;
            margin-left: 0;
            color: #fff;
            transition: all .3s ease;
        }

        /* Hover – expand button */
        .add-inquiry-btn:hover {
            width: auto;
            padding: 0 16px;
            background: #0E7490;
            transform: scale(1.05);
            box-shadow: 0 8px 22px rgba(8, 145, 178, .45), 0 0 0 4px rgba(8, 145, 178, .25);
        }

        .add-inquiry-btn:hover .btn-text {
            max-width: 150px;
            opacity: 1;
            margin-left: 8px;
        }

        .add-inquiry-btn:hover .icon {
            transform: scale(1.1);
        }

        /* Active (click feedback) */
        .add-inquiry-btn:active {
            transform: scale(0.95);
        }

        /* Added state */
        .add-inquiry-btn.added {
            background: #16a34a;
            cursor: default;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .25);
            width: 40px;
            padding: 0;
        }

        .add-inquiry-btn.added:hover {
            width: 40px;
            padding: 0;
            transform: scale(1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .25);
        }

        .add-inquiry-btn.added .btn-text {
            max-width: 0;
            opacity: 0;
            margin-left: 0;
        }

        .add-inquiry-btn.added .icon {
            font-size: 18px;
        }

        .add-inquiry-btn.added .icon::before {
            content: "✓";
        }

        /* Mobile */
        @media (max-width: 768px) {
            .categories-grid {
                grid-template-columns: 1fr;
                padding: 0 20px 40px;
            }
        }
    </style>

    <!-- HERO -->
    <section class="categories-hero">
        <div class="categories-hero-content">
            <h1>{{ translate('Explore') }}</h1>
            <p class="subtitle">{{ translate('Our Categories') }}</p>
        </div>
    </section>

    <!-- CATEGORIES -->
    <section>
        <div class="container">
            <div class="categories-grid">
                @foreach ($categories as $category)
                    <div class="category-card-wrap">

                        <a href="{{ route('products.category', $category->slug) }}" class="category-card">
                            <img src="{{ $category->banner ? uploaded_asset($category->banner) : asset('assets/img/eaf877854196422d963fe04e58d086e83a98ac67.png') }}"
                                class="category-image" alt="{{ $category->name }}">
                            <div class="category-content">
                                <h3 class="category-name">
                                    {{ $category->getTranslation('name') }}
                                </h3>
                            </div>
                        </a>

                        <!-- ADD BUTTON -->
                        <div class="add-inquiry-wrap">
                            <button type="button" class="add-inquiry-btn js-add-category" data-id="{{ $category->id }}"
                                data-name="{{ $category->getTranslation('name') }}"
                                title="{{ translate('Add to Inquiry') }}">
                                <span class="icon icon-plus">+</span>
                                <span class="icon icon-check"></span>
                                <span class="btn-text">Add to Inquiry</span>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).on('click', '.js-add-category', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $btn = $(this);
            if ($btn.hasClass('added') || $btn.data('loading') === 1) return;

            const categoryId = $btn.data('id');
            const categoryName = $btn.data('name');

            $btn.data('loading', 1);

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
                            const c = (data.cart_count === undefined || data.cart_count === null) ? 0 :
                                data.cart_count;
                            $('.cart-count').html(c).attr('data-count', c);
                        }
                        if (typeof flashHeaderCartSuccess === 'function') {
                            flashHeaderCartSuccess();
                        }

                        $btn.addClass('added').prop('disabled', true);

                        AIZ.plugins.notify(
                            'success',
                            categoryName + " {{ translate('added to inquiry') }}"
                        );
                    } else {
                        AIZ.plugins.notify(
                            'danger',
                            data?.message || "{{ translate('Something went wrong') }}"
                        );
                    }
                },
                error: function() {
                    AIZ.plugins.notify(
                        'danger',
                        "{{ translate('Something went wrong') }}"
                    );
                },
                complete: function() {
                    $btn.data('loading', 0);
                }
            });
        });
    </script>
@endsection
