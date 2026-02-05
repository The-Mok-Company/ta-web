@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('New Menu Item') }}</h5>
                </div>
                <div class="card-body">
                    @if (!empty($selected_parent))
                        <div class="alert alert-info mb-4">
                            {{ translate('Adding a dropdown child under') }}: <strong>{{ $selected_parent->label }}</strong>
                        </div>
                    @endif
                    <form action="{{ route('menu-items.store') }}" method="POST" id="menuItemForm">
                        @csrf
                        @if (!empty($selected_parent_id))
                            <input type="hidden" name="parent_id" value="{{ $selected_parent_id }}">
                        @endif
                        <div class="form-group mb-3">
                            <label class="col-form-label">{{ translate('Label') }}</label>
                            <input type="text" name="label" class="form-control" maxlength="255" placeholder="{{ translate('e.g. Home, Categories, About Us') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="col-form-label">{{ translate('Parent') }}</label>
                            <select name="{{ empty($selected_parent_id) ? 'parent_id' : 'parent_id_display' }}" class="form-control" {{ !empty($selected_parent_id) ? 'disabled' : '' }}>
                                <option value="">{{ translate('No Parent (top-level link)') }}</option>
                                @foreach ($parents ?? [] as $row)
                                    @php
                                        /** @var \App\Models\MenuItem $p */
                                        $p = $row['item'];
                                        $depth = (int) ($row['depth'] ?? 0);
                                    @endphp
                                    <option value="{{ $p->id }}" {{ (string)($p->id) === (string)old('parent_id', $selected_parent_id ?? '') ? 'selected' : '' }}>
                                        {{ ($depth > 0 ? str_repeat('— ', $depth) . ' ' : '') . $p->label }}
                                    </option>
                                @endforeach
                            </select>
                            @if (!empty($selected_parent_id))
                                <small class="text-muted">{{ translate('This item will appear under the parent selected above.') }}</small>
                            @else
                                <small class="text-muted">{{ translate('Select a parent to make this a dropdown child (e.g. under "Our Partners"). Leave empty for a main nav link.') }}</small>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label class="col-form-label">{{ translate('Link') }}</label>
                            <input type="text" name="link" class="form-control" maxlength="500" placeholder="{{ translate('URL with http:// or https://, or path like /categories') }}">
                            <small class="text-muted">{{ translate('Use your categories page URL for "Categories" to show the Categories dropdown. For a dropdown parent (e.g. Our Partners), use # or the main page URL.') }}</small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="col-form-label">{{ translate('Order') }}</label>
                            <input type="number" name="sort_order" class="form-control" value="0" placeholder="{{ translate('Higher number = higher in menu') }}">
                            <small class="text-muted">{{ translate('Higher number appears first in the navbar.') }}</small>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <a href="{{ route('menu-items.index') }}" class="btn btn-soft-secondary">{{ translate('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
