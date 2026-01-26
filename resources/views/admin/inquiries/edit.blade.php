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
    .inquiry-card .card-header.fees-header {
        background: linear-gradient(135deg, #4776E6 0%, #8E54E9 100%);
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
    .item-card.marked-delete {
        background: #fff5f5;
        border-color: #fed7d7;
        opacity: 0.6;
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
        margin-bottom: 0.75rem;
    }
    .item-meta-badge {
        background: #e2e8f0;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: #4a5568;
    }
    .item-note-input {
        background: #fff;
        border: 1px dashed #cbd5e0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        width: 100%;
        transition: all 0.2s;
    }
    .item-note-input:focus {
        border-style: solid;
        border-color: #667eea;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .item-image {
        width: 70px;
        height: 70px;
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
        font-size: 1.5rem;
    }
    .delete-btn {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: #fff;
        color: #e53e3e;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .delete-btn:hover {
        background: #e53e3e;
        color: #fff;
        transform: scale(1.1);
    }
    .undo-btn {
        background: #48bb78;
        color: white;
    }
    .undo-btn:hover {
        background: #38a169;
        color: white;
    }
    .quantity-input {
        width: 80px;
        text-align: center;
        font-weight: 600;
        border-radius: 8px;
    }
    .unit-input {
        width: 100px;
        border-radius: 8px;
    }
    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 13px;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-processing { background: #dbeafe; color: #1e40af; }
    .status-completed { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }
    .summary-card {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        color: white;
        border-radius: 12px;
    }
    .summary-card .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .summary-card .summary-row:last-child {
        border-bottom: none;
    }
    .summary-card .summary-total {
        font-size: 1.5rem;
        font-weight: 700;
    }
    .save-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .fee-input-group {
        background: #f7fafc;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 12px;
    }
    .fee-input-group label {
        font-size: 12px;
        color: #718096;
        margin-bottom: 5px;
        font-weight: 500;
    }
    .fee-input-group input {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px;
        font-weight: 600;
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
    .user-note-card {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        border-radius: 12px;
        padding: 1rem;
        border: 2px solid #7dd3fc;
        margin-bottom: 1rem;
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
    .item-user-note {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        border: 2px solid #7dd3fc;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        color: #0c4a6e;
        margin-bottom: 10px;
    }
    .item-user-note .note-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0369a1;
        margin-bottom: 3px;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="las la-file-invoice text-primary"></i>
                {{ translate('Edit Inquiry') }}
                <span class="text-muted">#{{ $inquiry->code }}</span>
            </h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="btn btn-light rounded-pill px-4">
                <i class="las la-arrow-left"></i> {{ translate('Back') }}
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.inquiries.update', $inquiry->id) }}" method="POST" id="inquiry-form">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Inquiry Info Card -->
            <div class="card inquiry-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="las la-info-circle mr-2"></i>{{ translate('Inquiry Information') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">{{ translate('Customer') }}</label>
                            <div class="d-flex align-items-center mt-1">
                                <div class="avatar avatar-sm mr-2">
                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                        {{ $inquiry->user ? strtoupper(substr($inquiry->user->name, 0, 1)) : 'G' }}
                                    </span>
                                </div>
                                <div>
                                    <strong>{{ $inquiry->user ? $inquiry->user->name : translate('Guest') }}</strong>
                                    @if($inquiry->user)
                                        <br><small class="text-muted">{{ $inquiry->user->email }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">{{ translate('Status') }}</label>
                            <select name="status" class="form-control mt-1" style="border-radius: 8px;">
                                <option value="pending" {{ $inquiry->status == 'pending' ? 'selected' : '' }}>{{ translate('Pending') }}</option>
                                <option value="processing" {{ $inquiry->status == 'processing' ? 'selected' : '' }}>{{ translate('Processing') }}</option>
                                <option value="completed" {{ $inquiry->status == 'completed' ? 'selected' : '' }}>{{ translate('Completed') }}</option>
                                <option value="cancelled" {{ $inquiry->status == 'cancelled' ? 'selected' : '' }}>{{ translate('Cancelled') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">{{ translate('Created') }}</label>
                            <div class="mt-1">
                                <i class="las la-calendar text-muted"></i>
                                {{ $inquiry->created_at->format('d M, Y') }}
                                <span class="text-muted">{{ translate('at') }}</span>
                                {{ $inquiry->created_at->format('h:i A') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">{{ translate('Last Updated') }}</label>
                            <div class="mt-1">
                                <i class="las la-clock text-muted"></i>
                                {{ $inquiry->updated_at->diffForHumans() }}
                            </div>
                        </div>
                        @if($inquiry->user_note)
                            <div class="col-12 mb-3">
                                <div class="user-note-card">
                                    <div class="note-label"><i class="las la-user mr-1"></i>{{ translate('Customer Note') }} <small class="text-muted">({{ translate('Read Only') }})</small></div>
                                    <div class="note-text">{{ $inquiry->user_note }}</div>
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <label class="text-muted small">{{ translate('Admin Note') }}</label>
                            <textarea name="note" class="form-control mt-1" rows="2" style="border-radius: 8px;" placeholder="{{ translate('Add internal notes about this inquiry...') }}">{{ $inquiry->note }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="card inquiry-card mb-4">
                <div class="card-header products-header">
                    <h5 class="mb-0">
                        <i class="las la-box mr-2"></i>{{ translate('Products') }}
                        <span class="badge badge-light text-dark ml-2" id="products-count">
                            {{ $inquiry->items->where('type', 'product')->count() }}
                        </span>
                    </h5>
                </div>
                <div class="card-body p-4" id="products-container">
                    @php $productIndex = 0; @endphp
                    @forelse($inquiry->items->where('type', 'product') as $item)
                        <div class="item-card product-item" data-item-id="{{ $item->id }}">
                            <span class="item-number">{{ ++$productIndex }}</span>
                            <button type="button" class="delete-btn" onclick="toggleDelete(this)" title="{{ translate('Delete') }}">
                                <i class="las la-times"></i>
                            </button>

                            <input type="hidden" name="items[{{ $loop->parent->index ?? $item->id }}][id]" value="{{ $item->id }}">
                            <input type="hidden" name="items[{{ $loop->parent->index ?? $item->id }}][type]" value="product">
                            <input type="hidden" name="items[{{ $loop->parent->index ?? $item->id }}][product_id]" value="{{ $item->product_id }}">
                            <input type="hidden" name="items[{{ $loop->parent->index ?? $item->id }}][_delete]" value="0" class="delete-flag">

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

                                    <div class="item-meta mb-2">
                                        @if($item->product)
                                            <span class="item-meta-badge">
                                                <i class="las la-tag"></i>
                                                {{ number_format($item->product->unit_price ?? 0, 2) }} {{ translate('EGP') }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($item->user_note)
                                        <div class="item-user-note">
                                            <div class="note-label"><i class="las la-user mr-1"></i>{{ translate('Customer Note') }}</div>
                                            {{ $item->user_note }}
                                        </div>
                                    @endif

                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label class="small text-muted mb-1">{{ translate('Qty') }}</label>
                                            <input type="number" name="items[{{ $loop->parent->index ?? $item->id }}][quantity]"
                                                   class="form-control quantity-input" value="{{ $item->quantity }}" min="0.01" step="0.01">
                                        </div>
                                        <div class="col-auto">
                                            <label class="small text-muted mb-1">{{ translate('Unit') }}</label>
                                            <input type="text" name="items[{{ $loop->parent->index ?? $item->id }}][unit]"
                                                   class="form-control unit-input" value="{{ $item->unit }}" placeholder="{{ translate('pcs, kg...') }}">
                                        </div>
                                        <div class="col">
                                            <label class="small text-muted mb-1">{{ translate('Admin Note') }}</label>
                                            <input type="text" name="items[{{ $loop->parent->index ?? $item->id }}][note]"
                                                   class="item-note-input" value="{{ $item->note }}" placeholder="{{ translate('Add note for this item...') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="section-empty" id="no-products">
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
                        <span class="badge badge-light text-dark ml-2" id="categories-count">
                            {{ $inquiry->items->where('type', 'category')->count() }}
                        </span>
                    </h5>
                </div>
                <div class="card-body p-4" id="categories-container">
                    @php $categoryIndex = 0; @endphp
                    @forelse($inquiry->items->where('type', 'category') as $item)
                        <div class="item-card category-item" data-item-id="{{ $item->id }}">
                            <span class="item-number">{{ ++$categoryIndex }}</span>
                            <button type="button" class="delete-btn" onclick="toggleDelete(this)" title="{{ translate('Delete') }}">
                                <i class="las la-times"></i>
                            </button>

                            <input type="hidden" name="items[cat_{{ $item->id }}][id]" value="{{ $item->id }}">
                            <input type="hidden" name="items[cat_{{ $item->id }}][type]" value="category">
                            <input type="hidden" name="items[cat_{{ $item->id }}][category_id]" value="{{ $item->category_id }}">
                            <input type="hidden" name="items[cat_{{ $item->id }}][_delete]" value="0" class="delete-flag">

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

                                    @if($item->user_note)
                                        <div class="item-user-note">
                                            <div class="note-label"><i class="las la-user mr-1"></i>{{ translate('Customer Note') }}</div>
                                            {{ $item->user_note }}
                                        </div>
                                    @endif

                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label class="small text-muted mb-1">{{ translate('Qty') }}</label>
                                            <input type="number" name="items[cat_{{ $item->id }}][quantity]"
                                                   class="form-control quantity-input" value="{{ $item->quantity }}" min="0.01" step="0.01">
                                        </div>
                                        <div class="col-auto">
                                            <label class="small text-muted mb-1">{{ translate('Unit') }}</label>
                                            <input type="text" name="items[cat_{{ $item->id }}][unit]"
                                                   class="form-control unit-input" value="{{ $item->unit }}" placeholder="{{ translate('pcs, kg...') }}">
                                        </div>
                                        <div class="col">
                                            <label class="small text-muted mb-1">{{ translate('Admin Note') }}</label>
                                            <input type="text" name="items[cat_{{ $item->id }}][note]"
                                                   class="item-note-input" value="{{ $item->note }}" placeholder="{{ translate('Add note for this item...') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="section-empty" id="no-categories">
                            <i class="las la-folder"></i>
                            <p>{{ translate('No categories in this inquiry') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Fees Card -->
            <div class="card inquiry-card mb-4">
                <div class="card-header fees-header">
                    <h5 class="mb-0"><i class="las la-calculator mr-2"></i>{{ translate('Fees & Adjustments') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="fee-input-group">
                        <label><i class="las la-percent mr-1"></i>{{ translate('Tax') }}</label>
                        <div class="input-group">
                            <input type="number" name="tax" id="tax" class="form-control calc-input" value="{{ $inquiry->tax }}" min="0" step="0.01">
                            <div class="input-group-append">
                                <span class="input-group-text">{{ translate('EGP') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="fee-input-group">
                        <label><i class="las la-truck mr-1"></i>{{ translate('Delivery') }}</label>
                        <div class="input-group">
                            <input type="number" name="delivery" id="delivery" class="form-control calc-input" value="{{ $inquiry->delivery }}" min="0" step="0.01">
                            <div class="input-group-append">
                                <span class="input-group-text">{{ translate('EGP') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="fee-input-group">
                        <label><i class="las la-plus-circle mr-1"></i>{{ translate('Extra Fees') }}</label>
                        <div class="input-group">
                            <input type="number" name="extra_fees" id="extra_fees" class="form-control calc-input" value="{{ $inquiry->extra_fees }}" min="0" step="0.01">
                            <div class="input-group-append">
                                <span class="input-group-text">{{ translate('EGP') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="fee-input-group" style="background: #fff5f5;">
                        <label><i class="las la-minus-circle mr-1 text-danger"></i>{{ translate('Discount') }}</label>
                        <div class="input-group">
                            <input type="number" name="discount" id="discount" class="form-control calc-input" value="{{ $inquiry->discount }}" min="0" step="0.01">
                            <div class="input-group-append">
                                <span class="input-group-text">{{ translate('EGP') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Totals Card (Auto-Calculated) -->
            <div class="card inquiry-card mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <h5 class="mb-0"><i class="las la-coins mr-2"></i>{{ translate('Totals') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="fee-input-group">
                        <label><i class="las la-box mr-1"></i>{{ translate('Products Total') }}</label>
                        <div class="input-group">
                            <input type="number" name="products_total" id="products_total" class="form-control calc-input" value="{{ $inquiry->products_total }}" min="0" step="0.01">
                            <div class="input-group-append">
                                <span class="input-group-text">{{ translate('EGP') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="fee-input-group">
                        <label><i class="las la-folder mr-1"></i>{{ translate('Categories Total') }}</label>
                        <div class="input-group">
                            <input type="number" name="categories_total" id="categories_total" class="form-control calc-input" value="{{ $inquiry->categories_total }}" min="0" step="0.01">
                            <div class="input-group-append">
                                <span class="input-group-text">{{ translate('EGP') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="fee-input-group" style="background: #e6fffa;">
                        <label><i class="las la-calculator mr-1"></i>{{ translate('Subtotal') }} <small class="text-muted">({{ translate('Auto') }})</small></label>
                        <div class="input-group">
                            <input type="number" name="subtotal" id="subtotal" class="form-control" value="{{ $inquiry->subtotal }}" min="0" step="0.01" readonly style="background: #e6fffa; font-weight: 600;">
                            <div class="input-group-append">
                                <span class="input-group-text">{{ translate('EGP') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="fee-input-group" style="background: #f0fff4; border: 2px solid #38ef7d;">
                        <label><i class="las la-money-bill-wave mr-1 text-success"></i>{{ translate('Total') }} <small class="text-muted">({{ translate('Auto') }})</small></label>
                        <div class="input-group">
                            <input type="number" name="total" id="total" class="form-control font-weight-bold" value="{{ $inquiry->total }}" min="0" step="0.01" readonly style="font-size: 1.1rem; background: #f0fff4;">
                            <div class="input-group-append">
                                <span class="input-group-text">{{ translate('EGP') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Card (Preview) -->
            <div class="card summary-card mb-4">
                <div class="card-body p-4">
                    <h6 class="text-white-50 mb-3">{{ translate('Live Preview') }}</h6>

                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Products Total') }}</span>
                        <span id="preview-products-total">{{ number_format($inquiry->products_total, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Categories Total') }}</span>
                        <span id="preview-categories-total">{{ number_format($inquiry->categories_total, 2) }}</span>
                    </div>
                    <div class="summary-row" style="background: rgba(56, 239, 125, 0.1); margin: 0 -1rem; padding: 10px 1rem; border-radius: 6px;">
                        <span class="text-white-50"><strong>{{ translate('Subtotal') }}</strong></span>
                        <span id="preview-subtotal" style="font-weight: 600;">{{ number_format($inquiry->subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Tax') }}</span>
                        <span class="text-success" id="preview-tax">+{{ number_format($inquiry->tax, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Delivery') }}</span>
                        <span class="text-success" id="preview-delivery">+{{ number_format($inquiry->delivery, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Extra Fees') }}</span>
                        <span class="text-success" id="preview-extra-fees">+{{ number_format($inquiry->extra_fees, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Discount') }}</span>
                        <span class="text-danger" id="preview-discount">-{{ number_format($inquiry->discount, 2) }}</span>
                    </div>
                    <div class="summary-row pt-3 mt-2" style="border-top: 2px solid rgba(255,255,255,0.2);">
                        <span class="summary-total">{{ translate('Total') }}</span>
                        <span class="summary-total"><span id="preview-total">{{ number_format($inquiry->total, 2) }}</span> <small>{{ translate('EGP') }}</small></span>
                    </div>

                    <div class="mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                        <small class="text-white-50">
                            <i class="las la-sync-alt"></i>
                            {{ translate('Updates automatically as you type') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card inquiry-card">
                <div class="card-body p-4 text-center">
                    <button type="submit" class="btn btn-primary save-btn btn-block mb-3">
                        <i class="las la-save mr-2"></i>{{ translate('Save Changes') }}
                    </button>
                    <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="btn btn-light btn-block rounded-pill">
                        {{ translate('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('script')
<script type="text/javascript">
    function toggleDelete(btn) {
        var card = $(btn).closest('.item-card');
        var deleteFlag = card.find('.delete-flag');
        var icon = $(btn).find('i');

        if (deleteFlag.val() === '0') {
            // Mark for deletion
            deleteFlag.val('1');
            card.addClass('marked-delete');
            icon.removeClass('la-times').addClass('la-undo');
            $(btn).addClass('undo-btn');
            $(btn).attr('title', '{{ translate("Undo") }}');
        } else {
            // Undo deletion
            deleteFlag.val('0');
            card.removeClass('marked-delete');
            icon.removeClass('la-undo').addClass('la-times');
            $(btn).removeClass('undo-btn');
            $(btn).attr('title', '{{ translate("Delete") }}');
        }

        updateCounts();
    }

    function updateCounts() {
        var activeProducts = $('#products-container .item-card:not(.marked-delete)').length;
        var activeCategories = $('#categories-container .item-card:not(.marked-delete)').length;

        $('#products-count').text(activeProducts);
        $('#categories-count').text(activeCategories);

        // Show/hide empty messages
        if (activeProducts === 0) {
            if ($('#no-products').length === 0) {
                $('#products-container').append('<div class="section-empty" id="no-products"><i class="las la-box"></i><p>{{ translate("No products in this inquiry") }}</p></div>');
            }
            $('#no-products').show();
        } else {
            $('#no-products').hide();
        }

        if (activeCategories === 0) {
            if ($('#no-categories').length === 0) {
                $('#categories-container').append('<div class="section-empty" id="no-categories"><i class="las la-folder"></i><p>{{ translate("No categories in this inquiry") }}</p></div>');
            }
            $('#no-categories').show();
        } else {
            $('#no-categories').hide();
        }
    }

    // ============ Auto Calculate Totals ============
    function calculateTotals() {
        // Get values (default to 0 if empty)
        var productsTotal = parseFloat($('#products_total').val()) || 0;
        var categoriesTotal = parseFloat($('#categories_total').val()) || 0;
        var tax = parseFloat($('#tax').val()) || 0;
        var delivery = parseFloat($('#delivery').val()) || 0;
        var extraFees = parseFloat($('#extra_fees').val()) || 0;
        var discount = parseFloat($('#discount').val()) || 0;

        // Calculate Subtotal = Products Total + Categories Total
        var subtotal = productsTotal + categoriesTotal;

        // Calculate Total = Subtotal + Tax + Delivery + Extra Fees - Discount
        var total = subtotal + tax + delivery + extraFees - discount;

        // Update the readonly fields
        $('#subtotal').val(subtotal.toFixed(2));
        $('#total').val(total.toFixed(2));

        // Update the preview card as well
        updatePreviewCard(productsTotal, categoriesTotal, subtotal, tax, delivery, extraFees, discount, total);
    }

    function updatePreviewCard(productsTotal, categoriesTotal, subtotal, tax, delivery, extraFees, discount, total) {
        $('#preview-products-total').text(productsTotal.toFixed(2));
        $('#preview-categories-total').text(categoriesTotal.toFixed(2));
        $('#preview-subtotal').text(subtotal.toFixed(2));
        $('#preview-tax').text('+' + tax.toFixed(2));
        $('#preview-delivery').text('+' + delivery.toFixed(2));
        $('#preview-extra-fees').text('+' + extraFees.toFixed(2));
        $('#preview-discount').text('-' + discount.toFixed(2));
        $('#preview-total').text(total.toFixed(2));
    }

    $(document).ready(function() {
        updateCounts();

        // Listen for changes on all calc-input fields
        $('.calc-input').on('input change', function() {
            calculateTotals();
        });

        // Calculate on page load
        calculateTotals();

        // Animate cards on load
        $('.item-card').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            }).delay(index * 50).animate({
                'opacity': '1'
            }, 300).css('transform', 'translateY(0)');
        });
    });
</script>
@endsection
