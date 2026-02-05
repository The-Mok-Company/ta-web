<option value="0">{{ translate('No Parent') }}</option>
@foreach ($categories as $p_category)
    <option value="{{ $p_category->id }}" @if(isset($selected_parent_id) && $selected_parent_id == $p_category->id) selected @endif>{{ $p_category->getTranslation('name') }}</option>
    @foreach ($p_category->childrenCategories as $childCategory)
        @include('categories.child_category', ['child_category' => $childCategory, 'selected_parent_id' => $selected_parent_id ?? null])
    @endforeach
@endforeach
