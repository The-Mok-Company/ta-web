@php
    $value = null;
    for ($i=0; $i < $child_category->level; $i++){
        $value .= '--';
    }
    $is_selected = isset($selected_parent_id) && $selected_parent_id == $child_category->id;
@endphp
<option class="hov-text-white" value="{{ $child_category->id }}" @if($is_selected) selected @endif>{{ $value." ".$child_category->getTranslation('name') }}</option>
@if ($child_category->categories)
    @foreach ($child_category->categories as $childCategory)
        @include('categories.child_category', ['child_category' => $childCategory, 'selected_parent_id' => $selected_parent_id ?? null])
    @endforeach
@endif
