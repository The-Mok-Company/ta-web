@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Pages Setup') }}</h1>
		</div>
	</div>
</div>

<!-- System Pages -->
<div class="card mb-3">
	<div class="card-header">
		<h6 class="mb-0 fw-600">{{ translate('System Pages') }}</h6>
		<small class="text-muted">{{ translate('These pages are managed from System Settings') }}</small>
	</div>
	<div class="card-body">
		<table class="table aiz-table mb-0">
			<thead>
				<tr>
					<th data-breakpoints="lg">#</th>
					<th>{{translate('Page Name')}}</th>
					<th data-breakpoints="md">{{translate('Data Source')}}</th>
					<th data-breakpoints="sm">{{translate('Status')}}</th>
					<th class="text-right">{{translate('Actions')}}</th>
				</tr>
			</thead>
			<tbody>
				<!-- Homepage -->
				<tr>
					<td>1</td>
					<td>
						<strong>{{ translate('Homepage') }}</strong>
						<br><small class="text-muted">{{ translate('Hero banners, featured categories, marketing sections') }}</small>
					</td>
					<td>
						<span class="badge badge-info">{{ translate('System Settings') }}</span>
					</td>
					<td>
						<span class="badge badge-success">{{ translate('Active') }}</span>
					</td>
					<td class="text-right">
						<a href="{{ route('pages-setup.preview.homepage') }}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="{{ translate('Preview') }}">
							<i class="las la-eye"></i>
						</a>
						<a href="{{ route('custom-pages.edit', ['id' => 'home', 'lang' => env('DEFAULT_LANGUAGE'), 'page' => 'home']) }}" class="btn btn-icon btn-circle btn-sm btn-soft-info" title="{{ translate('Edit in Settings') }}">
							<i class="las la-cog"></i>
						</a>
					</td>
				</tr>

				<!-- About Us -->
				<tr>
					<td>2</td>
					<td>
						<strong>{{ translate('About Us') }}</strong>
						<br><small class="text-muted">{{ translate('Company overview, vision & mission, platform values') }}</small>
					</td>
					<td>
						<span class="badge badge-info">{{ translate('System Settings') }}</span>
					</td>
					<td>
						@php
							$aboutUsHero = \App\Models\AboutUs::where('key', 'hero')->first();
							$hasContent = $aboutUsHero && !empty($aboutUsHero->value);
						@endphp
						@if($hasContent)
							<span class="badge badge-success">{{ translate('Configured') }}</span>
						@else
							<span class="badge badge-warning">{{ translate('Not Configured') }}</span>
						@endif
					</td>
					<td class="text-right">
						<a href="{{ route('pages-setup.preview.about-us') }}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="{{ translate('Preview') }}">
							<i class="las la-eye"></i>
						</a>
						<a href="{{ route('settings.about-us') }}" class="btn btn-icon btn-circle btn-sm btn-soft-info" title="{{ translate('Edit in Settings') }}">
							<i class="las la-cog"></i>
						</a>
					</td>
				</tr>

				<!-- Our Partners -->
				<tr>
					<td>3</td>
					<td>
						<strong>{{ translate('Our Partners') }}</strong>
						<br><small class="text-muted">{{ translate('Partner logos and names (Approved only)') }}</small>
					</td>
					<td>
						<span class="badge badge-info">{{ translate('System Settings') }}</span>
					</td>
					<td>
						@php
							$partnersBrands = \App\Models\OurPartners::where('key', 'brands')->first();
							$hasPartners = $partnersBrands && !empty($partnersBrands->value['items'] ?? []);
						@endphp
						@if($hasPartners)
							<span class="badge badge-success">{{ translate('Active') }}</span>
						@else
							<span class="badge badge-warning">{{ translate('No Partners') }}</span>
						@endif
					</td>
					<td class="text-right">
						<a href="{{ route('pages-setup.preview.our-partners') }}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="{{ translate('Preview') }}">
							<i class="las la-eye"></i>
						</a>
						<a href="{{ route('settings.our-partners') }}" class="btn btn-icon btn-circle btn-sm btn-soft-info" title="{{ translate('Edit in Settings') }}">
							<i class="las la-cog"></i>
						</a>
					</td>
				</tr>

				<!-- Our Services -->
				<tr>
					<td>4</td>
					<td>
						<strong>{{ translate('Our Services') }}</strong>
						<br><small class="text-muted">{{ translate('Platform offerings and services') }}</small>
					</td>
					<td>
						<span class="badge badge-secondary">{{ translate('Coming Soon') }}</span>
					</td>
					<td>
						<span class="badge badge-warning">{{ translate('In Progress') }}</span>
					</td>
					<td class="text-right">
						<a href="{{ route('pages-setup.preview.our-services') }}" class="btn btn-icon btn-circle btn-sm btn-soft-secondary" title="{{ translate('Preview') }}">
							<i class="las la-eye"></i>
						</a>
					</td>
				</tr>

				<!-- Footer -->
				<tr>
					<td>5</td>
					<td>
						<strong>{{ translate('Footer') }}</strong>
						<br><small class="text-muted">{{ translate('Footer links, social media, copyright text') }}</small>
					</td>
					<td>
						<span class="badge badge-info">{{ translate('System Settings') }}</span>
					</td>
					<td>
						<span class="badge badge-success">{{ translate('Active') }}</span>
					</td>
					<td class="text-right">
						<a href="{{ route('pages-setup.preview.footer') }}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="{{ translate('Preview') }}">
							<i class="las la-eye"></i>
						</a>
						<a href="{{ route('website.footer') }}" class="btn btn-icon btn-circle btn-sm btn-soft-info" title="{{ translate('Edit in Settings') }}">
							<i class="las la-cog"></i>
						</a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<!-- Custom Pages -->
