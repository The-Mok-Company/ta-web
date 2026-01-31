@extends('frontend.layouts.app')

@section('content')
    <div class="inquiry-container">
        <!-- Header -->
        <div class="inquiry-header">
            <h1>Inquiry Tracking</h1>
        </div>

        <!-- Inquiry Items -->
        <div class="inquiry-list">
            <!-- Inquiry Item 1 - Ongoing -->
            <a href="{{ route('cart.inquiry') }}">
                <div class="inquiry-card">
                    <div class="inquiry-content">
                        <!-- Left Side: Image and Details -->
                        <div class="inquiry-left">
                            <!-- Image -->
                            <div class="inquiry-image">
                                <img src="https://via.placeholder.com/70" alt="Inquiry Image">
                            </div>

                            <!-- Details -->
                            <div class="inquiry-details">
                                <h3>Inquiry #356</h3>
                                <p class="products-count">3 Products</p>
                                <p class="description">
                                    From food and beverages to raw materials and recycled goods — Tradex Aria bridges global
                                    demand and supply with precision, trust, and efficiency.
                                </p>
                            </div>
                        </div>

                        <!-- Right Side: Price, Status, and Dates -->
                        <div class="inquiry-right">
                            <div class="buttons-wrapper">
                                <button class="btn btn-price">Price&nbsp;&nbsp;&nbsp;3,600 EGP</button>
                                <span class="btn btn-status ongoing">Ongoing</span>
                            </div>
                            <div class="dates-info">
                                <div>Date Created: <span>19 May 2025</span></div>
                                <div>Last Modified: <span>15 May 2025</span></div>
                                <div>Modified By: <span>Admin Gaser</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Inquiry Item 2 - Closed -->
            <a href="{{ route('cart.inquiry') }}">
                <div class="inquiry-card">
                    <div class="inquiry-content">
                        <!-- Left Side: Image and Details -->
                        <div class="inquiry-left">
                            <!-- Image -->
                            <div class="inquiry-image">
                                <img src="https://via.placeholder.com/70" alt="Inquiry Image">
                            </div>

                            <!-- Details -->
                            <div class="inquiry-details">
                                <h3>Inquiry #356</h3>
                                <p class="products-count">3 Products</p>
                                <p class="description">
                                    From food and beverages to raw materials and recycled goods — Tradex Aria bridges global
                                    demand and supply with precision, trust, and efficiency.
                                </p>
                            </div>
                        </div>

                        <!-- Right Side: Price, Status, and Dates -->
                        <div class="inquiry-right">
                            <div class="buttons-wrapper">
                                <button class="btn btn-price">Price&nbsp;&nbsp;&nbsp;3,600 EGP</button>
                                <span class="btn btn-status closed">Closed</span>
                            </div>
                            <div class="dates-info">
                                <div>Date Created: <span>19 May 2025</span></div>
                                <div>Last Modified: <span>15 May 2025</span></div>
                                <div>Modified By: <span>Admin Gaser</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <style>
        /* Container */
        .inquiry-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 48px 16px;
            margin-top: 100px;
        }

        /* Header */
        .inquiry-header {
            margin-bottom: 40px;
        }

        .inquiry-header h1 {
            font-size: 36px;
            font-weight: bold;
            color: #111827;
            margin: 0;
        }

        /* List */
        .inquiry-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Card */
        .inquiry-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .inquiry-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
            border-color: #d1d5db;
        }

        /* Content */
        .inquiry-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
        }

        /* Left Side */
        .inquiry-left {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            flex: 1;
        }

        /* Image */
        .inquiry-image {
            width: 70px;
            height: 70px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .inquiry-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Details */
        .inquiry-details {
            flex: 1;
        }

        .inquiry-details h3 {
            font-size: 17px;
            font-weight: bold;
            color: #111827;
            margin: 0 0 4px 0;
        }

        .inquiry-details .products-count {
            font-size: 12px;
            font-weight: 500;
            color: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);;
            margin: 0 0 8px 0;
        }

        .inquiry-details .description {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.6;
            margin: 0;
        }

        /* Right Side */
        .inquiry-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
            flex-shrink: 0;
        }

        /* Buttons Wrapper */
        .buttons-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Buttons */
        .btn {
            padding: 6px 18px;
            border-radius: 18px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            display: inline-block;
        }

        .btn-price {
            background-color: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);;
            color: white;
        }

        .btn-price:hover {
            background-color: #5FB3F6;
        }

        .btn-status.ongoing {
            background-color: #ef4444;
            color: white;
        }

        .btn-status.closed {
            background-color: #16a34a;
            color: white;
        }

        /* Dates Info */
        .dates-info {
            text-align: right;
            font-size: 10px;
            color: #6b7280;
            line-height: 1.6;
        }

        .dates-info div {
            margin: 2px 0;
        }

        .dates-info span {
            color: #374151;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .inquiry-content {
                flex-direction: column;
                align-items: stretch;
            }

            .inquiry-right {
                align-items: flex-start;
            }

            .buttons-wrapper {
                width: 100%;
                justify-content: flex-start;
            }

            .dates-info {
                text-align: left;
            }
        }
    </style>
@endsection
