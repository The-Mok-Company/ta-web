{{-- Our Categories (slider + add to inquiry) --}}

<style>
    /* Our Categories Section - New Design */
    .categories-section-new {
        padding: clamp(40px, 8vw, 80px) 0;
        background-color: #ffffff;
        position: relative;
    }

    .categories-section-new .section-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: clamp(15px, 3vw, 30px);
        margin-bottom: clamp(30px, 5vw, 50px);
        flex-wrap: wrap;
        padding: 0 15px;
    }

    .categories-section-new .section-header h2 {
        font-size: clamp(1.5rem, 4vw, 2rem);
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        text-align: center;
    }

    .categories-section-new .nav-btn {
        width: clamp(40px, 5vw, 45px);
        height: clamp(40px, 5vw, 45px);
        border-radius: 50%;
        border: none;
        background-color: white;
        color: #007bff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .categories-section-new .nav-btn:hover {
        background-color: #007bff;
        color: white;
        transform: scale(1.05);
    }

    .categories-slider-new {
        position: relative;
        overflow: hidden;
        padding: 0 15px;
    }

    .categories-wrapper-new {
        display: flex;
        gap: clamp(15px, 2vw, 25px);
        transition: transform 0.5s ease;
        padding: 10px 0;
    }

    .category-card-new {
        flex: 0 0 calc(33.333% - 17px);
        position: relative;
        border-radius: clamp(12px, 2vw, 20px);
        overflow: hidden;
        height: clamp(200px, 30vw, 280px);
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
    }

    .category-card-new:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .category-card-new img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .category-card-new:hover img {
        transform: scale(1.08);
    }

    .category-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.5));
        z-index: 1;
    }

    .category-card-new .content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: clamp(15px, 3vw, 25px);
        z-index: 2;
        color: white;
    }

    .category-card-new .content h3 {
        font-size: clamp(1.2rem, 3vw, 1.6rem);
        font-weight: 700;
        margin: 0;
    }

    /* ===========================
       Category Add Button (+ -> ✓)
    =========================== */

    .category-card-new {
        position: relative;
    }

    .category-card-new .category-add-btn {
        position: absolute;
        top: clamp(12px, 2vw, 20px);
        right: clamp(12px, 2vw, 20px);

        width: clamp(36px, 5vw, 42px);
        height: clamp(36px, 5vw, 42px);
        border-radius: 50%;

        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);;
        border: none;
        color: #fff;

        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 0;

        z-index: 5;
        cursor: pointer;

        opacity: 0;
        transform: scale(.85);
        transition: opacity .3s ease, transform .3s ease, background .3s ease, box-shadow .3s ease, width .3s ease, border-radius .3s ease, gap .3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .20);
        overflow: hidden;
        white-space: nowrap;
    }

    @media (hover:hover) and (pointer:fine) {
        .category-card-new:hover .category-add-btn {
            opacity: 1;
            transform: scale(1);
        }

        .category-card-new:hover .category-add-btn:hover {
            width: auto;
            padding: 0 16px;
            border-radius: 25px;
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);;
            transform: scale(1.05);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .25);
            gap: 6px;
        }

        .category-card-new:hover .category-add-btn:hover .btn-text {
            opacity: 1;
            max-width: 150px;
        }
    }

    @media (max-width: 768px) {
        .category-card-new .category-add-btn {
            opacity: 1;
            transform: scale(1);
        }
    }

    .category-card-new .category-add-btn:active {
        transform: scale(.95);
    }

    .category-card-new .category-add-btn:focus-visible {
        box-shadow: 0 0 0 4px rgba(8, 145, 178, .25), 0 2px 10px rgba(0, 0, 0, .20);
    }

    .category-card-new .category-add-btn .btn-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        width: 25px;
        height: 25px;
        flex-shrink: 0;
    }

    .category-card-new .category-add-btn .btn-plus,
    .category-card-new .category-add-btn .btn-tick {
        position: absolute;
        transition: opacity .18s ease, transform .18s ease;
        pointer-events: none;
        font-size: 25px;
        line-height: 1;
    }

    .category-card-new .category-add-btn .btn-tick {
        font-size: 18px;
        font-weight: 900;
        opacity: 0;
        transform: scale(.85);
    }

    .category-card-new .category-add-btn .btn-text {
        font-size: 13px;
        font-weight: 600;
        opacity: 0;
        max-width: 0;
        overflow: hidden;
        transition: opacity .3s ease, max-width .3s ease;
        flex-shrink: 0;
    }

    .category-card-new .category-add-btn.added .btn-tick {
        opacity: 1 !important;
        transform: scale(1) !important;
    }

    /* loading */
    .category-card-new .category-add-btn.is-loading {
        opacity: .9 !important;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* added */
    .category-card-new .category-add-btn.added {
        background: #16a34a;
        opacity: 1 !important;
        pointer-events: none;
        animation: inquiryPulse .35s ease-out 1;
    }

    @keyframes inquiryPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.12); }
        100% { transform: scale(1); }
    }

    .category-card-new .category-add-btn.added .btn-plus {
        opacity: 0 !important;
        transform: scale(.7) !important;
    }

    /* Responsive bits for this component only */
    @media (max-width: 1024px) {
        .category-card-new {
            flex: 0 0 calc(50% - 12px);
        }
    }

    @media (max-width: 768px) {
        .categories-section-new .section-header {
            flex-direction: row;
            gap: 15px;
        }

        .category-card-new {
            flex: 0 0 calc(50% - 10px);
            height: clamp(180px, 35vw, 220px);
        }

        .category-card-new .category-add-btn .btn-text {
            display: none;
        }
    }

    @media (max-width: 640px) {
        .categories-section-new {
            padding: 30px 0;
        }

        .category-card-new {
            flex: 0 0 100%;
            height: clamp(220px, 50vw, 280px);
        }

        .categories-wrapper-new {
            gap: 15px;
        }
    }

    @media (max-width: 480px) {
        .categories-section-new .nav-btn {
            width: 36px;
            height: 36px;
        }

        .categories-slider-new {
            padding: 0 10px;
        }
    }

    @media (max-width: 360px) {
        .categories-section-new .section-header h2 {
            font-size: 1.3rem;
        }

        .category-card-new .content h3 {
            font-size: 1.1rem;
        }
    }
