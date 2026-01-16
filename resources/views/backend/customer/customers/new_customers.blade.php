@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
        <h1 class="h3">{{translate('New Customers')}}</h1>
    </div>
</div>

<p>
    <span class="bg-danger d-inline-block h-10px rounded-2 w-10px" ></span> {{ translate('This color indicates that the customer is marked as blocked.') }}
    <br>
    <span class="bg-info d-inline-block h-10px rounded-2 w-10px"></span> {{ translate('This color indicates that the customer is marked as suspicious.') }}
</p>

<div class="card">
    <form class="" id="sort_customers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('New Customers')}} ({{translate('Last')}} {{ $days }} {{translate('days')}})</h5>
            </div>

            <div class="col-lg-2 ml-auto">
                <select class="form-control aiz-selectpicker" name="days" onchange="sort_customers()" data-selected="{{ $days }}">
                    <option value="7">{{ translate('Last 7 days') }}</option>
                    <option value="30">{{ translate('Last 30 days') }}</option>
                    <option value="60">{{ translate('Last 60 days') }}</option>
                    <option value="90">{{ translate('Last 90 days') }}</option>
                </select>
            </div>
            <div class="col-lg-2 ml-auto">
                <select class="form-control aiz-selectpicker" name="verification_status" onchange="sort_customers()" data-selected="{{ $verification_status }}">
                    <option value="">{{ translate('Filter by Verification Status') }}</option>
                    <option value="verified">{{ translate('Verified') }}</option>
                    <option value="un_verified">{{ translate('Unverified') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type email or name & Enter') }}">
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>{{translate('Name')}}</th>
                        <th data-breakpoints="lg">{{translate('Email Address')}}</th>
                        <th data-breakpoints="lg">{{translate('Phone')}}</th>
                        <th data-breakpoints="lg">{{translate('Registration Date')}}</th>
                        <th data-breakpoints="lg">{{translate('Package')}}</th>
                        <th data-breakpoints="lg">{{translate('Wallet Balance')}}</th>
                        <th data-breakpoints="lg">{{translate('Verification Status')}}</th>
                        <th class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        @if ($user != null)
                            <tr>
                                <td>
                                    <p class="@if($user->banned == 1) text-danger @elseif($user->is_suspicious == 1) text-info @endif">
                                        @if($user->banned == 1) 
                                            <i class="las la-ban las" aria-hidden="true"></i>
                                        @elseif($user->is_suspicious == 1) 
                                            <i class="las la-exclamation-circle" aria-hidden="true"></i> 
                                        @endif 
                                        {{$user->name}}
                                    </p>
                                </td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone}}</td>
                                <td>{{$user->created_at->format('Y-m-d H:i')}}</td>
                                <td>
                                    @if ($user->customer_package != null)
                                        {{$user->customer_package->getTranslation('name')}}
                                    @endif
                                </td>
                                <td>{{single_price($user->balance)}}</td>
                                <td>
                                    @if($user->email_verified_at != null)
                                        <span class="badge badge-inline badge-success">{{translate('Verified')}}</span>
                                    @else
                                        <span class="badge badge-inline badge-warning">{{translate('Unverified')}}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($user->email_verified_at != null && auth()->user()->can('login_as_customer'))
                                        <a href="{{route('customers.login', encrypt($user->id))}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Log in as this Customer') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                    @endif
                                    @can('ban_customer')
                                        @if($user->banned != 1)
                                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm" onclick="confirm_ban('{{route('customers.ban', encrypt($user->id))}}');" title="{{ translate('Ban this Customer') }}">
                                                <i class="las la-user-slash"></i>
                                            </a>
                                            @else
                                            <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm" onclick="confirm_unban('{{route('customers.ban', encrypt($user->id))}}');" title="{{ translate('Unban this Customer') }}">
                                                <i class="las la-user-check"></i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('delete_customer')
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('customers.destroy', $user->id)}}" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $users->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>


<div class="modal fade" id="confirm-ban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Do you really want to ban this Customer?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                <a type="button" id="confirmation" class="btn btn-primary">{{translate('Proceed!')}}</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-unban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Do you really want to unban this Customer?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                <a type="button" id="confirmationunban" class="btn btn-primary">{{translate('Proceed!')}}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">

        function sort_customers(el){
            $('#sort_customers').submit();
        }
        function confirm_ban(url)
        {
            if('{{env('DEMO_MODE')}}' == 'On'){
                    AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                    return;
                }

            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href' , url);
        }

        function confirm_unban(url)
        {
            if('{{env('DEMO_MODE')}}' == 'On'){
                    AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                    return;
                }

            $('#confirm-unban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationunban').setAttribute('href' , url);
        }
    </script>
@endsection
