@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Edit Menu Item') }}</h5>
    </div>
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('menu-items.update', $menu_item->id) }}" method="POST" id="menuItemForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label class="col-form-label">{{ translate('Label') }}</label>
                            <input type="text" name="label" class="form-control" maxlength="255" value="{{ old('label', $menu_item->label) }}" placeholder="{{ translate('e.g. Home, Categories') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="col-form-label">{{ translate('Parent') }}</label>
                            <select name="parent_id" class="form-control">
                                <option value="">{{ translate('No Parent (top-level)') }}</option>
                                @foreach ($parents ?? [] as $row)
                                    @php
                                        /** @var \App\Models\MenuItem $p */
                                        $p = $row['item'];
                                        $depth = (int) ($row['depth'] ?? 0);
                                    @endphp
                                    <option value="{{ $p->id }}" {{ (string)old('parent_id', $menu_item->parent_id) === (string)$p->id ? 'selected' : '' }}>
                                        {{ ($depth > 0 ? str_repeat('— ', $depth) . ' ' : '') . $p->label }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ translate('Select parent to show under a dropdown.') }}</small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="col-form-label">{{ translate('Link') }}</label>
                            <input type="text" name="link" class="form-control" maxlength="500" value="{{ old('link', $menu_item->link) }}" placeholder="{{ translate('URL or path') }}">
                            <small class="text-muted">{{ translate('Use categories page URL for "Categories" to show the Categories dropdown.') }}</small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="col-form-label">{{ translate('Order') }}</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $menu_item->sort_order) }}">
                            <small class="text-muted">{{ translate('Higher number appears first.') }}</small>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <a href="{{ route('menu-items.index') }}" class="btn btn-soft-secondary">{{ translate('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
