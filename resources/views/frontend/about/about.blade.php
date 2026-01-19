@extends('frontend.layouts.app')

@section('meta_title', 'About Us')


@php
    use App\Models\AboutUs;

    $hero = AboutUs::where('key', 'hero')->first();
    $mission = AboutUs::where('key', 'mission')->first();
    $vision = AboutUs::where('key', 'vision')->first();
@endphp

<style>
    /* ========================
       WHO WE ARE PAGE STYLE
    ======================== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .who-we-are-page {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        min-height: 100vh;
        padding: 100px 0;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        margin-top: 60px;
    }

    /* Hero Section */
    .who-hero {
        text-align: center;
        margin-bottom: 100px;
        position: relative;
    }

    @media (min-width: 1500px) {

        .container,
        .container-xxl {
            max-width: 1520px !important;
        }
    }

    .imagetwosection {
        background-image: url("{{ asset('assets/img/about/ac70617a702d56c2d3485fb65532dbcabb5a30a8.png') }}");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: left center;
        position: relative;
    }

    @media (min-width: 1200px) {
        .imagetwosection {
            background-size: auto 100%;
            background-position: left center;
        }
    }

    @media (max-width: 768px) {
        .imagetwosection {
            background-size: contain;
            background-position: center top;
        }
    }

    .who-hero h1 {
        font-size: 56px;
        font-weight: 800;
        color: #0a0a0a;
        margin-bottom: 24px;
        letter-spacing: -1px;
        line-height: 1.2;
    }

    .who-hero-subtitle {
        font-size: 18px;
        line-height: 1.8;
        color: #64748b;
        max-width: 850px;
        margin: 0 auto 70px;
        padding: 0 20px;
        font-weight: 400;
    }

    /* Trapezoid Container - Enhanced with Floating Effect */
    .trapezoid-container {
        position: relative;
        max-width: 1000px;
        margin: 0 auto;
        padding: 80px 40px;
        perspective: 1500px;
    }

    .trapezoid-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: floatingImage 6s ease-in-out infinite;
    }

    @keyframes floatingImage {

        0%,
        100% {
            transform: translateY(0px) rotateX(2deg);
        }

        50% {
            transform: translateY(-20px) rotateX(-2deg);
        }
    }

    .hero-illustration {
        width: 100%;
        max-width: 800px;
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 25px 50px rgba(95, 110, 246, 0.3)) drop-shadow(0 15px 30px rgba(0, 0, 0, 0.15));
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 20px;
        transform-style: preserve-3d;
    }

    .hero-illustration:hover {
        transform: scale(1.05) translateY(-10px);
        filter: drop-shadow(0 35px 70px rgba(95, 110, 246, 0.4)) drop-shadow(0 20px 40px rgba(0, 0, 0, 0.2));
    }

    /* Decorative Elements for Floating Effect */
    .trapezoid-content::before {
        content: '';
        position: absolute;
        width: 120%;
        height: 120%;
        background: radial-gradient(circle, rgba(95, 110, 246, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        z-index: -1;
        animation: pulseGlow 4s ease-in-out infinite;
    }

    @keyframes pulseGlow {

        0%,
        100% {
            transform: scale(0.95);
            opacity: 0.5;
        }

        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }
    }

    .trapezoid-content::after {
        content: '';
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        width: 70%;
        height: 30px;
        background: radial-gradient(ellipse, rgba(0, 0, 0, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        filter: blur(20px);
        animation: shadowPulse 6s ease-in-out infinite;
    }

    @keyframes shadowPulse {

        0%,
        100% {
            transform: translateX(-50%) scale(0.9);
            opacity: 0.4;
        }

        50% {
            transform: translateX(-50%) scale(1.1);
            opacity: 0.6;
        }
    }

    /* Mission Section */
    .mission-section {
        margin-bottom: 100px;
    }

    .mission-container {
        position: relative;
        max-width: 1100px;
        margin: 0 auto;
        padding: 60px 80px;
    }

    .mission-border {
        position: absolute;
    }

    .mission-border::before {
        content: '';
        position: absolute;
        inset: 2px;
        background: rgba(255, 255, 255, 0.95);
        clip-path: polygon(0% 0%, 100% 0%, 88% 100%, 12% 100%);
        backdrop-filter: blur(10px);
    }

    .mission-border::after {
        content: '';
        position: absolute;
        inset: 0;
        border: 2px solid rgba(95, 123, 246, 0.3);
        clip-path: polygon(0% 0%, 100% 0%, 88% 100%, 12% 100%);
        box-shadow: 0 8px 32px rgba(95, 95, 246, 0.12);
        border-radius: 16px;
    }

    .mission-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 200px;
    }

    .mission-text {
        flex: 1;
    }

    .mission-text h2 {
        font-size: 38px;
        font-weight: 700;
        color: #0a0a0a;
        margin-bottom: 0;
        line-height: 1.3;
        letter-spacing: -0.5px;
    }

    .mission-description {
        flex: 1;
    }

    .mission-description p {
        font-size: 17px;
        line-height: 1.9;
        color: #64748b;
        margin: 0;
        font-weight: 400;
    }

    /* Vision Section */
    .vision-section {
        max-width: 1100px;
        margin: 0 auto 100px;
        display: flex;
        align-items: center;
        gap: 80px;
        padding: 0 40px;
    }

    .vision-images {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    .vision-image-item {
        border-radius: 24px;
        overflow: hidden;
        aspect-ratio: 1;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    /* Zigzag Effect - الصور الزوجية (العمود الثاني) نازلة */
    .vision-image-item:nth-child(even) {
        transform: translateY(40px);
    }

    .vision-image-item::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(95, 110, 246, 0.2) 0%, rgba(59, 65, 232, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 1;
    }

    .vision-image-item:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(95, 115, 246, 0.25);
    }

    /* تعديل hover للعناصر الزوجية علشان تحافظ على التأثير */
    .vision-image-item:nth-child(even):hover {
        transform: translateY(32px) scale(1.02);
    }

    .vision-image-item:hover::before {
        opacity: 1;
    }

    .vision-image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .vision-content {
        flex: 1;
    }

    .vision-content h2 {
        font-size: 38px;
        font-weight: 700;
        color: #0a0a0a;
        margin-bottom: 24px;
        letter-spacing: -0.5px;
    }

    .vision-content p {
        font-size: 17px;
        line-height: 1.9;
        color: #64748b;
        margin: 0;
        font-weight: 400;
    }

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

    .category-card-new .cart-icon {
        position: absolute;
        top: clamp(12px, 2vw, 20px);
        left: clamp(12px, 2vw, 20px);
        width: clamp(36px, 5vw, 42px);
        height: clamp(36px, 5vw, 42px);
        background-color: rgb(0, 0, 0);
        border-radius: 50px;
        display: flex;
        color: white;
        align-items: center;
        justify-content: center;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .category-card-new:hover .cart-icon {
        background-color: white;
        color: #000;
        transform: scale(1.1);
    }

    .category-card-new .cart-icon svg {
        width: clamp(18px, 2.5vw, 22px);
        height: clamp(18px, 2.5vw, 22px);
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

    /* Responsive */
    @media (max-width: 992px) {
        .who-we-are-page {
            padding: 80px 0;
        }

        .who-hero h1 {
            font-size: 44px;
        }

        .trapezoid-container {
            padding: 60px 40px;
        }

        .hero-illustration {
            max-width: 700px;
        }

        .mission-container {
            padding: 50px 50px;
        }

        .mission-content {
            flex-direction: column;
            gap: 35px;
            text-align: center;
        }

        .vision-section {
            flex-direction: column;
            gap: 50px;
        }

        .vision-content {
            text-align: center;
        }

        .category-card-new {
            flex: 0 0 calc(50% - 13px);
        }
    }

    @media (max-width: 768px) {
        .who-we-are-page {
            padding: 60px 0;
        }

        .who-hero {
            margin-bottom: 70px;
        }

        .who-hero h1 {
            font-size: 38px;
        }

        .who-hero-subtitle {
            font-size: 16px;
        }

        .trapezoid-container {
            padding: 50px 20px;
        }

        .hero-illustration {
            max-width: 100%;
        }

        .mission-container {
            padding: 40px 30px;
        }

        .mission-border,
        .mission-border::before,
        .mission-border::after {
            clip-path: polygon(0% 0%, 100% 0%, 92% 100%, 8% 100%);
        }

        .mission-text h2,
        .vision-content h2 {
            font-size: 32px;
        }

        .vision-images {
            gap: 18px;
        }

        .vision-section {
            gap: 40px;
        }

        .category-card-new {
            flex: 0 0 100%;
        }
    }

    @media (max-width: 480px) {
        .who-hero h1 {
            font-size: 32px;
        }

        .who-hero-subtitle {
            font-size: 15px;
            margin-bottom: 50px;
        }

        .trapezoid-container {
            padding: 40px 15px;
        }

        .mission-container {
            padding: 35px 20px;
        }

        .mission-text h2,
        .vision-content h2 {
            font-size: 26px;
        }

        .mission-description p,
        .vision-content p {
            font-size: 15px;
        }

        .vision-section {
            padding: 0 20px;
        }

        .vision-images {
            gap: 12px;
        }

        .vision-image-item {
            border-radius: 18px;
        }
    }
</style>

@section('content')
    <div class="who-we-are-page">
        <div class="container">
            <div class="imagetwosection">
                {{-- Hero Section --}}
                <div class="who-hero">
                    <h1>{{ $hero && isset($hero->value['title']) ? $hero->value['title'] : 'Who We Are' }}</h1>

                    <p class="who-hero-subtitle">
                        {{ $hero && isset($hero->value['subtitle']) ? $hero->value['subtitle'] : 'Trades Axis was founded as an extension of our sister distribution business, which has been thriving in the regional market since 20XX. Our evolution from the import-export field reflects our ongoing commitment to expand our value chain and deliver excellence in every market we serve.' }}
                    </p>

                    {{-- Trapezoid Container with Floating Illustration --}}
                    <div class="trapezoid-container">
                        <div class="trapezoid-content">
                            @if ($hero && isset($hero->value['image']) && $hero->value['image'])
                                <img src="{{ asset($hero->value['image']) }}" alt="Who We Are" class="hero-illustration"
                                    onerror="this.src='https://via.placeholder.com/800x500?text=Who+We+Are+Illustration'">
                            @else
                                <img src="{{ asset('assets/img/about/60e4d4135fac5d6e76b09f98b4a872dfa0909dfb.png') }}"
                                    alt="Who We Are" class="hero-illustration"
                                    onerror="this.src='https://via.placeholder.com/800x500?text=Who+We+Are+Illustration'">
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Mission Section --}}
                <div class="mission-section">
                    <div class="mission-container">
                        <div class="mission-border"></div>
                        <div class="mission-content">
                            <div class="mission-text">
                                <h2>{{ $mission && isset($mission->value['title']) ? $mission->value['title'] : 'We are here to complete a certain mission' }}
                                </h2>
                            </div>
                            <div class="mission-description">
                                <p>
                                    {{ $mission && isset($mission->value['description']) ? $mission->value['description'] : 'To connect global markets efficiently and ethically by providing exceptional sourcing and trade solutions that enhance business value and foster sustainable growth.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vision Section --}}
            <div class="vision-section">
                <div class="vision-images">
                    @if (
                        $vision &&
                            isset($vision->value['images']) &&
                            is_array($vision->value['images']) &&
                            count($vision->value['images']) > 0)
                        @foreach ($vision->value['images'] as $index => $image)
                            <div class="vision-image-item">
                                @if ($image)
                                    <img src="{{ asset($image) }}" alt="Team Member {{ $index + 1 }}"
                                        onerror="this.src='https://via.placeholder.com/300x300?text=Team+{{ $index + 1 }}'">
                                @else
                                    <img src="{{ static_asset('assets/img/vision/team' . ($index + 1) . '.jpg') }}"
                                        alt="Team Member {{ $index + 1 }}"
                                        onerror="this.src='https://via.placeholder.com/300x300?text=Team+{{ $index + 1 }}'">
                                @endif
                            </div>
                        @endforeach
                    @else
                        {{-- Default 4 images if no data --}}
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="vision-image-item">
                                <img src="{{ static_asset('assets/img/vision/team' . $i . '.jpg') }}"
                                    alt="Team Member {{ $i }}"
                                    onerror="this.src='https://via.placeholder.com/300x300?text=Team+{{ $i }}'">
                            </div>
                        @endfor
                    @endif
                </div>

                <div class="vision-content">
                    <h2>{{ $vision && isset($vision->value['title']) ? $vision->value['title'] : 'Our Vision' }}</h2>
                    <p>
                        {{ $vision && isset($vision->value['description']) ? $vision->value['description'] : 'To become a trusted international trading platform between suppliers and buyers, recognized for our reliability, precision, and customer-centric approach.' }}
                    </p>
                </div>
            </div>

        </div>

        {{-- Categories Section --}}
        <section class="categories-section-new">
            <div class="container">
                <div class="section-header">
                    <button class="nav-btn" id="categoriesPrevBtn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M15 18l-6-6 6-6" />
                        </svg>
                    </button>
                    <h2>Our Categories</h2>
                    <button class="nav-btn" id="categoriesNextBtn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
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
                                        $categoryImage =
                                            'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800';
                                    } elseif (
                                        str_contains($lowerName, 'beverage') ||
                                        str_contains($lowerName, 'juice')
                                    ) {
                                        $categoryImage =
                                            'https://images.unsplash.com/photo-1610970881699-44a5587cabec?w=800';
                                    } elseif (str_contains($lowerName, 'frozen')) {
                                        $categoryImage =
                                            'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800';
                                    } else {
                                        $categoryImage =
                                            'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800';
                                    }
                                }

                                $categoryUrl = route('products.category', $category->slug);
                            @endphp

                            <a href="{{ $categoryUrl }}" class="category-card-new">
                                <div class="cart-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="9" cy="21" r="1" />
                                        <circle cx="20" cy="21" r="1" />
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                                    </svg>
                                </div>
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
    </div>

    {{-- JavaScript for Categories Slider --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('categoriesWrapperNew');
            const prevBtn = document.getElementById('categoriesPrevBtn');
            const nextBtn = document.getElementById('categoriesNextBtn');

            if (!wrapper || !prevBtn || !nextBtn) return;

            let currentIndex = 0;
            const cards = wrapper.querySelectorAll('.category-card-new');
            const totalCards = cards.length;

            // Get number of visible cards based on screen width
            function getVisibleCards() {
                if (window.innerWidth <= 768) return 1;
                if (window.innerWidth <= 992) return 2;
                return 3;
            }

            function updateSlider() {
                const visibleCards = getVisibleCards();
                const maxIndex = Math.max(0, totalCards - visibleCards);
                currentIndex = Math.min(currentIndex, maxIndex);

                const cardWidth = cards[0].offsetWidth;
                const gap = parseInt(getComputedStyle(wrapper).gap) || 25;
                const offset = currentIndex * (cardWidth + gap);

                wrapper.style.transform = `translateX(-${offset}px)`;

                // Update button states
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= maxIndex;

                prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
            }

            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            });

            nextBtn.addEventListener('click', function() {
                const visibleCards = getVisibleCards();
                const maxIndex = totalCards - visibleCards;
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateSlider();
                }
            });

            // Reset on window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    updateSlider();
                }, 250);
            });

            // Initialize
            updateSlider();
        });
    </script>
@endsection
