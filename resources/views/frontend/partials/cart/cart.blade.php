@php
    $total = 0;
    $carts = get_user_cart();
    if (count($carts) > 0) {
        foreach ($carts as $key => $cartItem) {
            if(isset($cartItem['product_id'])) {
                $product = get_single_product($cartItem['product_id']);
                if($product != null) {
                    $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
                }
            }
        }
    }
@endphp

<!-- Cart button with cart count -->
<a href="javascript:void(0)"
   class="d-flex align-items-center @if (get_setting('header_element') !=6) px-3 @endif h-100"
   data-toggle="dropdown" data-display="static"
   title="{{translate('Cart')}}">

    @if (get_setting('header_element') != 6)
        <span class="mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20.562" viewBox="0 0 24 20.562"
                 class="{{ get_setting('header_element') !=5 ? 'bottom-text-color-visibility' : 'middle-text-color-visibility' }}"
                 style="color: {{ get_setting('header_element') !=5 ? get_setting('bottom_header_text_color'): get_setting('middle_header_text_color') }}">
                <g id="_5e67fc94b53aaec8ca181b806dd815ee" data-name="5e67fc94b53aaec8ca181b806dd815ee"
                   transform="translate(-33.276 -101)">
                    <path fill="currentColor" id="Path_32659" data-name="Path 32659"
                          d="M34.034,102.519H38.2l-.732-.557c.122.37.243.739.365,1.112q.441,1.333.879,2.666.528,1.6,1.058,3.211.46,1.394.917,2.788c.149.451.291.9.446,1.352l.008.02a.76.76,0,0,0,1.466-.4c-.122-.37-.243-.739-.365-1.112q-.441-1.333-.879-2.666-.528-1.607-1.058-3.213-.46-1.394-.917-2.788c-.149-.451-.289-.9-.446-1.352l-.008-.02a.783.783,0,0,0-.732-.557H34.037a.76.76,0,0,0,0,1.519Z" />
                    <path fill="currentColor" id="Path_32660" data-name="Path 32660"
                          d="M288.931,541.934q-.615,1.1-1.233,2.193c-.058.106-.119.21-.177.317a.767.767,0,0,0,.656,1.142h11.6c.534,0,1.071.01,1.608,0h.023a.76.76,0,0,0,0-1.519h-11.6c-.534,0-1.074-.015-1.608,0h-.023l.656,1.142q.615-1.1,1.233-2.193c.058-.106.119-.21.177-.316a.759.759,0,0,0-1.312-.765Z"
                          transform="translate(-247.711 -429.41)" />
                    <circle fill="currentColor" id="Ellipse_553" data-name="Ellipse 553" cx="1.724" cy="1.724" r="1.724"
                            transform="translate(49.612 117.606)" />
                    <circle fill="currentColor" id="Ellipse_554" data-name="Ellipse 554" cx="1.724" cy="1.724" r="1.724"
                            transform="translate(40.884 117.606)" />
                    <path fill="currentColor" id="Path_32663" data-name="Path 32663"
                          d="M267.044,237.988q-.52,1.341-1.038,2.682-.828,2.138-1.654,4.274l-.38.983.489-.372H254.1c-.476,0-.957-.02-1.436,0h-.02l.489.372q-.444-1.348-.886-2.694-.7-2.131-1.4-4.264c-.109-.327-.215-.653-.324-.983l-.489.641h16.791c.228,0,.456.005.681,0h.03a.506.506,0,0,0,0-1.013H250.744c-.228,0-.456-.005-.681,0h-.03a.511.511,0,0,0-.489.641q.444,1.348.886,2.694.7,2.131,1.4,4.264c.109.327.215.653.324.983a.523.523,0,0,0,.489.372h10.359c.476,0,.957.018,1.436,0h.02a.526.526,0,0,0,.489-.372q.52-1.341,1.038-2.682.828-2.138,1.654-4.274l.38-.983a.508.508,0,0,0-.355-.623A.52.52,0,0,0,267.044,237.988Z"
                          transform="translate(-210.769 -133.152)" />
                </g>
            </svg>
        </span>

        <span class="d-none d-xl-block ml-2 fs-14 fw-700 {{ get_setting('header_element') !=5 ? 'bottom-text-color-visibility' : 'middle-text-color-visibility' }}"
              style="color: {{ get_setting('header_element') !=5 ? get_setting('bottom_header_text_color'): get_setting('middle_header_text_color') }}">
            {{ single_price($total) }}
        </span>

        <span class="nav-box-text d-none d-xl-block ml-2 fs-12 {{ get_setting('header_element') !=5 ? 'bottom-text-color-visibility' : 'middle-text-color-visibility' }}"
              style="color: {{ get_setting('header_element') !=5 ? get_setting('bottom_header_text_color'): get_setting('middle_header_text_color') }}">
            (<span class="cart-count">{{count($carts) > 0 ? count($carts) : 0 }}</span> {{translate('Items')}})
        </span>

    @else
        <span class="mr-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                 class="bottom-text-color-visibility"
                 style="color: {{ get_setting('bottom_header_text_color') }}"
                 role="img" aria-hidden="true">
                <path fill="currentColor" d="M17 6V5a5 5 0 0 0-10 0v1H4v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6h-3zM9 5a3 3 0 0 1 6 0v1H9V5z"/>
                <rect x="9" y="10" width="6" height="2" fill="#fff" rx="0.3"/>
            </svg>
        </span>
        <span class="d-none d-xl-block ml-1 fs-14 fw-700 bottom-text-color-visibility"
              style="color: {{ get_setting('bottom_header_text_color') }}">{{translate('Cart')}}</span>
        <span class="nav-box-text d-none d-xl-block ml-1 fw-700 bottom-text-color-visibility"
              style="color: {{ get_setting('bottom_header_text_color') }}">
            (<span class="cart-count">{{count($carts) > 0 ? count($carts) : 0 }}</span>)
        </span>
    @endif