</style>

<section class="categories-section-new">
    <div class="container">
        <div class="section-header">
            <button class="nav-btn" id="categoriesPrevBtn" type="button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>

            <h2>Our Categories</h2>

            <button class="nav-btn" id="categoriesNextBtn" type="button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>
        </div>

        <div class="categories-slider-new">
            <div class="categories-wrapper-new" id="categoriesWrapperNew">
                @foreach ($categories as $category)
                    @php
                        $categoryName = $category->getTranslation('name', $lang);

                        $categoryImage = null;
                        if ($category->banner) {
                            $categoryImage = uploaded_asset($category->banner);
                        } elseif ($category->cover_image) {
                            $categoryImage = uploaded_asset($category->cover_image);
                        }

                        if (!$categoryImage) {
                            $lowerName = strtolower($categoryName);
                            if (str_contains($lowerName, 'bakery') || str_contains($lowerName, 'bread')) {
                                $categoryImage = 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800';
                            } elseif (str_contains($lowerName, 'beverage') || str_contains($lowerName, 'juice')) {
                                $categoryImage = 'https://images.unsplash.com/photo-1610970881699-44a5587cabec?w=800';
                            } elseif (str_contains($lowerName, 'frozen')) {
                                $categoryImage = 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800';
                            } else {
                                $categoryImage = 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800';
                            }
                        }

                        $categoryUrl = route('products.category', $category->slug);
                    @endphp

                    <a href="{{ $categoryUrl }}" class="category-card-new">
                        <button type="button" class="category-add-btn js-add-category" data-id="{{ $category->id }}"
                            data-name="{{ $categoryName }}" title="{{ translate('Add to Inquiry') }}"
                            aria-label="Add to Inquiry">
                            <div class="btn-icon-wrapper">
                                <span class="btn-plus">+</span>
                                <span class="btn-tick">✓</span>
                            </div>
                            <span class="btn-text">{{ translate('Add to Inquiry') }}</span>
                        </button>

                        <img src="{{ $categoryImage }}" alt="{{ $categoryName }}"
                            onerror="this.src='https://images.unsplash.com/photo-1542838132-92c53300491e?w=800'">

                        <div class="content">
                            <h3>{{ $categoryName }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ==========================================
        // Categories Slider Navigation
        // ==========================================
        const categoriesWrapper = document.getElementById('categoriesWrapperNew');
        const categoriesPrevBtn = document.getElementById('categoriesPrevBtn');
        const categoriesNextBtn = document.getElementById('categoriesNextBtn');

        if (categoriesWrapper && categoriesPrevBtn && categoriesNextBtn) {
            function getStep() {
                const firstCard = categoriesWrapper.querySelector('.category-card-new');
                if (!firstCard) return 260;
                const cardWidth = firstCard.getBoundingClientRect().width;
                const styles = window.getComputedStyle(categoriesWrapper);
                const gap = parseFloat(styles.columnGap || styles.gap || 18) || 18;
                return cardWidth + gap;
            }

            let currentX = 0;

            function getMaxTranslate() {
                const parent = categoriesWrapper.parentElement;
                const parentWidth = parent.getBoundingClientRect().width;
                const contentWidth = categoriesWrapper.scrollWidth;
                return Math.max(0, contentWidth - parentWidth);
            }

            function apply() {
                categoriesWrapper.style.transform = `translateX(${-currentX}px)`;
            }

            function clamp() {
                const max = getMaxTranslate();
                if (currentX < 0) currentX = 0;
                if (currentX > max) currentX = max;
            }

            categoriesNextBtn.addEventListener('click', function() {
                currentX += getStep();
                clamp();
                apply();
            });

            categoriesPrevBtn.addEventListener('click', function() {
                currentX -= getStep();
                clamp();
                apply();
            });

            window.addEventListener('resize', function() {
                clamp();
                apply();
            });
        }

        // ==========================================
        // Add Category to Cart (AJAX)
        // ==========================================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.js-add-category');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            const categoryId = btn.getAttribute('data-id');
            const categoryName = btn.getAttribute('data-name') || 'Category';

            addCategoryToCart(categoryId, categoryName);
        });

        function addCategoryToCart(categoryId, categoryName) {
            $.ajax({
                type: "POST",
                url: '{{ route('cart.addCategoryToCart') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: categoryId
                },
                success: function(data) {
                    if (data && data.status == 1) {
                        // update cart count
                        if (data.cart_count !== undefined) {
                            const c = (data.cart_count === undefined || data.cart_count === null) ? 0 : data.cart_count;
                            $('.cart-count').html(c).attr('data-count', c);
                        }
                        if (typeof flashHeaderCartSuccess === 'function') {
                            flashHeaderCartSuccess();
                        }

                        // switch button to "added" state (+ -> ✓)
                        const btn = document.querySelector('.js-add-category[data-id="' + categoryId + '"]');
                        if (btn) {
                            btn.classList.add('added');
                        }

                        // notifications
                        if (data.message === 'Category already in cart') {
                            AIZ.plugins.notify('warning', categoryName +
                                " {{ translate('is already in cart') }}");
                        } else {
                            AIZ.plugins.notify('success', categoryName +
                                " {{ translate('added to cart successfully') }}");
                        }
                    } else {
                        AIZ.plugins.notify('danger', (data && data.message) ? data.message :
                            "{{ translate('Something went wrong') }}");
                    }
                },
                error: function() {
                    AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
                }
            });
        }
    });
</script>
