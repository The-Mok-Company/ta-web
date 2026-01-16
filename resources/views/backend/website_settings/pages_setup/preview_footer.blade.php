@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Footer Preview') }}</h1>
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
			<p class="mb-2"><strong>{{ translate('Note:') }}</strong> {{ translate('This is a read-only preview of your footer content.') }}</p>
			<p class="mb-0">
				{{ translate('To edit footer content, click') }} 
				<a href="{{ route('website.footer') }}" class="alert-link">
					<strong>{{ translate('Edit in Settings') }}</strong>
				</a>
			</p>
		</div>

		<!-- Footer Content Preview -->
		<div class="preview-content">
			<!-- About Description -->
			<div class="mb-4">
				<h5 class="mb-3">{{ translate('About Description') }}</h5>
				<div class="border rounded p-4">
					@if(!empty($footerSettings['about_us_description']))
						<div>{!! $footerSettings['about_us_description'] !!}</div>
					@else
						<p class="text-muted mb-0">{{ translate('Not configured') }}</p>
					@endif
				</div>
			</div>

			<!-- App Store Links -->
			<div class="mb-4">
				<h5 class="mb-3">{{ translate('App Store Links') }}</h5>
				<div class="row">
					<div class="col-md-6">
						<div class="border rounded p-3">
							<strong>{{ translate('Play Store:') }}</strong>
							@if(!empty($footerSettings['play_store_link']))
								<a href="{{ $footerSettings['play_store_link'] }}" target="_blank" class="d-block mt-2">
									{{ $footerSettings['play_store_link'] }}
								</a>
							@else
								<p class="text-muted mb-0 mt-2">{{ translate('Not configured') }}</p>
							@endif
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-3">
							<strong>{{ translate('App Store:') }}</strong>
							@if(!empty($footerSettings['app_store_link']))
								<a href="{{ $footerSettings['app_store_link'] }}" target="_blank" class="d-block mt-2">
									{{ $footerSettings['app_store_link'] }}
								</a>
							@else
								<p class="text-muted mb-0 mt-2">{{ translate('Not configured') }}</p>
							@endif
						</div>
					</div>
				</div>
			</div>

			<!-- Social Media Links -->
			<div class="mb-4">
				<h5 class="mb-3">{{ translate('Social Media Links') }}</h5>
				<div class="border rounded p-4">
					@if($footerSettings['show_social_links'] == 'on')
						<div class="row">
							@if(!empty($footerSettings['facebook_link']))
								<div class="col-md-6 mb-2">
									<i class="lab la-facebook-f"></i> 
									<strong>Facebook:</strong> 
									<a href="{{ $footerSettings['facebook_link'] }}" target="_blank">{{ $footerSettings['facebook_link'] }}</a>
								</div>
							@endif
							@if(!empty($footerSettings['twitter_link']))
								<div class="col-md-6 mb-2">
									<i class="lab la-twitter"></i> 
									<strong>Twitter:</strong> 
									<a href="{{ $footerSettings['twitter_link'] }}" target="_blank">{{ $footerSettings['twitter_link'] }}</a>
								</div>
							@endif
							@if(!empty($footerSettings['instagram_link']))
								<div class="col-md-6 mb-2">
									<i class="lab la-instagram"></i> 
									<strong>Instagram:</strong> 
									<a href="{{ $footerSettings['instagram_link'] }}" target="_blank">{{ $footerSettings['instagram_link'] }}</a>
								</div>
							@endif
							@if(!empty($footerSettings['youtube_link']))
								<div class="col-md-6 mb-2">
									<i class="lab la-youtube"></i> 
									<strong>YouTube:</strong> 
									<a href="{{ $footerSettings['youtube_link'] }}" target="_blank">{{ $footerSettings['youtube_link'] }}</a>
								</div>
							@endif
							@if(!empty($footerSettings['linkedin_link']))
								<div class="col-md-6 mb-2">
									<i class="lab la-linkedin-in"></i> 
									<strong>LinkedIn:</strong> 
									<a href="{{ $footerSettings['linkedin_link'] }}" target="_blank">{{ $footerSettings['linkedin_link'] }}</a>
								</div>
							@endif
						</div>
						@if(empty($footerSettings['facebook_link']) && empty($footerSettings['twitter_link']) && empty($footerSettings['instagram_link']) && empty($footerSettings['youtube_link']) && empty($footerSettings['linkedin_link']))
							<p class="text-muted mb-0">{{ translate('Social links enabled but no links configured') }}</p>
						@endif
					@else
						<p class="text-muted mb-0">{{ translate('Social links are disabled') }}</p>
					@endif
				</div>
			</div>

			<!-- Copyright Text -->
			<div class="mb-4">
				<h5 class="mb-3">{{ translate('Copyright Text') }}</h5>
				<div class="border rounded p-4">
					@if(!empty($footerSettings['frontend_copyright_text']))
						<div>{!! $footerSettings['frontend_copyright_text'] !!}</div>
					@else
						<p class="text-muted mb-0">{{ translate('Not configured') }}</p>
					@endif
				</div>
			</div>
		</div>

		<div class="text-center mt-4">
			<a href="{{ route('website.footer') }}" class="btn btn-primary">
				<i class="las la-cog"></i> {{ translate('Edit in System Settings') }}
			</a>
		</div>
	</div>
</div>
@endsection
