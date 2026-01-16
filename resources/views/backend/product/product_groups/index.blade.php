@extends('backend.layouts.app')

@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp

    <div class="col-12 col-sm-12 col-lg-10 mx-auto">
        <div class="aiz-titlebar text-left pb-5px">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h1 class="h3 fw-bold">{{ translate('All Product Groups') }}</h1>
                </div>
            </div>
        </div>
        <div class="card">
            <!--Nav Tab -->
            <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom border-light px-25px table-nav-tabs pb-3 pb-xl-0">
                <!--Right Side- Add New Button -->
                <div class="mb-3 mb-md-0">
                    @can('add_product_category')
                    <a href="{{ route('product-groups.create') }}" class="position-relative overflow-hidden add-new-btn">
                        <span class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Add New Product Group') }}</span>
                        <span class="position-absolute top-0 right-0 h-100 w-40px bg-blue d-flex align-items-center justify-content-end z-1 plus-icon-container m-0 p-0 rounded-pill">
                            <svg id="plus-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                <path id="Path_45216" data-name="Path 45216"
                                    d="M141.874-812.13a.706.706,0,0,1-.515-.21.7.7,0,0,1-.212-.514V-817.4h-4.553a.7.7,0,0,1-.514-.209.694.694,0,0,1-.21-.511.706.706,0,0,1,.21-.515.7.7,0,0,1,.514-.212h4.549v-4.557a.7.7,0,0,1,.209-.514.694.694,0,0,1,.511-.21.706.706,0,0,1,.515.21.7.7,0,0,1,.212.514v4.553h4.557a.7.7,0,0,1,.514.208.694.694,0,0,1,.21.511.706.706,0,0,1-.21.515.7.7,0,0,1-.514.212h-4.553v4.553a.7.7,0,0,1-.209.514A.694.694,0,0,1,141.874-812.13Z"
                                    transform="translate(-135.87 824.13)" fill="#fff" />
                            </svg>
                        </span>
                    </a>
                    @endif
                </div>
            </div>

            <!--Card Header (Search) Start-->
            <div class="tab-filter-bar">
                <form class="" id="sort_product_groups" action="" method="GET">
                    <div class="card-header row border-0 pb-0 mt-2">
                        <div class="col pl-0 pl-md-3">
                            <div class="input-group mb-0 border border-light px-3 bg-light rounded-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-0 bg-transparent px-0" id="search">
                                        <svg id="Group_38844" data-name="Group 38844" xmlns="http://www.w3.org/2000/svg"
                                            width="16.001" height="16" viewBox="0 0 16.001 16">
                                            <path id="Path_3090" data-name="Path 3090"
                                                d="M8.248,14.642a6.394,6.394,0,1,1,6.394-6.394A6.4,6.4,0,0,1,8.248,14.642Zm0-11.509a5.115,5.115,0,1,0,5.115,5.115A5.121,5.121,0,0,0,8.248,3.133Z"
                                                transform="translate(-1.854 -1.854)" fill="#a5a5b8" />
                                            <path id="Path_3091" data-name="Path 3091"
                                                d="M23.011,23.651a.637.637,0,0,1-.452-.187l-4.92-4.92a.639.639,0,0,1,.9-.9l4.92,4.92a.639.639,0,0,1-.452,1.091Z"
                                                transform="translate(-7.651 -7.651)" fill="#a5a5b8" />
                                        </svg>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-sm border-0 px-2 bg-transparent"
                                    id="search_input" name="search" placeholder="{{translate('Search Product Groups...')}}" value="{{ $sort_search }}">
                            </div>
                        </div>

                        <div class="col-md-3 mt-2 mt-md-0">
                            <select class="form-control form-control-sm aiz-selectpicker" name="category_id" id="category_filter" data-live-search="true">
                                <option value="">{{ translate('All Categories') }}</option>
                                @foreach($sub_categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->parentCategory->name ?? '' }} > {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <div class="aiz-table-filtered">
                        <table class="table aiz-table mb-0 footable footable-1 breakpoint breakpoint-lg" data-filtering="false">
                            <thead>
                                <tr class="footable-header">
                                    <th>
                                        <div class="form-group">
                                            <div class="aiz-checkbox-inline">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-all">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </th>
                                    <th>{{ translate('Name') }}</th>
                                    <th>{{ translate('Sub-Category') }}</th>
                                    <th>{{ translate('Main Category') }}</th>
                                    <th>{{ translate('Products') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                    <th class="text-right">{{ translate('Options') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product_groups as $group)
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="aiz-checkbox-inline">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]" value="{{ $group->id }}">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $group->name }}</td>
                                    <td>
                                        @if($group->category)
                                            {{ $group->category->getTranslation('name') }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($group->category && $group->category->parentCategory)
                                            {{ $group->category->parentCategory->getTranslation('name') }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $group->products->count() }}</td>
                                    <td>
                                        @if($group->active)
                                            <span class="badge badge-inline badge-success">{{ translate('Active') }}</span>
                                        @else
                                            <span class="badge badge-inline badge-secondary">{{ translate('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @can('edit_product_category')
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('product-groups.edit', $group->id) }}" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete_product_category')
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('product-groups.destroy', $group->id) }}" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="aiz-pagination">
                        {{ $product_groups->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#search_input').on('keyup', function() {
            if($(this).val() != '' || $('#category_filter').val() != '') {
                $('#sort_product_groups').submit();
            }
        });

        $('#category_filter').on('change', function() {
            $('#sort_product_groups').submit();
        });
    });
</script>
@endsection
