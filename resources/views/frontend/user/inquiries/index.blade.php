@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 fs-20 fw-700 mb-0">{{ translate('My Inquiries') }}</h1>
            </div>
        </div>
    </div>

    <div class="card rounded-0 shadow-sm">
        <div class="card-body">
            @if ($inquiries->count() === 0)
                <div class="text-center p-4">
                    <i class="las la-inbox fs-48 opacity-60 mb-3"></i>
                    <h4 class="fs-16 fw-700 mb-2">{{ translate('No inquiries yet') }}</h4>
                    <p class="mb-0 opacity-70">
                        {{ translate('When you submit an inquiry, it will appear here.') }}
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ translate('Code') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-center">{{ translate('Products') }}</th>
                                <th class="text-center">{{ translate('Categories') }}</th>
                                <th>{{ translate('Created') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inquiries as $inq)
                                <tr>
                                    <td class="fw-700">{{ $inq->code }}</td>
                                    <td>
                                        <span class="badge badge-inline badge-soft-primary">
                                            {{ ucfirst((string) $inq->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ (int) $inq->products_total }}</td>
                                    <td class="text-center">{{ (int) $inq->categories_total }}</td>
                                    <td>{{ optional($inq->created_at)->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $inquiries->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

