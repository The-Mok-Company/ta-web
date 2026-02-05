@extends('backend.layouts.app')

@section('content')

<style>
    .inquiry-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .inquiry-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }
    .inquiry-card .card-header {
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
    .stats-card.total {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    }
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
    }
    .stats-label {
        opacity: 0.9;
        font-size: 0.9rem;
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
        border: none;
        padding: 15px;
        vertical-align: middle;
        border-top: 1px solid #f1f1f1;
        border-bottom: 1px solid #f1f1f1;
    }
    .table-modern tbody td:first-child {
        border-left: 1px solid #f1f1f1;
        border-radius: 8px 0 0 8px;
    }
    .table-modern tbody td:last-child {
        border-right: 1px solid #f1f1f1;
        border-radius: 0 8px 8px 0;
    }
    .inquiry-code {
        font-weight: 700;
        color: #667eea;
        font-size: 14px;
    }
    .inquiry-code:hover {
        color: #764ba2;
    }
    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-new {
        background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%);
        color: #1e40af;
    }
    .status-pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    .status-responded {
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #3730a3;
    }
    .status-offer_sent {
        background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
        color: #6b21a8;
    }
    .status-accepted {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }
    .status-rejected {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }
    .status-deal_closed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .status-cancelled {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }
    .status-on_hold {
        background: linear-gradient(135deg, #fef3c7 0%, #fbbf24 100%);
        color: #78350f;
    }
    .status-expired {
        background: linear-gradient(135deg, #e5e7eb 0%, #9ca3af 100%);
        color: #374151;
    }
    .action-btn {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
        margin: 0 2px;
    }
    .action-btn:hover {
        transform: scale(1.1);
    }
    .action-btn.view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .action-btn.edit {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }
    .action-btn.delete {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: white;
    }
    .filter-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .filter-input {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        transition: all 0.2s;
    }
    .filter-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .filter-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        padding: 10px 25px;
        color: white;
        font-weight: 600;
        transition: all 0.3s;
    }
    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    .items-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }
    .total-amount {
        font-weight: 700;
        color: #1a1a2e;
        font-size: 15px;
    }
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    .empty-state i {
        font-size: 5rem;
        color: #cbd5e0;
        margin-bottom: 1.5rem;
    }
    .empty-state h4 {
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    .empty-state p {
        color: #a0aec0;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="las la-file-invoice text-primary"></i>
                {{ translate('Inquiries Management') }}
            </h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.inquiries.reports') }}" class="btn btn-info mr-2">
                <i class="las la-chart-bar mr-1"></i>{{ translate('Reports') }}
            </a>
            <a href="{{ route('admin.inquiries.create') }}" class="btn btn-success">
                <i class="las la-plus mr-1"></i>{{ translate('Create New Inquiry') }}
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
@php
    $newCount = \App\Models\Inquiry::where('status', 'new')->count();
    $pendingCount = \App\Models\Inquiry::where('status', 'pending')->count();
    $offerSentCount = \App\Models\Inquiry::where('status', 'offer_sent')->count();
    $acceptedCount = \App\Models\Inquiry::where('status', 'accepted')->count();
    $dealClosedCount = \App\Models\Inquiry::where('status', 'deal_closed')->count();
    $totalCount = \App\Models\Inquiry::count();
@endphp
<div class="row mb-4">
    <div class="col-md-2 mb-3">
        <a href="{{ route('admin.inquiries.index', ['status' => 'new']) }}" class="text-decoration-none">
            <div class="stats-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $newCount }}</div>
                        <div class="stats-label">{{ translate('New') }}</div>
                    </div>
                    <i class="las la-envelope-open" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-2 mb-3">
        <a href="{{ route('admin.inquiries.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="stats-card pending">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $pendingCount }}</div>
                        <div class="stats-label">{{ translate('Pending') }}</div>
                    </div>
                    <i class="las la-clock" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-2 mb-3">
        <a href="{{ route('admin.inquiries.index', ['status' => 'offer_sent']) }}" class="text-decoration-none">
            <div class="stats-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $offerSentCount }}</div>
                        <div class="stats-label">{{ translate('Offer Sent') }}</div>
                    </div>
                    <i class="las la-paper-plane" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-2 mb-3">
        <a href="{{ route('admin.inquiries.index', ['status' => 'accepted']) }}" class="text-decoration-none">
            <div class="stats-card completed">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $acceptedCount }}</div>
                        <div class="stats-label">{{ translate('Accepted') }}</div>
                    </div>
                    <i class="las la-thumbs-up" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-2 mb-3">
        <a href="{{ route('admin.inquiries.index', ['status' => 'deal_closed']) }}" class="text-decoration-none">
            <div class="stats-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $dealClosedCount }}</div>
                        <div class="stats-label">{{ translate('Deal Closed') }}</div>
                    </div>
                    <i class="las la-handshake" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-2 mb-3">
        <a href="{{ route('admin.inquiries.index') }}" class="text-decoration-none">
            <div class="stats-card total">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $totalCount }}</div>
                        <div class="stats-label">{{ translate('Total') }}</div>
                    </div>
                    <i class="las la-file-alt" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Main Card -->
