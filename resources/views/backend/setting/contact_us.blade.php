@extends('backend.layouts.app')

@section('content')

<style>
    .section-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #0d6efd;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #212529;
        margin: 0;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control, .form-control:focus {
        border-radius: 6px;
        border: 1px solid #dee2e6;
        padding: 10px 15px;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .save-button {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        border: none;
        padding: 12px 40px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        transition: all 0.3s ease;
    }

    .save-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-envelope-fill me-2"></i>Contact Us Settings
            </h4>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('settings.contact-us.update') }}" method="POST">
                @csrf

                {{-- ================= Header Section ================= --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title">Header Section</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Page Title</label>
                            <input type="text" class="form-control" name="header_title"
                                   value="{{ $header->value['title'] ?? 'Contact us' }}"
                                   placeholder="Enter page title">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Page Description</label>
                            <textarea class="form-control" name="header_description" rows="3"
                                      placeholder="Enter page description">{{ $header->value['description'] ?? 'We\'re here to help! Whether you have a question about your order, need assistance with a product, or just want to share feedback, our team is ready to assist you.' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ================= Contact Info Section ================= --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title">Contact Information</h5>
                    </div>

                    <div class="info-grid">
                        {{-- Address --}}
                        <div class="mb-3">
                            <label class="form-label">Address Label</label>
                            <input type="text" class="form-control" name="address_label"
                                   value="{{ $contactInfo->value['address_label'] ?? 'Address' }}"
                                   placeholder="e.g., Address">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address Value</label>
                            <input type="text" class="form-control" name="address_value"
                                   value="{{ $contactInfo->value['address_value'] ?? '13th Street, 47 W 13th St, New York, NY 10011, USA' }}"
                                   placeholder="Enter address">
                        </div>

                        {{-- Phone --}}
                        <div class="mb-3">
                            <label class="form-label">Phone Label</label>
                            <input type="text" class="form-control" name="phone_label"
                                   value="{{ $contactInfo->value['phone_label'] ?? 'Phone' }}"
                                   placeholder="e.g., Phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Value</label>
                            <input type="text" class="form-control" name="phone_value"
                                   value="{{ $contactInfo->value['phone_value'] ?? '124-251-524' }}"
                                   placeholder="Enter phone number">
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label">Email Label</label>
                            <input type="text" class="form-control" name="email_label"
                                   value="{{ $contactInfo->value['email_label'] ?? 'Email Address' }}"
                                   placeholder="e.g., Email Address">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Value</label>
                            <input type="email" class="form-control" name="email_value"
                                   value="{{ $contactInfo->value['email_value'] ?? 'sales@tradesaxis.me' }}"
                                   placeholder="Enter email address">
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary save-button">
                        <i class="bi bi-save me-2"></i>Save All Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
