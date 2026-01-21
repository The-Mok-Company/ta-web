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

        .carousel-control-prev,
        .carousel-control-next {
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            top: 50%;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 3;
            border: none;
        }

        .carousel-control-prev {
            left: 40px;
        }

        .carousel-control-next {
            right: 40px;
        }

        .hero-carousel:hover .carousel-control-prev,
        .hero-carousel:hover .carousel-control-next {
            opacity: 1;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
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
            color: #007bff;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .customers-section .about-link:hover {
            color: #0056b3;
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
            color: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .categories-section .arrow-btn:hover {
            background-color: #007bff;
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

   /* ===========================
   Category Add Button (+ -> ✓)
=========================== */

.category-card-new{ position: relative; }

.category-card-new .category-add-btn{
    position: absolute;
    top: clamp(12px, 2vw, 20px);
    right: clamp(12px, 2vw, 20px);

    width: clamp(36px, 5vw, 42px);
    height: clamp(36px, 5vw, 42px);
    border-radius: 50%;

    background: #0891B2;
    border: none;
    color: #fff;

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 5;
    cursor: pointer;

    opacity: 0;
    transform: scale(.96);
    transition: opacity .2s ease, transform .2s ease, background .2s ease, box-shadow .2s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,.20);
    overflow: hidden;
}

@media (hover:hover) and (pointer:fine){
    .category-card-new:hover .category-add-btn{
        opacity: 1;
        transform: scale(1);
    }
}

@media (max-width: 768px){
    .category-card-new .category-add-btn{
        opacity: 1;
        transform: scale(1);
    }
}

.category-card-new .category-add-btn:hover{
    background: #0E7490;
    transform: scale(1.08);
    box-shadow: 0 6px 18px rgba(0,0,0,.25);
}

.category-card-new .category-add-btn:active{
    transform: scale(.98);
}

.category-card-new .category-add-btn:focus-visible{
    box-shadow: 0 0 0 4px rgba(8,145,178,.25), 0 2px 10px rgba(0,0,0,.20);
}

.category-card-new .category-add-btn .btn-plus,
.category-card-new .category-add-btn .btn-tick{
    position: absolute;
    transition: opacity .18s ease, transform .18s ease;
    pointer-events: none;
}

.category-card-new .category-add-btn .btn-tick{
    font-size: 18px;
    font-weight: 900;
    line-height: 1;
    opacity: 0;
    transform: scale(.85);
}

.category-card-new .category-add-btn.added .btn-tick{
    opacity: 1 !important;
    transform: scale(1) !important;
}


/* loading */
.category-card-new .category-add-btn.is-loading{
    opacity: .9 !important;
    cursor: not-allowed;
    pointer-events: none;
}

/* added */
.category-card-new .category-add-btn.added{
    background: #16a34a;
    opacity: 1 !important;
    pointer-events: none;
    animation: inquiryPulse .35s ease-out 1;
}

@keyframes inquiryPulse{
    0%{ transform: scale(1); }
    50%{ transform: scale(1.12); }
    100%{ transform: scale(1); }
}

.category-card-new .category-add-btn.added .btn-plus{
    opacity: 0 !important;
    transform: scale(.7) !important;
}

.category-card-new .category-add-btn.added .btn-check{
    opacity: 1 !important;
    transform: scale(1) !important;
}

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
            color: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            flex-shrink: 0;
        }

        .featured-products-section .nav-btn:hover {
            background-color: #007bff;
            color: white;
            transform: scale(1.05);
        }

        .featured-products-section .view-all {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            font-size: clamp(0.85rem, 2vw, 0.95rem);
            transition: color 0.3s ease;
            white-space: nowrap;
        }

        .featured-products-section .view-all:hover {
            color: #0056b3;
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
            .category-card-new {
                flex: 0 0 calc(50% - 12px);
            }

            .product-card {
                flex: 0 0 calc(33.333% - 14px);
            }
        }

        /* Tablets */
        @media (max-width: 768px) {
            .categories-section-new .section-header {
                flex-direction: row;
                gap: 15px;
            }

            .category-card-new {
                flex: 0 0 calc(50% - 10px);
                height: clamp(180px, 35vw, 220px);
            }

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
            .categories-section-new {
                padding: 30px 0;
            }

            .category-card-new {
                flex: 0 0 100%;
                height: clamp(220px, 50vw, 280px);
            }

            .product-card {
                flex: 0 0 100%;
            }

            .product-card .image-wrapper {
                height: clamp(200px, 50vw, 250px);
            }

            .categories-wrapper-new,
            .products-wrapper {
                gap: 15px;
            }
        }

        /* Small Mobile Devices */
        @media (max-width: 480px) {

            .categories-section-new .nav-btn,
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

            .categories-slider-new,
            .products-slider {
                padding: 0 10px;
            }

            .products-wrapper {
                margin: 10px;
            }
        }

        /* Extra Small Devices */
        @media (max-width: 360px) {

            .categories-section-new .section-header h2,
            .featured-products-section .section-header h2 {
                font-size: 1.3rem;
            }

            .category-card-new .content h3 {
                font-size: 1.1rem;
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

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"  style="width: 51px;top: 50%;left: 3px;">
            <span class="carousel-control-prev-icon" aria-hidden="true"
               ></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="width: 51px;top: 50%;left: 3px;">
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

    <!-- ✅ Categories Section with Add Button -->
    <section class="categories-section-new">
        <div class="container">
            <div class="section-header">
                <button class="nav-btn" id="categoriesPrevBtn" type="button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </button>

                <h2>Our Categories</h2>

                <button class="nav-btn" id="categoriesNextBtn" type="button">
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
                                } elseif (str_contains($lowerName, 'beverage') || str_contains($lowerName, 'juice')) {
                                    $categoryImage =
                                        'https://images.unsplash.com/photo-1610970881699-44a5587cabec?w=800';
                                } elseif (str_contains($lowerName, 'frozen')) {
                                    $categoryImage =
                                        'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800';
                                } else {
                                    $categoryImage = 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800';
                                }
                            }

                            $categoryUrl = route('products.category', $category->slug);
                        @endphp

                        <a href="{{ $categoryUrl }}" class="category-card-new">
                            <!-- Left cart icon -->
                            <div class="cart-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="9" cy="21" r="1" />
                                    <circle cx="20" cy="21" r="1" />
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                                </svg>
                            </div>

                            <!-- ✅ Add Button (Top Right) -->
                          <button type="button"
        class="category-add-btn js-add-category"
        data-id="{{ $category->id }}"
        data-name="{{ $categoryName }}"
        title="{{ translate('Add to Inquiry') }}"
        aria-label="Add to Inquiry">
    <span class="btn-plus">+</span>
