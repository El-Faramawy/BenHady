@extends('layouts.admin.app')
@section('page_title')
    كتالوج وتفاصيل السيارات
@endsection

@push('admin_css')
    @include('Admin.Car.parts.catalog-css')
@endpush

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-sm-12">
            <h3 class="font-weight-bold text-dark mb-1">
                <i class="fe fe-grid text-primary ml-2"></i>كتالوج وتفاصيل السيارات
            </h3>
            <p class="text-muted mb-0">استعراض مرئي لجميع السيارات المتوفرة في الأسطول وتفاصيل كل سيارة.</p>
        </div>
        <div class="col-md-6 col-sm-12 text-md-left mt-3 mt-md-0">
            <a href="{{ route('cars.index') }}" class="btn btn-outline-primary ml-2 font-weight-bold">
                <i class="fa fa-list ml-1"></i>جدول إدارة السيارات
            </a>
            <button id="addCarBtn" class="btn btn-primary font-weight-bold">
                <i class="fa fa-plus ml-1"></i>إضافة سيارة جديدة
            </button>
        </div>
    </div>

    <!-- Filter input -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-6 col-sm-12 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-left-0"><i class="fa fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="catalogSearch" class="form-control border-right-0" placeholder="ابحث باسم السيارة، الماركة، أو القسم...">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-md-left text-muted font-size-13 font-weight-bold">
                    إجمالي السيارات في الأسطول: <span class="badge badge-primary px-3 py-1 font-size-13">{{ $cars->count() }} سيارة</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cars Grid -->
    <div class="row" id="carsGrid">
        @forelse($cars as $car)
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4 car-card-item" data-keywords="{{ strtolower($car->name_ar . ' ' . $car->name_en . ' ' . ($car->brand?->name_ar ?? '') . ' ' . ($car->category?->name_ar ?? '')) }}">
                <div class="catalog-car-card">
                    <div class="catalog-car-img-box">
                        @php
                            $defaultImg = asset('Admin/imgs/default.jpg');
                            $carImgPath = $car->primaryImage?->image_path ?? $car->images?->first()?->image_path;
                            $img = $carImgPath ? get_file($carImgPath) : $defaultImg;
                        @endphp
                        <img src="{{ $img }}" class="catalog-car-img" onerror="this.onerror=null;this.src='{{ $defaultImg }}';" alt="{{ $car->name_ar }}">

                        <div class="catalog-brand-badge">
                            @php
                                $catBrandLogo = $car->brand?->logo_url ? get_file($car->brand->logo_url) : $defaultImg;
                            @endphp
                            <img src="{{ $catBrandLogo }}" style="width: 18px; height: 18px; object-fit: contain;" onerror="this.onerror=null;this.src='{{ $defaultImg }}';" alt="logo">
                            <span>{{ $car->brand?->name_ar ?? '-' }}</span>
                        </div>

                        <div class="catalog-price-badge">
                            {{ number_format((float) $car->daily_price, 2) }} ر.س / يوم
                        </div>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h5 class="font-weight-bold text-dark mb-0">{{ $car->name_ar }}</h5>
                            <span class="badge badge-light border font-weight-bold">{{ $car->modelYear?->year }}</span>
                        </div>
                        <small class="text-muted mb-3 d-block" dir="ltr">{{ $car->name_en }}</small>

                        <!-- Specs Chips -->
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            <span class="spec-chip ml-1 mb-1"><i class="fa fa-users text-primary"></i>{{ $car->seats }} مقاعد</span>
                            <span class="spec-chip ml-1 mb-1"><i class="fa fa-cogs text-primary"></i>{{ $car->transmission?->name_ar }}</span>
                            <span class="spec-chip ml-1 mb-1"><i class="fa fa-tint text-primary"></i>{{ $car->fuelType?->name_ar }}</span>
                            <span class="spec-chip mb-1"><i class="fa fa-map-marker text-danger"></i>{{ $car->branch?->name_ar }}</span>
                        </div>

                        <!-- Status & Badges -->
                        <div class="d-flex align-items-center justify-content-between mb-3 border-top pt-2">
                            <div>
                                @php $statusVal = is_object($car->status) ? $car->status->value : (string) $car->status; @endphp
                                @if($statusVal === 'available')
                                    <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle ml-1"></i>متاحة</span>
                                @elseif($statusVal === 'rented')
                                    <span class="badge badge-warning px-2 py-1"><i class="fa fa-clock-o ml-1"></i>مؤجرة</span>
                                @elseif($statusVal === 'maintenance')
                                    <span class="badge badge-danger px-2 py-1"><i class="fa fa-wrench ml-1"></i>صيانة</span>
                                @else
                                    <span class="badge badge-info px-2 py-1"><i class="fa fa-bookmark ml-1"></i>محجوزة</span>
                                @endif

                                @if($car->is_featured)
                                    <span class="badge badge-warning px-2 py-1 font-size-10"><i class="fa fa-star"></i></span>
                                @endif
                                @if($car->is_handpicked)
                                    <span class="badge badge-info px-2 py-1 font-size-10"><i class="fa fa-thumbs-up"></i></span>
                                @endif
                            </div>

                            <span class="font-size-12 text-muted">
                                {{ $car->category?->name_ar }}
                            </span>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-auto pt-2">
                            <a href="{{ route('cars.show', $car->id) }}" class="btn btn-primary btn-block font-weight-bold" style="border-radius: 8px;">
                                <i class="fa fa-eye ml-1"></i>عرض تفاصيل السيارة الكاملة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fa fa-car font-size-48 text-muted mb-3 d-block"></i>
                <h5 class="text-muted font-weight-bold">لا توجد سيارات مسجلة في الأسطول حالياً</h5>
            </div>
        @endforelse
    </div>

    <!-- Modal for adding new car -->
    <x-list.modal name="السيارات" :save_button="true"></x-list.modal>
@endsection

@push('admin_js')
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/fileupload.js"></script>
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/file-upload.js"></script>
    <script>
        // Live search filter in catalog
        $('#catalogSearch').on('keyup', function () {
            var val = $(this).val().toLowerCase().trim();
            $('.car-card-item').each(function () {
                var keywords = $(this).data('keywords');
                if (keywords.indexOf(val) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Trigger add car modal from catalog page
        $(document).on('click', '#addCarBtn', function () {
            $('#form-load').html(loader);
            $('#Modal').modal('show');
            setTimeout(function () {
                $('#form-load').load("{{ route('cars.create') }}", function () {
                    $('.dropify').dropify();
                });
            }, 100);
        });

        // Form submit handler for adding new car
        $(document).off('submit', 'form#form').on('submit', 'form#form', function (e) {
            e.preventDefault();
            var $form = $(this);

            if ($form.data('is_submitting')) {
                return false;
            }
            $form.data('is_submitting', true);

            var $btn = $('#submit');
            var formData = new FormData(this);
            var url = $form.attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#global-loader').show();
                    $btn.prop('disabled', true);
                },
                success: function (data) {
                    $('#global-loader').hide();
                    $btn.prop('disabled', false);
                    $form.data('is_submitting', false);
                    $('#Modal').modal('hide');
                    my_toaster(data.message || 'تمت الإضافة بنجاح');
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                },
                error: function (data) {
                    $('#global-loader').hide();
                    $btn.prop('disabled', false);
                    $form.data('is_submitting', false);
                    handleAjaxError(data);
                }
            });
        });
    </script>
@endpush
