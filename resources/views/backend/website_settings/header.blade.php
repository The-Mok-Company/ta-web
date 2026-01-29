@extends('backend.layouts.app')

@section('content')

	<div class="aiz-titlebar text-left mt-2 mb-3">
		<div class="row align-items-center">
			<div class="col">
				<h1 class="h3">{{ translate('Selected Header') }}</h1>
			</div>
		</div>
	</div>

	@include('header.' .get_element_type_by_id(get_setting('header_element')))
	<br>

	<div class="row">
		<div class="col-md-8 mx-auto">
			<div class="card">
				<div class="card-header">
					<div class="d-flex align-items-center justify-content-between w-100">
						<h6 class="mb-0">{{ translate('Header Setting') }}</h6>
						<a href="{{ route('menu-items.index') }}" class="btn btn-soft-primary btn-sm">{{ translate('Menu Items') }}</a>
					</div>
				</div>

				<div class="card-body">
					<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<!-- Header Logo -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{ translate('Header Logo') }}</label>
							<div class="col-md-8">
								<div class=" input-group " data-toggle="aizuploader" data-type="image">
									<div class="input-group-prepend">
										<div class="input-group-text bg-soft-secondary font-weight-medium">
											{{ translate('Browse') }}
										</div>
									</div>
									<div class="form-control file-amount">{{ translate('Choose File') }}</div>
									<input type="hidden" name="types[]" value="header_logo">
									<input type="hidden" name="header_logo" class="selected-files"
										value="{{ get_setting('header_logo') }}">
								</div>
								<div class="file-preview"></div>
								<small
									class="text-muted">{{ translate("Minimum dimensions required: 244px width X 40px height.") }}</small>
							</div>
						</div>
						<!-- Show Language Switcher -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Show Language Switcher?')}}</label>
							<div class="col-md-8">
								<label class="aiz-switch aiz-switch-success mb-0">
									<input type="hidden" name="types[]" value="show_language_switcher">
									<input type="checkbox" name="show_language_switcher"
										@if(get_setting('show_language_switcher') == 'on') checked @endif>
									<span></span>
								</label>
							</div>
						</div>
						<!-- Show Currency Switcher -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Show Currency Switcher?')}}</label>
							<div class="col-md-8">
								<label class="aiz-switch aiz-switch-success mb-0">
									<input type="hidden" name="types[]" value="show_currency_switcher">
									<input type="checkbox" name="show_currency_switcher"
										@if(get_setting('show_currency_switcher') == 'on') checked @endif>
									<span></span>
								</label>
							</div>
						</div>
						<!-- Enable stikcy header -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Enable stikcy header?')}}</label>
							<div class="col-md-8">
								<label class="aiz-switch aiz-switch-success mb-0">
									<input type="hidden" name="types[]" value="header_stikcy">
									<input type="checkbox" name="header_stikcy" @if(get_setting('header_stikcy') == 'on')
									checked @endif>
									<span></span>
								</label>
							</div>
						</div>

						<div class="border-top pt-3">
							@foreach($element_type->element_styles as $style)

							<div class="form-group row">
								<label class="col-md-3 col-from-label">{{ translate($style->name) }}</label>
								<div class="col-md-8">
									<div class="input-group">
										<input type="hidden" name="types[]" value="{{ $style->name }}">
										<input type="text" class="form-control aiz-color-input" placeholder="#000000"
											name="{{ $style->name }}" value="{{ get_setting($style->name) }}">
										<div class="input-group-append">
											<span class="input-group-text p-0">
												<input data-target="{{ $style->name }}"
													class="aiz-color-picker border-0 size-40px" type="color"
													value="{{ get_setting($style->name) }}">
											</span>
										</div>
									</div>
								</div>
							</div>
							@endforeach
							<!-- Help line number -->
							<div class="form-group row">
								<label class="col-md-3 col-from-label">{{translate('Help line number')}}</label>
								<div class="col-md-8">
									<div class="form-group">
										<input type="hidden" name="types[]" value="helpline_number">
										<input type="text" class="form-control"
											placeholder="{{ translate('Help line number') }}" name="helpline_number"
											value="{{ get_setting('helpline_number') }}">
									</div>
								</div>
							</div>

							<!-- Categories Dropdown Menu (header "Categories" dropdown items) -->
							<div class="border-top pt-3 mt-3">
								<label class="">{{ translate('Categories Dropdown Menu') }}</label>
								<small class="d-block text-muted mb-2">{{ translate('These are the main categories and sub-categories shown in the header "Categories" dropdown. Edit each item below; you can reorder main categories with the arrows.') }}</small>
								<style>
									/* Make the categories list readable and scrollable */
									.header-categories-dropdown-list {
										max-height: 520px;
										overflow: auto;
										padding-right: 8px;
									}
								</style>
								<input type="hidden" name="types[]" value="header_categories_order">
								<div class="header-categories-dropdown-list">
									@foreach ($mainCategories ?? [] as $mainCat)
										<div class="header-category-row border rounded p-2 mb-2" data-category-id="{{ $mainCat->id }}">
											<div class="d-flex align-items-center flex-wrap gap-2">
												<div class="d-flex flex-column gap-0">
													<button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-secondary move-category-up" title="{{ translate('Move up') }}"><i class="las la-arrow-up"></i></button>
													<button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-secondary move-category-down" title="{{ translate('Move down') }}"><i class="las la-arrow-down"></i></button>
												</div>
												<input type="hidden" name="header_categories_order[]" value="{{ $mainCat->id }}">
												<div class="d-flex align-items-center flex-grow-1">
													@if ($mainCat->catIcon && $mainCat->catIcon->file_name)
														<img src="{{ my_asset($mainCat->catIcon->file_name) }}" alt="" width="24" height="24" class="rounded mr-2" style="object-fit: contain;">
													@else
														<span class="mr-2 rounded bg-light d-inline-block" style="width:24px;height:24px;"></span>
													@endif
													<span class="fw-500">{{ $mainCat->getTranslation('name') }}</span>
													<span class="badge badge-soft-info ml-2">{{ translate('Main') }}</span>
												</div>
												<a href="/admin/categories/edit/{{ $mainCat->id }}?lang={{ env('DEFAULT_LANGUAGE') ?? 'en' }}" data-edit-url="/admin/categories/edit/{{ $mainCat->id }}?lang={{ env('DEFAULT_LANGUAGE') ?? 'en' }}" target="_blank" class="btn btn-soft-primary btn-sm header-category-edit-link" rel="noopener">
													<i class="las la-edit"></i> {{ translate('Edit') }}
												</a>
											</div>
											@if ($mainCat->childrenCategories && $mainCat->childrenCategories->count() > 0)
												<div class="ml-5 mt-2 pl-2 border-left">
													@foreach ($mainCat->childrenCategories as $subCat)
														@include('backend.website_settings.partials.header_category_child_row', ['category' => $subCat, 'depth' => 1])
													@endforeach
												</div>
											@endif
										</div>
									@endforeach
								</div>
								@if (isset($mainCategories) && $mainCategories->count() > 0)
									<p class="small text-muted mt-2">
										<a href="{{ route('categories.index') }}">{{ translate('Manage all categories') }}</a> ({{ translate('Products') }} → {{ translate('Categories') }})
									</p>
								@else
									<p class="small text-muted mt-2">{{ translate('No main categories yet.') }} <a href="{{ route('categories.create') }}">{{ translate('Add category') }}</a></p>
								@endif
							</div>
							<br>
							<!-- Update Button -->
							<div class="mt-4 text-right">
								<button type="submit"
									class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
							</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection

