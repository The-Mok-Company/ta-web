@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Our Services') }}</h1>
		</div>
		<div class="col-auto">
			<a href="{{ route('website.pages') }}" class="btn btn-circle btn-info">
				<i class="las la-arrow-left"></i> {{ translate('Back to Pages') }}
			</a>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header bg-secondary">
		<div class="d-flex align-items-center">
			<i class="las la-clock mr-2"></i>
			<div>
				<h6 class="mb-0 fw-600">{{ translate('Coming Soon') }}</h6>
				<small class="text-white">{{ translate('Page design is in progress') }}</small>
			</div>
		</div>
	</div>
	<div class="card-body text-center py-5">
		<div class="mb-4">
			<i class="las la-tools" style="font-size: 64px; color: #6c757d;"></i>
		</div>
		<h4 class="mb-3">{{ translate('Our Services Page') }}</h4>
		<p class="text-muted mb-4">
			{{ translate('This page is currently under development. The design is being handled by the design team.') }}
		</p>
		<div class="alert alert-info mx-auto" style="max-width: 600px;">
			<p class="mb-2"><strong>{{ translate('Status:') }}</strong> {{ translate('In Progress') }}</p>
			<p class="mb-0">
				{{ translate('Once the design is finalized, this page will support:') }}
			</p>
			<ul class="text-left mt-3 mb-0">
				<li>{{ translate('Service title') }}</li>
				<li>{{ translate('Service description') }}</li>
				<li>{{ translate('Icon or image per service') }}</li>
			</ul>
		</div>
		<p class="text-muted mt-4">
			<small>{{ translate('No edit actions are available until the design is finalized.') }}</small>
		</p>
	</div>
</div>
@endsection