</a>

<!-- Cart Items -->
<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg p-0 stop-propagation rounded-0">
    @if (isset($carts) && count($carts) > 0)

        <div class="fs-16 fw-700 text-soft-dark pt-4 pb-2 mx-4 border-bottom" style="border-color: #e5e5e5 !important;">
            {{ translate('Cart Items') }}
        </div>

        <!-- Cart Products -->
        <ul class="h-360px overflow-auto c-scrollbar-light list-group list-group-flush mx-1">
            @foreach ($carts as $key => $cartItem)
                @if(isset($cartItem['product_id']))
                    @php
                        $product = get_single_product($cartItem['product_id']);
                    @endphp

                    @if ($product != null)
                        <!-- ✅ added id -->
                        <li class="list-group-item border-0 hov-scale-img" id="nav-cart-item-{{ $cartItem['id'] }}">
                            <span class="d-flex align-items-center">
                                <a href="{{ route('product', $product->slug) }}"
                                   class="text-reset d-flex align-items-center flex-grow-1">
                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                         data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                         class="img-fit lazyload size-60px has-transition"
                                         alt="{{ $product->getTranslation('name') }}"
                                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    <span class="minw-0 pl-2 flex-grow-1">
                                        <span class="fw-700 fs-13 text-dark mb-2 text-truncate-2"
                                              title="{{ $product->getTranslation('name') }}">
                                            {{ $product->getTranslation('name') }}
                                        </span>
                                        <span class="fs-14 fw-400 text-secondary">{{ $cartItem['quantity'] }}x</span>
                                        <span class="fs-14 fw-400 text-secondary">{{ cart_product_price($cartItem, $product) }}</span>
                                    </span>
                                </a>

                                <span>
                                    <!-- ✅ modified: type=button + send this -->
                                    <button type="button"
                                            onclick="removeFromCart('{{ $cartItem['id'] }}', this)"
                                            class="btn btn-sm btn-icon stop-propagation">
                                        <i class="la la-close fs-18 fw-600 text-secondary"></i>
                                    </button>
                                </span>
                            </span>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>

        <!-- Subtotal -->
        <div class="px-3 py-2 fs-15 border-top d-flex justify-content-between mx-4"
             style="border-color: #e5e5e5 !important;">
            <span class="fs-14 fw-400 text-secondary">{{ translate('Subtotal') }}</span>
            <span class="fs-16 fw-700 text-dark">{{ single_price($total) }}</span>
        </div>

        <!-- View cart & Checkout Buttons -->
        <div class="py-3 text-center border-top mx-4" style="border-color: #e5e5e5 !important;">
            <div class="row gutters-10 justify-content-center">
                <div class="col-sm-6 mb-2">
                    <a href="{{ route('cart') }}" class="btn btn-secondary-base btn-sm btn-block rounded-4 text-white">
                        {{ translate('View cart') }}
                    </a>
                </div>
            </div>
        </div>

    @else
        <div class="text-center p-3">
            <i class="las la-frown la-3x opacity-60 mb-3"></i>
            <h3 class="h6 fw-700">{{ translate('Your Cart is empty') }}</h3>
        </div>
    @endif
</div>

{{-- ✅ JS: put this once in footer scripts (or same blade) --}}
<script>
    // Remove item from header dropdown cart without refresh
    function removeFromCart(cartId, btnEl) {
        const $li = btnEl ? $(btnEl).closest('li') : $('#nav-cart-item-' + cartId);

        $.ajax({
            type: "POST",
            url: '{{ route("cart.removeFromCart") }}',
            data: {
                _token: '{{ csrf_token() }}',
                id: cartId
            },
            success: function (data) {

                // Remove from UI instantly
                if ($li.length) {
                    $li.stop(true, true).fadeOut(150, function () {
                        $(this).remove();
                    });
                }

                // Update nav cart view & count
                if (typeof updateNavCart === 'function') {
                    updateNavCart(data.nav_cart_view, data.cart_count);
                } else {
                    // fallback: update only count
                    const c = (data.cart_count === undefined || data.cart_count === null) ? 0 : data.cart_count;
                    $('.cart-count').text(c).attr('data-count', c);
                }

                // Optional: update cart page summary if exists
                if (typeof updateCartSummary === 'function') {
                    setTimeout(updateCartSummary, 50);
                }

                AIZ.plugins.notify('success', "{{ translate('Item removed from cart') }}");
            },
            error: function () {
                AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
            }
        });
    }
</script>
