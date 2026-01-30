@extends('backend.layouts.app')

@section('content')

<style>
    .inquiry-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .inquiry-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }
    .inquiry-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1rem 1.5rem;
    }
    .inquiry-card .card-header.products-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .inquiry-card .card-header.categories-header {
        background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
    }
    .status-badge-lg {
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .status-pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    .status-processing {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }
    .status-completed {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }
    .status-cancelled {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }
    .info-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 1rem;
        transition: all 0.2s;
    }
    .info-item:hover {
        background: #f1f5f9;
    }
    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #718096;
        margin-bottom: 5px;
    }
    .info-value {
        font-weight: 600;
        color: #2d3748;
        font-size: 15px;
    }
    .customer-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
    }
    .customer-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        margin-right: 1rem;
    }
    .item-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
        position: relative;
    }
    .item-card:hover {
        background: #fff;
        border-color: #dee2e6;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .item-number {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 12px;
    }
    .item-card.product-item .item-number {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .item-card.category-item .item-number {
        background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
    }
    .item-name {
        font-weight: 600;
        color: #2d3748;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    .item-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .item-meta-badge {
        background: #e2e8f0;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: #4a5568;
    }
    .item-note {
        background: #fff;
        border: 1px dashed #cbd5e0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        color: #4a5568;
        margin-top: 10px;
    }
    .item-image {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .item-image .no-image {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e0;
        font-size: 2rem;
    }
    .summary-card {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        color: white;
        border-radius: 12px;
    }
    .summary-card .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .summary-card .summary-row:last-child {
        border-bottom: none;
    }
    .summary-card .summary-total {
        font-size: 1.75rem;
        font-weight: 700;
    }
    .action-btn-lg {
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
    }
    .action-btn-lg:hover {
        transform: translateY(-2px);
    }
    .action-btn-lg.edit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .action-btn-lg.edit:hover {
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    .action-btn-lg.delete {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: white;
    }
    .action-btn-lg.delete:hover {
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.4);
        color: white;
    }
    .back-btn {
        background: #f1f5f9;
        color: #4a5568;
        border-radius: 25px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .back-btn:hover {
        background: #e2e8f0;
        color: #2d3748;
    }
    .section-empty {
        text-align: center;
        padding: 2rem;
        color: #a0aec0;
    }
    .section-empty i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    .note-card {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border-radius: 12px;
        padding: 1rem;
    }
    .note-card .note-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #744210;
        margin-bottom: 5px;
    }
    .note-card .note-text {
        color: #744210;
        font-weight: 500;
    }
    .user-note-card {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        border-radius: 12px;
        padding: 1rem;
        border: 2px solid #7dd3fc;
    }
    .user-note-card .note-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0369a1;
        margin-bottom: 5px;
    }
    .user-note-card .note-text {
        color: #0c4a6e;
        font-weight: 500;
    }
    .user-note-card .note-icon {
        color: #0284c7;
    }
    .item-user-note {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        border: 2px solid #7dd3fc;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        color: #0c4a6e;
        margin-top: 8px;
    }
    .item-user-note .note-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0369a1;
        margin-bottom: 3px;
    }
    .conv-message {
        padding: 12px 16px;
        border-radius: 10px;
        max-width: 85%;
    }
    .conv-message.system-msg {
        background: #f3f4f6;
        max-width: 100%;
        text-align: center;
        border-radius: 8px;
        padding: 10px;
    }
    .conv-message.admin-msg {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        margin-left: auto;
        border-bottom-right-radius: 4px;
    }
    .conv-message.user-msg {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        margin-right: auto;
        border-bottom-left-radius: 4px;
    }
    .msg-body {
        color: #374151;
        line-height: 1.5;
        white-space: pre-wrap;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="las la-file-invoice text-primary"></i>
                {{ translate('Inquiry Details') }}
                <span class="text-muted">#{{ $inquiry->code }}</span>
            </h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.inquiries.index') }}" class="back-btn">
                <i class="las la-arrow-left mr-1"></i> {{ translate('Back to List') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Status & Info Card -->
        <div class="card inquiry-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="las la-info-circle mr-2"></i>{{ translate('Inquiry Information') }}</h5>
                @switch($inquiry->status)
                    @case('pending')
                        <span class="status-badge-lg status-pending">{{ translate('Pending') }}</span>
                        @break
                    @case('processing')
                        <span class="status-badge-lg status-processing">{{ translate('Processing') }}</span>
                        @break
                    @case('completed')
                        <span class="status-badge-lg status-completed">{{ translate('Completed') }}</span>
                        @break
                    @case('cancelled')
                        <span class="status-badge-lg status-cancelled">{{ translate('Cancelled') }}</span>
                        @break
                    @default
                        <span class="status-badge-lg" style="background: #e2e8f0; color: #4a5568;">{{ $inquiry->status }}</span>
                @endswitch
            </div>
            <div class="card-body p-4">
                <!-- Customer Card -->
                <div class="customer-card mb-4">
                    <div class="d-flex align-items-center">
                        <div class="customer-avatar">
                            {{ $inquiry->user ? strtoupper(substr($inquiry->user->name, 0, 1)) : 'G' }}
                        </div>
                        <div>
                            <div style="opacity: 0.8; font-size: 12px;">{{ translate('Customer') }}</div>
                            <div style="font-size: 1.25rem; font-weight: 700;">
                                {{ $inquiry->user ? $inquiry->user->name : translate('Guest') }}
                            </div>
                            @if($inquiry->user)
                                <div style="opacity: 0.9;">{{ $inquiry->user->email }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">{{ translate('Inquiry Code') }}</div>
                            <div class="info-value">{{ $inquiry->code }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">{{ translate('Assigned Admin') }}</div>
                            <div class="info-value">
                                @if($inquiry->admin)
                                    <i class="las la-user-shield text-primary mr-1"></i>
                                    {{ $inquiry->admin->name }}
                                @else
                                    <span class="text-muted">{{ translate('Not Assigned') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">{{ translate('Created At') }}</div>
                            <div class="info-value">
                                <i class="las la-calendar text-muted mr-1"></i>
                                {{ $inquiry->created_at->format('d M, Y') }}
                                <span class="text-muted">{{ translate('at') }}</span>
                                {{ $inquiry->created_at->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">{{ translate('Last Updated') }}</div>
                            <div class="info-value">
                                <i class="las la-clock text-muted mr-1"></i>
                                {{ $inquiry->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>

                @if($inquiry->user_note)
                    <div class="user-note-card mt-3">
                        <div class="note-label"><i class="las la-user note-icon mr-1"></i>{{ translate('Customer Note') }}</div>
                        <div class="note-text">{{ $inquiry->user_note }}</div>
                    </div>
                @endif

                @if($inquiry->note)
                    <div class="note-card mt-3">
                        <div class="note-label"><i class="las la-sticky-note mr-1"></i>{{ translate('Admin Note') }}</div>
                        <div class="note-text">{{ $inquiry->note }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Products Section -->
        <div class="card inquiry-card mb-4">
            <div class="card-header products-header">
                <h5 class="mb-0">
                    <i class="las la-box mr-2"></i>{{ translate('Products') }}
                    <span class="badge badge-light text-dark ml-2">
                        {{ $inquiry->items->where('type', 'product')->count() }}
                    </span>
                </h5>
            </div>
            <div class="card-body p-4">
                @php $productIndex = 0; @endphp
                @forelse($inquiry->items->where('type', 'product') as $item)

                    @php
                        // ✅ Price source:
                        // 1) saved item price (if stored)
                        // 2) fallback to product unit_price
                        $unitPrice = (float)($item->price ?? ($item->product->unit_price ?? 0));
                        $qty = (float)($item->quantity ?? 0);
                        $lineTotal = $unitPrice * $qty;
                    @endphp

                    <div class="item-card product-item">
                        <span class="item-number">{{ ++$productIndex }}</span>

                        <div class="d-flex">
                            <!-- Product Image -->
                            <div class="item-image mr-3">
                                @if($item->product && $item->product->thumbnail_img)
                                    <img src="{{ uploaded_asset($item->product->thumbnail_img) }}"
                                         alt="{{ $item->product->name }}"
                                         onerror="this.src='{{ static_asset('assets/img/placeholder.jpg') }}'">
                                @else
                                    <div class="no-image">
                                        <i class="las la-image"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="flex-grow-1">
                                <div class="item-name">
                                    {{ $item->product ? $item->product->name : translate('Unknown Product') }}
                                </div>

                                <div class="item-meta">
                                    <span class="item-meta-badge">
                                        <i class="las la-sort-amount-up"></i>
                                        {{ translate('Qty') }}: <strong>{{ $qty }}</strong>
                                    </span>

                                    @if($item->unit)
                                        <span class="item-meta-badge">
                                            <i class="las la-balance-scale"></i>
                                            {{ $item->unit }}
                                        </span>
                                    @endif

                                    <!-- ✅ Unit Price -->
                                    <span class="item-meta-badge">
                                        <i class="las la-tag"></i>
                                        {{ translate('Unit Price') }}:
                                        <strong>{{ number_format($unitPrice, 2) }}</strong>
                                        {{ translate('EGP') }}
                                    </span>

                                    <!-- ✅ Line Total -->
                                    <span class="item-meta-badge" style="background: #d1fae5; color: #065f46;">
                                        <i class="las la-calculator"></i>
                                        {{ translate('Total') }}:
                                        <strong>{{ number_format($lineTotal, 2) }}</strong>
                                        {{ translate('EGP') }}
                                    </span>
                                </div>

                                @if($item->user_note)
                                    <div class="item-user-note mt-2">
                                        <div class="note-label"><i class="las la-user mr-1"></i>{{ translate('Customer Note') }}</div>
                                        {{ $item->user_note }}
                                    </div>
                                @endif

                                @if($item->note)
                                    <div class="item-note mt-2">
                                        <i class="las la-comment-dots text-muted mr-1"></i>
                                        <strong>{{ translate('Admin') }}:</strong> {{ $item->note }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="section-empty">
                        <i class="las la-box"></i>
                        <p>{{ translate('No products in this inquiry') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Categories Section -->
        <div class="card inquiry-card mb-4">
            <div class="card-header categories-header">
                <h5 class="mb-0">
                    <i class="las la-folder mr-2"></i>{{ translate('Categories') }}
                    <span class="badge badge-light text-dark ml-2">
                        {{ $inquiry->items->where('type', 'category')->count() }}
                    </span>
                </h5>
            </div>
            <div class="card-body p-4">
                @php $categoryIndex = 0; @endphp
                @forelse($inquiry->items->where('type', 'category') as $item)

                    @php
                        // ✅ Category unit price is stored on item.price (no product fallback)
                        $catUnitPrice = (float)($item->price ?? 0);
                        $catQty = (float)($item->quantity ?? 0);
                        $catLineTotal = $catUnitPrice * $catQty;
                    @endphp

                    <div class="item-card category-item">
                        <span class="item-number">{{ ++$categoryIndex }}</span>

                        <div class="d-flex">
                            <!-- Category Image -->
                            <div class="item-image mr-3">
                                @if($item->category && $item->category->banner)
                                    <img src="{{ uploaded_asset($item->category->banner) }}"
                                         alt="{{ $item->category->name }}"
                                         onerror="this.src='{{ static_asset('assets/img/placeholder.jpg') }}'">
                                @else
                                    <div class="no-image">
                                        <i class="las la-folder"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Category Details -->
                            <div class="flex-grow-1">
                                <div class="item-name">
                                    {{ $item->category ? $item->category->name : translate('Unknown Category') }}
                                </div>

                                <div class="item-meta">
                                    <span class="item-meta-badge">
                                        <i class="las la-sort-amount-up"></i>
                                        {{ translate('Qty') }}: <strong>{{ $catQty }}</strong>
                                    </span>

                                    @if($item->unit)
                                        <span class="item-meta-badge">
                                            <i class="las la-balance-scale"></i>
                                            {{ $item->unit }}
                                        </span>
                                    @endif

                                    <!-- ✅ Unit Price (Category) -->
                                    <span class="item-meta-badge">
                                        <i class="las la-tag"></i>
                                        {{ translate('Unit Price') }}:
                                        <strong>{{ number_format($catUnitPrice, 2) }}</strong>
                                        {{ translate('EGP') }}
                                    </span>

                                    <!-- ✅ Line Total (Category) -->
                                    <span class="item-meta-badge" style="background: #d1fae5; color: #065f46;">
                                        <i class="las la-calculator"></i>
                                        {{ translate('Total') }}:
                                        <strong>{{ number_format($catLineTotal, 2) }}</strong>
                                        {{ translate('EGP') }}
                                    </span>
                                </div>

                                @if($item->user_note)
                                    <div class="item-user-note mt-2">
                                        <div class="note-label"><i class="las la-user mr-1"></i>{{ translate('Customer Note') }}</div>
                                        {{ $item->user_note }}
                                    </div>
                                @endif

                                @if($item->note)
                                    <div class="item-note mt-2">
                                        <i class="las la-comment-dots text-muted mr-1"></i>
                                        <strong>{{ translate('Admin') }}:</strong> {{ $item->note }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="section-empty">
                        <i class="las la-folder"></i>
                        <p>{{ translate('No categories in this inquiry') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Conversation/Notes Section -->
        <div class="card inquiry-card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                <h5 class="mb-0">
                    <i class="las la-comments mr-2"></i>{{ translate('Conversation') }}
                    <span class="badge badge-light text-dark ml-2">{{ $inquiry->notes->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="conversation-container" id="conversationContainer" style="max-height: 400px; overflow-y: auto;">
                    {{-- System message for inquiry creation --}}
                    <div class="conv-message system-msg mb-3">
                        <div class="d-flex align-items-center justify-content-center text-muted">
                            <i class="las la-file-alt mr-2"></i>
                            <span>{{ translate('Inquiry created') }}</span>
                            <span class="mx-2">•</span>
                            <small>{{ $inquiry->created_at->format('d M Y - H:i') }}</small>
                        </div>
                    </div>

                    {{-- Display all notes --}}
                    @foreach($inquiry->notes as $note)
                        <div class="conv-message {{ $note->sender_type === 'admin' ? 'admin-msg' : 'user-msg' }} mb-3">
                            <div class="msg-header d-flex justify-content-between align-items-center mb-2">
                                <span class="msg-sender">
                                    @if($note->sender_type === 'admin')
                                        <i class="las la-user-shield text-primary mr-1"></i>
                                        <strong>{{ $note->user->name ?? 'Admin' }}</strong>
                                        <span class="badge badge-primary ml-1">{{ translate('Admin') }}</span>
                                    @else
                                        <i class="las la-user text-info mr-1"></i>
                                        <strong>{{ $note->user->name ?? 'Customer' }}</strong>
                                        <span class="badge badge-info ml-1">{{ translate('Customer') }}</span>
                                    @endif
                                </span>
                                <small class="text-muted">{{ $note->created_at->format('d M Y - H:i') }}</small>
                            </div>
                            <div class="msg-body">
                                {{ $note->message }}
                            </div>
                        </div>
                    @endforeach

                    @if($inquiry->notes->count() === 0)
                        <div class="text-center text-muted py-4">
                            <i class="las la-comment-slash" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-2">{{ translate('No messages yet') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Add Note Form --}}
                <div class="add-note-form mt-4 pt-3" style="border-top: 1px solid #e9ecef;">
                    <form action="{{ route('admin.inquiries.addNote', $inquiry->id) }}" method="POST" id="addNoteForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label fw-600">{{ translate('Add a Note') }}</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="{{ translate('Type your message here...') }}" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-paper-plane mr-1"></i>{{ translate('Send') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Summary Card -->
        <div class="card summary-card mb-4">
            <div class="card-body p-4">
                <h6 class="text-white-50 mb-3">
                    <i class="las la-receipt mr-1"></i>
                    {{ translate('Order Summary') }}
                </h6>

                <div class="summary-row">
                    <span class="text-white-50">{{ translate('Products Total') }}</span>
                    <span>{{ number_format($inquiry->products_total, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="text-white-50">{{ translate('Categories Total') }}</span>
                    <span>{{ number_format($inquiry->categories_total, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="text-white-50">{{ translate('Subtotal') }}</span>
                    <span>{{ number_format($inquiry->subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="text-white-50">{{ translate('Tax') }}</span>
                    <span class="text-success">+{{ number_format($inquiry->tax, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="text-white-50">{{ translate('Delivery') }}</span>
                    <span class="text-success">+{{ number_format($inquiry->delivery, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="text-white-50">{{ translate('Extra Fees') }}</span>
                    <span class="text-success">+{{ number_format($inquiry->extra_fees, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="text-white-50">{{ translate('Discount') }}</span>
                    <span class="text-danger">-{{ number_format($inquiry->discount, 2) }}</span>
                </div>
                <div class="summary-row pt-3 mt-2" style="border-top: 2px solid rgba(255,255,255,0.2);">
                    <span class="summary-total">{{ translate('Total') }}</span>
                    <span class="summary-total">{{ number_format($inquiry->total, 2) }}</span>
                </div>
                <div class="text-center mt-2">
                    <small class="text-white-50">{{ translate('EGP') }}</small>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card inquiry-card">
            <div class="card-body p-4">
                <h6 class="text-muted mb-3">
                    <i class="las la-cog mr-1"></i>
                    {{ translate('Quick Actions') }}
                </h6>

                <a href="{{ route('admin.inquiries.edit', $inquiry->id) }}" class="btn action-btn-lg edit btn-block mb-3">
                    <i class="las la-edit mr-2"></i>{{ translate('Edit Inquiry') }}
                </a>

                <a href="#" class="btn action-btn-lg delete btn-block confirm-delete" data-href="{{ route('admin.inquiries.destroy', $inquiry->id) }}">
                    <i class="las la-trash mr-2"></i>{{ translate('Delete Inquiry') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        // Animate items on load
        $('.item-card').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            }).delay(index * 100).animate({
                'opacity': '1'
            }, 400).css('transform', 'translateY(0)');
        });

        // Handle note form submission via AJAX
        $('#addNoteForm').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var textarea = form.find('textarea[name="message"]');
            var submitBtn = form.find('button[type="submit"]');
            var message = textarea.val().trim();

            if (!message) return;

            // Disable form while submitting
            textarea.prop('disabled', true);
            submitBtn.prop('disabled', true).html('<i class="las la-spinner la-spin mr-1"></i> {{ translate("Sending...") }}');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    message: message
                },
                dataType: 'json',
                success: function(data) {
                    if (data.ok) {
                        // Create new message HTML
                        var messageHtml = `
                            <div class="conv-message admin-msg mb-3" style="animation: slideIn 0.3s ease;">
                                <div class="msg-header d-flex justify-content-between align-items-center mb-2">
                                    <span class="msg-sender">
                                        <i class="las la-user-shield text-primary mr-1"></i>
                                        <strong>${data.note.user_name}</strong>
                                        <span class="badge badge-primary ml-1">{{ translate('Admin') }}</span>
                                    </span>
                                    <small class="text-muted">${data.note.created_at}</small>
                                </div>
                                <div class="msg-body">${data.note.message}</div>
                            </div>
                        `;

                        // Remove "No messages" placeholder if exists
                        $('#conversationContainer .text-center.text-muted').remove();

                        // Append new message
                        $('#conversationContainer').append(messageHtml);

                        // Update counter
                        var badge = $('.card-header .badge');
                        var count = parseInt(badge.text()) || 0;
                        badge.text(count + 1);

                        // Clear textarea
                        textarea.val('');

                        // Scroll to bottom
                        var container = document.getElementById('conversationContainer');
                        container.scrollTop = container.scrollHeight;

                        // Show success toast
                        AIZ.plugins.notify('success', '{{ translate("Note sent successfully") }}');
                    } else {
                        AIZ.plugins.notify('danger', data.message || '{{ translate("Failed to send note") }}');
                    }
                },
                error: function(xhr) {
                    var errorMsg = xhr.responseJSON?.message || '{{ translate("Failed to send note") }}';
                    AIZ.plugins.notify('danger', errorMsg);
                },
                complete: function() {
                    textarea.prop('disabled', false);
                    submitBtn.prop('disabled', false).html('<i class="las la-paper-plane mr-1"></i>{{ translate("Send") }}');
                    textarea.focus();
                }
            });
        });
    });
</script>
<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
