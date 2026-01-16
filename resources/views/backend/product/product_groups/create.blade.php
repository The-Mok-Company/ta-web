@extends('backend.layouts.app')

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Product Group Information')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('product-groups.store') }}" method="POST" enctype="multipart/form-data" id="aizSubmitForm">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Name')}} <span class="text-danger">*</span></label>
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" maxlength="255" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Sub-Category')}} <span class="text-danger">*</span></label>
                        <select class="select2 form-control aiz-selectpicker" name="category_id" data-toggle="select2" data-placeholder="{{ translate('Choose Sub-Category') }}" data-live-search="true" required>
                            <option value="">{{ translate('Select Sub-Category') }}</option>
                            @foreach ($sub_categories as $category)
                                <option value="{{ $category->id }}">
                                    @if($category->parentCategory)
                                        {{ $category->parentCategory->getTranslation('name') }} > 
                                    @endif
                                    {{ $category->getTranslation('name') }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">{{ translate('Product groups must be assigned to a sub-category') }}</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Description')}}</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="{{translate('Description')}}"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="col-form-label" for="signinSrEmail">{{translate('Icon')}}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="icon" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                        <small class="text-muted">{{ translate('Optional icon for the product group') }}</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Ordering Number')}}</label>
                        <input type="number" integer-only name="order_level" class="form-control" id="order_level" placeholder="{{translate('Order Level')}}" value="0">
                        <small>{{translate('Higher number has high priority')}}</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Slug')}}</label>
                        <input type="text" placeholder="{{translate('Slug')}}" name="slug" class="form-control">
                        <small class="text-muted">{{ translate('Leave blank to auto-generate from name') }}</small>
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="active" id="active" value="1" checked>
                            <label class="form-check-label" for="active">
                                {{ translate('Active') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        AIZ.uploader.previewGenerate();
    });
</script>
@endsection
