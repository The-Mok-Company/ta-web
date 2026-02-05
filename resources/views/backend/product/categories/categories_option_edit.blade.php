<option value="0">{{ translate('No Parent') }}</option>
@foreach ($categories as $p_category)
    <option value="{{ $p_category->id }}" @if(isset($category) && $category->parent_id == $p_category->id) selected @endif>{{ $p_category->getTranslation('name') }}</option>
    @foreach ($p_category->childrenCategories as $childCategory)
        @include('backend.product.categories.child_category_edit', ['child_category' => $childCategory, 'category' => $category ?? null])
    @endforeach
@endforeach
