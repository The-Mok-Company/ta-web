@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
        <h1 class="h3">{{translate('Search Reports')}}</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Search Across Reports') }}</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('search_reports.index') }}">
                    <div class="row">
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="q" value="{{ $search_term }}" placeholder="{{ translate('Search inquiries, products, users...') }}" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">{{ translate('Search') }}</button>
                        </div>
                    </div>
                </form>

                @if($search_term)
                    <div class="mt-4">
                        @if($results['inquiries']->count() > 0)
                            <h5 class="mb-3">{{ translate('Inquiries') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered aiz-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Customer') }}</th>
                                            <th>{{ translate('Product') }}</th>
                                            <th>{{ translate('Question') }}</th>
                                            <th>{{ translate('Status') }}</th>
                                            <th>{{ translate('Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results['inquiries'] as $inquiry)
                                            <tr>
                                                <td>{{ $inquiry->user->name ?? translate('N/A') }}</td>
                                                <td>{{ $inquiry->product->name ?? translate('N/A') }}</td>
                                                <td>{{ Str::limit($inquiry->question, 50) }}</td>
                                                <td>
                                                    <span class="badge {{ $inquiry->status->badgeClass() }}">
                                                        {{ $inquiry->status->label() }}
                                                    </span>
                                                </td>
                                                <td>{{ $inquiry->created_at->format('Y-m-d') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($results['products']->count() > 0)
                            <h5 class="mb-3 mt-4">{{ translate('Products') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered aiz-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Product Name') }}</th>
                                            <th>{{ translate('Category') }}</th>
                                            <th>{{ translate('Price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results['products'] as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name ?? translate('N/A') }}</td>
                                                <td>{{ single_price($product->unit_price) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($results['users']->count() > 0)
                            <h5 class="mb-3 mt-4">{{ translate('Users') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered aiz-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Name') }}</th>
                                            <th>{{ translate('Email') }}</th>
                                            <th>{{ translate('Type') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results['users'] as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ ucfirst($user->user_type) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($results['inquiries']->count() == 0 && $results['products']->count() == 0 && $results['users']->count() == 0)
                            <div class="alert alert-info mt-4">
                                {{ translate('No results found for') }} "{{ $search_term }}"
                            </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-info mt-4">
                        {{ translate('Enter a search term to find relevant information across reports') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
