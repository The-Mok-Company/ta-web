@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('About Us Preview') }}</h1>
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
			<p class="mb-2"><strong>{{ translate('Note:') }}</strong> {{ translate('This is a read-only preview of your About Us page content.') }}</p>
			<p class="mb-0">
				{{ translate('To edit About Us content, click') }} 
				<a href="{{ route('settings.about-us') }}" class="alert-link">
					<strong>{{ translate('Edit in Settings') }}</strong>
				</a>
			</p>
		</div>

		<!-- About Us Content Preview -->
		<div class="preview-content">
			@if(!$hero && !$mission && !$vision)
				<div class="alert alert-warning">
					<strong>{{ translate('Content Not Configured') }}</strong>
					<p class="mb-0">{{ translate('Please configure the About Us content in System Settings.') }}</p>
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

				<!-- Mission Section -->
				@if($mission && !empty($mission->value))
					<div class="mb-4">
						<h5 class="mb-3">{{ translate('Mission') }}</h5>
						<div class="border rounded p-4">
							@if(!empty($mission->value['title']))
								<h4>{{ $mission->value['title'] }}</h4>
							@endif
							@if(!empty($mission->value['description']))
								<p>{{ $mission->value['description'] }}</p>
							@endif
						</div>
					</div>
				@endif

				<!-- Vision Section -->
				@if($vision && !empty($vision->value))
					<div class="mb-4">
						<h5 class="mb-3">{{ translate('Vision') }}</h5>
						<div class="border rounded p-4">
							@if(!empty($vision->value['title']))
								<h4>{{ $vision->value['title'] }}</h4>
							@endif
							@if(!empty($vision->value['description']))
								<p>{{ $vision->value['description'] }}</p>
							@endif
							@if(!empty($vision->value['images']))
								<div class="row mt-3">
									@foreach($vision->value['images'] as $image)
										@if($image)
											<div class="col-md-3 mb-3">
												<img src="{{ asset($image) }}" alt="Vision" class="img-fluid rounded">
											</div>
										@endif
									@endforeach
								</div>
							@endif
						</div>
					</div>
				@endif
			@endif
		</div>

		<div class="text-center mt-4">
			<a href="{{ route('settings.about-us') }}" class="btn btn-primary">
				<i class="las la-cog"></i> {{ translate('Edit in System Settings') }}
			</a>
		</div>
	</div>
</div>
@endsection
