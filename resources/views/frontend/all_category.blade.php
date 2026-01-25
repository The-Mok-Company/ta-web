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

        /* ================= HERO (left-aligned at bottom of banner, same colors as category-hero) ================= */
        .categories-hero {
            position: relative;
            height: 450px;
            padding: 20px;
            padding-bottom: 50px;
            margin-bottom: 60px;
            background-image: url("{{ asset('assets/img/eaf877854196422d963fe04e58d086e83a98ac67.png') }}");
            background-size: cover;
            background-position: center;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
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
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px;
        }

        .categories-hero h1 {
            color: #5fb3f6;
            font-size: 46px;
            font-weight: 700;
            margin: 0 0 5px 0;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.9);
            line-height: 1.2;
        }

        .categories-hero .subtitle {
            color: #fff;
            font-size: 52px;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.9);
            line-height: 1.2;
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

        /* ================= ADD BUTTON (matches featured-inquiry-btn: colors, circle, hover tooltip) ================= */
        .add-inquiry-wrap {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 5;
        }

        .add-inquiry-btn {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: #fff;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.25);
            transition: box-shadow .2s ease, background .2s ease, color .2s ease;
            position: relative;
            overflow: visible;
            flex-shrink: 0;
            padding: 0;
        }

        .add-inquiry-btn:hover {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            box-shadow: 0 3px 10px rgba(33, 150, 243, 0.35);
        }

        .add-inquiry-btn .icon {
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            line-height: 1;
            flex-shrink: 0;
        }

        .add-inquiry-btn .icon-check { display: none; }
        .add-inquiry-btn.added .icon-plus { display: none; }
        .add-inquiry-btn.added .icon-check { display: block; }
        .add-inquiry-btn.added .icon-check::before { content: "✓"; }

        .add-inquiry-btn .btn-text {
            position: absolute;
            top: 50%;
            right: calc(100% + 8px);
            transform: translateY(-50%) translateX(6px);
            opacity: 0;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1;
            white-space: nowrap;
            pointer-events: none;
            transition: opacity .18s ease, transform .18s ease;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: #fff;
            box-shadow: 0 10px 22px rgba(33, 150, 243, 0.35);
        }

        .add-inquiry-btn:hover .btn-text,
        .add-inquiry-btn:focus-visible .btn-text {
            opacity: 1;
            transform: translateY(-50%) translateX(0);
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        }

        .add-inquiry-btn.added .btn-text,
        .add-inquiry-btn.added:hover .btn-text {
            opacity: 0;
            visibility: hidden;
        }

        /* Added state */
        .add-inquiry-btn.added {
            background: #16a34a;
            cursor: default;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .25);
        }

        .add-inquiry-btn.added:hover {
            background: #16a34a;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .25);
        }

        /* Hero responsive (match category-hero breakpoints) */
        @media (min-width: 1400px) {
            .categories-hero { height: 500px; }
            .categories-hero h1 { font-size: 52px; }
            .categories-hero .subtitle { font-size: 60px; }
        }

        @media (max-width: 1024px) {
            .categories-hero { height: 380px; padding-bottom: 40px; }
            .categories-hero h1 { font-size: 38px; }
            .categories-hero .subtitle { font-size: 44px; }
        }

        @media (max-width: 768px) {
            .categories-hero { height: 320px; padding: 15px; padding-bottom: 35px; }
            .categories-hero h1 { font-size: 32px; }
            .categories-hero .subtitle { font-size: 36px; }
            .categories-hero-content { padding: 0 20px; }
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
