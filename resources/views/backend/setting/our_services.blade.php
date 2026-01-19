@extends('backend.layouts.app')

@section('content')

<style>
    .upload-preview-box {
        position: relative;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .upload-preview-box:hover {
        border-color: #0d6efd;
        background: #e7f1ff;
    }

    .upload-preview-box.has-image {
        border-color: #198754;
        background: #fff;
    }

    .preview-image {
        max-width: 100%;
        max-height: 150px;
        border-radius: 6px;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        object-fit: contain;
    }

    .upload-text {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .file-input-wrapper {
        position: relative;
        width: 100%;
    }

    .file-input-wrapper input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        cursor: pointer;
        z-index: 5;
    }

    .btn-choose-file {
        background: #0d6efd;
        color: white;
        padding: 8px 20px;
        border-radius: 6px;
        font-size: 14px;
        display: inline-block;
        cursor: pointer;
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .btn-choose-file:hover {
        background: #0b5ed7;
    }

    .btn-remove-image {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .btn-remove-image:hover {
        background: #bb2d3b;
        transform: scale(1.1);
    }

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

    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    .item-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
    }

    .item-number {
        background: #0d6efd;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .branding-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    .branding-card-item {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .branding-card-item:hover {
        border-color: #0d6efd;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.15);
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
</style>

<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-briefcase-fill me-2"></i>Our Services Settings
            </h4>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('settings.our-services.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ================= 1. Value Services Section ================= --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title">📊 Value Added Services Section</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="value_services_title"
                                   value="{{ $valueServices->value['title'] ?? ' ' }}"
                                   placeholder="Enter section title">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="value_services_description" rows="4"
                                      placeholder="Enter description">{{ $valueServices->value['description'] ?? '' }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Balance Illustration Image</label>
                            <div class="upload-preview-box {{ isset($valueServices->value['image']) && $valueServices->value['image'] ? 'has-image' : '' }}" id="value-services-preview-box">
                                @if(isset($valueServices->value['image']) && $valueServices->value['image'])
                                    <button type="button" class="btn-remove-image" onclick="removeImage('value-services')">✕</button>
                                    <img src="{{ asset($valueServices->value['image']) }}" class="preview-image" id="value-services-preview">
                                @endif
                                <div class="upload-text">{{ isset($valueServices->value['image']) && $valueServices->value['image'] ? 'Click to change image' : 'Click to upload image' }}</div>
                                <div class="file-input-wrapper">
                                    <span class="btn-choose-file">Choose Image</span>
                                    <input type="file" name="value_services_image" accept="image/*" onchange="previewImage(event, 'value-services')">
                                </div>
                            </div>
                            <input type="hidden" name="value_services_image_old" value="{{ $valueServices->value['image'] ?? '' }}">
                        </div>
                    </div>
                </div>

                {{-- ================= 2. Sourcing & Compliance Section ================= --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title">🔍 Sourcing & Compliance Section</h5>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Section Title</label>
                            <input type="text" class="form-control" name="sourcing_title"
                                   value="{{ $sourcing->value['title'] ?? ' ' }}"
                                   placeholder="Enter section title">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Section Image</label>
                            <div class="upload-preview-box {{ isset($sourcing->value['image']) && $sourcing->value['image'] ? 'has-image' : '' }}" id="sourcing-preview-box">
                                @if(isset($sourcing->value['image']) && $sourcing->value['image'])
                                    <button type="button" class="btn-remove-image" onclick="removeImage('sourcing')">✕</button>
                                    <img src="{{ asset($sourcing->value['image']) }}" class="preview-image" id="sourcing-preview">
                                @endif
                                <div class="upload-text">{{ isset($sourcing->value['image']) && $sourcing->value['image'] ? 'Click to change image' : 'Click to upload image' }}</div>
                                <div class="file-input-wrapper">
                                    <span class="btn-choose-file">Choose Image</span>
                                    <input type="file" name="sourcing_image" accept="image/*" onchange="previewImage(event, 'sourcing')">
                                </div>
                            </div>
                            <input type="hidden" name="sourcing_image_old" value="{{ $sourcing->value['image'] ?? '' }}">
                        </div>
                    </div>

                    <label class="form-label mb-3">Sourcing Items (3 items)</label>
                    <div class="items-grid">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="item-card">
                                <span class="item-number">{{ $i }}</span>
                                <div class="mb-3">
                                    <label class="form-label">Item Title</label>
                                    <input type="text" class="form-control" name="sourcing_item_{{ $i }}_title"
                                           value="{{ $sourcing->value['items'][$i-1]['title'] ?? '' }}"
                                           placeholder="Enter item title">
                                </div>
                                <div>
                                    <label class="form-label">Item Description</label>
                                    <textarea class="form-control" name="sourcing_item_{{ $i }}_description" rows="3"
                                              placeholder="Enter description">{{ $sourcing->value['items'][$i-1]['description'] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- ================= 3. Branding & Quality Section ================= --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title">🎨 Branding & Quality Section</h5>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label">Section Title</label>
                            <input type="text" class="form-control" name="branding_title"
                                   value="{{ $branding->value['title'] ?? ' ' }}"
                                   placeholder="Enter section title">
                        </div>
                    </div>

                    <label class="form-label mb-3">Branding Cards (3 cards)</label>
                    <div class="branding-cards-grid">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="branding-card-item">
                                <span class="item-number">{{ $i }}</span>

                                <div class="mb-3">
                                    <label class="form-label">Card Image</label>
                                    <div class="upload-preview-box {{ isset($branding->value['cards'][$i-1]['image']) && $branding->value['cards'][$i-1]['image'] ? 'has-image' : '' }}" id="branding-{{ $i }}-preview-box">
                                        @if(isset($branding->value['cards'][$i-1]['image']) && $branding->value['cards'][$i-1]['image'])
                                            <button type="button" class="btn-remove-image" onclick="removeImage('branding-{{ $i }}')">✕</button>
                                            <img src="{{ asset($branding->value['cards'][$i-1]['image']) }}" class="preview-image" id="branding-{{ $i }}-preview">
                                        @endif
                                        <div class="upload-text">{{ isset($branding->value['cards'][$i-1]['image']) && $branding->value['cards'][$i-1]['image'] ? 'Click to change' : 'Click to upload' }}</div>
                                        <div class="file-input-wrapper">
                                            <span class="btn-choose-file">Choose</span>
                                            <input type="file" name="branding_image_{{ $i }}" accept="image/*" onchange="previewImage(event, 'branding-{{ $i }}')">
                                        </div>
                                    </div>
                                    <input type="hidden" name="branding_image_{{ $i }}_old" value="{{ $branding->value['cards'][$i-1]['image'] ?? '' }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Card Title</label>
                                    <input type="text" class="form-control" name="branding_card_{{ $i }}_title"
                                           value="{{ $branding->value['cards'][$i-1]['title'] ?? '' }}"
                                           placeholder="Enter card title">
                                </div>

                                <div>
                                    <label class="form-label">Card Description</label>
                                    <textarea class="form-control" name="branding_card_{{ $i }}_description" rows="3"
                                              placeholder="Enter description">{{ $branding->value['cards'][$i-1]['description'] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- ================= 4. Logistics & Trade Support Section ================= --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title">🚚 Logistics & Trade Support Section</h5>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label">Section Title</label>
                            <input type="text" class="form-control" name="logistics_title"
                                   value="{{ $logistics->value['title'] ?? ' ' }}"
                                   placeholder="Enter section title">
                        </div>
                    </div>

                    <label class="form-label mb-3">Logistics Items (4 items)</label>
                    <div class="items-grid">
                        @for($i = 1; $i <= 4; $i++)
                            <div class="item-card">
                                <span class="item-number">{{ $i }}</span>
                                <div class="mb-3">
                                    <label class="form-label">Item Title</label>
                                    <input type="text" class="form-control" name="logistics_item_{{ $i }}_title"
                                           value="{{ $logistics->value['items'][$i-1]['title'] ?? '' }}"
                                           placeholder="Enter item title">
                                </div>
                                <div>
                                    <label class="form-label">Item Description</label>
                                    <textarea class="form-control" name="logistics_item_{{ $i }}_description" rows="3"
                                              placeholder="Enter description">{{ $logistics->value['items'][$i-1]['description'] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- ================= 5. Legal & Contractual Support Section ================= --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title">⚖️ Legal & Contractual Support Section</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Section Title</label>
                            <input type="text" class="form-control" name="legal_title"
                                   value="{{ $legal->value['title'] ?? ' ' }}"
                                   placeholder="Enter section title">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="legal_description" rows="4"
                                      placeholder="Enter description">{{ $legal->value['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ================= 6. Why Work With Us Section ================= --}}
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title">🤝 Why Work With Us Section</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="why_work_title"
                                   value="{{ $whyWork->value['title'] ?? 'Why Work With Us' }}"
                                   placeholder="Enter section title">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="why_work_description" rows="4"
                                      placeholder="Enter description">{{ $whyWork->value['description'] ?? '' }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Handshake Image</label>
                            <div class="upload-preview-box {{ isset($whyWork->value['image']) && $whyWork->value['image'] ? 'has-image' : '' }}" id="why-work-preview-box">
                                @if(isset($whyWork->value['image']) && $whyWork->value['image'])
                                    <button type="button" class="btn-remove-image" onclick="removeImage('why-work')">✕</button>
                                    <img src="{{ asset($whyWork->value['image']) }}" class="preview-image" id="why-work-preview">
                                @endif
                                <div class="upload-text">{{ isset($whyWork->value['image']) && $whyWork->value['image'] ? 'Click to change image' : 'Click to upload image' }}</div>
                                <div class="file-input-wrapper">
                                    <span class="btn-choose-file">Choose Image</span>
                                    <input type="file" name="why_work_image" accept="image/*" onchange="previewImage(event, 'why-work')">
                                </div>
                            </div>
                            <input type="hidden" name="why_work_image_old" value="{{ $whyWork->value['image'] ?? '' }}">
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

<script>
function previewImage(event, id) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const previewBox = document.getElementById(id + '-preview-box');
        previewBox.classList.add('has-image');

        let img = previewBox.querySelector('.preview-image');
        if (img) {
            img.src = e.target.result;
        } else {
            img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-image';
            img.id = id + '-preview';
            previewBox.insertBefore(img, previewBox.querySelector('.upload-text'));
        }

        if (!previewBox.querySelector('.btn-remove-image')) {
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-remove-image';
            removeBtn.innerHTML = '✕';
            removeBtn.onclick = function(e) {
                e.stopPropagation();
                removeImage(id);
            };
            previewBox.insertBefore(removeBtn, previewBox.firstChild);
        }

        const uploadText = previewBox.querySelector('.upload-text');
        if (uploadText) {
            uploadText.textContent = 'Click to change image';
        }
    }
    reader.readAsDataURL(file);
}

function removeImage(id) {
    const previewBox = document.getElementById(id + '-preview-box');
    const fileInput = previewBox.querySelector('input[type="file"]');

    fileInput.value = '';

    const img = previewBox.querySelector('.preview-image');
    const removeBtn = previewBox.querySelector('.btn-remove-image');
    if (img) img.remove();
    if (removeBtn) removeBtn.remove();

    const uploadText = previewBox.querySelector('.upload-text');
    if (uploadText) {
        uploadText.textContent = 'Click to upload image';
    }

    previewBox.classList.remove('has-image');

    const oldInput = document.querySelector(`input[name="${id.replace(/-/g, '_')}_old"]`);
    if (oldInput) oldInput.value = '';
}
</script>

@endsection
