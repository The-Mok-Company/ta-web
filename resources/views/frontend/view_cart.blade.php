@extends('frontend.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .container.py-4 { padding-top: 120px !important; }
    @media (max-width: 768px) { .container.py-4 { padding-top: 100px !important; } }
    :root { --primary-color: #0891B2; --primary-hover: #0E7490; }
    .inquiry-title{ font-size:42px; font-weight:800; letter-spacing:-.5px; color:#1e293b; margin-bottom: 24px; }
    .product-card{ border:0; border-radius:16px; box-shadow:0 4px 14px rgba(8,145,178,.08); margin-bottom:16px; transition:all 0.3s ease; }
    .product-card:hover{ box-shadow:0 8px 24px rgba(8,145,178,.15); }
    .product-img{ width:80px; height:80px; object-fit:cover; border-radius:14px; }
    .qty-btn{ width:40px; height:40px; border-radius:50%; border:0; background:#0891B2; color:#fff; font-weight:700; font-size:18px; display:flex; align-items:center; justify-content:center; transition:all 0.2s ease; }
    .qty-btn:hover{ background:#0E7490; transform:scale(1.05); }
    .qty-value{ width:60px; border:0; background:transparent; text-align:center; font-weight:700; font-size:18px; color:#1e293b; }
    .note-input{ border-radius:12px; height:44px; border:1px solid #e2e8f0; padding:0 16px; font-size:14px; transition:all 0.2s ease; }
    .note-input:focus{ border-color:#0891B2; box-shadow:0 0 0 3px rgba(8,145,178,.1); }
    .trash-btn{ width:40px; height:40px; border-radius:12px; border:0; background:#FEE2E2; color:#DC2626; transition:all 0.2s ease; }
    .trash-btn:hover{ background:#DC2626; color:#fff; }
    .summary-card{ border:0; border-radius:18px; box-shadow:0 4px 14px rgba(8,145,178,.08); position:sticky; top:120px; }
    .pill{ border-radius:999px; padding:12px 20px; font-weight:700; font-size:14px; }
    .pill.bg-primary{ background:#0891B2 !important; }
    .avatar{ width:58px; height:58px; border-radius:50%; background:#1e293b; display:flex; align-items:center; justify-content:center; }
    .avatar i{ color:#fff; font-size:26px; }
    .request-btn{ height:56px; border-radius:14px; font-weight:700; background:#0891B2; border:0; font-size:16px; transition:all 0.3s ease; }
    .request-btn:hover{ background:#0E7490; transform:translateY(-2px); box-shadow:0 8px 16px rgba(8,145,178,.3); }
    .request-btn span{ color:#fff !important; }
    .product-title{ font-weight:700; color:#1e293b; font-size:16px; margin-bottom:4px; }
    .product-category{ color:#0891B2; font-weight:600; font-size:13px; }
    .product-description{ color:#64748b; font-size:13px; line-height:1.5; }
    .summary-title{ font-weight:700; font-size:18px; color:#1e293b; margin-bottom:20px; }
    .summary-row{ display:flex; justify-content:space-between; color:#64748b; font-weight:600; font-size:14px; margin-bottom:12px; }
    .user-info-name{ font-weight:700; color:#1e293b; font-size:15px; }
    .user-info-detail{ color:#64748b; font-size:13px; }
    .edit-link{ color:#0891B2; font-weight:700; font-size:13px; text-decoration:none; }
    .edit-link:hover{ color:#0E7490; text-decoration:underline; }

    .cart-section { background: #f8fafc; padding: 60px 0; min-height: calc(100vh - 80px); }
    .cart-section .container { padding-top: 40px !important; }
</style>

<section class="cart-section">
    <div class="container py-4">

        <div class="mb-4">
            <div class="inquiry-title">{{ translate('Inquiry') }}</div>
        </div>
        <div class="row g-4">

            <!-- Left: Products -->
            <div class="col-lg-8" id="cart-items-container">
                @include('frontend.partials.cart.cart_details', ['carts' => $carts])
            </div>

            <!-- Right: Summary -->
            <div class="col-lg-4">
                <div class="card summary-card">
                    <div class="card-body p-4">

                        <div class="summary-title">{{ translate('Cart Summary') }}</div>

                        @php
                            $totalProducts = isset($carts) ? count($carts) : 0;
                            $totalItems = 0;
                            if(isset($carts) && count($carts) > 0) {
                                foreach($carts as $cart) {
                                    $totalItems += $cart['quantity'] ?? 1;
                                }
                            }
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="pill bg-primary text-white">{{ translate('Total Products') }}</div>
                            <div class="pill bg-primary text-white" id="total-products">{{ str_pad($totalProducts, 2, '0', STR_PAD_LEFT) }}</div>
                        </div>

                        <div class="summary-row">
                            <span>{{ translate('Products') }}</span>
                            <span id="summary-products">{{ $totalProducts }} {{ translate('Products') }}</span>
                        </div>

                        <div class="summary-row mb-4">
                            <span>{{ translate('Items') }}</span>
                            <span id="summary-items">{{ $totalItems }} {{ translate('Items') }}</span>
                        </div>

                        <hr style="border-color:#e2e8f0;margin:24px 0">

                        <textarea class="form-control mb-4 note-input"
                                  rows="3"
                                  id="inquiry-note"
                                  placeholder="{{ translate('Note...') }}"></textarea>

                        <div id="inquiry-sent-msg" class="mb-4" style="display:none;">
                            <div class="alert alert-success mb-0" style="border-radius:12px; font-weight:700;">
                                Your request has been sent.
                            </div>
                        </div>

                        <button type="button"
                                id="request-offer-btn"
                                class="btn w-100 request-btn"
                                onclick="submitInquiryRequest()"
                                @if($totalProducts == 0) disabled @endif>
                            <span>{{ translate('Request Offer') }}</span>
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@section('script')
<script>
    // Update cart quantity
    function updateCartQuantity(cartId, change) {
        var qtyInput = document.getElementById('qty-' + cartId);

        // ✅ safe parse
        var currentQty = parseInt(qtyInput ? qtyInput.value : 1, 10);
        if (isNaN(currentQty) || currentQty < 1) currentQty = 1;

        var newQty = currentQty + change;

        // لو نزلت عن 1 احذف
        if (newQty < 1) {
            removeCartItem(cartId);
            return;
        }

        $.ajax({
            type: "POST",
            url: '{{ route("cart.updateQuantity") }}',
            data: {
                _token: '{{ csrf_token() }}',
                id: String(cartId),
                quantity: parseInt(newQty, 10)
            },
            success: function (data) {

                // ✅ لو السيرفر بيرجع cart_view حدث الجزء (الأفضل عشان DOM يبقى متزامن)
                if (data && data.cart_view !== undefined) {
                    $('#cart-items-container').html(data.cart_view);
                } else {
                    // fallback: حدّث input مباشرة
                    if (qtyInput) qtyInput.value = newQty;
                }

                // ✅ update nav cart
                if (typeof updateNavCart === 'function' && data && data.nav_cart_view !== undefined) {
                    updateNavCart(data.nav_cart_view, data.cart_count);
                }

                updateCartSummary();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
                }
            }
        });
    }

    // Remove cart item
    function removeCartItem(cartId) {
        $.ajax({
            type: "POST",
            url: '{{ route("cart.removeFromCart") }}',
            data: {
                _token: '{{ csrf_token() }}',
                id: String(cartId)
            },
            success: function (data) {

                // ✅ replace cart details
                if (data && data.cart_view !== undefined) {
                    $('#cart-items-container').html(data.cart_view);
                }

                // ✅ update nav cart
                if (typeof updateNavCart === 'function' && data && data.nav_cart_view !== undefined) {
                    updateNavCart(data.nav_cart_view, data.cart_count);
                }

                updateCartSummary();

                if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('success', "{{ translate('Item removed from cart') }}");
                }
            },
            error: function () {
                if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
                }
            }
        });
    }

    // Update cart summary
    function updateCartSummary() {
        var totalProducts = $('#cart-items-container .cart-row').length;
        var totalItems = 0;

        // ✅ اجمع أي qty inputs موجودة
        $('#cart-items-container .qty-value').each(function () {
            var v = parseInt($(this).val(), 10);
            totalItems += isNaN(v) ? 0 : v;
        });

        // ✅ لو عندك category rows مفيهاش qty input → اعتبرها 1
        $('#cart-items-container .cart-row[data-type="category"]').each(function(){
            var $row = $(this);
            var cartId = $row.data('cart-id');

            var q = 1;
            var el = document.getElementById('qty-' + cartId);
            if (el) {
                var vv = parseInt(el.value, 10);
                if (!isNaN(vv) && vv > 0) q = vv;
            }

            // لو الـ row نفسه متحسبتش ضمن qty-value (غالبًا كده)، زودها
            // بس علشان متزودش مرتين: لو فيه input qty-value للـ category، مش هنزود هنا
            if (!el) totalItems += q;
        });

        $('#total-products').text(totalProducts.toString().padStart(2, '0'));
        $('#summary-products').text(totalProducts + ' {{ translate("Products") }}');
        $('#summary-items').text(totalItems + ' {{ translate("Items") }}');

        // disable button if empty
        $('#request-offer-btn').prop('disabled', totalProducts === 0);
    }

    // Collect items from cart_details and send to InquiryController
function submitInquiryRequest() {
    var $btn = $('#request-offer-btn');

    // prevent double click
    if ($btn.prop('disabled')) return;

    var items = [];

    $('#cart-items-container .cart-row').each(function () {
        var $row = $(this);

        var cartId = $row.data('cart-id');
        var type = $row.data('type'); // product / category
        var productId = $row.data('product-id') || null;
        var categoryId = $row.data('category-id') || null;

        // quantity
        var qtyEl = document.getElementById('qty-' + cartId);
        var qty = 1;
        if (qtyEl) {
            var parsed = parseInt(qtyEl.value, 10);
            qty = (isNaN(parsed) || parsed < 1) ? 1 : parsed;
        }

        items.push({
            cart_id: String(cartId),
            type: String(type || ''),
            product_id: productId ? parseInt(productId, 10) : null,
            category_id: categoryId ? parseInt(categoryId, 10) : null,
            quantity: qty
        });
    });

    if (!items.length) {
        AIZ.plugins.notify('warning', "{{ translate('Cart is empty') }}");
        return;
    }

    var note = $('#inquiry-note').val();

    // UI
    $btn.prop('disabled', true);
    $btn.find('span').text("{{ translate('Sending...') }}");

    $.ajax({
        type: "POST",
        url: "{{ route('inquiry.requestOffer') }}",
        data: {
            _token: "{{ csrf_token() }}",
            note: note,
            items: JSON.stringify(items)
        },
        success: function(res) {
            // ✅ إشعار سريع (اختياري)
            if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                AIZ.plugins.notify('success', "{{ translate('Your request has been sent') }}");
            }

            // ✅ ريفرش للصفحة بعد نجاح الإرسال
            // replace علشان مايرجعش الفورم لو ضغط Back
            window.location.replace(window.location.href);
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
                AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
            }
            $btn.prop('disabled', false);
            $btn.find('span').text("{{ translate('Request Offer') }}");
        }
    });
}

    // run once on load
    $(document).ready(function(){
        updateCartSummary();
    });
</script>
@endsection
