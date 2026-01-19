                                <!-- Price range -->
                                <div class="bg-white border-bottom-listing-sidebar">
                                    <div class="fs-16 fw-700 p-3">
                                        <a href="#collapse_price"
                                            class="dropdown-toggle collapsed filter-section text-dark d-flex align-items-center justify-content-between"
                                            data-toggle="collapse" data-target="#collapse_price">
                                            {{ translate('Price range') }}
                                        </a>
                                    </div>
                                    <div class="collapse" id="collapse_price">
                                        <div class="px16px py22px hover-effect">
                                            @php
                                                $product_count = get_products_count();
                                            @endphp

                                            <div class="aiz-range-slider">


                                                <div id="input-slider-range"
                                                    data-range-value-min="@if (true) 0 @else {{ get_product_min_unit_price() }} @endif"
                                                    data-range-value-max="@if ($product_count < 1) 0 @else {{ get_product_max_unit_price() }} @endif">
                                                    <div
                                                        style="width: 4px; height: 16px; background-color: #DFDFE6; position: absolute; top: -7px; left: -1px;  ">
                                                    </div>
                                                    <div
                                                        style="width: 4px; height: 16px; background-color: #DFDFE6; position: absolute; top: -7px; right: -1px;  ">
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                                            {{-- @if (isset($min_price)) data-range-value-low="{{ $min_price }}"
                                                            @elseif($products->min('unit_price') > 0)
                                                                data-range-value-low="{{ $products->min('unit_price') }}"
                                                            @else --}} data-range-value-low="0"
                                                            {{-- @endif --}} id="input-slider-range-value-low">0</span>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                                            {{-- @if (isset($max_price)) data-range-value-high="{{ $max_price }}"
                                                            @elseif($products->max('unit_price') > 0)
                                                                data-range-value-high="{{ $products->max('unit_price') }}"
                                                            @else --}}
                                                            data-range-value-high="{{ get_product_max_unit_price() / 2 }}"
                                                            {{-- @endif --}} id="input-slider-range-value-high"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Hidden Items -->
                                        <input type="hidden" name="min_price" value="">
                                        <input type="hidden" name="max_price" value="">
                                    </div>
                                </div>







