<div class="card inquiry-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="las la-list mr-2"></i>{{ translate('All Inquiries') }}
        </h5>
        <span style="color: white; font-size: 14px;">{{ $inquiries->total() }} {{ translate('records') }}</span>
    </div>

    <div class="card-body">
        <!-- Filters -->
        <form id="sort_inquiries" action="" method="GET">
            <div class="filter-card">
                <div class="row align-items-center">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select class="form-control filter-input" name="status" onchange="sort_inquiries()">
                            <option value="">{{ translate('All Status') }}</option>
                            @foreach(\App\Models\Inquiry::getStatuses() as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                    {{ translate($label) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <input type="text" class="form-control filter-input" name="code" value="{{ request('code') }}" placeholder="{{ translate('Search by inquiry code...') }}">
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <input type="text" class="form-control filter-input" name="user_id" value="{{ request('user_id') }}" placeholder="{{ translate('Customer ID...') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn filter-btn btn-block">
                            <i class="las la-search mr-1"></i> {{ translate('Search') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if($inquiries->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Code') }}</th>
                            <th>{{ translate('Customer') }}</th>
                            <th>{{ translate('Items') }}</th>
                            <th>{{ translate('Total') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th>{{ translate('Date') }}</th>
                            <th class="text-center">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inquiries as $key => $inquiry)
                            <tr>
                                <td>
                                    <span class="text-muted">{{ $inquiries->firstItem() + $key }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="inquiry-code">
                                        {{ $inquiry->code }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="customer-avatar bg-soft-primary text-primary mr-2">
                                            {{ $inquiry->user ? strtoupper(substr($inquiry->user->name, 0, 1)) : 'G' }}
                                        </div>
                                        <div>
                                            <div class="font-weight-600">{{ $inquiry->user ? $inquiry->user->name : translate('Guest') }}</div>
                                            @if($inquiry->user)
                                                <small class="text-muted">{{ $inquiry->user->email }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="items-badge">
                                        {{ $inquiry->items_count }} {{ translate('items') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="total-amount">{{ number_format($inquiry->total, 2) }}</span>
                                    <small class="text-muted d-block">{{ translate('EGP') }}</small>
                                </td>
                                <td>
                                    @php
                                        $statuses = \App\Models\Inquiry::getStatuses();
                                        $statusLabel = $statuses[$inquiry->status] ?? ucfirst(str_replace('_', ' ', $inquiry->status));
                                    @endphp
                                    <span class="status-badge status-{{ $inquiry->status }}">
                                        {{ translate($statusLabel) }}
                                    </span>
                                    @if($inquiry->isExpired())
                                        <small class="d-block text-danger mt-1">
                                            <i class="las la-exclamation-triangle"></i> {{ translate('Expired') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-600">{{ $inquiry->created_at->format('d M, Y') }}</div>
                                    <small class="text-muted">{{ $inquiry->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="action-btn view" title="{{ translate('View') }}">
                                        <i class="las la-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.inquiries.edit', $inquiry->id) }}" class="action-btn edit" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                    <a href="#" class="action-btn delete confirm-delete" data-href="{{ route('admin.inquiries.destroy', $inquiry->id) }}" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="aiz-pagination mt-4">
                {{ $inquiries->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="las la-inbox"></i>
                <h4>{{ translate('No Inquiries Found') }}</h4>
                <p>{{ translate('There are no inquiries matching your criteria.') }}</p>
            </div>
        @endif
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function sort_inquiries() {
        $('#sort_inquiries').submit();
    }
</script>
@endsection
