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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
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

        .form-control,
        .form-control:focus {
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

        .slide-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }

        .slide-number {
            background: #0d6efd;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 15px;
        }
    </style>

    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-house-fill me-2"></i>Home Page Settings
                </h4>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('settings.home-page.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- ================= Hero Carousel Section ================= --}}
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="section-title">Hero Carousel (3 Slides)</h5>
                        </div>

                        @for ($i = 1; $i <= 3; $i++)
                            @php
                                $slide = $hero->value['slides'][$i - 1] ?? null;
                            @endphp
                            <div class="slide-card">
                                <span class="slide-number">{{ $i }}</span>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Slide {{ $i }} - Title</label>
                                        <input type="text" class="form-control" name="hero_title_{{ $i }}"
                                            value="{{ $slide['title'] ?? 'Connecting Markets, Delivering Value.' }}"
                                            placeholder="Enter slide title">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Slide {{ $i }} - Description</label>
                                        <textarea class="form-control" name="hero_description_{{ $i }}" rows="3"
                                            placeholder="Enter slide description">{{ $slide['description'] ?? 'From food and beverages to raw materials and recycled goods – Trades Axis bridges global demand and supply with precision, trust, and expertise.' }}</textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Slide {{ $i }} - Image</label>
                                        <div class="upload-preview-box {{ isset($slide['image']) && $slide['image'] ? 'has-image' : '' }}"
                                            id="hero-slide-{{ $i }}-preview-box">
                                            @if (isset($slide['image']) && $slide['image'])
                                                <button type="button" class="btn-remove-image"
                                                    onclick="removeImage('hero-slide-{{ $i }}')">✕</button>
                                                <img src="{{ asset($slide['image']) }}" class="preview-image"
                                                    id="hero-slide-{{ $i }}-preview">
                                            @endif
                                            <div class="upload-text">
                                                {{ isset($slide['image']) && $slide['image'] ? 'Click to change image' : 'Click to upload slide image' }}
                                            </div>
                                            <div class="file-input-wrapper">
                                                <span class="btn-choose-file">Choose Image</span>
                                                <input type="file" name="hero_slide_{{ $i }}"
                                                    accept="image/*"
                                                    onchange="previewImage(event, 'hero-slide-{{ $i }}')">
                                            </div>
                                        </div>
                                        <input type="hidden" name="hero_slide_{{ $i }}_old"
                                            value="{{ $slide['image'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    {{-- ================= Customers Section ================= --}}
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="section-title">Customers Section</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="customers_title"
                                    value="{{ $customers->value['title'] ?? 'Make your customers happy by giving the best products.' }}"
                                    placeholder="Enter customers section title">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="customers_description" rows="3" placeholder="Enter description">{{ $customers->value['description'] ?? 'We trade common products and food for improving your business and making sure you keep providing the highest quality.' }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Link Text</label>
                                <input type="text" class="form-control" name="customers_link_text"
                                    value="{{ $customers->value['link_text'] ?? 'About us' }}" placeholder="e.g. About us">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Link URL</label>
                                <input type="text" class="form-control" name="customers_link_url"
                                    value="{{ $customers->value['link_url'] ?? '#' }}" placeholder="e.g. /about">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Image</label>
                                <div class="upload-preview-box {{ isset($customers->value['image']) && $customers->value['image'] ? 'has-image' : '' }}"
                                    id="customers-preview-box">
                                    @if (isset($customers->value['image']) && $customers->value['image'])
                                        <button type="button" class="btn-remove-image"
                                            onclick="removeImage('customers')">✕</button>
                                        <img src="{{ asset($customers->value['image']) }}" class="preview-image"
                                            id="customers-preview">
                                    @endif
                                    <div class="upload-text">
                                        {{ isset($customers->value['image']) && $customers->value['image'] ? 'Click to change image' : 'Click to upload image' }}
                                    </div>
                                    <div class="file-input-wrapper">
                                        <span class="btn-choose-file">Choose Image</span>
                                        <input type="file" name="customers_image" accept="image/*"
                                            onchange="previewImage(event, 'customers')">
                                    </div>
                                </div>
                                <input type="hidden" name="customers_image_old"
                                    value="{{ $customers->value['image'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="section-title">Gather Quality Section</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Top Bar - Title</label>
                                <input type="text" class="form-control" name="gather_top_title"
                                    value="{{ $gather->value['top_title'] ?? 'Enjoy Most Completed Trading platform' }}"
                                    placeholder="Enter top bar title">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Top Bar - Description</label>
                                <textarea class="form-control" name="gather_top_description" rows="2" placeholder="Enter top bar description">{{ $gather->value['top_description'] ?? 'Explore through our large set of Categories. Find the products you need and inquire about them.' }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Button Text</label>
                                <input type="text" class="form-control" name="gather_button_text"
                                    value="{{ $gather->value['button_text'] ?? 'Explore Categories' }}"
                                    placeholder="e.g. Explore Categories">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Button URL</label>
                                <input type="text" class="form-control" name="gather_button_url"
                                    value="{{ $gather->value['button_url'] ?? '/categories' }}"
                                    placeholder="e.g. /categories">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Overlay Title (Big Text on Image)</label>
                                <input type="text" class="form-control" name="gather_overlay_title"
                                    value="{{ $gather->value['overlay_title'] ?? 'We Gather the highest Quality Products' }}"
                                    placeholder="Enter overlay title">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Background Image</label>
                                <div class="upload-preview-box {{ isset($gather->value['image']) && $gather->value['image'] ? 'has-image' : '' }}"
                                    id="gather-preview-box">
                                    @if (isset($gather->value['image']) && $gather->value['image'])
                                        <button type="button" class="btn-remove-image"
                                            onclick="removeImage('gather')">✕</button>
                                        <img src="{{ asset($gather->value['image']) }}" class="preview-image"
                                            id="gather-preview">
                                    @endif
                                    <div class="upload-text">
                                        {{ isset($gather->value['image']) && $gather->value['image'] ? 'Click to change image' : 'Click to upload image' }}
                                    </div>
                                    <div class="file-input-wrapper">
                                        <span class="btn-choose-file">Choose Image</span>
                                        <input type="file" name="gather_image" accept="image/*"
                                            onchange="previewImage(event, 'gather')">
                                    </div>
                                </div>
                                <input type="hidden" name="gather_image_old"
                                    value="{{ $gather->value['image'] ?? '' }}">
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