{{-- modal --}}
@section('modal')
	<div class="image-show-overlay" id="image-show-overlay">
		<div class="d-flex justify-content-end my-3 mr-3">
			<button type="button" class="btn text-white d-flex align-items-center justify-content-center"><i
					class="las la-2x la-times"></i></button>
		</div>
		<div class="overlay-img">
			<img src="{{ static_asset('assets/img/authentication_pages/boxed.png') }}" class="w-100" alt="img-show">
		</div>
	</div>
@endsection

@section('script')
	{{-- Language,currency, stikcy header visibility --}}
	<script>
		$(document).ready(function () {
			function toggleVisibility(inputName, targetClass, toggleClass = null) {
				const isChecked = $(`input[name="${inputName}"]`).is(':checked');

				if (toggleClass) {
					if (isChecked) {
						$(`.${targetClass}`).addClass(toggleClass);
					} else {
						$(`.${targetClass}`).removeClass(toggleClass);
					}
				} else {
					if (isChecked) {
						$(`.${targetClass}`).removeClass('d-none');
					} else {
						$(`.${targetClass}`).addClass('d-none');
					}
				}
			}

			function updateUI() {
				toggleVisibility('show_language_switcher', 'lang-visibility');
				toggleVisibility('show_currency_switcher', 'currency-visibility');
				toggleVisibility('header_stikcy', 'stikcy-header-visibility', 'sticky-top');
			}

			updateUI();

			$('input[name="show_language_switcher"], input[name="show_currency_switcher"], input[name="header_stikcy"]').on('change', function () {
				updateUI();
			});

			// Header menu: reorder links (move up / move down)
			$(document).on('click', '.move-menu-up', function () {
				var row = $(this).closest('.header-menu-row');
				var prev = row.prev('.header-menu-row');
				if (prev.length) row.insertBefore(prev);
			});
			$(document).on('click', '.move-menu-down', function () {
				var row = $(this).closest('.header-menu-row');
				var next = row.next('.header-menu-row');
				if (next.length) row.insertAfter(next);
			});

			// Hide "Add New" when 7 or more links; show when fewer
			function updateHeaderAddNewVisibility() {
				var count = $('.header-nav-menu .header-menu-row').length;
				$('#header-add-menu-btn-wrap').toggleClass('d-none', count >= 7);
			}
			updateHeaderAddNewVisibility();
			$(document).on('click', '.header-add-menu-btn', function () {
				setTimeout(updateHeaderAddNewVisibility, 50);
			});
			$(document).on('click', '[data-toggle="remove-parent"]', function () {
				var parent = $(this).closest($(this).data('parent'));
				if (parent.hasClass('header-menu-row') || parent.find('.header-menu-row').length) {
					setTimeout(updateHeaderAddNewVisibility, 150);
				}
			});

			// Categories dropdown: reorder main categories (move up / move down)
			$(document).on('click', '.move-category-up', function () {
				var row = $(this).closest('.header-category-row');
				var prev = row.prev('.header-category-row');
				if (prev.length) row.insertBefore(prev);
			});
			$(document).on('click', '.move-category-down', function () {
				var row = $(this).closest('.header-category-row');
				var next = row.next('.header-category-row');
				if (next.length) row.insertAfter(next);
			});

			// Category Edit: handle click ourselves so no other listener can redirect
			$(document).on('click', '.header-category-edit-link', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var url = $(this).data('edit-url') || $(this).attr('href');
				if (url) window.open(url, '_blank', 'noopener');
			});
		});
	</script>

	{{-- top_header_bg_color --}}
	<script>
		$(document).ready(function () {
			function updateTopHeaderBgColor() {
				const newColor = $('input[name="top_header_bg_color"]').val();
				if (newColor) {
					$('.top-background-color-visibility').css('background-color', newColor);
				}
			}

			$('input[name="top_header_bg_color"]').on('input keyup change', function (e) {
				e.preventDefault();
				updateTopHeaderBgColor();
			});

			$('.aiz-color-picker').on('input change', function (e) {
				e.preventDefault();
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateTopHeaderBgColor();
		});
	</script>

	{{-- middle_header_bg_color --}}
	<script>
		$(document).ready(function () {
			function updateMiddleHeaderBgColor() {
				const newColor = $('input[name="middle_header_bg_color"]').val();
				if (newColor) {
					$('.middle-background-color-visibility').css('background-color', newColor);
				}
			}

			$('input[name="middle_header_bg_color"]').on('input keyup change', function (e) {
				e.preventDefault();
				updateMiddleHeaderBgColor();
			});

			$('.aiz-color-picker').on('input change', function (e) {
				e.preventDefault();
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateMiddleHeaderBgColor();
		});
	</script>

	{{-- bottom_header_bg_color --}}
	<script>
		$(document).ready(function () {
			function updateBottomHeaderBgColor() {
				const newColor = $('input[name="bottom_header_bg_color"]').val();
				if (newColor) {
					$('.bottom-background-color-visibility').css('background-color', newColor);
				}
			}

			$('input[name="bottom_header_bg_color"]').on('input keyup change', function (e) {
				e.preventDefault();
				updateBottomHeaderBgColor();
			});

			$('.aiz-color-picker').on('input change', function (e) {
				e.preventDefault();
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateBottomHeaderBgColor();
		});
	</script>

	{{-- top_header_text_color --}}
	<script>
		$(document).ready(function () {
			function updateTopHeaderTextColor(name, cssProp, selector) {
				const newColor = $('input[name="' + name + '"]').val();
				if (newColor) {
					$(selector).css(cssProp, newColor);
				}
			}
			$('input[name="top_header_text_color"]').on('input change', function () {
				updateTopHeaderTextColor('top_header_text_color', 'color', '.top-text-color-visibility');
			});

			$('.aiz-color-picker').on('input change', function () {
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateTopHeaderTextColor('top_header_text_color', 'color', '.top-text-color-visibility');
		});

	</script>

	{{-- middle_header_text_color --}}
	<script>
		$(document).ready(function () {
			function updateMiddleHeaderTextColor(name, cssProp, selector) {
				const newColor = $('input[name="' + name + '"]').val();
				if (newColor) {
					$(selector).css(cssProp, newColor);
				}
			}
			$('input[name="middle_header_text_color"]').on('input change', function () {
				updateMiddleHeaderTextColor('middle_header_text_color', 'color', '.middle-text-color-visibility');
			});

			$('.aiz-color-picker').on('input change', function () {
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateMiddleHeaderTextColor('middle_header_text_color', 'color', '.middle-text-color-visibility');
		});
	</script>

	{{-- bottom_header_text_color --}}
	<script>
		$(document).ready(function () {
			function updateBottomHeaderTextColor(name, cssProp, selector) {
				const newColor = $('input[name="' + name + '"]').val();
				if (newColor) {
					$(selector).css(cssProp, newColor);
				}
			}
			$('input[name="bottom_header_text_color"]').on('input change', function () {
				updateBottomHeaderTextColor('bottom_header_text_color', 'color', '.bottom-text-color-visibility');
			});

			$('.aiz-color-picker').on('input change', function () {
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateBottomHeaderTextColor('bottom_header_text_color', 'color', '.bottom-text-color-visibility');
		});
	</script>

	<script>
		$(document).ready(function () {
			let previousFileId = null;

			setInterval(function () {
				let fileId = $('.selected-files[name="header_logo"]').val();

				if (fileId && fileId !== previousFileId) {
					previousFileId = fileId;

					$.ajax({
						url: 'get-upload-file-name',
						method: 'POST',
						data: {
							_token: '{{ csrf_token() }}',
							id: fileId
						},
						success: function (res) {
							if (res.success) {
								// Full image path with domain + public
								let imagePath = '{{ url('public') }}/' + res.file_name;

								// Set to preview image
								$('#header-logo-preview').attr('src', imagePath);

								console.log("Live Image Path:", imagePath);
							}
							else {
								alert(res.message);
							}
						},
						error: function () {
							alert("Something went wrong.");
						}
					});
				}
			}, 500);
		});
	</script>

	<script>
		$(document).ready(function () {
			const helplineContainer = $('#admin-helpline-preview .helpline-container');
			const previewElement = $('#admin-helpline-preview');

			function updateHelplineNumber() {
				const newNumber = $('input[name="helpline_number"]').val().trim();

				if (newNumber === '') {
					previewElement.hide();
				} else {
					previewElement.show();
					$('.helpline-number-preview').text(newNumber);
				}
			}

			$('input[name="helpline_number"]').on('input keyup change', function () {
				updateHelplineNumber();
			});

			updateHelplineNumber();
		});
	</script>

@endsection