<span class="btn-tick">✓</span>
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
                            $categoryUrl = $category ? route('products.level2', $category->id) : '#';
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
            // ✅ Add Category to Cart (AJAX)
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
success: function (data) {
    if (data && data.status == 1) {

        // update cart count
        if (data.cart_count !== undefined) {
            $('.cart-count').html(data.cart_count);
        }

        // ✅ IMPORTANT: switch button to "added" state ( + -> ✓ )
        const btn = document.querySelector('.js-add-category[data-id="' + categoryId + '"]');
        if (btn) {
            btn.classList.add('added');
        }

        // notifications
        if (data.message === 'Category already in cart') {
            AIZ.plugins.notify('warning', categoryName + " {{ translate('is already in cart') }}");
        } else {
            AIZ.plugins.notify('success', categoryName + " {{ translate('added to cart successfully') }}");
        }

    } else {
        AIZ.plugins.notify('danger', (data && data.message) ? data.message : "{{ translate('Something went wrong') }}");
    }
},


                    error: function() {
                        AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
                    }
                });
            }

            // ==========================================
            // Products Slider Navigation
            // ==========================================
            const productsWrapper = document.getElementById('productsWrapper');
            const productsPrevBtn = document.getElementById('productsPrevBtn');
            const productsNextBtn = document.getElementById('productsNextBtn');

            if (productsWrapper && productsPrevBtn && productsNextBtn) {
                let productsIndex = 0;

                setTimeout(() => {
                    const firstProduct = productsWrapper.querySelector('.product-card');
                    if (firstProduct) {
                        const productWidth = firstProduct.offsetWidth + 20;

                        productsNextBtn.addEventListener('click', () => {
                            const maxScroll = productsWrapper.scrollWidth - productsWrapper
                                .parentElement.offsetWidth;
                            if (productsIndex < maxScroll) {
                                productsIndex += productWidth;
                                if (productsIndex > maxScroll) productsIndex = maxScroll;
                                productsWrapper.style.transform = `translateX(-${productsIndex}px)`;
                            }
                        });

                        productsPrevBtn.addEventListener('click', () => {
                            if (productsIndex > 0) {
                                productsIndex -= productWidth;
                                if (productsIndex < 0) productsIndex = 0;
                                productsWrapper.style.transform = `translateX(-${productsIndex}px)`;
                            }
                        });
                    }
                }, 100);
            }
        });
    </script>
