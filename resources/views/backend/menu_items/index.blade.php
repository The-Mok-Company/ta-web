@extends('backend.layouts.app')

@section('content')
    @php
        $topLevelMenuItemCount = \App\Models\MenuItem::whereNull('parent_id')->count();
    @endphp
    <div class="col-12 col-sm-12 col-lg-10 mx-auto">
        <div class="aiz-titlebar text-left pb-5px">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h1 class="h3 fw-bold">{{ translate('Menu Items') }}</h1>
                </div>
            </div>
        </div>
        <div class="card menu-items-index-card">
            <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom border-light px-25px table-nav-tabs pb-3 pt-3">
                <span class="fs-14 fw-500 text-secondary">{{ translate('All Menu Items') }}</span>
                @can('header_setup')
                    @if ($topLevelMenuItemCount < 7)
                        <a href="{{ route('menu-items.create') }}" class="btn btn-primary btn-sm">
                            <span class="fs-14 fw-500">{{ translate('Add New Menu Item') }}</span>
                        </a>
                    @endif
                @endcan
            </div>
            <style>
                .menu-items-index-card .table-nav-tabs .input-group-append .btn:hover { opacity: 0.9; }
            </style>
            <div class="card-body">
                <div class="mb-3 d-flex flex-wrap align-items-center gap-2">
                    <form class="d-inline-flex" action="{{ route('menu-items.index') }}" method="GET">
                        <div class="input-group border border-light px-3 bg-light rounded-1">
                            <input type="text" class="form-control form-control-sm border-0 bg-transparent" name="search" value="{{ $sort_search ?? '' }}" placeholder="{{ translate('Search menu items...') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-sm btn-soft-secondary">{{ translate('Search') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase fs-10 fw-700 text-secondary">#</th>
                            <th class="text-uppercase fs-10 fw-700 text-secondary">{{ translate('Label') }}</th>
                            <th class="text-uppercase fs-10 fw-700 text-secondary">{{ translate('Parent') }}</th>
                            <th class="text-uppercase fs-10 fw-700 text-secondary">{{ translate('Link') }}</th>
                            <th class="text-uppercase fs-10 fw-700 text-secondary">{{ translate('Order') }}</th>
                            <th class="text-right text-uppercase fs-10 fw-700 text-secondary">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menu_items as $key => $row)
                            @php
                                /** @var \App\Models\MenuItem $item */
                                $item = $row['item'];
                                $depth = (int) ($row['depth'] ?? 0);
                                $matches = (bool) ($row['matches'] ?? false);
                                $hasChildren = $item->relationLoaded('childrenWithNested') && $item->childrenWithNested && $item->childrenWithNested->count() > 0;
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-500">
                                    <div style="padding-left: {{ $depth * 18 }}px;">
                                        @if ($depth > 0)
                                            <span class="text-muted mr-1">{{ str_repeat('— ', $depth) }}</span>
                                        @endif
                                        <span class="{{ $matches ? 'text-primary fw-700' : '' }}">{{ $item->label }}</span>
                                        @if ($hasChildren)
                                            <span class="badge badge-inline badge-soft-info ml-1 px-2">{{ translate('Dropdown') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-muted small">{{ $item->parent_id ? optional($item->parent)->label : '—' }}</td>
                                <td class="text-muted small">{{ Str::limit($item->link, 40) }}</td>
                                <td>{{ $item->sort_order }}</td>
                                <td class="text-right">
                                    @can('header_setup')
                                    <a href="{{ route('menu-items.create', ['parent_id' => $item->id]) }}" class="btn btn-soft-info btn-sm">{{ translate('Add child') }}</a>
                                    <a href="{{ route('menu-items.edit', $item->id) }}" class="btn btn-soft-primary btn-sm">{{ translate('Edit') }}</a>
                                    <form action="{{ route('menu-items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ translate('Delete this menu item?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-sm">{{ translate('Delete') }}</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="text-muted mb-3">{{ translate('No menu items yet.') }}</p>
                                    @can('header_setup')
                                    <a href="{{ route('menu-items.create') }}" class="btn btn-primary btn-sm mr-2">{{ translate('Add New Menu Item') }}</a>
                                    @else
                                    <a href="{{ route('website.header') }}" class="btn btn-soft-secondary btn-sm">{{ translate('Header Settings') }}</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
