@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Our Partners Preview') }}</h1>
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
				<small class="text-white">{{ translate('Content is auto-populated from Approved Partners') }}</small>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="alert alert-info">
			<p class="mb-2"><strong>{{ translate('Note:') }}</strong> {{ translate('This page automatically displays approved partners. No manual editing allowed.') }}</p>
			<p class="mb-0">
				{{ translate('To manage partners, click') }} 
				<a href="{{ route('settings.our-partners') }}" class="alert-link">
					<strong>{{ translate('Edit in Settings') }}</strong>
				</a>
			</p>
		</div>

		<!-- Partners Content Preview -->
		<div class="preview-content">
			@php
				$hasPartners = $brands && !empty($brands->value['items'] ?? []);
			@endphp

			@if(!$hasPartners)
				<div class="alert alert-warning">
					<strong>{{ translate('No Partners Configured') }}</strong>
					<p class="mb-0">{{ translate('No approved partners are currently configured. Partners will appear here once they are added in System Settings.') }}</p>
				</div>
			@else
				<!-- Hero Section -->
				@if($hero && !empty($hero->value))
					<div class="mb-4">
						<h5 class="mb-3">{{ translate('Hero Section') }}</h5>
						<div class="border rounded p-4">
							@if(!empty($hero->value['image']))
								<div class="mb-3">
									<img src="{{ asset($hero->value['image']) }}" alt="Hero" class="img-fluid rounded" style="max-height: 300px;">
								</div>
							@endif
							@if(!empty($hero->value['title']))
								<h4>{{ $hero->value['title'] }}</h4>
							@endif
							@if(!empty($hero->value['subtitle']))
								<p class="text-muted">{{ $hero->value['subtitle'] }}</p>
							@endif
						</div>
					</div>
				@endif

				<!-- Trust Text -->
				@if($trust && !empty($trust->value['text']))
					<div class="mb-4">
						<h5 class="mb-3">{{ translate('Trust Section') }}</h5>
						<div class="border rounded p-4">
							<p class="mb-0">{{ $trust->value['text'] }}</p>
						</div>
					</div>
				@endif

				<!-- Partner Brands -->
				@if($brands && !empty($brands->value['items']))
					<div class="mb-4">
						<h5 class="mb-3">{{ translate('Partner Brands') }} ({{ count($brands->value['items']) }})</h5>
						<div class="row">
							@foreach($brands->value['items'] as $brand)
								<div class="col-md-3 col-sm-4 col-6 mb-3">
									<div class="border rounded p-3 text-center">
										@if(!empty($brand['logo']))
											<img src="{{ asset($brand['logo']) }}" alt="{{ $brand['name'] ?? 'Partner' }}" class="img-fluid mb-2" style="max-height: 80px;">
										@else
											<div class="bg-light p-3 mb-2 text-muted">
												{{ translate('No Logo') }}
											</div>
										@endif
										@if(!empty($brand['name']))
											<strong class="d-block">{{ $brand['name'] }}</strong>
										@endif
									</div>
								</div>
							@endforeach
						</div>
					</div>
				@endif

				<!-- Count Section -->
				@if($count && !empty($count->value))
					<div class="mb-4">
						<h5 class="mb-3">{{ translate('Statistics') }}</h5>
						<div class="border rounded p-4">
							<pre class="mb-0">{{ json_encode($count->value, JSON_PRETTY_PRINT) }}</pre>
						</div>
					</div>
				@endif
			@endif
		</div>

		<div class="text-center mt-4">
			<a href="{{ route('settings.our-partners') }}" class="btn btn-primary">
				<i class="las la-cog"></i> {{ translate('Edit in System Settings') }}
			</a>
		</div>
	</div>
</div>
@endsection
