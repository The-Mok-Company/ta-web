@extends('seller.layouts.app')

@section('panel_content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    {{ $query->product->getTranslation('name') }}
                </h5>
            </div>

            <div class="card-body">
                <!-- Status Section -->
                <div class="mb-3">
                    <label class="form-label">{{ translate('Current Status') }}</label>
                    <div>
                        <span class="badge badge-inline {{ $query->status->badgeClass() }}" style="font-size: 14px; padding: 8px 12px;">
                            {{ $query->status->label() }}
                        </span>
                    </div>
                </div>

                <!-- Update Status Form -->
                @if (Auth::user()->id == $query->seller_id)
                    <form action="{{ route('seller.product_query.update_status', $query->id) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">{{ translate('Change Status') }}</label>
                                <select class="form-control aiz-selectpicker" name="status" required>
                                    @foreach(\App\Enums\InquiryStatus::cases() as $status)
                                        <option value="{{ $status->value }}" {{ $query->status == $status ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-block">{{ translate('Update Status') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif

                <!-- Inquiry Details -->
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                        <div class="media mb-2">
                            <img class="avatar avatar-xs mr-3"
                                @if ($query->user != null) src="{{ uploaded_asset($query->user->avatar_original) }}" @endif
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                            <div class="media-body">
                                <h6 class="mb-0 fw-600">
                                    @if ($query->user != null)
                                        {{ $query->user->name }}
                                    @endif
                                </h6>
                                <p class="opacity-50">{{ $query->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p>
                            {{ strip_tags($query->question) }}
                        </p>
                    </li>
                </ul>

                <!-- Reply Form -->
                @if (Auth::user()->id == $query->seller_id)
                    <form action="{{ route('seller.product_query.reply',$query->id) }}" method="POST">
                        @method('put')
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $query->id }}">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">{{ translate('Reply') }}</label>
                                <textarea class="form-control" rows="4" name="reply" placeholder="{{ translate('Type your reply') }}"
                                    required>{{ $query->reply }}</textarea>
                            </div>
                        </div>
                        <br>
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">{{  $query->reply == null ? translate('Send') : translate('Update') }}</button>
                        </div>
                    </form>
                @endif

                <!-- Additional Info -->
                <div class="mt-3">
                    <small class="text-muted">
                        @if($query->expires_at)
                            {{ translate('Expires') }}: {{ $query->expires_at->format('Y-m-d H:i') }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection
