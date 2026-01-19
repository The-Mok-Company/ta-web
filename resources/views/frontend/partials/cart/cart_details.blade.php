@if(isset($carts) && count($carts) > 0)

    @foreach($carts as $cartKey => $cartItem)
        @php
            $product = isset($cartItem['product_id']) ? \App\Models\Product::find($cartItem['product_id']) : null;
            $category = isset($cartItem['category_id']) ? \App\Models\Category::find($cartItem['category_id']) : null;

            // ✅ IMPORTANT: cart id is string
            $cartId = $cartItem['id'] ?? $cartKey;
        @endphp

        {{-- Product Item --}}
        @if($product != null)
            <div class="card product-card cart-row"
                 data-cart-id="{{ $cartId }}"
                 data-type="product"
                 data-product-id="{{ $product->id }}"
                 id="cart-item-{{ $cartId }}">
                <div class="card-body p-4">
                    <div class="row align-items-center g-3">

                        <div class="col-auto">
                            <a href="{{ route('product', $product->slug) }}">
                                <img class="product-img"
                                     src="{{ uploaded_asset($product->thumbnail_img) }}"
                                     alt="{{ $product->getTranslation('name') }}"
                                     onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                        </div>

                        <div class="col-12 col-md">
                            <a href="{{ route('product', $product->slug) }}" class="text-reset">
                                <div class="product-title">{{ $product->getTranslation('name') }}</div>
                            </a>
                            @if($product->category)
                                <div class="product-category mb-2">{{ $product->category->getTranslation('name') }}</div>
                            @endif
                            <div class="product-description" style="max-width: 400px;">
                                {{ $product->getTranslation('description') ? Str::limit(strip_tags($product->getTranslation('description')), 150) : translate('No description available') }}
                            </div>
                        </div>

                        <div class="col-12 col-md-auto">
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="qty-btn" onclick="updateCartQuantity('{{ $cartId }}', -1)">−</button>

                                    <input type="text"
                                           class="qty-value"
                                           id="qty-{{ $cartId }}"
                                           value="{{ $cartItem['quantity'] }}"
                                           readonly>

                                    <button type="button" class="qty-btn" onclick="updateCartQuantity('{{ $cartId }}', 1)">+</button>

                                    <span class="ms-2 fw-semibold" style="color:#64748b">{{ $product->unit }}</span>
                                </div>

                                <input type="text"
                                       class="form-control note-input cart-item-note"
                                       id="note-{{ $cartId }}"
                                       data-cart-id="{{ $cartId }}"
                                       placeholder="{{ translate('Note...') }}"
                                       style="width: 220px;">
                            </div>
                        </div>

                        <div class="col-auto">
                            <button type="button" class="trash-btn" onclick="removeCartItem('{{ $cartId }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        {{-- Category Item --}}
        @elseif($category != null)
            <div class="card product-card cart-row"
                 data-cart-id="{{ $cartId }}"
                 data-type="category"
                 data-category-id="{{ $category->id }}"
                 id="cart-item-{{ $cartId }}">
                <div class="card-body p-4">
                    <div class="row align-items-center g-3">

                        <div class="col-auto">
                            <a href="{{ route('products.category', $category->slug) }}">
                                <img class="product-img"
                                     src="{{ $category->banner ? uploaded_asset($category->banner) : static_asset('assets/img/placeholder.jpg') }}"
                                     alt="{{ $category->getTranslation('name') }}"
                                     onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                        </div>

                        <div class="col-12 col-md">
                            <a href="{{ route('products.category', $category->slug) }}" class="text-reset">
                                <div class="product-title">{{ $category->getTranslation('name') }}</div>
                            </a>
                            <div class="product-category mb-2">{{ translate('Category') }}</div>
                            <div class="product-description" style="max-width: 400px;">
                                {{ $category->getTranslation('description') ? Str::limit(strip_tags($category->getTranslation('description')), 150) : translate('No description available') }}
                            </div>
                        </div>

                        <div class="col-12 col-md-auto">
                            <div class="d-flex flex-column gap-2">
                                <input type="text"
                                       class="form-control note-input cart-item-note"
                                       id="note-{{ $cartId }}"
                                       data-cart-id="{{ $cartId }}"
                                       placeholder="{{ translate('Note...') }}"
                                       style="width: 220px;">
                            </div>
                        </div>

                        <div class="col-auto">
                            <button type="button" class="trash-btn" onclick="removeCartItem('{{ $cartId }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    @endforeach

@else
    <div class="card product-card">
        <div class="card-body p-5 text-center">
            <i class="bi bi-cart-x" style="font-size: 48px; color: #94a3b8;"></i>
            <h4 class="mt-3" style="color: #64748b;">{{ translate('Your cart is empty') }}</h4>
            <a href="{{ route('search') }}" class="btn request-btn mt-3" style="width: auto; padding: 12px 32px;">
                <span>{{ translate('Continue Shopping') }}</span>
            </a>
        </div>
    </div>
@endif