<div class="card">
	@can('add_website_page')
		<div class="card-header">
			<h6 class="mb-0 fw-600">{{ translate('Custom Pages') }}</h6>
			<a href="{{ route('custom-pages.create') }}" class="btn btn-circle btn-info">{{ translate('Add New Page') }}</a>
		</div>
	@endcan
	<div class="card-body">
		<table class="table aiz-table mb-0">
			<thead>
				<tr>
					<th data-breakpoints="lg">#</th>
					<th>{{translate('Name')}}</th>
					<th data-breakpoints="md">{{translate('URL')}}</th>
					<th data-breakpoints="sm">{{translate('Type')}}</th>
					<th class="text-right">{{translate('Actions')}}</th>
				</tr>
			</thead>
			<tbody>
				<!-- Contact Us -->
				@php
					$contactPage = \App\Models\Page::where('slug', 'contact-us')->orWhere('type', 'contact_us_page')->first();
				@endphp
				@if($contactPage)
				<tr>
					<td>1</td>
					<td>
						<strong>{{ translate('Contact Us') }}</strong>
						<br><small class="text-muted">{{ translate('Contact form and company information') }}</small>
					</td>
					<td>{{ route('home') }}/{{ $contactPage->slug }}</td>
					<td>
						<span class="badge badge-primary">{{ translate('Editable') }}</span>
					</td>
					<td class="text-right">
						@can('edit_website_page')
							<a href="{{route('custom-pages.edit', ['id'=>$contactPage->slug, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="{{ translate('Edit') }}">
								<i class="las la-pen"></i>
							</a>
						@endcan
						<a href="{{ route('custom-pages.show_custom_page', $contactPage->slug) }}" class="btn btn-icon btn-circle btn-sm btn-soft-info" target="_blank" title="{{ translate('View') }}">
							<i class="las la-external-link-alt"></i>
						</a>
					</td>
				</tr>
				@endif

				<!-- Other Custom Pages -->
				@foreach ($page as $key => $pageItem)
					@if($pageItem->type == 'custom_page' && $pageItem->slug != 'contact-us')
					<tr>
						<td>{{ $key+2 }}</td>
						<td><a href="{{ route('custom-pages.show_custom_page', $pageItem->slug) }}" class="text-reset">{{ $pageItem->getTranslation('title') }}</a></td>
						<td>{{ route('home') }}/{{ $pageItem->slug }}</td>
						<td>
							<span class="badge badge-secondary">{{ translate('Custom') }}</span>
						</td>
						<td class="text-right">
							@can('edit_website_page')
								<a href="{{route('custom-pages.edit', ['id'=>$pageItem->slug, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="{{ translate('Edit') }}">
									<i class="las la-pen"></i>
								</a>
							@endcan
							@if(auth()->user()->can('delete_website_page'))
								<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('custom-pages.destroy', $pageItem->id)}} " title="{{ translate('Delete') }}">
									<i class="las la-trash"></i>
								</a>
							@endif
						</td>
					</tr>
					@endif
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
