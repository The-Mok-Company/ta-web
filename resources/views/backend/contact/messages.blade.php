@extends('backend.layouts.app')

@section('meta_title', 'Admin Dashboard')

@section('content')
    <style>
        .messages-container {
            padding: 30px;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .messages-count {
            display: inline-block;
            background: #0088cc;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 15px;
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .messages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
        }

        .message-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            position: relative;
            overflow: hidden;
        }

        .message-card.unread {
            border-left: 4px solid #ffc107;
            background: #fffef8;
        }

        .message-card.read {
            border-left: 4px solid #28a745;
        }

        .message-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .message-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .message-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0088cc, #00b4d8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .message-info {
            flex: 1;
        }

        .message-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 5px 0;
        }

        .message-date {
            font-size: 12px;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.unread {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.read {
            background: #d4edda;
            color: #155724;
        }

        .message-details {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .detail-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0088cc;
            font-size: 16px;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 11px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 14px;
            color: #333333;
            word-break: break-word;
        }

        .detail-value.message-text {
            line-height: 1.6;
            color: #495057;
        }

        .message-actions {
            display: flex;
            gap: 8px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .btn {
            flex: 1;
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-toggle {
            background: #ffc107;
            color: #000;
        }

        .btn-toggle:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            flex: 0.5;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #dee2e6;
        }

        .empty-title {
            font-size: 24px;
            font-weight: 700;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .empty-text {
            font-size: 16px;
            color: #adb5bd;
        }

        .pagination-wrapper {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination a,
        .pagination span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            font-size: 14px;
            font-weight: 600;
            color: #6c757d;
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination a:hover {
            background: #0088cc;
            color: #ffffff;
            border-color: #0088cc;
            transform: translateY(-2px);
        }

        .pagination .active span {
            background: #0088cc;
            color: #ffffff;
            border-color: #0088cc;
        }

        .pagination .disabled span {
            color: #dee2e6;
            cursor: not-allowed;
            background: #f8f9fa;
        }

        @media (max-width: 768px) {
            .messages-container {
                padding: 15px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .messages-grid {
                grid-template-columns: 1fr;
            }

            .message-card {
                padding: 20px;
            }

            .message-actions {
                flex-direction: column;
            }

            .btn-delete {
                flex: 1;
            }
        }
    </style>

    <div class="messages-container">
        <div class="page-header">
            <div>
                <h1 class="page-title">
                    Contact Messages
                    <span class="messages-count">{{ $messages->total() }}</span>
                </h1>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($messages->count() > 0)
            <div class="messages-grid">
                @foreach ($messages as $message)
                    <div class="message-card {{ $message->status ? 'read' : 'unread' }}">
                        <span class="status-badge {{ $message->status ? 'read' : 'unread' }}">
                            {{ $message->status ? 'Read' : 'Unread' }}
                        </span>

                        <div class="message-header">
                            <div class="message-avatar">
                                {{ strtoupper(substr($message->name, 0, 1)) }}
                            </div>
                            <div class="message-info">
                                <h3 class="message-name">{{ $message->name }}</h3>
                                <div class="message-date">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <div class="message-details">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Email</div>
                                    <div class="detail-value">{{ $message->email }}</div>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Subject</div>
                                    <div class="detail-value">{{ $message->subject }}</div>
                                </div>
                            </div>

                            @if ($message->category)
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                        </svg>
                                    </div>
                                    <div class="detail-content">
                                        <div class="detail-label">Category</div>
                                        <div class="detail-value">{{ $message->category->name }}</div>
                                    </div>
                                </div>
                            @endif

                            <div class="detail-item">
                                <div class="detail-icon">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Message</div>
                                    <div class="detail-value message-text">{{ $message->message }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="message-actions">
                            <form action="{{ route('admin.contact.messages.toggle-status', $message->id) }}" method="POST"
                                style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn btn-toggle">
                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                        @if ($message->status)
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd"
                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                clip-rule="evenodd" />
                                        @else
                                            <path fill-rule="evenodd"
                                                d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                                clip-rule="evenodd" />
                                            <path
                                                d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                                        @endif
                                    </svg>
                                    {{ $message->status ? 'Mark Unread' : 'Mark Read' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $messages->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="64" height="64" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </div>
                <h2 class="empty-title">No Messages Yet</h2>
                <p class="empty-text">Contact messages will appear here when submitted</p>
            </div>
        @endif
    </div>
@endsection
