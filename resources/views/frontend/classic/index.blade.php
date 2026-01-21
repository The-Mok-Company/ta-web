@extends('frontend.layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@section('meta_title', 'Home Page')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
        }

        .hero-carousel {
            position: relative;
            height: 100vh;
            overflow: hidden;
            background-color: #000;
        }

        .hero-carousel .carousel-item {
            height: 100vh;
            position: relative;
            background-color: #000;
        }

        .hero-carousel .carousel-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .hero-carousel img {
            object-fit: cover;
            height: 100%;
            width: 100%;
            background-color: #000;
        }

        .carousel-item {
            transition: transform 0.6s ease-in-out;
            background-color: #000;
        }

        .carousel-item.active,
        .carousel-item-next,
        .carousel-item-prev {
            display: block;
            background-color: #000;
        }

        .carousel-fade .carousel-item {
            opacity: 0;
            transition-property: opacity;
            transform: none;
        }

        .carousel-fade .carousel-item.active,
        .carousel-fade .carousel-item-next.carousel-item-start,
        .carousel-fade .carousel-item-prev.carousel-item-end {
            opacity: 1;
            z-index: 1;
        }

        .carousel-fade .active.carousel-item-start,
        .carousel-fade .active.carousel-item-end {
            opacity: 0;
            z-index: 0;
            transition: opacity 0s 0.6s;
        }

        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            text-align: center;
            color: white;
            width: 90%;
            max-width: 1200px;
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.1rem;
            opacity: 0.95;
            max-width: 800px;
            margin: 0 auto;
        }

        .carousel-indicators {
            bottom: 40px;
            z-index: 3;
            margin-bottom: 0;
        }

        .carousel-indicators [data-bs-target] {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
            background-color: transparent;
            opacity: 0.7;
            margin: 0 6px;
            padding: 0;
        }

        .carousel-indicators .active {
            background-color: white;
            opacity: 1;
        }

        /* Hero controls: fixed size + centered (override Bootstrap defaults) */
        #heroCarousel .carousel-control-prev,
        #heroCarousel .carousel-control-next {
            position: absolute;
            top: 50%;
            bottom: auto; /* override Bootstrap bottom:0 */
            transform: translateY(-50%);

            width: 51px;  /* override Bootstrap width:15% */
            height: 51px;
            padding: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            opacity: 0.85;
            transition: background-color 0.2s ease, opacity 0.2s ease, transform 0.2s ease;
            z-index: 3;
            border: none;
        }

        #heroCarousel .carousel-control-prev { left: 12px; }
        #heroCarousel .carousel-control-next { right: 12px; }

        /* Smaller screens: smaller buttons + icons */
        @media (max-width: 576px) {
            #heroCarousel .carousel-control-prev,
            #heroCarousel .carousel-control-next {
                width: 42px;
                height: 42px;
            }

            #heroCarousel .carousel-control-prev-icon,
            #heroCarousel .carousel-control-next-icon {
                width: 18px;
                height: 18px;
            }
        }

        @media (max-width: 360px) {
            #heroCarousel .carousel-control-prev,
            #heroCarousel .carousel-control-next {
                width: 36px;
                height: 36px;
            }

            #heroCarousel .carousel-control-prev-icon,
            #heroCarousel .carousel-control-next-icon {
                width: 16px;
                height: 16px;
            }
        }

        .hero-carousel:hover .carousel-control-prev,
        .hero-carousel:hover .carousel-control-next {
            opacity: 1;
        }

        /* Touch devices: always show controls (no hover) */
        @media (hover: none) and (pointer: coarse) {
            #heroCarousel .carousel-control-prev,
            #heroCarousel .carousel-control-next {
                opacity: 1;
            }
        }

        #heroCarousel .carousel-control-prev:hover,
        #heroCarousel .carousel-control-next:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 24px;
            height: 24px;
            background-size: 100% 100%;
        }


        /* Second Section Styles */
        .customers-section {
            padding: 100px 0 0 0;
            background-color: #ffffff;
            position: relative;
        }

        .customers-section .container {
            max-width: 1200px;
        }

        .customers-section .row {
            align-items: center;
        }

        .customers-section .image-wrapper {
            position: relative;
        }

        .customers-section .image-wrapper img {
            width: 100%;
            max-width: 450px;
            height: auto;
        }

        .customers-section .content-wrapper h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .customers-section .content-wrapper p {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .customers-section .about-link {
            display: inline-flex;
            align-items: center;
            color: var(--blue);
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .customers-section .about-link:hover {
            color: var(--hov-blue);
        }

        .customers-section .about-link svg {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .customers-section .about-link:hover svg {
            transform: translateX(5px);
        }

        .triangle-wrapper {
            width: 100%;
            margin: 0;
            padding: 0;
            display: block;
        }

        .triangle-wrapper img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0;
            padding: 0;
        }

        /* Categories Section */
        .categories-section {
            padding: 80px 0;
            background-color: #ffffff;
            position: relative;
        }

        .categories-section .section-header {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .categories-section .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            display: inline-block;
            position: relative;
        }

        .categories-section .nav-arrows {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 15px;
        }

        .categories-section .nav-arrows.left {
            left: 0;
        }

        .categories-section .nav-arrows.right {
            right: 0;
        }

        .categories-section .arrow-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: none;
            background-color: #f8f9fa;
            color: var(--blue);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .categories-section .arrow-btn:hover {
            background-color: var(--blue);
            color: white;
        }

        .categories-slider {
            position: relative;
            overflow: hidden;
        }

        .categories-wrapper {
            display: flex;
            gap: 25px;
            transition: transform 0.5s ease;
            padding: 10px 0;
        }

        .category-card {
            flex: 0 0 calc(33.333% - 17px);
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            height: 300px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .category-card:hover img {
            transform: scale(1.1);
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.6));
            z-index: 1;
        }

        .category-card .content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 25px;
            z-index: 2;
            color: white;
        }

        .category-card .content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .category-card .cart-icon {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 40px;
            height: 40px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
            display: flex;
            color: white;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .category-card:hover .cart-icon {
            background-color: white;
            color: #000;
            transform: scale(1.1);
        }

        .category-card .cart-icon svg {
            width: 20px;
            height: 20px;
        }

        /* Gather Quality Section */
        .gather-section {
            position: relative;
            padding: 0;
            margin: 0;
        }

        .gather-section .top-bar {
            background: linear-gradient(135deg, #1e4d7b 0%, #2d5f8d 100%);
            padding: 30px 0;
        }

        .gather-section .top-bar .container {
            max-width: 1200px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .gather-section .top-bar .left-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .gather-section .top-bar .icon-grid {
            display: grid;
            grid-template-columns: repeat(2, 30px);
            grid-template-rows: repeat(2, 30px);
            gap: 8px;
        }

        .gather-section .top-bar .icon-box {
            width: 30px;
            height: 30px;
            border: 3px solid white;
            border-radius: 8px;
        }

        .gather-section .top-bar .icon-box:nth-child(1),
        .gather-section .top-bar .icon-box:nth-child(4) {
            border-radius: 50%;
        }

        .gather-section .top-bar .text-content h3 {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .gather-section .top-bar .text-content p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.95rem;
            margin: 0;
            max-width: 450px;
        }

        .gather-section .top-bar .explore-btn {
            background-color: transparent;
            color: white;
            border: 2px solid white;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .gather-section .top-bar .explore-btn:hover {
            background-color: white;
            color: #1e4d7b;
        }

        .gather-section .image-section {
            position: relative;
            width: 100%;
            height: 600px;
            overflow: hidden;
        }

        .gather-section .image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gather-section .image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5));
            z-index: 1;
        }

        .gather-section .image-section .overlay-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            text-align: center;
            color: white;
            width: 90%;
            max-width: 900px;
        }

        .gather-section .image-section .overlay-text h2 {
            font-size: 4.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
        }

        @media (max-width: 992px) {
            .category-card {
                flex: 0 0 calc(50% - 13px);
            }

            .gather-section .top-bar .container {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .gather-section .top-bar .left-content {
                flex-direction: column;
            }

            .gather-section .image-section .overlay-text h2 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.0rem;
            }

            .hero-content p {
                font-size: 0.6rem;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 45px;
                height: 45px;
                opacity: 0.8;
            }

            .carousel-control-prev {
                left: 15px;
            }

            .carousel-control-next {
                right: 15px;
            }

            .carousel-indicators {
                bottom: 20px;
            }

            .carousel-indicators [data-bs-target] {
                width: 10px;
                height: 10px;
                margin: 0 4px;
            }

            .customers-section {
                padding: 60px 0 0 0;
            }

            .customers-section .content-wrapper h2 {
                font-size: 2rem;
            }

            .customers-section .content-wrapper p {
                font-size: 0.95rem;
            }

            .customers-section .image-wrapper {
                margin-bottom: 30px;
                text-align: center;
            }

            .categories-section .section-header h2 {
                font-size: 2rem;
            }

            .categories-section .nav-arrows {
                display: none;
            }

            .category-card {
                flex: 0 0 100%;
            }

            .categories-section {
                padding: 50px 0;
            }

            .gather-section .top-bar {
                padding: 20px 15px;
            }

            .gather-section .top-bar .text-content h3 {
                font-size: 1.2rem;
            }

            .gather-section .top-bar .text-content p {
                font-size: 0.85rem;
            }

            .gather-section .image-section {
                height: 300px;
            }

            .gather-section .image-section .overlay-text h2 {
                font-size: 2rem;
            }
        }
    </style>

    <style>
        /* Featured Products Section */
        .featured-products-section {
            padding: clamp(40px, 6vw, 60px) 0 clamp(50px, 8vw, 80px) 0;
            background-color: #ffffff;
        }

        .featured-products-section .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: clamp(25px, 4vw, 40px);
            padding: 0 15px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .featured-products-section .header-left {
            display: flex;
            align-items: center;
            gap: clamp(12px, 2vw, 20px);
            flex-wrap: wrap;
        }

        .featured-products-section .section-header h2 {
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .featured-products-section .nav-buttons {
            display: flex;
            gap: 12px;
        }

        .featured-products-section .nav-btn {
            width: clamp(36px, 5vw, 40px);
            height: clamp(36px, 5vw, 40px);
            border-radius: 50%;
            border: none;
            background-color: white;
            color: var(--blue);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            flex-shrink: 0;
        }

        .featured-products-section .nav-btn:hover {
            background-color: var(--blue);
            color: white;
            transform: scale(1.05);
        }

        .featured-products-section .view-all {
            color: var(--blue);
            text-decoration: none;
            font-weight: 500;
            font-size: clamp(0.85rem, 2vw, 0.95rem);
            transition: color 0.3s ease;
            white-space: nowrap;
        }

        .featured-products-section .view-all:hover {
            color: var(--hov-blue);
        }

        .products-slider {
            position: relative;
            overflow: hidden;
        }

        .products-wrapper {
            display: flex;
            gap: clamp(12px, 2vw, 20px);
            margin: 10px 15px;
            transition: transform 0.5s ease;
        }

        .product-card {
            flex: 0 0 calc(25% - 15px);
            background: white;
            border-radius: clamp(12px, 2vw, 15px);
            overflow: hidden;
            cursor: pointer;
            padding: clamp(12px, 2vw, 16px);
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .product-card .image-wrapper {
            position: relative;
            width: 100%;
            height: clamp(150px, 20vw, 200px);
            overflow: hidden;
        }

        .product-card .image-wrapper img {
            width: 100%;
            height: 100%;
            border-radius: clamp(8px, 1.5vw, 12px);
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .image-wrapper img {
            transform: scale(1.1);
        }

        .product-card .add-btn {
            position: absolute;
            bottom: clamp(10px, 1.5vw, 15px);
            right: clamp(10px, 1.5vw, 15px);
            width: clamp(32px, 4vw, 36px);
            height: clamp(32px, 4vw, 36px);
            background-color: #1a1a1a;
            border-radius: 18px;
            border: none;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            white-space: nowrap;
            padding: 0;
            z-index: 2;
            transition: width 0.4s ease,
                padding 0.4s ease,
                background-color 0.3s ease;
        }

        .product-card .add-btn svg {
            width: clamp(16px, 2vw, 18px);
            height: clamp(16px, 2vw, 18px);
            flex-shrink: 0;
        }

        .product-card .add-btn .btn-text {
            font-size: clamp(0.8rem, 1.5vw, 0.9rem);
            font-weight: 500;
            opacity: 0;
            max-width: 0;
            margin-left: 0;
            transform: translateX(-10px);
            transition: all 0.3s ease;
        }

        .product-card .add-btn:hover {
            background-color: #000000;
            width: auto;
            padding: 0 16px 0 12px;
        }

        .product-card .add-btn:hover .btn-text {
            opacity: 1;
            max-width: 150px;
            margin-left: 8px;
            transform: translateX(0);
        }

        .product-card .product-info {
            padding: clamp(12px, 2.5vw, 20px);
            max-height: clamp(60px, 8vw, 70px);
        }

        .product-card .product-info h3 {
            font-size: clamp(0.95rem, 2vw, 1.1rem);
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-card .product-info p {
            font-size: clamp(0.65rem, 1.2vw, 0.7rem);
            color: #6c757d;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-info {
            cursor: pointer;
        }

        .product-info:hover .featured-product-category {
            text-decoration: underline;
        }

        /* ================= Responsive Media Queries ================= */

        /* Large Tablets and Small Desktops */
        @media (max-width: 1024px) {
            .product-card {
                flex: 0 0 calc(33.333% - 14px);
            }
        }

        /* Tablets */
        @media (max-width: 768px) {
            .product-card {
                flex: 0 0 calc(50% - 10px);
            }

            .product-card .image-wrapper {
                height: clamp(140px, 25vw, 180px);
            }

            .featured-products-section .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .featured-products-section .nav-buttons {
                margin-top: 10px;
            }
        }

        /* Mobile Devices */
        @media (max-width: 640px) {
            .product-card {
                flex: 0 0 100%;
            }

            .product-card .image-wrapper {
                height: clamp(200px, 50vw, 250px);
            }

            .products-wrapper {
                gap: 15px;
            }
        }

        /* Small Mobile Devices */
        @media (max-width: 480px) {
            .featured-products-section .nav-btn {
                width: 36px;
                height: 36px;
            }

            .product-card .add-btn:hover {
                width: clamp(32px, 4vw, 36px);
                padding: 0;
            }

            .product-card .add-btn:hover .btn-text {
                opacity: 0;
                max-width: 0;
            }

            .product-card .product-info {
                padding: 15px;
            }

            .products-slider {
                padding: 0 10px;
            }

            .products-wrapper {
                margin: 10px;
            }
        }

        /* Extra Small Devices */
        @media (max-width: 360px) {
            .featured-products-section .section-header h2 {
                font-size: 1.3rem;
            }

            .product-card .product-info h3 {
                font-size: 0.9rem;
            }
        }
    </style>

    @php
        use App\Models\HomePage;

        $hero = HomePage::where('key', 'hero')->first();
        $customers = HomePage::where('key', 'customers')->first();
        $gather = HomePage::where('key', 'gather')->first();
    @endphp

    <!-- Carousel Section -->
    <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @if ($hero && isset($hero->value['slides']))
                @foreach ($hero->value['slides'] as $index => $slide)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}"
                        class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            @else
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            @endif
        </div>

        <div class="carousel-inner">
            @if ($hero && isset($hero->value['slides']) && count($hero->value['slides']) > 0)
                @foreach ($hero->value['slides'] as $index => $slide)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="5000">
                        @if (isset($slide['image']) && $slide['image'])
                            <img src="{{ asset($slide['image']) }}" class="d-block w-100" alt="Slide {{ $index + 1 }}"
                                onerror="this.src='{{ static_asset('assets/img/firstCarousal.jpg') }}'">
                        @else
                            <img src="{{ static_asset('assets/img/firstCarousal.jpg') }}" class="d-block w-100"
                                alt="Slide {{ $index + 1 }}">
                        @endif

                        <div class="hero-content">
                            <h1>{{ $slide['title'] ?? 'Connecting Markets, Delivering Value.' }}</h1>
                            <p>{{ $slide['description'] ?? 'From food and beverages to raw materials and recycled goods – Trades Axis bridges global demand and supply with precision, trust, and expertise.' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Default slides if no data exists --}}
                <div class="carousel-item active" data-bs-interval="5000">
                    <img src="{{ static_asset('assets/img/firstCarousal.jpg') }}" class="d-block w-100" alt="Slide 1">
                    <div class="hero-content">
                        <h1>Connecting Markets, Delivering Value.</h1>
                        <p>From food and beverages to raw materials and recycled goods – Trades Axis bridges global demand
                            and supply with precision, trust, and expertise.</p>
                    </div>
                </div>

                <div class="carousel-item" data-bs-interval="5000">
                    <img src="{{ static_asset('assets/img/firstCarousal.jpg') }}" class="d-block w-100" alt="Slide 2">
                    <div class="hero-content">
                        <h1>Connecting Markets, Delivering Value.</h1>
                        <p>From food and beverages to raw materials and recycled goods – Trades Axis bridges global demand
                            and supply with precision, trust, and expertise.</p>
                    </div>
                </div>

                <div class="carousel-item" data-bs-interval="5000">
                    <img src="{{ static_asset('assets/img/firstCarousal.jpg') }}" class="d-block w-100" alt="Slide 3">
                    <div class="hero-content">
                        <h1>Connecting Markets, Delivering Value.</h1>
                        <p>From food and beverages to raw materials and recycled goods – Trades Axis bridges global demand
                            and supply with precision, trust, and expertise.</p>
                    </div>
                </div>
            @endif
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Customers Section -->
    <section class="customers-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="image-wrapper">
                        @if ($customers && isset($customers->value['image']) && $customers->value['image'])
                            <img src="{{ asset($customers->value['image']) }}" alt="Make customers happy"
                                onerror="this.src='{{ static_asset('assets/img/secondSection.png') }}'">
                        @else
                            <img src="{{ static_asset('assets/img/secondSection.png') }}" alt="Make customers happy">
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="content-wrapper">
                        <h2>{{ $customers && isset($customers->value['title']) ? $customers->value['title'] : 'Make your customers happy by giving the best products.' }}
                        </h2>

                        <p>{{ $customers && isset($customers->value['description']) ? $customers->value['description'] : 'We trade common products and food for improving your business and making sure you keep providing the highest quality.' }}
                        </p>

                        <a href="{{ $customers && isset($customers->value['link_url']) ? $customers->value['link_url'] : '#' }}"
                            class="about-link">
                            {{ $customers && isset($customers->value['link_text']) ? $customers->value['link_text'] : 'About us' }}
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Categories -->
    @include('frontend.classic.partials.our_categories_section', ['categories' => $categories, 'lang' => $lang])

    <!-- Featured Products Section -->
    <section class="featured-products-section">
        <div class="container">
            <div class="section-header">
                <div class="header-left">
                    <h2>Featured Products</h2>
                    <div class="nav-buttons">
                        <button class="nav-btn" id="productsPrevBtn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M15 18l-6-6 6-6" />
                            </svg>
                        </button>
                        <button class="nav-btn" id="productsNextBtn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M9 18l6-6-6-6" />
                            </svg>
                        </button>
                    </div>
                </div>
                <a href="#" class="view-all">View All</a>
            </div>

            <div class="products-slider">
                <div class="products-wrapper" id="productsWrapper">
                    @php
                        $featuredProducts = \App\Models\Product::where('published', 1)
                            ->where('approved', 1)
                            ->where('featured', 1)
                            ->take(8)
                            ->get();

                        if ($featuredProducts->count() < 4) {
                            $featuredProducts = \App\Models\Product::where('published', 1)
                                ->where('approved', 1)
                                ->orderBy('num_of_sale', 'desc')
                                ->take(8)
                                ->get();
                        }

                        if ($featuredProducts->count() == 0) {
                            $featuredProducts = \App\Models\Product::where('published', 1)
                                ->where('approved', 1)
                                ->orderBy('created_at', 'desc')
                                ->take(8)
                                ->get();
                        }
                    @endphp

                    @foreach ($featuredProducts as $product)
                        @php
                            $productName = $product->getTranslation('name', $lang);
                            $productImage = uploaded_asset($product->thumbnail_img);
                            $productUrl = route('product', $product->slug);

                            $category = \App\Models\Category::find($product->category_id);
                            $categoryName = $category?->getTranslation('name');
                            $categoryUrl = $category ? (route('categories.level2', $category->id) . '?open=' . $category->id) : '#';
                        @endphp

                        <a href="{{ $productUrl }}" class="product-card">
                            <div class="image-wrapper">
                                <img src="{{ $productImage }}" alt="{{ $productName }}"
                                    onerror="this.src='https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400'">
                                <button class="add-btn" onclick="event.preventDefault();">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="btn-text">Inquire Now</span>
                                </button>
                            </div>

                            <div class="product-info" data-category-url="{{ $categoryUrl }}"
                                onclick="event.preventDefault(); event.stopPropagation(); window.location.href = this.getAttribute('data-category-url');">
                                <h3>{{ $productName }}</h3>

                                <p class="featured-product-category">
                                    {{ $categoryName ?? translate('Products') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Gather Quality Section -->
    <section class="gather-section">
        <div class="top-bar">
            <div class="container">
                <div class="left-content">
                    <div class="icon-grid">
                        <div class="icon-box"></div>
                        <div class="icon-box"></div>
                        <div class="icon-box"></div>
                        <div class="icon-box"></div>
                    </div>
                    <div class="text-content">
                        <h3>{{ $gather && isset($gather->value['top_title']) ? $gather->value['top_title'] : 'Enjoy Most Completed Trading platform' }}
                        </h3>
                        <p>{{ $gather && isset($gather->value['top_description']) ? $gather->value['top_description'] : 'Explore through our large set of Categories. Find the products you need and inquire about them.' }}
                        </p>
                    </div>
                </div>
                <a href="{{ $gather && isset($gather->value['button_url']) ? $gather->value['button_url'] : route('categories.all') }}"
                    class="explore-btn">
                    {{ $gather && isset($gather->value['button_text']) ? $gather->value['button_text'] : 'Explore Categories' }}
                </a>
            </div>
        </div>

        <div class="image-section">
            @if ($gather && isset($gather->value['image']) && $gather->value['image'])
                <img src="{{ asset($gather->value['image']) }}" alt="Quality Products"
                    onerror="this.src='{{ static_asset('assets/img/gather.png') }}'">
            @else
                <img src="{{ static_asset('assets/img/gather.png') }}" alt="Quality Products">
            @endif

            <div class="overlay-text">
                <h2>{{ $gather && isset($gather->value['overlay_title']) ? $gather->value['overlay_title'] : 'We Gather the highest Quality Products' }}
                </h2>
            </div>
        </div>
    </section>

    @if (count($categories) > 3)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const wrapper = document.getElementById('categoriesWrapper');
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');

                if (wrapper && prevBtn && nextBtn) {
                    let currentIndex = 0;

                    setTimeout(() => {
                        const firstCard = wrapper.querySelector('.category-card');
                        if (firstCard) {
                            const cardWidth = firstCard.offsetWidth + 25;

                            nextBtn.addEventListener('click', () => {
                                const maxScroll = wrapper.scrollWidth - wrapper.parentElement
                                    .offsetWidth;
                                if (currentIndex < maxScroll) {
                                    currentIndex += cardWidth;
                                    if (currentIndex > maxScroll) currentIndex = maxScroll;
                                    wrapper.style.transform = `translateX(-${currentIndex}px)`;
                                }
                            });

                            prevBtn.addEventListener('click', () => {
                                if (currentIndex > 0) {
                                    currentIndex -= cardWidth;
                                    if (currentIndex < 0) currentIndex = 0;
                                    wrapper.style.transform = `translateX(-${currentIndex}px)`;
                                }
                            });
                        }
                    }, 100);
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Our Categories slider + Add-to-Inquiry logic moved to:
            // resources/views/frontend/classic/partials/our_categories_section.blade.php

            // ==========================================
            // Products Slider Navigation (infinite loop)
            // ==========================================
            const productsWrapper = document.getElementById('productsWrapper');
            const productsPrevBtn = document.getElementById('productsPrevBtn');
            const productsNextBtn = document.getElementById('productsNextBtn');

            if (productsWrapper && productsPrevBtn && productsNextBtn) {
                const slider = {
                    wrapper: productsWrapper,
                    viewport: productsWrapper.parentElement,
                    prevBtn: productsPrevBtn,
                    nextBtn: productsNextBtn,
                    originalCount: 0,
                    clonesEachSide: 0,
                    index: 0,
                    step: 0,
                    isAnimating: false,
                    transition: 'transform 0.5s ease',
                };

                function getStep() {
                    const first = slider.wrapper.querySelector('.product-card');
                    if (!first) return 0;
                    const cardWidth = first.getBoundingClientRect().width;
                    const styles = window.getComputedStyle(slider.wrapper);
                    const gap = parseFloat(styles.columnGap || styles.gap || 0) || 0;
                    return cardWidth + gap;
                }

                function getItemsPerView(step) {
                    if (!step) return 1;
                    const width = slider.viewport.getBoundingClientRect().width;
                    return Math.max(1, Math.round(width / step));
                }

                function removeClones() {
                    slider.wrapper.querySelectorAll('.product-card.is-clone').forEach((n) => n.remove());
                }

                function buildInfiniteLoop() {
                    removeClones();

                    const originals = Array.from(slider.wrapper.querySelectorAll('.product-card')).filter(
                        (n) => !n.classList.contains('is-clone')
                    );

                    slider.originalCount = originals.length;
                    slider.step = getStep();

                    const perView = getItemsPerView(slider.step);
                    slider.clonesEachSide = Math.min(perView, slider.originalCount);

                    // Not enough items to loop cleanly.
                    if (slider.originalCount <= slider.clonesEachSide) {
                        slider.index = 0;
                        slider.wrapper.style.transition = '';
                        slider.wrapper.style.transform = 'translateX(0px)';
                        return;
                    }

                    // Prepend clones of last N cards
                    originals
                        .slice(-slider.clonesEachSide)
                        .map((n) => n.cloneNode(true))
                        .forEach((clone) => {
                            clone.classList.add('is-clone');
                            slider.wrapper.insertBefore(clone, slider.wrapper.firstChild);
                        });

                    // Append clones of first N cards
                    originals
                        .slice(0, slider.clonesEachSide)
                        .map((n) => n.cloneNode(true))
                        .forEach((clone) => {
                            clone.classList.add('is-clone');
                            slider.wrapper.appendChild(clone);
                        });

                    // Start on the first real slide (after prepended clones)
                    slider.index = slider.clonesEachSide;
                    slider.wrapper.style.transition = 'none';
                    slider.wrapper.style.transform = `translateX(-${slider.index * slider.step}px)`;
                    requestAnimationFrame(() => {
                        slider.wrapper.style.transition = slider.transition;
                    });
                }

                function goToIndex(nextIndex) {
                    slider.index = nextIndex;
                    slider.wrapper.style.transform = `translateX(-${slider.index * slider.step}px)`;
                }

                function next() {
                    if (slider.isAnimating || slider.originalCount <= slider.clonesEachSide) return;
                    slider.isAnimating = true;
                    goToIndex(slider.index + 1);
                }

                function prev() {
                    if (slider.isAnimating || slider.originalCount <= slider.clonesEachSide) return;
                    slider.isAnimating = true;
                    goToIndex(slider.index - 1);
                }

                slider.nextBtn.addEventListener('click', next);
                slider.prevBtn.addEventListener('click', prev);

                slider.wrapper.addEventListener('transitionend', () => {
                    if (slider.originalCount <= slider.clonesEachSide) {
                        slider.isAnimating = false;
                        return;
                    }

                    // If we moved onto the appended "first" clone, jump back to the real first.
                    if (slider.index === slider.clonesEachSide + slider.originalCount) {
                        slider.wrapper.style.transition = 'none';
                        slider.index = slider.clonesEachSide;
                        slider.wrapper.style.transform = `translateX(-${slider.index * slider.step}px)`;
                        requestAnimationFrame(() => {
                            slider.wrapper.style.transition = slider.transition;
                        });
                    }

                    // If we moved onto the prepended "last" clone, jump back to the real last.
                    if (slider.index === slider.clonesEachSide - 1) {
                        slider.wrapper.style.transition = 'none';
                        slider.index = slider.clonesEachSide + slider.originalCount - 1;
                        slider.wrapper.style.transform = `translateX(-${slider.index * slider.step}px)`;
                        requestAnimationFrame(() => {
                            slider.wrapper.style.transition = slider.transition;
                        });
                    }

                    slider.isAnimating = false;
                });

                // Init + keep responsive
                setTimeout(buildInfiniteLoop, 50);
                let resizeTimer = null;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(buildInfiniteLoop, 120);
                });
            }
        });
    </script>
