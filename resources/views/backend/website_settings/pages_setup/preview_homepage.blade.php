@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Homepage Preview') }}</h1>
		</div>
		<div class="col-auto">
			<a href="{{ route('website.pages') }}" class="btn btn-circle btn-info">
				<i class="las la-arrow-left"></i> {{ translate('Back to Pages') }}
			</a>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header bg-warning">
		<div class="d-flex align-items-center">
			<i class="las la-info-circle mr-2"></i>
			<div>
				<h6 class="mb-0 fw-600">{{ translate('Read-Only Preview') }}</h6>
				<small class="text-white">{{ translate('Content is managed from System Settings') }}</small>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="alert alert-info">
			<p class="mb-2"><strong>{{ translate('Note:') }}</strong> {{ translate('This is a read-only preview of your homepage content.') }}</p>
			<p class="mb-0">
				{{ translate('To edit homepage content, click') }} 
				<a href="{{ route('custom-pages.edit', ['id' => 'home', 'lang' => env('DEFAULT_LANGUAGE'), 'page' => 'home']) }}" class="alert-link">
					<strong>{{ translate('Edit in Settings') }}</strong>
				</a>
			</p>
		</div>

		<!-- Homepage Content Preview -->
		<div class="preview-content">
			<!-- Hero Slider -->
			<div class="mb-4">
				<h5 class="mb-3">{{ translate('Hero Banners') }}</h5>
				@php
					$sliderImages = $homepageSettings['slider_images'] ? json_decode($homepageSettings['slider_images'], true) : [];
					$sliderLinks = $homepageSettings['slider_links'] ? json_decode($homepageSettings['slider_links'], true) : [];
				@endphp
				@if(!empty($sliderImages))
					<div class="row">
						@foreach($sliderImages as $key => $imageId)
							<div class="col-md-4 mb-3">
								<div class="border rounded p-2">
									@if($imageId)
										<img src="{{ uploaded_asset($imageId) }}" alt="Slider {{ $key+1 }}" class="img-fluid rounded">
									@else
										<div class="bg-light p-5 text-center text-muted">
											{{ translate('No Image') }}
										</div>
									@endif
									@if(isset($sliderLinks[$key]))
										<small class="d-block mt-2 text-muted">
											<i class="las la-link"></i> {{ $sliderLinks[$key] }}
										</small>
									@endif
								</div>
							</div>
						@endforeach
					</div>
				@else
					<div class="alert alert-warning">
						{{ translate('No hero banners configured') }}
					</div>
				@endif
			</div>

			<!-- Banner 1 -->
			<div class="mb-4">
				<h5 class="mb-3">{{ translate('Banner Section 1') }}</h5>
				@php
					$banner1Images = $homepageSettings['banner1_images'] ? json_decode($homepageSettings['banner1_images'], true) : [];
					$banner1Links = $homepageSettings['banner1_links'] ? json_decode($homepageSettings['banner1_links'], true) : [];
				@endphp
				@if(!empty($banner1Images))
					<div class="row">
						@foreach($banner1Images as $key => $imageId)
							<div class="col-md-3 mb-3">
								<div class="border rounded p-2">
									@if($imageId)
										<img src="{{ uploaded_asset($imageId) }}" alt="Banner 1 {{ $key+1 }}" class="img-fluid rounded">
									@else
										<div class="bg-light p-3 text-center text-muted">
											{{ translate('No Image') }}
										</div>
									@endif
									@if(isset($banner1Links[$key]))
										<small class="d-block mt-2 text-muted">
											<i class="las la-link"></i> {{ $banner1Links[$key] }}
										</small>
									@endif
								</div>
							</div>
						@endforeach
					</div>
				@else
					<div class="alert alert-warning">
						{{ translate('No banners configured') }}
					</div>
				@endif
			</div>

			<!-- Banner 2 -->
			<div class="mb-4">
				<h5 class="mb-3">{{ translate('Banner Section 2') }}</h5>
				@php
					$banner2Images = $homepageSettings['banner2_images'] ? json_decode($homepageSettings['banner2_images'], true) : [];
					$banner2Links = $homepageSettings['banner2_links'] ? json_decode($homepageSettings['banner2_links'], true) : [];
				@endphp
				@if(!empty($banner2Images))
					<div class="row">
						@foreach($banner2Images as $key => $imageId)
							<div class="col-md-3 mb-3">
								<div class="border rounded p-2">
									@if($imageId)
										<img src="{{ uploaded_asset($imageId) }}" alt="Banner 2 {{ $key+1 }}" class="img-fluid rounded">
									@else
										<div class="bg-light p-3 text-center text-muted">
											{{ translate('No Image') }}
										</div>
									@endif
									@if(isset($banner2Links[$key]))
										<small class="d-block mt-2 text-muted">
											<i class="las la-link"></i> {{ $banner2Links[$key] }}
										</small>
									@endif
								</div>
							</div>
						@endforeach
					</div>
				@else
					<div class="alert alert-warning">
						{{ translate('No banners configured') }}
					</div>
				@endif
			</div>

			<!-- Featured Categories -->
			<div class="mb-4">
				<h5 class="mb-3">{{ translate('Featured Categories') }}</h5>
				@php
					$featuredCategories = $homepageSettings['featured_categories'] ? json_decode($homepageSettings['featured_categories'], true) : [];
				@endphp
				@if(!empty($featuredCategories))
					<div class="alert alert-info">
						{{ translate('Featured Categories:') }} {{ count($featuredCategories) }} {{ translate('categories configured') }}
					</div>
				@else
					<div class="alert alert-warning">
						{{ translate('No featured categories configured') }}
					</div>
				@endif
			</div>
		</div>

		<div class="text-center mt-4">
			<a href="{{ route('custom-pages.edit', ['id' => 'home', 'lang' => env('DEFAULT_LANGUAGE'), 'page' => 'home']) }}" class="btn btn-primary">
				<i class="las la-cog"></i> {{ translate('Edit in System Settings') }}
			</a>
		</div>
	</div>
</div>
@endsection
