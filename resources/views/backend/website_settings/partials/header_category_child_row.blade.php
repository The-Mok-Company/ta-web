@php
    /** @var \App\Models\Category $category */
    $depth = (int) ($depth ?? 1);
@endphp

<div class="d-flex align-items-center justify-content-between py-1" style="padding-left: {{ max(0, $depth - 1) * 16 }}px;">
    <span class="text-muted">
        {{ str_repeat('— ', $depth) }} {{ $category->getTranslation('name') }}
    </span>
    <a href="/admin/categories/edit/{{ $category->id }}?lang={{ env('DEFAULT_LANGUAGE') ?? 'en' }}" data-edit-url="/admin/categories/edit/{{ $category->id }}?lang={{ env('DEFAULT_LANGUAGE') ?? 'en' }}" target="_blank" class="btn btn-soft-secondary btn-xs header-category-edit-link" rel="noopener">
        <i class="las la-edit"></i> {{ translate('Edit') }}
    </a>
</div>

@if ($category->categories && $category->categories->count() > 0)
    @foreach ($category->categories as $child)
        @include('backend.website_settings.partials.header_category_child_row', ['category' => $child, 'depth' => $depth + 1])
    @endforeach
@endif

