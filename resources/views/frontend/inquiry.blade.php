@extends('frontend.layouts.app')

@section('content')
    <div class="inquiry-details-container">

        <h1 class="page-title">My Inquiries</h1>

        @forelse($inquiries as $index => $inquiry)
            @php
                $status = $inquiry->status ?? 'pending';

                $inquiryNumber = $inquiry->code
                    ? $inquiry->code
                    : ('INQ-' . str_pad($inquiry->id, 6, '0', STR_PAD_LEFT));

                // Badge styling based on status
                $badgeClass = match($status) {
                    'processing' => 'processing',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                    default => 'pending'
                };
                $badgeLabel = match($status) {
                    'processing' => 'Awaiting Your Response',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    default => 'Pending'
                };

                $itemsTabId = "itemsTab-{$inquiry->id}";
                $convTabId  = "conversationsTab-{$inquiry->id}";
                $itemsId    = "itemsContent-{$inquiry->id}";
                $convId     = "conversationsContent-{$inquiry->id}";

                $itemsCount = $inquiry->items->count();
                $isFirst = ($index === 0);
            @endphp

            <!-- Accordion Item -->
            <div class="inquiry-accordion {{ $isFirst ? 'open' : '' }}" data-inquiry-id="{{ $inquiry->id }}">
                <!-- Accordion Header (Clickable) -->
                <div class="accordion-header" onclick="toggleAccordion({{ $inquiry->id }})">
                    <div class="accordion-header-left">
                        <span class="accordion-icon">
                            <i class="las la-chevron-down"></i>
                        </span>
                        <div class="accordion-title-info">
                            <h3 class="accordion-title">Inquiry #{{ $inquiryNumber }}</h3>
                            <span class="accordion-meta">
                                <i class="las la-box"></i> {{ $itemsCount }} {{ $itemsCount === 1 ? 'item' : 'items' }}
                                <span class="meta-separator">•</span>
                                <i class="las la-calendar"></i> {{ optional($inquiry->created_at)->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="accordion-header-right">
                        <span class="status-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        @if($inquiry->total > 0)
                            <span class="accordion-total">{{ number_format($inquiry->total, 0) }} EGP</span>
                        @endif
                    </div>
                </div>

                <!-- Accordion Content -->
                <div class="accordion-content" id="accordion-content-{{ $inquiry->id }}" style="{{ $isFirst ? '' : 'display: none;' }}">

                    <!-- Tabs -->
                    <div class="tabs-container">
                        <div class="tab active" id="{{ $itemsTabId }}" data-inquiry="{{ $inquiry->id }}" data-target="{{ $itemsId }}">Items</div>
                        <div class="tab" id="{{ $convTabId }}" data-inquiry="{{ $inquiry->id }}" data-target="{{ $convId }}">Updates</div>
                    </div>

                    <!-- Main Content -->
                    <div class="main-content">
                        <!-- Left Section: Items/Conversations -->
                        <div class="content-section">

                            <!-- Items Section -->
                            <div class="items-section" id="{{ $itemsId }}">

                                @php
                                    $products = $inquiry->items->where('type', 'product');
                                    $categories = $inquiry->items->where('type', 'category');
                                @endphp

                                {{-- Products Section --}}
                                @if($products->count() > 0)
                                    <div class="items-group">
                                        <h4 class="items-group-title">
                                            <i class="las la-box"></i> Products
                                            <span class="items-count">{{ $products->count() }}</span>
                                        </h4>
                                        <div class="items-grid">
                                            @foreach($products as $item)
                                                @php
                                                    $title = $item->product->name ?? 'Product';
                                                    $qty = (int)($item->quantity ?? 1);

                                                    $img = static_asset('assets/img/placeholder.jpg');
                                                    if ($item->product && !empty($item->product->thumbnail_img)) {
                                                        $img = uploaded_asset($item->product->thumbnail_img);
                                                    }

                                                    // ✅ السعر يظهر فقط لو price مش null ومش 0
                                                    $price = null;
                                                    if (!is_null($item->price) && is_numeric($item->price)) {
                                                        $price = (float)$item->price; // from inquiry_items
                                                    } elseif ($item->product && isset($item->product->unit_price) && is_numeric($item->product->unit_price)) {
                                                        $price = (float)$item->product->unit_price; // fallback
                                                    }

                                                    $hasPrice = (!is_null($price) && $price > 0);
                                                @endphp

                                                <div class="item-card product-card">
                                                    <div class="item-image-wrapper">
                                                        <div class="item-image">
                                                            <img src="{{ $img }}" alt="{{ $title }}">
                                                        </div>
                                                        <span class="qty-badge">{{ $qty }}</span>
                                                    </div>

                                                    <div class="item-details">
                                                        <h3 class="item-title">{{ $title }}</h3>

                                                        {{-- Item Notes --}}
                                                        @if($item->user_note)
                                                            <div class="item-user-note">
                                                                <span class="note-label"><i class="las la-user"></i> Your Note</span>
                                                                <p class="note-text">{{ $item->user_note }}</p>
                                                            </div>
                                                        @endif

                                                        @if($item->note)
                                                            <div class="item-admin-note">
                                                                <span class="note-label"><i class="las la-user-shield"></i> Admin Note</span>
                                                                <p class="note-text">{{ $item->note }}</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="item-price">
                                                        @if($hasPrice)
                                                            <span class="price-value">{{ number_format($price, 0) }} EGP</span>
                                                        @else
                                                            <span class="price-waiting">Waiting for offer</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Categories Section --}}
                                @if($categories->count() > 0)
                                    <div class="items-group">
                                        <h4 class="items-group-title">
                                            <i class="las la-folder"></i> Categories
                                            <span class="items-count">{{ $categories->count() }}</span>
                                        </h4>
                                        <div class="items-grid">
                                            @foreach($categories as $item)
                                                @php
                                                    $title = $item->category->name ?? 'Category';
                                                    $qty = (int)($item->quantity ?? 1);

                                                    $img = static_asset('assets/img/placeholder.jpg');
                                                    if ($item->category && !empty($item->category->banner)) {
                                                        $img = uploaded_asset($item->category->banner);
                                                    }

                                                    // ✅ السعر يظهر فقط لو price مش null ومش 0
                                                    $price = null;
                                                    if (!is_null($item->price) && is_numeric($item->price)) {
                                                        $price = (float)$item->price;
                                                    }

                                                    $hasPrice = (!is_null($price) && $price > 0);
                                                @endphp

                                                <div class="item-card category-card">
                                                    <div class="item-image-wrapper">
                                                        <div class="item-image category-image">
                                                            <img src="{{ $img }}" alt="{{ $title }}">
                                                        </div>
                                                        @if($qty > 1)
                                                            <span class="qty-badge">{{ $qty }}</span>
                                                        @endif
                                                    </div>

                                                    <div class="item-details">
                                                        <h3 class="item-title">{{ $title }}</h3>
                                                        <span class="item-type-badge">Category Request</span>

                                                        {{-- Item Notes --}}
                                                        @if($item->user_note)
                                                            <div class="item-user-note">
                                                                <span class="note-label"><i class="las la-user"></i> Your Note</span>
                                                                <p class="note-text">{{ $item->user_note }}</p>
                                                            </div>
                                                        @endif

                                                        @if($item->note)
                                                            <div class="item-admin-note">
                                                                <span class="note-label"><i class="las la-user-shield"></i> Admin Note</span>
                                                                <p class="note-text">{{ $item->note }}</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="item-price">
                                                        @if($hasPrice)
                                                            <span class="price-value">{{ number_format($price, 0) }} EGP</span>
                                                        @else
                                                            <span class="price-waiting">Waiting for offer</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($products->count() === 0 && $categories->count() === 0)
                                    <div class="no-items-message">
                                        <i class="las la-inbox"></i>
                                        <p>No items in this inquiry</p>
                                    </div>
                                @endif

                            </div>

                            <!-- Conversations Section -->
                            <div class="conversations-section" id="{{ $convId }}" style="display: none;">
                                <div class="chat-container">
                                    <!-- Messages Container -->
                                    <div class="messages-container" id="messagesContainer-{{ $inquiry->id }}">
                                        {{-- System message for inquiry creation --}}
                                        <div class="conversation-message system-message">
                                            <div class="message-content">
                                                <p class="message-text">You created this inquiry.</p>
                                                <span class="message-time">{{ optional($inquiry->created_at)->format('d M Y - H:i') }}</span>
                                                <svg class="checkmark-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M13.5 4L6 11.5L2.5 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        </div>

                                        {{-- Display all notes --}}
                                        @foreach($inquiry->notes as $note)
                                            <div class="conversation-message {{ $note->sender_type === 'user' ? 'user-message' : 'admin-message' }}">
                                                <div class="message-header">
                                                    <span class="message-sender">
                                                        @if($note->sender_type === 'user')
                                                            <i class="las la-user"></i> You
                                                        @else
                                                            <i class="las la-user-shield"></i> {{ $note->user->name ?? 'Admin' }}
                                                        @endif
                                                    </span>
                                                    <span class="message-time">{{ $note->created_at->format('d M Y - H:i') }}</span>
                                                </div>
                                                <p class="message-text">{{ $note->message }}</p>
                                            </div>
                                        @endforeach

                                        {{-- Show status message if not pending --}}
                                        @if($status === 'processing')
                                            <div class="conversation-message status-message processing-status">
                                                <div class="message-content">
                                                    <i class="las la-clock"></i>
                                                    <p class="message-text">Awaiting your response - Please review the offer and accept or cancel.</p>
                                                </div>
                                            </div>
                                        @elseif($status === 'completed')
                                            <div class="conversation-message status-message completed-status">
                                                <div class="message-content">
                                                    <i class="las la-check-circle"></i>
                                                    <p class="message-text">Inquiry completed successfully.</p>
                                                </div>
                                            </div>
                                        @elseif($status === 'cancelled')
                                            <div class="conversation-message status-message cancelled-status">
                                                <div class="message-content">
                                                    <i class="las la-times-circle"></i>
                                                    <p class="message-text">Inquiry was cancelled.</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Chat Input -->
                                    @if(!in_array($status, ['completed', 'cancelled']))
                                        <div class="chat-input-container">
                                            <form class="chat-form" data-inquiry-id="{{ $inquiry->id }}">
                                                @csrf
                                                <div class="chat-input-wrapper">
                                                    <textarea class="chat-input" name="message" placeholder="Type your message here..." rows="1" required></textarea>
                                                    <button type="submit" class="send-button">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                            <path d="M18 2L9 11M18 2L12 18L9 11M18 2L2 8L9 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        <div class="chat-input-container disabled">
                                            <div class="chat-input-wrapper">
                                                <span class="chat-disabled-message">This inquiry is {{ $status }}. No more messages can be sent.</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <!-- Right Section: Inquiry Summary -->
                        <div class="summary-section">
                            <div class="summary-card">
                                <h2>Inquiry Summary</h2>

                                <div class="summary-header">
                                    <div class="summary-info">
                                        <span class="summary-label">Inquiry Number</span>
                                        <span class="summary-value">#{{ $inquiryNumber }}</span>
                                    </div>
                                    <span class="status-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                </div>

                                {{-- Inquiry Notes --}}
                                @if($inquiry->user_note)
                                    <div class="inquiry-user-note">
                                        <span class="note-label"><i class="las la-user"></i> Your Note</span>
                                        <p class="note-text">{{ $inquiry->user_note }}</p>
                                    </div>
                                @endif

                                @if($inquiry->note)
                                    <div class="inquiry-admin-note">
                                        <span class="note-label"><i class="las la-user-shield"></i> Admin Note</span>
                                        <p class="note-text">{{ $inquiry->note }}</p>
                                    </div>
                                @endif

                                <div class="summary-details">
                                    <div class="summary-row">
                                        <span class="row-label">Available products Price</span>
                                        <span class="row-value">{{ number_format((float)($inquiry->subtotal ?? 0), 0) }} EGP</span>
                                    </div>
                                    <div class="summary-row">
                                        <span class="row-label">Taxes</span>
                                        <span class="row-value">{{ number_format((float)($inquiry->tax ?? 0), 0) }} EGP</span>
                                    </div>
                                    <div class="summary-row">
                                        <span class="row-label">Delivery</span>
                                        <span class="row-value">{{ number_format((float)($inquiry->delivery ?? 0), 0) }} EGP</span>
                                    </div>
                                    <div class="summary-row">
                                        <span class="row-label">Discount</span>
                                        <span class="row-value">{{ number_format((float)($inquiry->discount ?? 0), 0) }} EGP</span>
                                    </div>
                                    <div class="summary-row">
                                        <span class="row-label">Extra fees</span>
                                        <span class="row-value">{{ number_format((float)($inquiry->extra_fees ?? 0), 0) }} EGP</span>
                                    </div>
                                </div>

                                <div class="summary-total">
                                    <span class="total-label">Total</span>
                                    <span class="total-value">{{ number_format((float)($inquiry->total ?? 0), 0) }} EGP</span>
                                </div>

                                @if($status === 'processing')
                                    <div class="action-buttons">
                                        <form method="POST" action="{{ route('inquiries.accept', $inquiry->id) }}" style="flex: 1;">
                                            @csrf
                                            <button class="btn btn-accept" type="submit">
                                                <i class="las la-check-circle"></i> Accept Offer
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('inquiries.cancel', $inquiry->id) }}" style="flex: 1;">
                                            @csrf
                                            <button class="btn btn-cancel" type="submit" onclick="return confirm('Are you sure you want to cancel this inquiry?')">
                                                <i class="las la-times-circle"></i> Cancel
                                            </button>
                                        </form>
                                    </div>
                                @elseif($status === 'completed')
                                    <button class="btn btn-completed" type="button" disabled>
                                        <i class="las la-check"></i> Completed
                                    </button>
                                @elseif($status === 'cancelled')
                                    <button class="btn btn-cancelled" type="button" disabled>
                                        <i class="las la-ban"></i> Cancelled
                                    </button>
                                @else
                                    <button class="btn btn-accept" type="button" disabled>
                                        <i class="las la-clock"></i> Waiting for offer
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                </div><!-- End accordion-content -->
            </div><!-- End inquiry-accordion -->
        @empty
            <div class="no-inquiries">
                <i class="las la-inbox"></i>
                <h2>No Inquiries Yet</h2>
                <p>You haven't created any inquiries yet.</p>
                <a href="{{ route('cart') }}" class="btn btn-primary">
                    <i class="las la-shopping-cart"></i> Go to Cart
                </a>
            </div>
        @endforelse
    </div>

    <style>
        /* (CSS كما هو في كودك بدون تغيير) */
        /* Container */
        .inquiry-details-container {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 48px 40px;
            margin-top: 100px;
        }
        .page-title { font-size: 32px; font-weight: bold; color: #111827; margin: 0 0 30px 0; }
        .inquiry-accordion { background: white; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 16px; overflow: hidden; transition: box-shadow 0.3s ease; }
        .inquiry-accordion:hover { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        .inquiry-accordion.open { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }

        .accordion-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; cursor: pointer; background: #f9fafb; border-bottom: 1px solid transparent; transition: all 0.3s ease; }
        .inquiry-accordion.open .accordion-header { background: white; border-bottom-color: #e5e7eb; }
        .accordion-header:hover { background: #f3f4f6; }
        .accordion-header-left { display: flex; align-items: center; gap: 16px; }

        .accordion-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #e5e7eb; border-radius: 8px; transition: all 0.3s ease; }
        .accordion-icon i { font-size: 18px; color: #6b7280; transition: transform 0.3s ease; }
        .inquiry-accordion.open .accordion-icon { background: #1976D2; }
        .inquiry-accordion.open .accordion-icon i { color: white; transform: rotate(180deg); }

        .accordion-title-info { display: flex; flex-direction: column; gap: 4px; }
        .accordion-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0; }
        .accordion-meta { font-size: 13px; color: #6b7280; display: flex; align-items: center; gap: 6px; }
        .accordion-meta i { font-size: 14px; }
        .meta-separator { color: #d1d5db; }

        .accordion-header-right { display: flex; align-items: center; gap: 16px; }
        .accordion-total { font-size: 16px; font-weight: 700; color: #111827; }

        .accordion-content { padding: 24px; animation: slideDown 0.3s ease; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        .no-inquiries { text-align: center; padding: 80px 20px; background: white; border-radius: 12px; border: 1px solid #e5e7eb; }
        .no-inquiries i { font-size: 64px; color: #d1d5db; margin-bottom: 20px; }
        .no-inquiries h2 { font-size: 24px; font-weight: 600; color: #111827; margin: 0 0 8px 0; }
        .no-inquiries p { font-size: 14px; color: #6b7280; margin: 0 0 24px 0; }
        .no-inquiries .btn-primary { background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: all 0.3s ease; }
        .no-inquiries .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3); }

        .tabs-container { display: flex; gap: 0; margin-bottom: 30px; border-bottom: 2px solid #e5e7eb; }
        .tab { padding: 12px 200px; font-size: 15px; font-weight: 500; color: #6b7280; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: all 0.3s ease; }
        .tab.active { color: #1976D2; border-bottom-color: #1976D2; }
        .tab:hover { color: #1976D2; }

        .main-content { display: flex; gap: 30px; align-items: flex-start; }
        .content-section { flex: 1; }
        .items-section { display: flex; flex-direction: column; gap: 24px; }

        .items-group { background: #f9fafb; border-radius: 12px; padding: 20px; }
        .items-group-title { font-size: 16px; font-weight: 600; color: #374151; margin: 0 0 16px 0; display: flex; align-items: center; gap: 10px; }
        .items-group-title i { font-size: 20px; color: #1976D2; }
        .items-count { background: #1976D2; color: white; font-size: 12px; font-weight: 600; padding: 2px 10px; border-radius: 12px; }

        .items-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
        .item-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; gap: 12px; transition: all 0.3s ease; }
        .item-card:hover { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); transform: translateY(-2px); }
        .product-card { border-left: 4px solid #1976D2; }
        .category-card { border-left: 4px solid #f59e0b; }

        .item-image-wrapper { position: relative; display: inline-block; }
        .item-image { width: 80px; height: 80px; border-radius: 10px; overflow: hidden; flex-shrink: 0; }
        .category-image { width: 100px; height: 70px; border-radius: 8px; }
        .item-image img { width: 100%; height: 100%; object-fit: cover; }

        .qty-badge { position: absolute; top: -8px; right: -8px; width: 28px; height: 28px; background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); color: white; font-size: 13px; font-weight: 700; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(25, 118, 210, 0.4); border: 2px solid white; }
        .category-card .qty-badge { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4); }

        .item-details { flex: 1; }
        .item-title { font-size: 15px; font-weight: 600; color: #111827; margin: 0 0 6px 0; line-height: 1.4; }
        .item-type-badge { display: inline-block; font-size: 11px; font-weight: 500; color: #92400e; background: #fef3c7; padding: 3px 10px; border-radius: 12px; margin-bottom: 8px; }

        .item-price { padding-top: 12px; border-top: 1px solid #e5e7eb; text-align: right; }
        .price-value { font-size: 16px; font-weight: 700; color: #059669; }
        .price-waiting { font-size: 13px; font-weight: 500; color: #6b7280; font-style: italic; }

        .no-items-message { text-align: center; padding: 40px 20px; color: #9ca3af; }
        .no-items-message i { font-size: 48px; margin-bottom: 12px; display: block; }
        .no-items-message p { margin: 0; font-size: 14px; }

        .item-user-note, .item-admin-note { margin-top: 10px; padding: 10px 12px; border-radius: 6px; font-size: 13px; }
        .item-user-note { background-color: #e0f2fe; border-left: 3px solid #0ea5e9; }
        .item-admin-note { background-color: #fef3c7; border-left: 3px solid #f59e0b; }
        .item-user-note .note-label, .item-admin-note .note-label { font-weight: 600; font-size: 12px; display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
        .item-user-note .note-label { color: #0369a1; }
        .item-admin-note .note-label { color: #b45309; }
        .item-user-note .note-text, .item-admin-note .note-text { margin: 0; line-height: 1.5; }
        .item-user-note .note-text { color: #0c4a6e; }
        .item-admin-note .note-text { color: #78350f; }

        .inquiry-user-note, .inquiry-admin-note { margin-bottom: 16px; padding: 12px 14px; border-radius: 8px; font-size: 13px; }
        .inquiry-user-note { background-color: #e0f2fe; border-left: 4px solid #0ea5e9; }
        .inquiry-admin-note { background-color: #fef3c7; border-left: 4px solid #f59e0b; }
        .inquiry-user-note .note-label, .inquiry-admin-note .note-label { font-weight: 600; font-size: 12px; display: flex; align-items: center; gap: 6px; margin-bottom: 6px; }
        .inquiry-user-note .note-label { color: #0369a1; }
        .inquiry-admin-note .note-label { color: #b45309; }
        .inquiry-user-note .note-text, .inquiry-admin-note .note-text { margin: 0; line-height: 1.5; }
        .inquiry-user-note .note-text { color: #0c4a6e; }
        .inquiry-admin-note .note-text { color: #78350f; }

        .chat-container { display: flex; flex-direction: column; height: 600px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
        .messages-container { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 16px; }

        .conversations-section { display: flex; flex-direction: column; }
        .conversation-message { border-radius: 8px; padding: 16px 20px; max-width: 70%; animation: messageSlide 0.3s ease; }
        @keyframes messageSlide { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .system-message { background: #f3f4f6; border: 1px solid #e5e7eb; align-self: center; max-width: 90%; }
        .system-message .message-content { display: flex; align-items: center; gap: 12px; justify-content: center; }
        .system-message .message-text { flex: 1; font-size: 14px; color: #111827; margin: 0; text-align: center; }
        .system-message .message-time { font-size: 11px; color: #6b7280; }
        .checkmark-icon { color: #10b981; flex-shrink: 0; }

        .chat-input-container { border-top: 1px solid #e5e7eb; padding: 16px 20px; background: #f9fafb; }
        .chat-input-wrapper { display: flex; gap: 12px; align-items: flex-end; }

        .chat-input { flex: 1; border: 1px solid #e5e7eb; border-radius: 20px; padding: 10px 16px; font-size: 14px; font-family: inherit; resize: none; max-height: 120px; min-height: 40px; background: white; transition: border-color 0.3s ease; }
        .send-button { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); border: none; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; flex-shrink: 0; }
        .send-button:disabled { background: #d1d5db; cursor: not-allowed; transform: none; }

        .user-message { background: #e0f2fe; border-left: 4px solid #0ea5e9; align-self: flex-end; }
        .admin-message { background: #fef3c7; border-left: 4px solid #f59e0b; align-self: flex-start; }
        .message-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .message-sender { font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; }
        .user-message .message-sender { color: #0369a1; }
        .admin-message .message-sender { color: #b45309; }
        .user-message .message-text { color: #0c4a6e; }
        .admin-message .message-text { color: #78350f; }

        .status-message { align-self: center; max-width: 90%; text-align: center; }
        .status-message .message-content { display: flex; align-items: center; gap: 10px; justify-content: center; }
        .status-message i { font-size: 20px; }
        .processing-status { background: #dbeafe; border: 1px solid #3b82f6; }
        .processing-status i, .processing-status .message-text { color: #1d4ed8; }
        .completed-status { background: #d1fae5; border: 1px solid #10b981; }
        .completed-status i, .completed-status .message-text { color: #047857; }
        .cancelled-status { background: #fee2e2; border: 1px solid #ef4444; }
        .cancelled-status i, .cancelled-status .message-text { color: #b91c1c; }

        .chat-form { display: flex; width: 100%; }
        .chat-form .chat-input-wrapper { width: 100%; }
        .chat-input-container.disabled { background: #f3f4f6; }
        .chat-disabled-message { color: #6b7280; font-size: 13px; font-style: italic; padding: 10px 0; }

        .summary-section { width: 350px; flex-shrink: 0; }
        .summary-card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; }
        .summary-card h2 { font-size: 18px; font-weight: bold; color: #111827; margin: 0 0 20px 0; }

        .summary-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb; }
        .summary-info { display: flex; flex-direction: row; background: #0585bc; padding: 12px 16px; border-radius: 40px; align-items: center; gap: 6px; }
        .summary-label { font-size: 11px; color: #ffffff; }
        .summary-value { font-size: 14px; font-weight: 600; color: #ffffff; }

        .status-badge { padding: 6px 16px; border-radius: 16px; font-size: 12px; font-weight: 500; }
        .status-badge.pending { background-color: #f59e0b; color: white; }
        .status-badge.processing { background-color: #3b82f6; color: white; }
        .status-badge.completed { background-color: #10b981; color: white; }
        .status-badge.cancelled { background-color: #ef4444; color: white; }

        .summary-details { display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
        .summary-row { display: flex; justify-content: space-between; align-items: center; }
        .row-label { font-size: 13px; color: #6b7280; }
        .row-value { font-size: 13px; font-weight: 600; color: #111827; }

        .summary-total { display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-bottom: 1px solid #e5e7eb; margin-bottom: 20px; }
        .total-label { font-size: 15px; font-weight: 600; color: #111827; }
        .total-value { font-size: 15px; font-weight: bold; color: #111827; }

        .btn { padding: 8px 20px; border-radius: 20px; font-size: 13px; font-weight: 500; border: none; cursor: pointer; white-space: nowrap; transition: all 0.3s ease; align-self: flex-start; }

        .action-buttons { display: flex; gap: 12px; }
        .action-buttons form { flex: 1; }

        .btn-accept { width: 100%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-cancel { width: 100%; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 12px; font-size: 14px; border-radius: 20px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s ease; }
        .btn-completed { width: 100%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px; font-size: 14px; border-radius: 20px; border: none; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-cancelled { width: 100%; background: #9ca3af; color: white; padding: 12px; font-size: 14px; border-radius: 20px; border: none; display: flex; align-items: center; justify-content: center; gap: 8px; }

        @media (max-width: 1024px) {
            .main-content { flex-direction: column; }
            .summary-section { width: 100%; }
            .tab { padding: 12px 80px; }
            .conversation-message { max-width: 85%; }
            .accordion-header { padding: 16px 20px; }
            .accordion-header-right { flex-direction: column; align-items: flex-end; gap: 8px; }
        }

        @media (max-width: 768px) {
            .inquiry-details-container { padding: 24px 16px; }
            .tab { padding: 12px 40px; }
            .chat-container { height: 500px; }
            .page-title { font-size: 24px; }
            .accordion-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .accordion-header-right { flex-direction: row; width: 100%; justify-content: space-between; }
            .accordion-title { font-size: 14px; }
            .accordion-meta { font-size: 12px; }
            .accordion-content { padding: 16px; }
            .accordion-total { font-size: 14px; }
            .items-grid { grid-template-columns: 1fr; }
            .items-group { padding: 16px; }
            .item-card { padding: 14px; }
        }
    </style>

    <script>
        // Toggle Accordion
        function toggleAccordion(inquiryId) {
            const accordion = document.querySelector('.inquiry-accordion[data-inquiry-id="' + inquiryId + '"]');
            const content = document.getElementById('accordion-content-' + inquiryId);

            if (!accordion || !content) return;

            const isOpen = accordion.classList.contains('open');

            if (isOpen) {
                accordion.classList.remove('open');
                content.style.display = 'none';
            } else {
                accordion.classList.add('open');
                content.style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Tabs switching
            document.querySelectorAll('.tabs-container .tab').forEach(function(tab) {
                tab.addEventListener('click', function(e) {
                    e.stopPropagation();

                    const inquiryId = tab.getAttribute('data-inquiry');
                    const targetId  = tab.getAttribute('data-target');

                    tab.parentElement.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    const items = document.getElementById('itemsContent-' + inquiryId);
                    const conv  = document.getElementById('conversationsContent-' + inquiryId);

                    if (!items || !conv) return;

                    if (targetId === ('itemsContent-' + inquiryId)) {
                        items.style.display = 'flex';
                        conv.style.display  = 'none';
                    } else {
                        conv.style.display  = 'flex';
                        items.style.display = 'none';
                    }
                });
            });

            // Handle chat form submission
            document.querySelectorAll('.chat-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const inquiryId = form.getAttribute('data-inquiry-id');
                    const textarea = form.querySelector('textarea[name="message"]');
                    const message = textarea.value.trim();
                    const submitBtn = form.querySelector('.send-button');

                    if (!message) return;

                    // Disable form while submitting
                    textarea.disabled = true;
                    submitBtn.disabled = true;

                    fetch('/inquiries/' + inquiryId + '/notes', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message: message })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            // Add new message to container
                            const container = document.getElementById('messagesContainer-' + inquiryId);
                            const statusMessages = container.querySelectorAll('.status-message');

                            const messageHtml = `
                                <div class="conversation-message user-message">
                                    <div class="message-header">
                                        <span class="message-sender">
                                            <i class="las la-user"></i> You
                                        </span>
                                        <span class="message-time">${data.note.created_at}</span>
                                    </div>
                                    <p class="message-text">${data.note.message}</p>
                                </div>
                            `;

                            // Insert before status messages or at the end
                            if (statusMessages.length > 0) {
                                statusMessages[0].insertAdjacentHTML('beforebegin', messageHtml);
                            } else {
                                container.insertAdjacentHTML('beforeend', messageHtml);
                            }

                            // Clear textarea
                            textarea.value = '';

                            // Scroll to bottom
                            container.scrollTop = container.scrollHeight;
                        } else {
                            alert(data.message || 'Failed to send message');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to send message');
                    })
                    .finally(() => {
                        textarea.disabled = false;
                        submitBtn.disabled = false;
                        textarea.focus();
                    });
                });
            });
        });
    </script>
@endsection
