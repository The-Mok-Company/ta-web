@extends('backend.layouts.app')

@section('content')

<style>
    .report-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .report-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }
    .report-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1rem 1.5rem;
    }
    .stats-card {
        border: none;
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .stats-card.pending {
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
    }
    .stats-card.processing {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .stats-card.completed {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .stats-card.cancelled {
        background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%);
    }
    .stats-card.total {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    }
    .stats-card.value {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
    }
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
    }
    .stats-label {
        opacity: 0.9;
        font-size: 0.9rem;
    }
    .filter-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }
    .chart-container {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    .table-modern thead th {
        border: none;
        background: #f8f9fa;
        padding: 12px 15px;
        font-weight: 600;
        color: #4a5568;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table-modern thead th:first-child {
        border-radius: 8px 0 0 8px;
    }
    .table-modern thead th:last-child {
        border-radius: 0 8px 8px 0;
    }
    .table-modern tbody tr {
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        transition: all 0.2s ease;
    }
    .table-modern tbody tr:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .table-modern tbody td {
        padding: 15px;
        vertical-align: middle;
        border: none;
    }
    .table-modern tbody td:first-child {
        border-radius: 8px 0 0 8px;
    }
    .table-modern tbody td:last-child {
        border-radius: 0 8px 8px 0;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Inquiry Reports') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-primary">
                <i class="las la-list"></i> {{ translate('All Inquiries') }}
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-card">
    <form action="{{ route('admin.inquiries.reports') }}" method="GET" class="row align-items-end">
        <div class="col-md-3">
            <label class="form-label">{{ translate('Start Date') }}</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ translate('End Date') }}</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ translate('Status') }}</label>
            <select name="status" class="form-control">
                <option value="">{{ translate('All Statuses') }}</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>{{ translate('Pending') }}</option>
                <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>{{ translate('Processing') }}</option>
                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>{{ translate('Completed') }}</option>
                <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>{{ translate('Cancelled') }}</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="las la-filter"></i> {{ translate('Apply Filters') }}
            </button>
        </div>
    </form>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="stats-card total">
            <div class="stats-number">{{ $totalInquiries }}</div>
            <div class="stats-label">{{ translate('Total Inquiries') }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card pending">
            <div class="stats-number">{{ $pendingInquiries }}</div>
            <div class="stats-label">{{ translate('Pending') }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card processing">
            <div class="stats-number">{{ $processingInquiries }}</div>
            <div class="stats-label">{{ translate('Processing') }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card completed">
            <div class="stats-number">{{ $completedInquiries }}</div>
            <div class="stats-label">{{ translate('Completed') }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card cancelled">
            <div class="stats-number">{{ $cancelledInquiries }}</div>
            <div class="stats-label">{{ translate('Cancelled') }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card value">
            <div class="stats-number">{{ single_price($totalValue) }}</div>
            <div class="stats-label">{{ translate('Total Value') }}</div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Daily Inquiries Chart -->
    <div class="col-md-8">
        <div class="chart-container">
            <h5 class="mb-4">{{ translate('Daily Inquiries') }}</h5>
            <canvas id="dailyInquiriesChart" height="120"></canvas>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="col-md-4">
        <div class="chart-container">
            <h5 class="mb-4">{{ translate('Status Distribution') }}</h5>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Users -->
    <div class="col-md-6 mb-4">
        <div class="report-card card">
            <div class="card-header">
                <h5 class="mb-0">{{ translate('Top Users by Inquiries') }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>{{ translate('User') }}</th>
                            <th class="text-center">{{ translate('Inquiries') }}</th>
                            <th class="text-right">{{ translate('Total Value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topUsers as $userStat)
                            <tr>
                                <td>
                                    @if($userStat->user)
                                        <strong>{{ $userStat->user->name }}</strong>
                                        <br><small class="text-muted">{{ $userStat->user->email }}</small>
                                    @else
                                        <span class="text-muted">{{ translate('Unknown User') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $userStat->inquiry_count }}</span>
                                </td>
                                <td class="text-right">
                                    {{ single_price($userStat->total_value) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    {{ translate('No data available') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Inquiries -->
    <div class="col-md-6 mb-4">
        <div class="report-card card">
            <div class="card-header">
                <h5 class="mb-0">{{ translate('Recent Inquiries') }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>{{ translate('Code') }}</th>
                            <th>{{ translate('User') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th>{{ translate('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInquiries as $inquiry)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.inquiries.show', $inquiry->id) }}">
                                        {{ $inquiry->code ?? 'INQ-' . $inquiry->id }}
                                    </a>
                                </td>
                                <td>{{ $inquiry->user->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($inquiry->status) {
                                            'pending' => 'badge-warning',
                                            'processing' => 'badge-info',
                                            'completed' => 'badge-success',
                                            'cancelled' => 'badge-danger',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($inquiry->status) }}</span>
                                </td>
                                <td>{{ $inquiry->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    {{ translate('No recent inquiries') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Inquiries Chart
    const dailyData = @json($dailyInquiries);
    const labels = dailyData.map(d => d.date);
    const counts = dailyData.map(d => d.count);
    const values = dailyData.map(d => parseFloat(d.total_value || 0));

    new Chart(document.getElementById('dailyInquiriesChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: '{{ translate("Inquiries") }}',
                data: counts,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y'
            }, {
                label: '{{ translate("Value") }}',
                data: values,
                borderColor: '#11998e',
                backgroundColor: 'rgba(17, 153, 142, 0.1)',
                tension: 0.4,
                fill: false,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '{{ translate("Count") }}'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                    title: {
                        display: true,
                        text: '{{ translate("Value") }}'
                    }
                }
            }
        }
    });

    // Status Distribution Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['{{ translate("Pending") }}', '{{ translate("Processing") }}', '{{ translate("Completed") }}', '{{ translate("Cancelled") }}'],
            datasets: [{
                data: [{{ $statusDistribution['pending'] }}, {{ $statusDistribution['processing'] }}, {{ $statusDistribution['completed'] }}, {{ $statusDistribution['cancelled'] }}],
                backgroundColor: ['#fda085', '#667eea', '#38ef7d', '#ef473a'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
