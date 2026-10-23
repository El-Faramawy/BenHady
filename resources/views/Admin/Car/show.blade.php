@extends('layouts.admin.app')
@section('page_title')
    تفاصيل السيارة: {{ $car->name_ar }}
@endsection

@push('admin_css')
    <link href="{{url('Admin')}}/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />
    @include('Admin.Car.parts.show-css')
@endpush

@section('content')
    <!-- Top Action Bar -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px; background: #ffffff; border: 1px solid #e2e8f0;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 16px;">
                <!-- Car Title & Badges -->
                <div>
                    <div class="d-flex align-items-baseline flex-wrap mb-2" style="gap: 10px;">
                        <h3 class="font-weight-bold text-dark mb-0 font-size-22">{{ $car->name_ar }}</h3>
                        <span class="text-muted font-size-14 font-weight-normal" dir="ltr">({{ $car->name_en }})</span>
                    </div>
                    <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                        <span class="badge px-3 py-2 font-weight-bold"
                            style="border-radius: 8px; font-size: 13px; background-color: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">
                            <i class="fa fa-tag ml-1"></i>{{ number_format((float) $car->daily_price, 2) }} ر.س / اليوم
                        </span>

                        @php $statusVal = is_object($car->status) ? $car->status->value : (string) $car->status; @endphp
                        @if($statusVal === 'available')
                            <span class="badge badge-success px-3 py-2 font-weight-bold"
                                style="border-radius: 8px; font-size: 13px;"><i class="fa fa-check-circle ml-1"></i>متاحة
                                للطلب</span>
                        @elseif($statusVal === 'rented')
                            <span class="badge badge-warning px-3 py-2 font-weight-bold"
                                style="border-radius: 8px; font-size: 13px;"><i class="fa fa-clock-o ml-1"></i>مؤجرة
                                حالياً</span>
                        @elseif($statusVal === 'maintenance')
                            <span class="badge badge-danger px-3 py-2 font-weight-bold"
                                style="border-radius: 8px; font-size: 13px;"><i class="fa fa-wrench ml-1"></i>في الصيانة</span>
                        @else
                            <span class="badge badge-info px-3 py-2 font-weight-bold"
                                style="border-radius: 8px; font-size: 13px;"><i class="fa fa-bookmark ml-1"></i>محجوزة</span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                    <button type="button" id="editBtn" class="btn btn-primary px-3 py-2 font-weight-bold shadow-sm"
                        onclick="openEditCarModal({{ $car->id }})" data-id="{{ $car->id }}" style="border-radius: 8px;">
                        <i class="fa fa-edit ml-1"></i>تعديل
                    </button>

                    <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary px-3 py-2 font-weight-bold"
                        style="border-radius: 8px;">
                        <i class="fa fa-arrow-right ml-1"></i>الرجوع لقائمة السيارات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Car Details Overview -->
    <div class="row">
        <!-- Left Column: Gallery & Description -->
        <div class="col-xl-7 col-lg-12 mb-4">
            <!-- Gallery Card -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="card-title font-weight-bold mb-0 text-primary">
                        <i class="fa fa-picture-o ml-2"></i>معرض صور السيارة ({{ $car->images->count() }})
                    </h5>
                    @if($car->is_featured)
                        <span class="badge badge-warning font-weight-bold px-2 py-1"><i class="fa fa-star ml-1"></i>مميزة</span>
                    @endif
                    @if($car->is_handpicked)
                        <span class="badge badge-info font-weight-bold px-2 py-1"><i class="fa fa-thumbs-up ml-1"></i>مختارة
                            بعناية</span>
                    @endif
                </div>
                <div class="card-body p-3">
                    @php
                        $defaultCarImg = asset('Admin/imgs/default.jpg');
                        $primaryImgUrl = $car->primaryImage ? get_file($car->primaryImage->image_path) : $defaultCarImg;
                    @endphp
                    <!-- Main Large Image View -->
                    <div class="position-relative mb-3">
                        <img id="mainCarImage" src="{{ $primaryImgUrl }}" class="car-hero-img"
                            onerror="if (this.src !== '{{ $defaultCarImg }}') { this.src = '{{ $defaultCarImg }}'; }"
                            alt="{{ $car->name_ar }}">
                    </div>

                    <!-- Thumbnails row -->
                    @if($car->images->count() > 1)
                        <div class="d-flex flex-wrap gap-2 pt-2">
                            @foreach($car->images as $img)
                                @php
                                    $imgUrl = get_file($img->image_path);
                                @endphp
                                <img src="{{ $imgUrl }}"
                                    class="car-thumb-img ml-2 mb-2 {{ $img->is_primary ? 'active' : '' }}"
                                    onclick="changeMainImage(this)"
                                    onerror="if (this.src !== '{{ $defaultCarImg }}') { this.src = '{{ $defaultCarImg }}'; }" alt="thumbnail">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Description Card -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title font-weight-bold mb-0 text-primary"><i class="fa fa-file-text-o ml-2"></i>وصف
                        السيارة</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-dark mb-2">الوصف بالعربية:</h6>
                        <p class="text-muted line-height-24 font-size-14 bg-light p-3 rounded border">
                            {{ $car->description_ar ?: 'لا يوجد وصف مدخل بالعربية لهذه السيارة.' }}
                        </p>
                    </div>

                    <div>
                        <h6 class="font-weight-bold text-dark mb-2">Description in English:</h6>
                        <p class="text-muted line-height-24 font-size-14 bg-light p-3 rounded border" dir="ltr">
                            {{ $car->description_en ?: 'No English description provided for this vehicle.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Specs, Features & Branches -->
        <div class="col-xl-5 col-lg-12">
            <!-- 4 Quick Specs Cards -->
            <div class="row mb-3">
                <div class="col-sm-6 mb-3">
                    <div class="spec-card">
                        <div class="spec-icon-box">
                            <i class="fa fa-users"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block font-size-12">المقاعد</span>
                            <strong class="font-size-15 text-dark">{{ $car->seats }} ركاب</strong>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="spec-card">
                        <div class="spec-icon-box">
                            <i class="fa fa-cogs"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block font-size-12">ناقل الحركة</span>
                            <strong class="font-size-15 text-dark">{{ $car->transmission?->name_ar ?? '-' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="spec-card">
                        <div class="spec-icon-box">
                            <i class="fa fa-tint"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block font-size-12">نوع الوقود</span>
                            <strong class="font-size-15 text-dark">{{ $car->fuelType?->name_ar ?? '-' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="spec-card">
                        <div class="spec-icon-box">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block font-size-12">سنة الصنع</span>
                            <strong class="font-size-15 text-dark">{{ $car->modelYear?->year ?? '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Brand & Category Card -->
            <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="card-title font-weight-bold mb-0 text-primary"><i class="fa fa-car ml-2"></i>التصنيف والشركة
                        المصنعة</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-6 border-left">
                            <div class="d-flex align-items-center">
                                @php
                                    $defaultBrandImg = asset('Admin/imgs/default.jpg');
                                    $brandLogo = $car->brand?->logo_url ? get_file($car->brand->logo_url) : $defaultBrandImg;
                                @endphp
                                <img src="{{ $brandLogo }}"
                                    style="width: 44px; height: 44px; object-fit: contain; padding: 3px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px;"
                                    class="ml-2 shadow-sm" onerror="this.onerror=null;this.src='{{ $defaultBrandImg }}';"
                                    alt="{{ $car->brand?->name_ar ?? 'brand' }}">
                                <div>
                                    <span class="text-muted d-block font-size-12">الماركة:</span>
                                    <strong class="font-size-14">{{ $car->brand?->name_ar ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                @if($car->category?->icon)
                                    <span class="ml-2">{!! featureIcon($car->category->icon, 'fa fa-th-large', 36) !!}</span>
                                @endif
                                <div>
                                    <span class="text-muted d-block font-size-12">الفئة / القسم:</span>
                                    <strong class="font-size-14">{{ $car->category?->name_ar }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location & Branches Card -->
            <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="card-title font-weight-bold mb-0 text-primary"><i class="fa fa-map-marker ml-2"></i>موقع
                        وتواجد السيارة والفروع</h6>
                </div>
                <div class="card-body p-3">
                    <div class="p-3 bg-light rounded border mb-3">
                        <span class="badge px-3 py-1 mb-2 font-size-12 font-weight-bold" style="background: #F5FDEA; color: #2E5A1C; border: 1px solid #D8F2B5; border-radius: 6px;">
                            <i class="fa fa-map-pin ml-1 text-success"></i>الفرع المتواجدة به حالياً
                        </span>
                        <h6 class="font-weight-bold text-dark mb-1">{{ $car->branch?->name_ar }}
                            ({{ $car->branch?->city?->name_ar }})</h6>
                        <small class="text-muted d-block mb-2">{{ $car->branch?->address_ar }}</small>
                        @if($car->branch?->phone)
                            <small class="text-muted d-block"><i class="fa fa-phone ml-1"></i><span
                                    dir="ltr">{{ $car->branch->phone }}</span></small>
                        @endif
                    </div>

                    @if($car->branches->isNotEmpty())
                        <div>
                            <span class="font-weight-bold font-size-13 d-block mb-2 text-dark">فروع إضافية متاحة
                                للاستلام:</span>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($car->branches as $b)
                                    <span class="badge badge-light px-2 py-1 mb-1 ml-1 border font-size-12">
                                        <i class="fa fa-building ml-1 text-primary"></i>{{ $b->name_ar }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Features Card -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="card-title font-weight-bold mb-0 text-primary"><i
                            class="fa fa-check-square ml-2"></i>المميزات والتجهيزات ({{ $car->features->count() }})</h6>
                </div>
                <div class="card-body p-3">
                    @if($car->features->isEmpty())
                        <p class="text-muted mb-0 font-size-13">لم يتم تعيين مميزات خاصة لهذه السيارة.</p>
                    @else
                        <div class="row">
                            @foreach($car->features as $feature)
                                <div class="col-sm-6 mb-2">
                                    <div class="d-flex align-items-center p-2 rounded bg-light border">
                                        @if($feature->icon)
                                            <span class="ml-2">{!! featureIcon($feature->icon, 'fa fa-check-square', 32) !!}</span>
                                        @else
                                            <i class="fa fa-check text-success ml-2"></i>
                                        @endif
                                        <span class="font-weight-bold font-size-13 text-dark">{{ $feature->name_ar }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings History for this Car -->
    <div class="card shadow-sm border-0 mt-3" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <h5 class="card-title font-weight-bold mb-0 text-primary">
                <i class="fa fa-calendar-check-o ml-2"></i>سجل حجوزات السيارة ({{ $car->bookings->count() }})
            </h5>
        </div>
        <div class="card-body p-0">
            @if($car->bookings->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa fa-calendar-times-o font-size-36 mb-2 d-block"></i>
                    <span>لا توجد حجوزات مسجلة لهذه السيارة حتى الآن</span>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 text-center align-middle modern-admin-table">
                        <thead class="admin-table-header text-white">
                            <tr>
                                <th class="text-white">رقم الحجز</th>
                                <th class="text-white">اسم العميل</th>
                                <th class="text-white">هاتف العميل</th>
                                <th class="text-white">فرع الاستلام</th>
                                <th class="text-white">تاريخ الاستلام</th>
                                <th class="text-white">تاريخ الإرجاع</th>
                                <th class="text-white">عدد الأيام</th>
                                <th class="text-white">الإجمالي</th>
                                <th class="text-white">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($car->bookings as $b)
                                <tr>
                                    <td class="font-weight-bold">{{ $b->booking_number }}</td>
                                    <td>{{ $b->user?->name ?? 'عميل محذوف' }}</td>
                                    <td dir="ltr">{{ $b->user?->phone ?? '-' }}</td>
                                    <td>{{ $b->pickupBranch?->name_ar ?? '-' }}</td>
                                    <td>{{ $b->pickup_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td>{{ $b->return_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td>{{ $b->total_days }} يوم</td>
                                    <td class="font-weight-bold text-success">{{ number_format((float) $b->total, 2) }} ر.س</td>
                                    <td>
                                        <span
                                            class="badge badge-info px-2 py-1 font-weight-bold">{{ $b->status_label ?? $b->status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Modal container on this page -->
    <x-list.modal name="السيارات" :save_button="true"></x-list.modal>
@endsection

@push('admin_js')
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/fileupload.js"></script>
    <script>
        var loader = ` <div class="linear-background">
                                    <div class="inter-crop"></div>
                                    <div class="inter-right--top"></div>
                                    <div class="inter-right--bottom"></div>
                                </div>
                `;

        function handleAjaxError(data) {
            if (data.status === 500) {
                if (typeof my_toaster === 'function') {
                    my_toaster('هناك خطأ ما', 'error');
                }
                return;
            }
            if (data.status === 422) {
                var res = data.responseJSON || (data.responseText ? $.parseJSON(data.responseText) : {});
                var hasErrors = false;
                if (res.errors) {
                    if (Array.isArray(res.errors)) {
                        $.each(res.errors, function (k, v) { if (typeof my_toaster === 'function') my_toaster(v, 'error'); hasErrors = true; });
                    } else if ($.isPlainObject(res.errors)) {
                        $.each(res.errors, function (key, val) {
                            if (Array.isArray(val)) {
                                $.each(val, function (i, msg) { if (typeof my_toaster === 'function') my_toaster(msg, 'error'); hasErrors = true; });
                            } else if (typeof val === 'string') {
                                if (typeof my_toaster === 'function') my_toaster(val, 'error');
                                hasErrors = true;
                            }
                        });
                    }
                }
                if (!hasErrors && res.message) {
                    if (typeof my_toaster === 'function') my_toaster(res.message, 'error');
                }
            }
        }

        function changeMainImage(thumbOrSrc, thumbElem) {
            var defaultImg = "{{ asset('Admin/imgs/default.jpg') }}";
            var newSrc = defaultImg;
            var $thumb = null;

            if (thumbOrSrc && typeof thumbOrSrc === 'object' && thumbOrSrc.nodeType) {
                $thumb = $(thumbOrSrc);
                newSrc = $thumb.attr('src') || defaultImg;
            } else if (thumbElem) {
                $thumb = $(thumbElem);
                newSrc = $thumb.attr('src') || thumbOrSrc || defaultImg;
            } else if (typeof thumbOrSrc === 'string') {
                newSrc = thumbOrSrc || defaultImg;
            }

            $('#mainCarImage').attr('src', newSrc);
            $('.car-thumb-img').removeClass('active');
            if ($thumb && $thumb.length) {
                $thumb.addClass('active');
            }
        }

        // Global function to open edit modal
        window.openEditCarModal = function (id) {
            id = id || {{ $car->id }};
            $('#form-load').html(loader);
            $('#Modal').modal('show');
            var url = "{{ route('cars.edit', 999999) }}".replace('999999', id);
            setTimeout(function () {
                $('#form-load').load(url, function () {
                    if ($.fn.dropify) {
                        $('.dropify').dropify({
                            messages: {
                                'default': 'اسحب وأفلت الصورة هنا أو اضغط للاختيار',
                                'replace': 'اسحب وأفلت أو اضغط للاستبدال',
                                'remove': 'حذف',
                                'error': 'حدث خطأ في الملف'
                            }
                        });
                    }
                });
            }, 100);
        };

        // jQuery delegation triggers
        $(document).off('click', '#editBtn').on('click', '#editBtn', function (e) {
            e.preventDefault();
            var id = $(this).data('id') || {{ $car->id }};
            window.openEditCarModal(id);
        });

        // Form submit handler for editing car inside modal
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
                    if (typeof my_toaster === 'function') {
                        my_toaster(data.message || 'تم التعديل بنجاح');
                    }
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

        $(document).on('click', '#close_model, #close_model_x, .btn-close-modal, [data-dismiss="modal"]', function (e) {
            $('#Modal').modal('hide');
        });
    </script>
@endpush