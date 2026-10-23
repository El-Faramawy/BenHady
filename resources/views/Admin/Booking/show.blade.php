@extends('layouts.admin.app')
@section('page_title')
    تفاصيل الحجز: #{{ $booking->booking_number }}
@endsection

@push('admin_css')
    <link href="{{url('Admin')}}/assets/plugins/select2/select2.min.css" rel="stylesheet" />
    @include('Admin.Booking.parts.show-css')
@endpush

@section('content')
    <!-- Top Action Bar -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px; background: #ffffff; border: 1px solid #e2e8f0;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 16px;">
                <!-- Booking Title & Status -->
                <div>
                    <div class="d-flex align-items-baseline flex-wrap mb-2" style="gap: 10px;">
                        <h3 class="font-weight-bold text-dark mb-0 font-size-22">
                            حجز رقم: <span class="text-primary">#{{ $booking->booking_number }}</span>
                        </h3>
                        <span class="text-muted font-size-13">تم الإنشاء في: {{ $booking->created_at?->format('Y-m-d H:i') }}</span>
                    </div>

                    <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                        @php $statusVal = $booking->status instanceof \App\Enums\Booking\BookingStatusEnum ? $booking->status->value : (string) $booking->status; @endphp
                        @if($statusVal === 'pending')
                            <span class="badge badge-warning px-3 py-2 font-weight-bold font-size-13" style="border-radius: 8px;"><i class="fa fa-clock-o ml-1"></i>قيد الانتظار</span>
                        @elseif($statusVal === 'active')
                            <span class="badge badge-success px-3 py-2 font-weight-bold font-size-13" style="border-radius: 8px;"><i class="fa fa-car ml-1"></i>حجز نشط (مستلم)</span>
                        @elseif($statusVal === 'completed')
                            <span class="badge badge-info px-3 py-2 font-weight-bold font-size-13" style="border-radius: 8px;"><i class="fa fa-check-circle ml-1"></i>حجز مكتمل</span>
                        @else
                            <span class="badge badge-danger px-3 py-2 font-weight-bold font-size-13" style="border-radius: 8px;"><i class="fa fa-times-circle ml-1"></i>حجز ملغي</span>
                        @endif

                        <span class="badge px-3 py-2 font-weight-bold font-size-13" style="border-radius: 8px; background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">
                            <i class="fa fa-credit-card ml-1"></i>الإجمالي: {{ number_format((float) $booking->total, 2) }} ر.س
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                    <a href="{{ route('bookings.invoice', $booking->id) }}" class="btn btn-info px-3 py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                        <i class="fa fa-file-text-o ml-1"></i>عرض الفاتورة
                    </a>

                    <button type="button" id="editBtn" class="btn btn-primary px-3 py-2 font-weight-bold shadow-sm" data-id="{{ $booking->id }}" style="border-radius: 8px;">
                        <i class="fa fa-edit ml-1"></i>تعديل الحجز
                    </button>

                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary px-3 py-2 font-weight-bold" style="border-radius: 8px;">
                        <i class="fa fa-arrow-right ml-1"></i>قائمة الحجوزات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="row">
        <!-- 1. Customer Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card detail-card">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="card-title font-weight-bold mb-0 text-primary">
                        <i class="fa fa-user-circle-o ml-2"></i>بيانات العميل المستأجر
                    </h6>
                    @if($booking->user)
                        <span class="badge badge-light-primary text-primary px-2 py-1 font-size-11">عميل مسجل</span>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if($booking->user)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-lg brround ml-3" style="width: 54px; height: 54px; line-height: 54px; font-size: 22px; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border-radius: 50%; text-align: center;">
                                {{ mb_substr($booking->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">{{ $booking->user->name }}</h5>
                                <span class="text-muted font-size-13" dir="ltr"><i class="fa fa-phone ml-1 text-primary"></i>{{ $booking->user->phone }}</span>
                                @if($booking->user->email)
                                    <span class="text-muted font-size-13 mr-3"><i class="fa fa-envelope-o ml-1"></i>{{ $booking->user->email }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row border-top pt-3">
                            <div class="col-sm-6 mb-2">
                                <span class="text-muted font-size-12 d-block">نوع المستخدم:</span>
                                <strong>
                                    @if($booking->user->type === 'national_id') مواطن سعودي
                                    @elseif($booking->user->type === 'resident_id') مقيم
                                    @else زائر @endif
                                </strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <span class="text-muted font-size-12 d-block">رقم الهوية / الحدود:</span>
                                <strong>{{ $booking->user->id_number ?: ($booking->user->border_entry_number ?: '-') }}</strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <span class="text-muted font-size-12 d-block">رقم رخصة القيادة:</span>
                                <strong>{{ $booking->user->driving_license_number ?: ($booking->user->license_number ?: '-') }}</strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <span class="text-muted font-size-12 d-block">حالة توثيق الحساب:</span>
                                <span>
                                    @if($booking->user->id_verified)
                                        <span class="badge badge-success px-2 py-1 font-size-11"><i class="fa fa-check ml-1"></i>الهوية موثقة</span>
                                    @else
                                        <span class="badge badge-light text-muted px-2 py-1 font-size-11">غير موثق</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-muted text-center py-4">بيانات العميل غير متوفرة</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. Car Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card detail-card">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="card-title font-weight-bold mb-0 text-primary">
                        <i class="fa fa-car ml-2"></i>بيانات السيارة المستأجرة
                    </h6>
                    @if($booking->car)
                        <a href="{{ route('cars.show', $booking->car_id) }}" class="btn btn-sm px-3 py-1 font-size-12 font-weight-bold" style="background: #F5FDEA; color: #2E5A1C; border: 1px solid #D8F2B5; border-radius: 6px;">
                            عرض صفحة السيارة <i class="fa fa-external-link mr-1"></i>
                        </a>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if($booking->car)
                        <div class="d-flex align-items-center mb-3">
                            @php
                                $defaultCarImg = asset('Admin/imgs/default.jpg');
                                $carImg = $booking->car->primaryImage?->image_path ? get_file($booking->car->primaryImage->image_path) : $defaultCarImg;
                            @endphp
                            <img src="{{ $carImg }}" class="shadow-sm rounded ml-3" style="width: 80px; height: 56px; object-fit: cover; border: 1px solid #e2e8f0;"
                                onerror="if (this.src !== '{{ $defaultCarImg }}') { this.src = '{{ $defaultCarImg }}'; }" alt="car">
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">{{ $booking->car->name_ar }}</h5>
                                <span class="text-muted font-size-13" dir="ltr">({{ $booking->car->name_en }})</span>
                                <div class="mt-1">
                                    <span class="badge badge-light px-2 py-1 font-size-11">{{ $booking->car->brand?->name_ar }}</span>
                                    <span class="badge badge-light px-2 py-1 font-size-11">{{ $booking->car->category?->name_ar }}</span>
                                    <span class="badge badge-light px-2 py-1 font-size-11">{{ $booking->car->modelYear?->year }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row border-top pt-3">
                            <div class="col-sm-4 mb-2">
                                <span class="text-muted font-size-12 d-block">السعر اليومي:</span>
                                <strong class="text-success font-size-14">{{ number_format((float) $booking->daily_price, 2) }} ر.س</strong>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <span class="text-muted font-size-12 d-block">ناقل الحركة:</span>
                                <strong>{{ $booking->car->transmission?->name_ar ?? '-' }}</strong>
                            </div>
                            <div class="col-sm-4 mb-2">
                                <span class="text-muted font-size-12 d-block">نوع الوقود:</span>
                                <strong>{{ $booking->car->fuelType?->name_ar ?? '-' }}</strong>
                            </div>
                        </div>
                    @else
                        <div class="text-muted text-center py-4">بيانات السيارة غير متوفرة</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3. Schedule & Branch Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card detail-card">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h6 class="card-title font-weight-bold mb-0 text-primary">
                        <i class="fa fa-map-marker ml-2"></i>الفروع ومواعيد الاستلام والتسليم
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Pickup -->
                        <div class="col-sm-6 mb-3 border-left">
                            <div class="d-flex align-items-center mb-2">
                                <div class="detail-icon-box bg-success-transparent text-success ml-2">
                                    <i class="fa fa-arrow-down"></i>
                                </div>
                                <div>
                                    <span class="text-muted font-size-12 d-block">فرع الاستلام:</span>
                                    <h6 class="font-weight-bold mb-0 text-dark">{{ $booking->pickupBranch?->name_ar ?? '-' }}</h6>
                                </div>
                            </div>
                            <div class="font-size-13 text-muted">
                                <div><i class="fa fa-map-pin ml-1"></i>{{ $booking->pickupBranch?->city?->name_ar ?? '-' }}</div>
                                <div><i class="fa fa-calendar ml-1 text-success"></i>موعد الاستلام: <strong class="text-dark">{{ $booking->pickup_at?->format('Y-m-d H:i') ?? '-' }}</strong></div>
                            </div>
                        </div>

                        <!-- Return -->
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="detail-icon-box bg-primary-transparent text-primary ml-2">
                                    <i class="fa fa-arrow-up"></i>
                                </div>
                                <div>
                                    <span class="text-muted font-size-12 d-block">فرع التسليم:</span>
                                    <h6 class="font-weight-bold mb-0 text-dark">{{ $booking->returnBranch?->name_ar ?? '-' }}</h6>
                                </div>
                            </div>
                            <div class="font-size-13 text-muted">
                                <div><i class="fa fa-map-pin ml-1"></i>{{ $booking->returnBranch?->city?->name_ar ?? '-' }}</div>
                                <div><i class="fa fa-calendar ml-1 text-primary"></i>موعد التسليم: <strong class="text-dark">{{ $booking->return_at?->format('Y-m-d H:i') ?? '-' }}</strong></div>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded bg-light border text-center mt-2">
                        <span class="font-size-13 text-muted">إجمالي مدة الحجز المحتسبة:</span>
                        <strong class="font-size-16 text-primary mr-1">{{ $booking->total_days }} يوم</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Financial & Payment Summary Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card detail-card">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="card-title font-weight-bold mb-0 text-primary">
                        <i class="fa fa-calculator ml-2"></i>تفاصيل الحسبة المالية والفاتورة
                    </h6>
                    <a href="{{ route('bookings.invoice', $booking->id) }}" class="btn btn-sm btn-outline-info font-size-12">
                        <i class="fa fa-print ml-1"></i>طباعة الفاتورة
                    </a>
                </div>
                <div class="card-body p-4">
                    <table class="table table-sm table-borderless mb-0 font-size-14">
                        <tbody>
                            <tr>
                                <td class="text-muted py-2">السعر اليومي للسيارة:</td>
                                <td class="text-left font-weight-bold py-2">{{ number_format((float) $booking->daily_price, 2) }} ر.س</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2">عدد أيام التأجير:</td>
                                <td class="text-left font-weight-bold py-2">× {{ $booking->total_days }} يوم</td>
                            </tr>
                            <tr class="border-top">
                                <td class="text-muted py-2">المجموع الفرعي:</td>
                                <td class="text-left font-weight-bold py-2">{{ number_format((float) $booking->subtotal, 2) }} ر.س</td>
                            </tr>
                            @if((float) $booking->discount > 0)
                                <tr>
                                    <td class="text-danger py-2">
                                        قيمة الخصم @if($booking->coupon_code) (كود: <span class="badge badge-light-danger">{{ $booking->coupon_code }}</span>) @endif:
                                    </td>
                                    <td class="text-left font-weight-bold text-danger py-2">- {{ number_format((float) $booking->discount, 2) }} ر.س</td>
                                </tr>
                            @endif
                            <tr class="border-top bg-light" style="border-radius: 8px;">
                                <td class="font-weight-bold py-3 text-dark font-size-15">المبلغ الإجمالي النهائي:</td>
                                <td class="text-left font-weight-bold py-3 text-success font-size-18">{{ number_format((float) $booking->total, 2) }} ر.س</td>
                            </tr>
                        </tbody>
                    </table>

                    @if($booking->notes)
                        <div class="mt-3 p-3 rounded bg-light border">
                            <span class="font-weight-bold font-size-12 text-muted d-block mb-1">ملاحظات الحجز:</span>
                            <p class="mb-0 font-size-13 text-dark">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal container on this page -->
    <x-list.modal name="الحجوزات" :save_button="true"></x-list.modal>
@endsection

@push('admin_js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{url('Admin')}}/assets/plugins/select2/select2.full.min.js"></script>
    <script>
        var loader = ` <div class="linear-background">
                            <div class="inter-crop"></div>
                            <div class="inter-right--top"></div>
                            <div class="inter-right--bottom"></div>
                        </div>
        `;

        function handleAjaxError(data) {
            if (data.status === 500) {
                my_toaster('هناك خطأ ما', 'error');
                return;
            }
            if (data.status === 422) {
                var res = data.responseJSON || (data.responseText ? $.parseJSON(data.responseText) : {});
                var hasErrors = false;
                if (res.errors) {
                    if (Array.isArray(res.errors)) {
                        $.each(res.errors, function(k, v) { my_toaster(v, 'error'); hasErrors = true; });
                    } else if ($.isPlainObject(res.errors)) {
                        $.each(res.errors, function(key, val) {
                            if (Array.isArray(val)) {
                                $.each(val, function(i, msg) { my_toaster(msg, 'error'); hasErrors = true; });
                            } else if (typeof val === 'string') {
                                my_toaster(val, 'error');
                                hasErrors = true;
                            }
                        });
                    }
                }
                if (!hasErrors && res.message) {
                    my_toaster(res.message, 'error');
                }
            }
        }

        // Global function to open edit modal
        window.openEditBookingModal = function (id) {
            id = id || {{ $booking->id }};
            $('#form-load').html(loader);
            $('#Modal').modal('show');
            var url = "{{ route('bookings.edit', 999999) }}".replace('999999', id);
            setTimeout(function () {
                $('#form-load').load(url, function () {
                    if ($.fn.select2) {
                        $('.select2-user').select2({
                            dropdownParent: $('#Modal'),
                            width: '100%',
                            dir: 'rtl'
                        });
                    }
                });
            }, 100);
        };

        // Edit button triggers the edit modal
        $(document).off('click', '#editBtn').on('click', '#editBtn', function (e) {
            e.preventDefault();
            var id = $(this).data('id') || {{ $booking->id }};
            window.openEditBookingModal(id);
        });

        // Form submit handler for editing booking inside modal
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
