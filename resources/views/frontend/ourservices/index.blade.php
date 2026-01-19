@extends('frontend.layouts.app')
@section("meta_title",'Services')

@php
    use App\Models\OurService;

    $valueServices = OurService::where('key', 'value_services')->first();
    $sourcing = OurService::where('key', 'sourcing')->first();
    $branding = OurService::where('key', 'branding')->first();
    $logistics = OurService::where('key', 'logistics')->first();
    $legal = OurService::where('key', 'legal')->first();
    $whyWork = OurService::where('key', 'why_work')->first();
@endphp

<style>
    .value-services-section {
        padding: 80px 20px;
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        position: relative;
        overflow: hidden;
    }

    .value-services-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, rgba(99, 179, 237, 0.1) 0%, transparent 100%);
        border-radius: 50%;
        transform: translate(-50%, -50%);
    }

    .value-services-section::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: linear-gradient(135deg, transparent 0%, rgba(129, 199, 132, 0.1) 100%);
        border-radius: 50%;
        transform: translate(50%, 50%);
    }

    .value-services-container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .value-services-header {
        text-align: center;
        margin-top: 60px;
        margin-bottom: 60px;
    }

    .value-services-title {
        font-size: 48px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .value-services-description {
        font-size: 18px;
        color: #555;
        line-height: 1.8;
        max-width: 900px;
        margin: 0 auto;
    }

    .balance-illustration {
        position: relative;
        margin-top: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
    }

    .balance-img {
        max-width: 100%;
        height: auto;
        width: 900px;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @media (max-width: 968px) {
        .value-services-title {
            font-size: 36px;
        }

        .balance-img {
            width: 700px;
        }
    }

    @media (max-width: 768px) {
        .value-services-section {
            padding: 60px 15px;
        }

        .value-services-title {
            font-size: 28px;
        }

        .value-services-description {
            font-size: 16px;
        }

        .balance-illustration {
            min-height: 300px;
            margin-top: 60px;
        }

        .balance-img {
            width: 100%;
            max-width: 500px;
        }
    }

    /* Sourcing & Compliance Section */
    .sourcing-compliance-section {
        padding: 100px 20px;
        background: #ffffff;
    }

    .sourcing-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }

    .sourcing-content {
        padding-right: 40px;
    }

    .sourcing-title {
        font-size: 42px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 50px;
        letter-spacing: -0.5px;
    }

    .sourcing-items {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    .sourcing-item {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .sourcing-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .pulse-icon {
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        color: rgba(84, 189, 149, 1);
        animation: pulse 2s ease-in-out infinite;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
        }

        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(74, 222, 128, 0);
        }
    }

    .clock-icon {
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        color: rgba(84, 189, 149, 1);
        animation: pulse 2s ease-in-out infinite;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    .scissors-icon {
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        color: rgba(84, 189, 149, 1);
        animation: pulse 2s ease-in-out infinite;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    .sourcing-icon:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .sourcing-text {
        flex: 1;
    }

    .sourcing-subtitle {
        font-size: 22px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .sourcing-description {
        font-size: 16px;
        color: #666;
        line-height: 1.6;
    }

    .sourcing-image {
        position: relative;
    }

    .sourcing-img {
        width: 100%;
        height: auto;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .sourcing-img:hover {
        transform: scale(1.02);
    }

    @media (max-width: 968px) {
        .sourcing-compliance-section {
            padding: 80px 20px;
        }

        .sourcing-container {
            grid-template-columns: 1fr;
            gap: 60px;
        }

        .sourcing-content {
            padding-right: 0;
        }

        .sourcing-title {
            font-size: 36px;
        }

        .sourcing-image {
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .sourcing-compliance-section {
            padding: 60px 15px;
        }

        .sourcing-title {
            font-size: 28px;
            margin-bottom: 40px;
        }

        .sourcing-items {
            gap: 30px;
        }

        .sourcing-subtitle {
            font-size: 18px;
        }

        .sourcing-description {
            font-size: 15px;
        }

        .sourcing-icon {
            width: 45px;
            height: 45px;
            min-width: 45px;
        }
    }

    /* Branding & Quality Section */
    .branding-quality-section {
        padding: 100px 20px;
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
    }

    .branding-container {
        max-width: 1300px;
        margin: 0 auto;
    }

    .branding-main-title {
        font-size: 42px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 60px;
        letter-spacing: -0.5px;
    }

    .branding-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
    }

    .branding-card {
        background: white;
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    .branding-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }

    .card-image-wrapper {
        height: 280px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 15px;
        padding: 20px;
        overflow: hidden;
    }

    .card-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .branding-card:hover .card-image {
        transform: scale(1.05);
    }

    .card-title {
        font-size: 22px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 12px;
    }

    .card-description {
        font-size: 15px;
        color: #64748b;
        line-height: 1.6;
    }

    @media (max-width: 1100px) {
        .branding-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .branding-quality-section {
            padding: 60px 15px;
        }

        .branding-main-title {
            font-size: 28px;
            margin-bottom: 40px;
        }

        .branding-cards {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .branding-card {
            padding: 30px 20px;
        }

        .card-image-wrapper {
            height: 240px;
        }

        .card-title {
            font-size: 20px;
        }

        .card-description {
            font-size: 14px;
        }
    }

    /* Logistics & Trade Support Section */
    .logistics-section {
        padding: 100px 20px;
        background: #ffffff;
    }

    .logistics-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .logistics-title {
        font-size: 42px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 60px;
        letter-spacing: -0.5px;
    }

    .logistics-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 50px 80px;
    }

    .logistics-item {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        transition: transform 0.3s ease;
    }

    .logistics-item:hover {
        transform: translateX(10px);
    }

    .logistics-icon {
        width: 55px;
        height: 55px;
        min-width: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .shield-icon {
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        color: rgba(84, 189, 149, 1);
        animation: pulse 2s ease-in-out infinite;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    .package-icon {
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        color: rgba(84, 189, 149, 1);
        animation: pulse 2s ease-in-out infinite;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    .list-icon {
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        color: rgba(84, 189, 149, 1);
        animation: pulse 2s ease-in-out infinite;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    .card-icon {
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        color: rgba(84, 189, 149, 1);
        animation: pulse 2s ease-in-out infinite;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    .logistics-item:hover .logistics-icon {
        transform: translateY(-5px) rotate(5deg);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    }

    .logistics-content {
        flex: 1;
    }

    .logistics-subtitle {
        font-size: 22px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .logistics-description {
        font-size: 16px;
        color: #64748b;
        line-height: 1.6;
    }

    @media (max-width: 968px) {
        .logistics-section {
            padding: 80px 20px;
        }

        .logistics-title {
            font-size: 36px;
            margin-bottom: 50px;
        }

        .logistics-grid {
            gap: 40px 60px;
        }
    }

    @media (max-width: 768px) {
        .logistics-section {
            padding: 60px 15px;
        }

        .logistics-title {
            font-size: 28px;
            margin-bottom: 40px;
        }

        .logistics-grid {
            grid-template-columns: 1fr;
            gap: 35px;
        }

        .logistics-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
        }

        .logistics-subtitle {
            font-size: 20px;
        }

        .logistics-description {
            font-size: 15px;
        }
    }

    /* Legal & Contractual Support Section */
    .legal-support-section {
        padding: 20px 20px;
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
    }

    .legal-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .legal-content {
        max-width: 800px;
    }

    .legal-title {
        font-size: 42px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        letter-spacing: -0.5px;
    }

    .legal-description {
        font-size: 17px;
        color: #64748b;
        line-height: 1.8;
    }

    /* Why Work With Us Section */
    .why-work-section {
        padding: 100px 20px;
        background: #ffffff;
    }

    .why-work-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }

    .why-work-image {
        position: relative;
    }

    .handshake-img {
        width: 100%;
        max-width: 500px;
        height: auto;
        animation: float 3s ease-in-out infinite;
    }

    .why-work-content {
        padding-left: 40px;
    }

    .why-work-title {
        font-size: 42px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 25px;
        letter-spacing: -0.5px;
    }

    .why-work-description {
        font-size: 17px;
        color: #64748b;
        line-height: 1.8;
    }

    @media (max-width: 968px) {
        .legal-support-section {
            padding: 70px 20px;
        }

        .legal-title {
            font-size: 36px;
        }

        .legal-description {
            font-size: 16px;
        }

        .why-work-section {
            padding: 80px 20px;
        }

        .why-work-container {
            grid-template-columns: 1fr;
            gap: 60px;
        }

        .why-work-content {
            padding-left: 0;
        }

        .why-work-title {
            font-size: 36px;
        }

        .why-work-image {
            order: -1;
            display: flex;
            justify-content: center;
        }

        .handshake-img {
            max-width: 400px;
        }
    }

    @media (max-width: 768px) {
        .legal-support-section {
            padding: 60px 15px;
        }

        .legal-title {
            font-size: 28px;
            margin-bottom: 18px;
        }

        .legal-description {
            font-size: 15px;
        }

        .why-work-section {
            padding: 60px 15px;
        }

        .why-work-container {
            gap: 50px;
        }

        .why-work-title {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .why-work-description {
            font-size: 15px;
        }

        .handshake-img {
            max-width: 350px;
        }
    }
</style>

@section('content')
    <!-- Value Added Services Section -->
    <section class="value-services-section">
        <div class="value-services-container">
            <div class="value-services-header">
                <h2 class="value-services-title">
                    {{ $valueServices->value['title'] ?? 'Our Value-Added Services' }}
                </h2>
                <p class="value-services-description">
                    {{ $valueServices->value['description'] ?? 'We act as a single point of contact — integrating verified sourcing, quality control, legal protection, inspections, and logistics — to deliver compliant, reliable, and market-ready products worldwide.' }}
                </p>
            </div>
            <div class="balance-illustration">
                <img src="{{ asset($valueServices->value['image'] ?? 'assets/img/ourservices/09661665a7ea8686fc741f1d4923ba1671507337.png') }}"
                    alt="Value Added Services" class="balance-img">
            </div>
        </div>
    </section>

    <!-- Sourcing & Compliance Section -->
    <section class="sourcing-compliance-section">
        <div class="sourcing-container">
            <div class="sourcing-content">
                <h2 class="sourcing-title">{{ $sourcing->value['title'] ?? '1. Sourcing & Compliance' }}</h2>

                <div class="sourcing-items">
                    @if (isset($sourcing->value['items']) && is_array($sourcing->value['items']))
                        @foreach ($sourcing->value['items'] as $index => $item)
                            <div class="sourcing-item">
                                <div
                                    class="sourcing-icon {{ $index === 0 ? 'pulse-icon' : ($index === 1 ? 'clock-icon' : 'scissors-icon') }}">
                                    @if ($index === 0)
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                        </svg>
                                    @elseif($index === 1)
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                    @else
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <circle cx="6" cy="6" r="3" />
                                            <circle cx="6" cy="18" r="3" />
                                            <line x1="20" y1="4" x2="8.12" y2="15.88" />
                                            <line x1="14.47" y1="14.48" x2="20" y2="20" />
                                            <line x1="8.12" y1="8.12" x2="12" y2="12" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="sourcing-text">
                                    <h3 class="sourcing-subtitle">{{ $item['title'] }}</h3>
                                    <p class="sourcing-description">{{ $item['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="sourcing-image">
                <img src="{{ asset($sourcing->value['image'] ?? 'assets/img/ourservices/38763c6df6db100b28e60d59499d8abd2962d5c8.png') }}"
                    alt="Sourcing" class="sourcing-img">
            </div>
        </div>
    </section>

    <!-- Branding & Quality Section -->
    <section class="branding-quality-section">
        <div class="branding-container">
            <h2 class="branding-main-title">{{ $branding->value['title'] ?? '2. Branding & Quality' }}</h2>

            <div class="branding-cards">
                @if (isset($branding->value['cards']) && is_array($branding->value['cards']))
                    @foreach ($branding->value['cards'] as $card)
                        <div class="branding-card">
                            <div class="card-image-wrapper">
                                <img src="{{ asset($card['image'] ?? 'assets/img/ourservices/75dd8ae42a9b6a98b488f4a6be1efe13dfdd924f.png') }}"
                                    alt="{{ $card['title'] }}" class="card-image">
                            </div>
                            <h3 class="card-title">{{ $card['title'] }}</h3>
                            <p class="card-description">{{ $card['description'] }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Logistics Section -->
    <section class="logistics-section">
        <div class="logistics-container">
            <h2 class="logistics-title">{{ $logistics->value['title'] ?? '3. Logistics & Trade Support' }}</h2>

            <div class="logistics-grid">
                @if (isset($logistics->value['items']) && is_array($logistics->value['items']))
                    @foreach ($logistics->value['items'] as $index => $item)
                        <div class="logistics-item">
                            <div
                                class="logistics-icon {{ $index === 0 ? 'shield-icon' : ($index === 1 ? 'package-icon' : ($index === 2 ? 'list-icon' : 'card-icon')) }}">
                                @if ($index === 0)
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                @elseif($index === 1)
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                        <line x1="12" y1="22.08" x2="12" y2="12" />
                                    </svg>
                                @elseif($index === 2)
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <line x1="8" y1="6" x2="21" y2="6" />
                                        <line x1="8" y1="12" x2="21" y2="12" />
                                        <line x1="8" y1="18" x2="21" y2="18" />
                                        <line x1="3" y1="6" x2="3.01" y2="6" />
                                        <line x1="3" y1="12" x2="3.01" y2="12" />
                                        <line x1="3" y1="18" x2="3.01" y2="18" />
                                    </svg>
                                @else
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                                        <line x1="1" y1="10" x2="23" y2="10" />
                                    </svg>
                                @endif
                            </div>
                            <div class="logistics-content">
                                <h3 class="logistics-subtitle">{{ $item['title'] }}</h3>
                                <p class="logistics-description">{{ $item['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Legal Section -->
    <section class="legal-support-section">
        <div class="legal-container">
            <div class="legal-content">
                <h2 class="legal-title">{{ $legal->value['title'] ?? '4. Legal & Contractual Support' }}</h2>
                <p class="legal-description">
                    {{ $legal->value['description'] ?? 'We work with experienced international legal firms to draft, review, and structure trade contracts that protect our clients\' rights. This includes clear commercial terms, risk allocation, and dispute protection across jurisdictions.' }}
                </p>
            </div>
        </div>
    </section>

    <!-- Why Work With Us -->
    <section class="why-work-section">
        <div class="why-work-container">
            <div class="why-work-image">
                <img src="{{ asset($whyWork->value['image'] ?? 'assets/img/ourservices/e9257ddfbb86967ae76a47068335b01c7ec7449f.png') }}"
                    alt="Why Work With Us" class="handshake-img">
            </div>
            <div class="why-work-content">
                <h2 class="why-work-title">{{ $whyWork->value['title'] ?? 'Why Work With Us' }}</h2>
                <p class="why-work-description">
                    {{ $whyWork->value['description'] ?? 'We act as a single point of contact — integrating verified sourcing, quality control, legal protection, inspections, and logistics — to deliver compliant, reliable, and market-ready products worldwide.' }}
                </p>
            </div>
        </div>
    </section>
@endsection
