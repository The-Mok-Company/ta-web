@extends('backend.layouts.app')

@section('content')

<style>
    .inquiry-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .inquiry-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.12); }
    .inquiry-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1rem 1.5rem;
    }
    .inquiry-card .card-header.products-header { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .inquiry-card .card-header.categories-header { background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%); }
    .inquiry-card .card-header.fees-header { background: linear-gradient(135deg, #4776E6 0%, #8E54E9 100%); }
    .inquiry-card .card-header.user-header { background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); }

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
    .item-card.product-item .item-number { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .item-card.category-item .item-number { background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%); }

    .item-image {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    .item-image img { width: 100%; height: 100%; object-fit: cover; }
    .item-image .no-image {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: #cbd5e0; font-size: 1.5rem;
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

    .summary-card {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-radius: 12px;
        color: white;
        position: sticky;
        top: 80px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-total { font-size: 1.25rem; font-weight: 700; }

    .form-control-modern {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        transition: all 0.2s;
    }
    .form-control-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .add-item-btn {
        border: 2px dashed #cbd5e0;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: transparent;
        width: 100%;
        color: #718096;
    }
    .add-item-btn:hover {
        border-color: #667eea;
        color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .category-filter {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        font-size: 13px;
    }
    .category-filter:focus {
        border-color: #11998e;
        background: #fff;
    }

    .product-select { font-weight: 500; }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{ translate('Create New Inquiry') }}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary">
                <i class="las la-arrow-left"></i> {{ translate('Back to List') }}
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.inquiries.store') }}" method="POST" id="inquiry-form">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <!-- User Selection -->
            <div class="card inquiry-card mb-4">
                <div class="card-header user-header">
                    <h5 class="mb-0">
                        <i class="las la-user mr-2"></i>{{ translate('Select Customer') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ translate('Customer') }} <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-control aiz-selectpicker" data-live-search="true" required>
                            <option value="">{{ translate('Select Customer') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="card inquiry-card mb-4">
                <div class="card-header products-header">
                    <h5 class="mb-0">
                        <i class="las la-box mr-2"></i>{{ translate('Products') }}
                        <span class="badge badge-light ml-2" id="products-count">0</span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div id="products-container"></div>

                    <button type="button" class="add-item-btn" onclick="addProduct()">
                        <i class="las la-plus-circle mr-2"></i>{{ translate('Add Product') }}
                    </button>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="card inquiry-card mb-4">
                <div class="card-header categories-header">
                    <h5 class="mb-0">
                        <i class="las la-folder mr-2"></i>{{ translate('Categories') }}
                        <span class="badge badge-light ml-2" id="categories-count">0</span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div id="categories-container"></div>

                    <button type="button" class="add-item-btn" onclick="addCategory()">
                        <i class="las la-plus-circle mr-2"></i>{{ translate('Add Category') }}
                    </button>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="card inquiry-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="las la-comment-alt mr-2"></i>{{ translate('Notes') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ translate('Admin Note') }}</label>
                        <textarea name="note" class="form-control form-control-modern" rows="3"
                                  placeholder="{{ translate('Add admin note...') }}"></textarea>
                        <small class="text-muted">{{ translate('This note is visible to the customer') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status & Fees -->
            <div class="card inquiry-card mb-4">
                <div class="card-header fees-header">
                    <h5 class="mb-0">
                        <i class="las la-cog mr-2"></i>{{ translate('Status & Fees') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ translate('Status') }}</label>
                        <select name="status" class="form-control form-control-modern">
                            <option value="pending">{{ translate('Pending') }}</option>
                            <option value="processing">{{ translate('Processing') }}</option>
                            <option value="completed">{{ translate('Completed') }}</option>
                            <option value="cancelled">{{ translate('Cancelled') }}</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ translate('Products Total') }}</label>
                                <input type="number" name="products_total" id="products_total"
                                       class="form-control form-control-modern" value="0" step="0.01" min="0" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ translate('Categories Total') }}</label>
                                <input type="number" name="categories_total" id="categories_total"
                                       class="form-control form-control-modern" value="0" step="0.01" min="0" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Tax') }}</label>
                        <input type="number" name="tax" id="tax"
                               class="form-control form-control-modern calc-input" value="0" step="0.01" min="0">
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Delivery') }}</label>
                        <input type="number" name="delivery" id="delivery"
                               class="form-control form-control-modern calc-input" value="0" step="0.01" min="0">
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Discount') }}</label>
                        <input type="number" name="discount" id="discount"
                               class="form-control form-control-modern calc-input" value="0" step="0.01" min="0">
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Extra Fees') }}</label>
                        <input type="number" name="extra_fees" id="extra_fees"
                               class="form-control form-control-modern calc-input" value="0" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="card summary-card mb-4">
                <div class="card-body p-4">
                    <h6 class="text-white-50 mb-3">{{ translate('Live Preview') }}</h6>

                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Products Total') }}</span>
                        <span id="preview-products-total">0.00</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Categories Total') }}</span>
                        <span id="preview-categories-total">0.00</span>
                    </div>
                    <div class="summary-row" style="background: rgba(56, 239, 125, 0.1); margin: 0 -1rem; padding: 10px 1rem; border-radius: 6px;">
                        <span class="text-white-50"><strong>{{ translate('Subtotal') }}</strong></span>
                        <span id="preview-subtotal" style="font-weight: 600;">0.00</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Tax') }}</span>
                        <span class="text-success" id="preview-tax">+0.00</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Delivery') }}</span>
                        <span class="text-success" id="preview-delivery">+0.00</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Extra Fees') }}</span>
                        <span class="text-success" id="preview-extra-fees">+0.00</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-white-50">{{ translate('Discount') }}</span>
                        <span class="text-danger" id="preview-discount">-0.00</span>
                    </div>
                    <div class="summary-row pt-3 mt-2" style="border-top: 2px solid rgba(255,255,255,0.2);">
                        <span class="summary-total">{{ translate('Total') }}</span>
                        <span class="summary-total"><span id="preview-total">0.00</span> <small>{{ translate('EGP') }}</small></span>
                    </div>

                    <input type="hidden" name="subtotal" id="subtotal" value="0">
                    <input type="hidden" name="total" id="total" value="0">

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
                    <button type="submit" class="btn btn-success btn-lg btn-block mb-3">
                        <i class="las la-check-circle mr-2"></i>{{ translate('Create Inquiry') }}
                    </button>
                    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary btn-block">
                        {{ translate('Cancel') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
</form>

@php
$productsJs = $products->map(function($p) {
    return [
        'id' => $p->id,
        'name' => $p->name,
        'category_id' => $p->category_id,
        'price' => (float)$p->unit_price,
        'image' => $p->thumbnail_img ? uploaded_asset($p->thumbnail_img) : static_asset('assets/img/placeholder.jpg')
    ];
})->values();

$categoriesJs = $allCategories->map(function($c) {
    return [
        'id' => $c->id,
        'name' => $c->name,
        'parent_id' => $c->parent_id,
        'level' => $c->level,
        // ✅ add image for preview
        'image' => $c->banner ? uploaded_asset($c->banner) : static_asset('assets/img/placeholder.jpg'),
        // ✅ optional price (if you don't have price in categories table, it will be 0)
        'price' => (float) ($c->unit_price ?? 0),
    ];
})->values();
@endphp

<script>
    const productsData = @json($productsJs);
    const categoriesData = @json($categoriesJs);
</script>

<!-- Product Template -->
<template id="product-template">
    <div class="item-card product-item" data-index="__INDEX__">
        <span class="item-number">__NUM__</span>
        <button type="button" class="delete-btn" onclick="removeItem(this)">
            <i class="las la-times"></i>
        </button>

        <input type="hidden" name="items[prod___INDEX__][type]" value="product">

        <div class="d-flex">
            <div class="item-image mr-3">
                <div class="no-image"><i class="las la-box"></i></div>
            </div>

            <div class="flex-grow-1">

                <!-- Category Filters (3 Levels) -->
                <div class="form-group mb-2">
                    <label class="small text-muted mb-1">{{ translate('Category (Level 1)') }}</label>
                    <select class="form-control form-control-modern category-filter" id="cat-l1-__INDEX__" onchange="onCatL1Change(__INDEX__)">
                        <option value="">{{ translate('Select Parent Category') }}</option>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label class="small text-muted mb-1">{{ translate('Category (Level 2)') }}</label>
                    <select class="form-control form-control-modern category-filter" id="cat-l2-__INDEX__" onchange="onCatL2Change(__INDEX__)" disabled>
                        <option value="">{{ translate('Select Sub Category') }}</option>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label class="small text-muted mb-1">{{ translate('Category (Level 3)') }}</label>
                    <select class="form-control form-control-modern category-filter" id="cat-l3-__INDEX__" onchange="onCatL3Change(__INDEX__)" disabled>
                        <option value="">{{ translate('Select Child Category') }}</option>
                    </select>
                </div>

                <!-- Product Select -->
                <div class="form-group mb-2">
                    <label class="small text-muted mb-1">{{ translate('Select Product') }}</label>
                    <select name="items[prod___INDEX__][product_id]"
                            id="product-select-__INDEX__"
                            class="form-control form-control-modern product-select"
                            required
                            onchange="onProductChange(__INDEX__)">
                        <option value="">{{ translate('Select Product') }}</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                    data-category="{{ $product->category_id }}"
                                    data-image="{{ $product->thumbnail_img ? uploaded_asset($product->thumbnail_img) : static_asset('assets/img/placeholder.jpg') }}"
                                    data-price="{{ $product->unit_price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Display -->
                <div class="d-flex gap-2 flex-wrap mb-2">
                    <span class="badge badge-light" style="padding:8px 12px;border-radius:10px;">
                        {{ translate('Unit Price') }}:
                        <strong id="prod-price-text-__INDEX__">0.00</strong> {{ translate('EGP') }}
                    </span>

                    <span class="badge badge-light" style="padding:8px 12px;border-radius:10px;background:#e6fffa;">
                        {{ translate('Line Total') }}:
                        <strong id="prod-line-total-__INDEX__">0.00</strong> {{ translate('EGP') }}
                    </span>
                </div>

                <!-- Hidden price to save in DB -->
                <input type="hidden" name="items[prod___INDEX__][price]" id="prod-price-__INDEX__" value="0">

                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <input type="number" name="items[prod___INDEX__][quantity]" id="prod-qty-__INDEX__"
                                   class="form-control form-control-modern"
                                   placeholder="{{ translate('Qty') }}" value="1" min="1" required
                                   oninput="onQtyChange(__INDEX__)">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <input type="text" name="items[prod___INDEX__][unit]" class="form-control form-control-modern"
                                   placeholder="{{ translate('Unit') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <input type="text" name="items[prod___INDEX__][note]" class="form-control form-control-modern"
                           placeholder="{{ translate('Note for this item...') }}">
                </div>

            </div>
        </div>
    </div>
</template>

<!-- ✅ Category Template (UPDATED: 3 Levels, pick any level) -->
<template id="category-template">
    <div class="item-card category-item" data-index="__INDEX__">
        <span class="item-number">__NUM__</span>
        <button type="button" class="delete-btn" onclick="removeItem(this)">
            <i class="las la-times"></i>
        </button>

        <input type="hidden" name="items[cat___INDEX__][type]" value="category">
        <input type="hidden" name="items[cat___INDEX__][category_id]" id="cat-selected-id-__INDEX__" value="">

        <div class="d-flex">
            <div class="item-image mr-3" id="cat-img-wrap-__INDEX__">
                <div class="no-image"><i class="las la-folder"></i></div>
            </div>

            <div class="flex-grow-1">

                <!-- Category Filters (3 Levels) -->
                <div class="form-group mb-2">
                    <label class="small text-muted mb-1">{{ translate('Category (Level 1)') }}</label>
                    <select class="form-control form-control-modern category-filter"
                            id="cat2-l1-__INDEX__"
                            onchange="onCat2L1Change(__INDEX__)">
                        <option value="">{{ translate('Select Parent Category') }}</option>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label class="small text-muted mb-1">{{ translate('Category (Level 2)') }}</label>
                    <select class="form-control form-control-modern category-filter"
                            id="cat2-l2-__INDEX__"
                            onchange="onCat2L2Change(__INDEX__)"
                            disabled>
                        <option value="">{{ translate('Select Sub Category') }}</option>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label class="small text-muted mb-1">{{ translate('Category (Level 3)') }}</label>
                    <select class="form-control form-control-modern category-filter"
                            id="cat2-l3-__INDEX__"
                            onchange="onCat2L3Change(__INDEX__)"
                            disabled>
                        <option value="">{{ translate('Select Child Category') }}</option>
                    </select>
                </div>

                <!-- Price Display -->
                <div class="d-flex gap-2 flex-wrap mb-2">
                    <span class="badge badge-light" style="padding:8px 12px;border-radius:10px;">
                        {{ translate('Unit Price') }}:
                        <strong id="cat-price-text-__INDEX__">0.00</strong> {{ translate('EGP') }}
                    </span>

                    <span class="badge badge-light" style="padding:8px 12px;border-radius:10px;background:#e6fffa;">
                        {{ translate('Line Total') }}:
                        <strong id="cat-line-total-__INDEX__">0.00</strong> {{ translate('EGP') }}
                    </span>
                </div>

                <input type="hidden" name="items[cat___INDEX__][price]" id="cat-price-__INDEX__" value="0">

                <div class="row">
                    <div class="col-4">
                        <div class="form-group mb-2">
                            <input type="number"
                                   name="items[cat___INDEX__][quantity]"
                                   id="cat-qty-__INDEX__"
                                   class="form-control form-control-modern"
                                   placeholder="{{ translate('Qty') }}"
                                   value="1" min="1" required
                                   oninput="onCatQtyChange(__INDEX__)">
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group mb-2">
                            <input type="number"
                                   class="form-control form-control-modern"
                                   id="cat-price-input-__INDEX__"
                                   placeholder="{{ translate('Price') }}"
                                   value="0" min="0" step="0.01"
                                   oninput="onCatPriceManualChange(__INDEX__)">
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group mb-2">
                            <input type="text" name="items[cat___INDEX__][unit]"
                                   class="form-control form-control-modern"
                                   placeholder="{{ translate('Unit') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <input type="text" name="items[cat___INDEX__][note]"
                           class="form-control form-control-modern"
                           placeholder="{{ translate('Note for this item...') }}">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    let productIndex = 0;
    let categoryIndex = 0;

    // ============ Add / Remove ============
    function addProduct() {
        const template = document.getElementById('product-template').innerHTML;
        const html = template
            .replace(/__INDEX__/g, productIndex)
            .replace(/__NUM__/g, document.querySelectorAll('#products-container .item-card').length + 1);

        const container = document.getElementById('products-container');
        container.insertAdjacentHTML('beforeend', html);

        initCategoryFilters(productIndex);

        productIndex++;
        updateCounts();
        calculateTotals();
    }

    function addCategory() {
        const template = document.getElementById('category-template').innerHTML;
        const html = template
            .replace(/__INDEX__/g, categoryIndex)
            .replace(/__NUM__/g, document.querySelectorAll('#categories-container .item-card').length + 1);

        const container = document.getElementById('categories-container');
        container.insertAdjacentHTML('beforeend', html);

        initCategoryItemFilters(categoryIndex);

        categoryIndex++;
        updateCounts();
        calculateTotals();
    }

    function removeItem(btn) {
        const card = btn.closest('.item-card');
        card.remove();
        updateCounts();
        renumberItems();
        calculateTotals();
    }

    function updateCounts() {
        document.getElementById('products-count').textContent =
            document.querySelectorAll('#products-container .item-card').length;
        document.getElementById('categories-count').textContent =
            document.querySelectorAll('#categories-container .item-card').length;
    }

    function renumberItems() {
        document.querySelectorAll('#products-container .item-card').forEach((card, i) => {
            card.querySelector('.item-number').textContent = i + 1;
        });
        document.querySelectorAll('#categories-container .item-card').forEach((card, i) => {
            card.querySelector('.item-number').textContent = i + 1;
        });
    }

    // ============ Product Image ============
    function setProductImageByIndex(index, imgUrl) {
        const select = document.getElementById('product-select-' + index);
        const card = select.closest('.item-card');
        const imageContainer = card.querySelector('.item-image');

        if (imgUrl && select.value) {
            imageContainer.innerHTML = `<img src="${imgUrl}" alt="Product">`;
        } else {
            imageContainer.innerHTML = '<div class="no-image"><i class="las la-box"></i></div>';
        }
    }

    // ============ Category Helpers ============
    function setOptions(selectEl, items, placeholderText) {
        selectEl.innerHTML = `<option value="">${placeholderText}</option>`;
        items.forEach(it => {
            selectEl.insertAdjacentHTML('beforeend', `<option value="${it.id}">${it.name}</option>`);
        });
    }

    function getChildren(parentId) {
        parentId = parentId === null ? null : parseInt(parentId);
        return categoriesData
            .filter(c => (parentId === null ? (c.parent_id === null || c.parent_id === 0) : parseInt(c.parent_id) === parentId))
            .map(c => ({ id: c.id, name: c.name }));
    }

    function getAllDescendants(rootId) {
        let result = [];
        function walk(pid) {
            categoriesData.forEach(c => {
                if (parseInt(c.parent_id) === pid) {
                    result.push(parseInt(c.id));
                    walk(parseInt(c.id));
                }
            });
        }
        walk(parseInt(rootId));
        return result;
    }

    // ============ Product Category Filters (3 Levels) ============
    function filterProductsByAllowedCategories(index, allowedIds) {
        const productSelect = document.getElementById('product-select-' + index);
        if (!productSelect) return;

        [...productSelect.options].forEach(opt => {
            if (!opt.value) { opt.hidden = false; return; }
            const catId = parseInt(opt.dataset.category);
            opt.hidden = allowedIds.length ? !allowedIds.includes(catId) : false;
        });

        productSelect.value = '';
        onProductChange(index);
    }

    function initCategoryFilters(index) {
        const l1 = document.getElementById('cat-l1-' + index);
        const l2 = document.getElementById('cat-l2-' + index);
        const l3 = document.getElementById('cat-l3-' + index);

        const parents = categoriesData
            .filter(c => c.parent_id === null || c.parent_id === 0)
            .map(c => ({ id: c.id, name: c.name }));

        setOptions(l1, parents, "{{ translate('Select Parent Category') }}");

        l2.disabled = true;
        l3.disabled = true;
        l2.innerHTML = `<option value="">{{ translate('Select Sub Category') }}</option>`;
        l3.innerHTML = `<option value="">{{ translate('Select Child Category') }}</option>`;

        filterProductsByAllowedCategories(index, []);
    }

    function onCatL1Change(index) {
        const l1Val = document.getElementById('cat-l1-' + index).value;
        const l2 = document.getElementById('cat-l2-' + index);
        const l3 = document.getElementById('cat-l3-' + index);

        l3.disabled = true;
        l3.innerHTML = `<option value="">{{ translate('Select Child Category') }}</option>`;

        if (!l1Val) {
            l2.disabled = true;
            l2.innerHTML = `<option value="">{{ translate('Select Sub Category') }}</option>`;
            filterProductsByAllowedCategories(index, []);
            return;
        }

        const children = getChildren(parseInt(l1Val));
        setOptions(l2, children, "{{ translate('Select Sub Category') }}");
        l2.disabled = false;

        const allowed = [parseInt(l1Val), ...getAllDescendants(l1Val)];
        filterProductsByAllowedCategories(index, allowed);
    }

    function onCatL2Change(index) {
        const l2Val = document.getElementById('cat-l2-' + index).value;
        const l3 = document.getElementById('cat-l3-' + index);

        if (!l2Val) {
            l3.disabled = true;
            l3.innerHTML = `<option value="">{{ translate('Select Child Category') }}</option>`;
            const l1Val = document.getElementById('cat-l1-' + index).value;
            const allowed = l1Val ? [parseInt(l1Val), ...getAllDescendants(l1Val)] : [];
            filterProductsByAllowedCategories(index, allowed);
            return;
        }

        const children = getChildren(parseInt(l2Val));
        setOptions(l3, children, "{{ translate('Select Child Category') }}");
        l3.disabled = false;

        const allowed = [parseInt(l2Val), ...getAllDescendants(l2Val)];
        filterProductsByAllowedCategories(index, allowed);
    }

    function onCatL3Change(index) {
        const l3Val = document.getElementById('cat-l3-' + index).value;

        if (!l3Val) {
            const l2Val = document.getElementById('cat-l2-' + index).value;
            const allowed = l2Val ? [parseInt(l2Val), ...getAllDescendants(l2Val)] : [];
            filterProductsByAllowedCategories(index, allowed);
            return;
        }

        const allowed = [parseInt(l3Val), ...getAllDescendants(l3Val)];
        filterProductsByAllowedCategories(index, allowed);
    }

    // ============ Product Price / Line Total ============
    function onProductChange(index) {
        const select = document.getElementById('product-select-' + index);
        const opt = select.options[select.selectedIndex];

        const price = opt?.dataset?.price ? parseFloat(opt.dataset.price) : 0;
        const imgUrl = opt?.dataset?.image || null;

        document.getElementById('prod-price-' + index).value = price.toFixed(2);
        document.getElementById('prod-price-text-' + index).textContent = price.toFixed(2);

        setProductImageByIndex(index, imgUrl);
        updateProductLineTotal(index);
        calculateTotals();
    }

    function onQtyChange(index) {
        updateProductLineTotal(index);
        calculateTotals();
    }

    function updateProductLineTotal(index) {
        const qty = parseFloat(document.getElementById('prod-qty-' + index).value) || 0;
        const price = parseFloat(document.getElementById('prod-price-' + index).value) || 0;
        const line = qty * price;
        document.getElementById('prod-line-total-' + index).textContent = line.toFixed(2);
    }

    // ===================== Category 3-Level Filters (Category Items) =====================
    function getCategoryById(id){
        id = parseInt(id);
        return categoriesData.find(c => parseInt(c.id) === id);
    }

    function setCategoryCardImage(index, imgUrl) {
        const wrap = document.getElementById('cat-img-wrap-' + index);
        if (!wrap) return;

        if (imgUrl) {
            wrap.innerHTML = `<img src="${imgUrl}" alt="Category">`;
        } else {
            wrap.innerHTML = `<div class="no-image"><i class="las la-folder"></i></div>`;
        }
    }

    function setCategoryPrice(index, price) {
        price = parseFloat(price) || 0;

        const hidden = document.getElementById('cat-price-' + index);
        const txt = document.getElementById('cat-price-text-' + index);
        const manual = document.getElementById('cat-price-input-' + index);

        if (hidden) hidden.value = price.toFixed(2);
        if (txt) txt.textContent = price.toFixed(2);
        if (manual) manual.value = price.toFixed(2);
    }

    function updateCategoryLineTotal(index) {
        const qty = parseFloat(document.getElementById('cat-qty-' + index)?.value) || 0;
        const price = parseFloat(document.getElementById('cat-price-' + index)?.value) || 0;
        const line = qty * price;
        const el = document.getElementById('cat-line-total-' + index);
        if (el) el.textContent = line.toFixed(2);
    }

    function applySelectedCategory(index, categoryId) {
        const hidden = document.getElementById('cat-selected-id-' + index);

        if (!categoryId) {
            if (hidden) hidden.value = '';
            setCategoryCardImage(index, null);
            setCategoryPrice(index, 0);
            updateCategoryLineTotal(index);
            calculateTotals();
            return;
        }

        if (hidden) hidden.value = categoryId;

        const cat = getCategoryById(categoryId);
        setCategoryCardImage(index, cat?.image || null);

        const defaultPrice = cat?.price ? parseFloat(cat.price) : 0;
        setCategoryPrice(index, defaultPrice);

        updateCategoryLineTotal(index);
        calculateTotals();
    }

    function initCategoryItemFilters(index) {
        const l1 = document.getElementById('cat2-l1-' + index);
        const l2 = document.getElementById('cat2-l2-' + index);
        const l3 = document.getElementById('cat2-l3-' + index);

        const parents = categoriesData
            .filter(c => c.parent_id === null || c.parent_id === 0)
            .map(c => ({ id: c.id, name: c.name }));

        setOptions(l1, parents, "{{ translate('Select Parent Category') }}");

        l2.disabled = true;
        l3.disabled = true;
        l2.innerHTML = `<option value="">{{ translate('Select Sub Category') }}</option>`;
        l3.innerHTML = `<option value="">{{ translate('Select Child Category') }}</option>`;

        applySelectedCategory(index, null);
    }

    function onCat2L1Change(index) {
        const l1Val = document.getElementById('cat2-l1-' + index).value;
        const l2 = document.getElementById('cat2-l2-' + index);
        const l3 = document.getElementById('cat2-l3-' + index);

        l3.disabled = true;
        l3.innerHTML = `<option value="">{{ translate('Select Child Category') }}</option>`;

        if (!l1Val) {
            l2.disabled = true;
            l2.innerHTML = `<option value="">{{ translate('Select Sub Category') }}</option>`;
            applySelectedCategory(index, null);
            return;
        }

        const children = getChildren(parseInt(l1Val));
        setOptions(l2, children, "{{ translate('Select Sub Category') }}");
        l2.disabled = false;

        // ✅ allow picking level 1 directly
        applySelectedCategory(index, parseInt(l1Val));
    }

    function onCat2L2Change(index) {
        const l2Val = document.getElementById('cat2-l2-' + index).value;
        const l3 = document.getElementById('cat2-l3-' + index);

        if (!l2Val) {
            l3.disabled = true;
            l3.innerHTML = `<option value="">{{ translate('Select Child Category') }}</option>`;
            const l1Val = document.getElementById('cat2-l1-' + index).value;
            applySelectedCategory(index, l1Val ? parseInt(l1Val) : null);
            return;
        }

        const children = getChildren(parseInt(l2Val));
        setOptions(l3, children, "{{ translate('Select Child Category') }}");
        l3.disabled = false;

        // ✅ allow picking level 2 directly
        applySelectedCategory(index, parseInt(l2Val));
    }

    function onCat2L3Change(index) {
        const l3Val = document.getElementById('cat2-l3-' + index).value;

        if (!l3Val) {
            const l2Val = document.getElementById('cat2-l2-' + index).value;
            applySelectedCategory(index, l2Val ? parseInt(l2Val) : null);
            return;
        }

        // ✅ level 3
        applySelectedCategory(index, parseInt(l3Val));
    }

    function onCatQtyChange(index) {
        updateCategoryLineTotal(index);
        calculateTotals();
    }

    function onCatPriceManualChange(index) {
        const manual = parseFloat(document.getElementById('cat-price-input-' + index).value) || 0;
        setCategoryPrice(index, manual);
        updateCategoryLineTotal(index);
        calculateTotals();
    }

    // ============ Totals ============
    function calculateTotals() {
        // Products total = sum(qty * price)
        let productsTotal = 0;
        document.querySelectorAll('#products-container .item-card.product-item').forEach(card => {
            const idx = card.getAttribute('data-index');
            const qty = parseFloat(document.getElementById('prod-qty-' + idx)?.value) || 0;
            const price = parseFloat(document.getElementById('prod-price-' + idx)?.value) || 0;
            productsTotal += (qty * price);
        });

        // Categories total = sum(qty * price) [NEW]
        let categoriesTotal = 0;
        document.querySelectorAll('#categories-container .item-card.category-item').forEach(card => {
            const idx = card.getAttribute('data-index');
            const qty = parseFloat(document.getElementById('cat-qty-' + idx)?.value) || 0;
            const price = parseFloat(document.getElementById('cat-price-' + idx)?.value) || 0;
            categoriesTotal += (qty * price);
        });

        const tax = parseFloat(document.getElementById('tax').value) || 0;
        const delivery = parseFloat(document.getElementById('delivery').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const extraFees = parseFloat(document.getElementById('extra_fees').value) || 0;

        const subtotal = productsTotal + categoriesTotal;
        const total = subtotal + tax + delivery + extraFees - discount;

        document.getElementById('products_total').value = productsTotal.toFixed(2);
        document.getElementById('categories_total').value = categoriesTotal.toFixed(2);
        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('total').value = total.toFixed(2);

        document.getElementById('preview-products-total').textContent = productsTotal.toFixed(2);
        document.getElementById('preview-categories-total').textContent = categoriesTotal.toFixed(2);
        document.getElementById('preview-subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('preview-tax').textContent = '+' + tax.toFixed(2);
        document.getElementById('preview-delivery').textContent = '+' + delivery.toFixed(2);
        document.getElementById('preview-extra-fees').textContent = '+' + extraFees.toFixed(2);
        document.getElementById('preview-discount').textContent = '-' + discount.toFixed(2);
        document.getElementById('preview-total').textContent = total.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // fees inputs update
        ['tax', 'delivery', 'discount', 'extra_fees'].forEach(id => {
            document.getElementById(id).addEventListener('input', calculateTotals);
        });

        calculateTotals();
    });
</script>

@endsection
