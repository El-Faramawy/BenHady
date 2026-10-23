<div class="row">
    <!-- User Overview Hero Card -->
    <div class="col-xl-4 col-lg-5 col-md-12 mb-4">
        <div class="card shadow-sm border-0 h-100" style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0;">
            <div class="card-body text-center p-4">
                <div class="avatar avatar-xxl brround mb-3 shadow" style="width: 86px; height: 86px; line-height: 86px; font-size: 34px; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; margin: 0 auto; border-radius: 50%;">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
                <h4 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2 font-size-14"><i class="fa fa-phone ml-1 text-primary"></i><span dir="ltr">{{ $user->phone }}</span></p>
                @if($user->email)
                    <p class="text-muted mb-3 font-size-13"><i class="fa fa-envelope-o ml-1 text-muted"></i>{{ $user->email }}</p>
                @endif

                <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 mb-3">
                    @if($user->status === 'active')
                        <span class="badge badge-success px-3 py-2 font-weight-bold ml-1 mb-1" style="border-radius: 8px; font-size: 12px;"><i class="fa fa-check-circle ml-1"></i>حساب نشط</span>
                    @elseif($user->status === 'pending')
                        <span class="badge badge-warning px-3 py-2 font-weight-bold ml-1 mb-1" style="border-radius: 8px; font-size: 12px;"><i class="fa fa-clock-o ml-1"></i>قيد المراجعة</span>
                    @else
                        <span class="badge badge-danger px-3 py-2 font-weight-bold ml-1 mb-1" style="border-radius: 8px; font-size: 12px;"><i class="fa fa-times-circle ml-1"></i>غير نشط</span>
                    @endif

                    <span class="badge badge-primary px-3 py-2 font-weight-bold mb-1" style="border-radius: 8px; font-size: 12px;">
                        @if($user->type === 'national_id')
                            <i class="fa fa-id-badge ml-1"></i>مواطن سعودي
                        @elseif($user->type === 'resident_id')
                            <i class="fa fa-address-card ml-1"></i>مقيم
                        @else
                            <i class="fa fa-plane ml-1"></i>زائر
                        @endif
                    </span>
                </div>

                <div class="p-3 rounded shadow-sm text-center mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                    <span class="text-muted d-block font-size-12 mb-1 font-weight-bold">رصيد المحفظة</span>
                    <h3 class="font-weight-bold text-success mb-0">{{ number_format((float) $user->wallet, 2) }} <small class="text-muted font-size-14">ر.س</small></h3>
                </div>

                <div class="text-muted font-size-12 border-top pt-3 d-flex justify-content-between align-items-center">
                    <span><i class="fa fa-calendar ml-1 text-primary"></i>تاريخ التسجيل</span>
                    <strong>{{ $user->created_at?->format('Y-m-d H:i') ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification & Documents Details -->
    <div class="col-xl-8 col-lg-7 col-md-12 mb-4">
        <!-- Verification Badges -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px; border: 1px solid #e2e8f0;">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="card-title font-weight-bold mb-0 text-primary"><i class="fa fa-shield ml-2"></i>حالات التوثيق والتحقق</h6>
            </div>
            <div class="card-body p-4">
                <div class="row text-center">
                    <div class="col-md-4 col-sm-12 mb-2">
                        <div class="p-3 rounded border" style="background: {{ $user->phone_verified ? '#ecfdf5' : '#f8fafc' }}; border-color: {{ $user->phone_verified ? '#a7f3d0' : '#e2e8f0' }} !important; border-radius: 12px;">
                            <i class="fa {{ $user->phone_verified ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' }} font-size-28 mb-2 d-block"></i>
                            <span class="font-weight-bold d-block mb-1 text-dark">رقم الهاتف</span>
                            <span class="badge {{ $user->phone_verified ? 'badge-success' : 'badge-light text-muted' }} px-2 py-1 font-size-11">{{ $user->phone_verified ? 'تم التحقق' : 'غير موثق' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-2">
                        <div class="p-3 rounded border" style="background: {{ $user->id_verified ? '#ecfdf5' : '#f8fafc' }}; border-color: {{ $user->id_verified ? '#a7f3d0' : '#e2e8f0' }} !important; border-radius: 12px;">
                            <i class="fa {{ $user->id_verified ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' }} font-size-28 mb-2 d-block"></i>
                            <span class="font-weight-bold d-block mb-1 text-dark">الهوية / الإقامة</span>
                            <span class="badge {{ $user->id_verified ? 'badge-success' : 'badge-light text-muted' }} px-2 py-1 font-size-11">{{ $user->id_verified ? 'تم التحقق' : 'غير موثقة' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-2">
                        <div class="p-3 rounded border" style="background: {{ $user->licence_verified ? '#ecfdf5' : '#f8fafc' }}; border-color: {{ $user->licence_verified ? '#a7f3d0' : '#e2e8f0' }} !important; border-radius: 12px;">
                            <i class="fa {{ $user->licence_verified ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' }} font-size-28 mb-2 d-block"></i>
                            <span class="font-weight-bold d-block mb-1 text-dark">رخصة القيادة</span>
                            <span class="badge {{ $user->licence_verified ? 'badge-success' : 'badge-light text-muted' }} px-2 py-1 font-size-11">{{ $user->licence_verified ? 'تم التحقق' : 'غير موثقة' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Identity & License Info -->
        <div class="card shadow-sm border-0" style="border-radius: 14px; border: 1px solid #e2e8f0;">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="card-title font-weight-bold mb-0 text-primary">
                    <i class="fa fa-id-card-o ml-2"></i>
                    @if($user->type === 'visitor')
                        بيانات الزائر ورخصة القيادة
                    @else
                        بيانات الهوية ورخصة القيادة
                    @endif
                </h6>
                <span class="badge badge-light px-3 py-1 font-size-12">
                    {{ $user->type === 'visitor' ? 'حساب زائر' : ($user->type === 'resident_id' ? 'إقامة مقيم' : 'هوية وطنية') }}
                </span>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    @if($user->type === 'visitor')
                        <!-- Visitor specific fields -->
                        <div class="col-sm-6 mb-3">
                            <span class="text-muted d-block font-size-12 mb-1">رقم الدخول الحدودي:</span>
                            <strong class="font-size-15 text-dark">{{ $user->border_entry_number ?: 'غير مسجل' }}</strong>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <span class="text-muted d-block font-size-12 mb-1">رقم الرخصة / الجواز:</span>
                            <strong class="font-size-15 text-dark">{{ $user->license_number ?: 'غير مسجل' }}</strong>
                        </div>
                    @else
                        <!-- National / Resident ID specific fields -->
                        <div class="col-sm-4 mb-3">
                            <span class="text-muted d-block font-size-12 mb-1">رقم الهوية / الإقامة:</span>
                            <strong class="font-size-15 text-dark">{{ $user->id_number ?: 'غير مسجل' }}</strong>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <span class="text-muted d-block font-size-12 mb-1">تاريخ انتهاء الهوية:</span>
                            <strong class="font-size-15 text-dark">{{ $user->id_number_end_date ?: 'غير مسجل' }}</strong>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <span class="text-muted d-block font-size-12 mb-1">رقم النسخة:</span>
                            <strong class="font-size-15 text-dark">{{ $user->version_number ?: '-' }}</strong>
                        </div>
                    @endif

                    <div class="col-sm-4 mb-3">
                        <span class="text-muted d-block font-size-12 mb-1">رقم رخصة القيادة:</span>
                        <strong class="font-size-15 text-dark">{{ $user->driving_license_number ?: 'غير مسجل' }}</strong>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <span class="text-muted d-block font-size-12 mb-1">تاريخ انتهاء الرخصة:</span>
                        <strong class="font-size-15 text-dark">{{ $user->license_expiry_date ?: 'غير مسجل' }}</strong>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <span class="text-muted d-block font-size-12 mb-1">تاريخ الميلاد:</span>
                        <strong class="font-size-15 text-dark">{{ $user->date_of_birth ?: 'غير مسجل' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bookings History -->
<div class="card shadow-sm border-0 mt-2" style="border-radius: 14px; border: 1px solid #e2e8f0;">
    <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
        <h6 class="card-title font-weight-bold mb-0 text-primary">
            <i class="fa fa-calendar-check-o ml-2"></i>سجل حجوزات العميل ({{ $user->bookings->count() }})
        </h6>
    </div>
    <div class="card-body p-0">
        @if($user->bookings->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fa fa-folder-open-o font-size-36 mb-2 d-block text-muted"></i>
                <span class="font-size-14 font-weight-bold">لا توجد حجوزات سابقة لهذا العميل</span>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center align-middle">
                    <thead class="admin-table-header text-white">
                        <tr>
                            <th class="py-3 text-white">رقم الحجز</th>
                            <th class="py-3 text-white">السيارة</th>
                            <th class="py-3 text-white">فرع الاستلام</th>
                            <th class="py-3 text-white">تاريخ الاستلام</th>
                            <th class="py-3 text-white">تاريخ الإرجاع</th>
                            <th class="py-3 text-white">الإجمالي</th>
                            <th class="py-3 text-white">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->bookings as $booking)
                            <tr>
                                <td class="font-weight-bold py-3"><span class="badge badge-light px-2 py-1 font-size-12">{{ $booking->booking_number }}</span></td>
                                <td class="py-3 font-weight-bold text-dark">{{ $booking->car?->name_ar ?? 'سيارة محذوفة' }}</td>
                                <td class="py-3">{{ $booking->pickupBranch?->name_ar ?? '-' }}</td>
                                <td class="py-3 font-size-13">{{ $booking->pickup_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td class="py-3 font-size-13">{{ $booking->return_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td class="py-3 font-weight-bold text-success font-size-14">{{ number_format((float) $booking->total, 2) }} ر.س</td>
                                <td class="py-3">
                                    <span class="badge badge-info px-3 py-1 font-weight-bold" style="border-radius: 6px;">{{ $booking->status_label ?? $booking->status }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
