@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
        <h1 class="h3">{{translate('Partner Applications')}}</h1>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-0 h6">{{translate('All Partner Applications')}}</h5>
        </div>
    </div>

    <div class="card-body">
        @if($partners->count() > 0)
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>{{translate('Company / Individual Name')}}</th>
                        <th data-breakpoints="lg">{{translate('Contact Person')}}</th>
                        <th data-breakpoints="lg">{{translate('Email & Phone')}}</th>
                        <th data-breakpoints="lg">{{translate('Application Date')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($partners as $partner)
                        <tr>
                            <td>
                                <strong>{{ $partner->name }}</strong>
                            </td>
                            <td>
                                @if($partner->user)
                                    {{ $partner->user->name }}
                                @else
                                    <span class="text-muted">{{ translate('N/A') }}</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <i class="las la-envelope mr-1"></i>
                                    <a href="mailto:{{ $partner->email }}">{{ $partner->email }}</a>
                                </div>
                                @if($partner->phone)
                                    <div class="mt-1">
                                        <i class="las la-phone mr-1"></i>
                                        <a href="tel:{{ $partner->phone }}">{{ $partner->phone }}</a>
                                    </div>
                                @else
                                    <div class="mt-1 text-muted">
                                        <i class="las la-phone mr-1"></i>
                                        {{ translate('N/A') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{ $partner->created_at->format('Y-m-d H:i') }}
                                <br>
                                <small class="text-muted">{{ $partner->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $partners->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="las la-inbox la-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ translate('No Partner Applications Yet') }}</h5>
                <p class="text-muted">{{ translate('Partner applications will appear here when submitted') }}</p>
            </div>
        @endif
    </div>
</div>

@endsection
