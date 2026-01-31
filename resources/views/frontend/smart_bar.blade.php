    @if (get_setting('smart_bar_status') != 0)

        @php
            $bg_color = get_setting('smart_bar_background_color') ?? '#fff';
            $text_color = get_setting('smart_bar_text_color') ?? '#000';
            $colors = is_string($detailedProduct->colors) ? json_decode($detailedProduct->colors, true) : $detailedProduct->colors;
            $attributes = is_string($detailedProduct->attributes) ? json_decode($detailedProduct->attributes, true) : $detailedProduct->attributes;
        @endphp

        <div id="smart-bar" class="fixed-bottom smart-bar smart-bar-mobile"
                style="background-color: {{ $bg_color }}; color: {{ $text_color }}; padding: 0.8rem 1rem;">

            <div class="container {{-- -fluid smart-bar-container pr-8 pl-8 --}}">
                <div class="d-flex align-items-center justify-content-between" style="gap: 0.9rem;">

                    <!-- Product image -->
                    <div class="flex-shrink-0">
                        <img src="{{ uploaded_asset($detailedProduct->thumbnail_img) }}"
                            alt="{{ $detailedProduct->getTranslation('name') }}"
                            class="img-fluid rounded"
                            style="width:60px; height:60px; object-fit:cover;">
                    </div>

                    <!-- Product title -->
                    <div class="d-none d-sm-inline flex-grow-1 overflow-hidden">
                        <h6 class="mb-0 text-truncate-1" style="color: {{ $text_color }}; font-size: 14px; font-weight:500;">
                            {{ $detailedProduct->getTranslation('name') }}
                        </h6>
                    </div>

                    {{-- Prices hidden for inquiry-based system --}}

                    <!-- Add to Inquiry button -->
                    <div class="flex-shrink-0">
                        @php
                            $detailHasVariants = false;
                            try {
                                $detailColors = is_string($detailedProduct->colors ?? null) ? json_decode($detailedProduct->colors, true) : ($detailedProduct->colors ?? []);
                                $detailChoice = is_string($detailedProduct->choice_options ?? null) ? json_decode($detailedProduct->choice_options, true) : ($detailedProduct->choice_options ?? []);
                                $detailHasVariants = (is_array($detailColors) && count($detailColors) > 0) || (is_array($detailChoice) && count($detailChoice) > 0);
                            } catch (\Throwable $e) {
                                $detailHasVariants = false;
                            }
                        @endphp
                        <button type="button"
                                class="btn btn-add-inquiry fw-600 rounded-0 text-white"
                                style="background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); border: none;"
                                data-product-id="{{ $detailedProduct->id }}"
                                data-has-variants="{{ $detailHasVariants ? 1 : 0 }}"
                                data-min-qty="{{ (int) $detailedProduct->min_qty }}"
                                onclick="event.preventDefault(); event.stopPropagation(); featuredInquiryAction(this);">
                            <i class="las la-plus"></i>
                            <span class="d-none d-sm-inline">{{ translate('Add to Inquiry') }}</span>
                        </button>
                    </div>
                    <div class="flex-shrink-0">
                        <!-- Close button -->
                        <a href="javascript:void(0)" onclick="closeSmartBar()">
                            <i style="color: {{ $text_color }};" class="la la-close la-2x"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    @endif