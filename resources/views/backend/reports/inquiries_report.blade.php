@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
        <h1 class="h3">{{translate('Inquiries Report')}}</h1>
    </div>
</div>

<div class="row gutters-16">
    <!-- Summary Cards -->
    <div class="col-lg-12 mb-3">
        <div class="row gutters-16">
            <div class="col-sm-3">
                <div class="dashboard-box bg-white h-220px mb-2rem overflow-hidden">
                    <div class="d-flex flex-column justify-content-between h-100 p-3">
                        <div>
                            <h1 class="fs-30 fw-600 text-dark mb-1">{{ number_format($total_inquiries) }}</h1>
                            <h3 class="fs-13 fw-600 text-secondary mb-0">{{ translate('Total Inquiries') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="dashboard-box bg-white h-220px mb-2rem overflow-hidden">
                    <div class="d-flex flex-column justify-content-between h-100 p-3">
                        <div>
                            <h1 class="fs-30 fw-600 text-dark mb-1">{{ number_format($response_rate, 1) }}%</h1>
                            <h3 class="fs-13 fw-600 text-secondary mb-0">{{ translate('Response Rate') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="dashboard-box bg-white h-220px mb-2rem overflow-hidden">
                    <div class="d-flex flex-column justify-content-between h-100 p-3">
                        <div>
                            <h1 class="fs-30 fw-600 text-dark mb-1">{{ number_format($conversion_rate, 1) }}%</h1>
                            <h3 class="fs-13 fw-600 text-secondary mb-0">{{ translate('Conversion Rate') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="dashboard-box bg-white h-220px mb-2rem overflow-hidden">
                    <div class="d-flex flex-column justify-content-between h-100 p-3">
                        <div>
                            <h1 class="fs-30 fw-600 text-dark mb-1">{{ $top_categories->count() }}</h1>
                            <h3 class="fs-13 fw-600 text-secondary mb-0">{{ translate('Active Categories') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Filters') }}</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inquiries_report.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{ translate('Status') }}</label>
                            <select class="form-control aiz-selectpicker" name="status" data-live-search="true">
                                <option value="">{{ translate('All Statuses') }}</option>
                                @foreach(\App\Enums\InquiryStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>{{ translate('Category') }}</label>
                            <select class="form-control aiz-selectpicker" name="category_id" data-live-search="true">
                                <option value="">{{ translate('All Categories') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->getTranslation('name') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>{{ translate('Date Range') }}</label>
                            <input type="text" class="form-control aiz-date-range" name="date_range" value="{{ request('date_range') }}" placeholder="{{ translate('Select Date') }}" data-time-picker="false" data-format="DD-MM-Y" data-separator=" / " autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label>{{ translate('Search') }}</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ translate('Search inquiries...') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                            <a href="{{ route('inquiries_report.index') }}" class="btn btn-secondary">{{ translate('Clear') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Status Breakdown Chart -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Inquiries by Status') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Monthly Trend Chart -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Monthly Trend (Last 12 Months)') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Top Categories by Inquiries') }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Category') }}</th>
                            <th>{{ translate('Inquiries') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($top_categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">{{ translate('No data available') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Top Products by Inquiries') }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Product') }}</th>
                            <th>{{ translate('Inquiries') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($top_products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">{{ translate('No data available') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Inquiries List -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('All Inquiries') }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Customer') }}</th>
                            <th>{{ translate('Product') }}</th>
                            <th>{{ translate('Category') }}</th>
                            <th>{{ translate('Question') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th>{{ translate('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquiries as $key => $inquiry)
                            <tr>
                                <td>{{ ($key+1) + ($inquiries->currentPage() - 1)*$inquiries->perPage() }}</td>
                                <td>
                                    @if($inquiry->user)
                                        {{ $inquiry->user->name }}<br>
                                        <small class="text-muted">{{ $inquiry->user->email }}</small>
                                    @else
                                        {{ translate('N/A') }}
                                    @endif
                                </td>
                                <td>
                                    @if($inquiry->product)
                                        {{ $inquiry->product->name }}
                                    @else
                                        {{ translate('N/A') }}
                                    @endif
                                </td>
                                <td>
                                    @if($inquiry->category)
                                        {{ $inquiry->category->getTranslation('name') }}
                                    @else
                                        {{ translate('N/A') }}
                                    @endif
                                </td>
                                <td>
                                    <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                        {{ Str::limit($inquiry->question, 100) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $inquiry->status->badgeClass() }}">
                                        {{ $inquiry->status->label() }}
                                    </span>
                                </td>
                                <td>{{ $inquiry->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ translate('No inquiries found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    {{ $inquiries->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Breakdown Chart
    const statusData = @json($inquiries_by_status);
    const statusLabels = [];
    const statusCounts = [];
    const statusColors = {
        'new': '#17a2b8',
        'pending': '#ffc107',
        'responded': '#007bff',
        'offer_sent': '#17a2b8',
        'accepted': '#28a745',
        'rejected': '#dc3545',
        'deal_closed': '#28a745',
        'cancelled': '#dc3545',
        'on_hold': '#ffc107',
        'expired': '#6c757d'
    };

    @foreach(\App\Enums\InquiryStatus::cases() as $status)
        @if(isset($inquiries_by_status[$status->value]))
            statusLabels.push('{{ $status->label() }}');
            statusCounts.push({{ $inquiries_by_status[$status->value] ?? 0 }});
        @endif
    @endforeach

    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: Object.values(statusColors)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Monthly Trend Chart
    const trendData = @json($monthly_trend);
    const trendLabels = trendData.map(item => item.month);
    const trendCounts = trendData.map(item => item.count);

    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: '{{ translate("Inquiries") }}',
                data: trendCounts,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
