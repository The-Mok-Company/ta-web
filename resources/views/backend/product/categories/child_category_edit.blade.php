@php
    $value = null;
    for ($i=0; $i < $child_category->level; $i++){
        $value .= '--';
    }
    $child_categories = $category ? $child_category->categories->whereNotIn('id', App\Utility\CategoryUtility::children_ids($category->id, true))->where('id', '!=', $category->id) : $child_category->categories;
@endphp
<option value="{{ $child_category->id }}" @if($category && $category->parent_id == $child_category->id) selected @endif>{{ $value." ".$child_category->getTranslation('name') }}</option>
@if (count($child_categories) > 0)
    @foreach ($child_categories as $childCategory)
        @include('backend.product.categories.child_category_edit', ['child_category' => $childCategory, 'category' => $category])
    @endforeach
@endif
