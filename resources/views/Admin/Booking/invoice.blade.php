@extends('layouts.admin.app')
@section('page_title')
    فاتورة حجز: #{{ $booking->booking_number }}
@endsection

@push('admin_css')
    <style>
        .invoice-box {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            max-width: 900px;
            margin: 0 auto;
        }
        .invoice-table th, .invoice-table td {
            padding: 12px 16px;
        }
        .invoice-table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            border-bottom: 2px solid #e2e8f0;
        }
        .invoice-status-stamp {
            border: 2px dashed #10b981;
            color: #059669;
            font-weight: 800;
            padding: 6px 18px;
            border-radius: 8px;
            display: inline-block;
            font-size: 15px;
            letter-spacing: 1px;
            transform: rotate(-3deg);
        }
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            .app-sidebar, .app-header, .page-header, .btn-no-print, .main-footer {
                display: none !important;
            }
            .app-content {
                margin: 0 !important;
                padding: 0 !important;
            }
            .invoice-box {
                border: none !important;
                box-shadow: none !important;
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Top Print Actions Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 btn-no-print" style="max-width: 900px; margin: 0 auto 20px auto;">
        <div>
            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-secondary font-weight-bold">
                <i class="fa fa-arrow-right ml-1"></i>الرجوع لتفاصيل الحجز
            </a>
        </div>
        <div>
            <button type="button" onclick="window.print()" class="btn btn-primary font-weight-bold px-4 shadow-sm">
                <i class="fa fa-print ml-2"></i>طباعة الفاتورة (Print / PDF)
            </button>
        </div>
    </div>

    <!-- Printable Invoice Container -->
    <div class="invoice-box">
        <!-- Invoice Header -->
        <div class="d-flex align-items-center justify-content-between border-bottom pb-4 mb-4">
            <div class="d-flex align-items-center">
                <img src="{{ get_file($setting?->logo) }}" style="max-height: 60px; max-width: 160px; object-fit: contain;" alt="logo" class="ml-3">
                <div>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $setting?->name_ar ?? 'بن هادي لتأجير السيارات' }}</h3>
                    <p class="text-muted font-size-12 mb-0">لتأجير السيارات وخدمات التنقل الذكي</p>
                    <span class="text-muted font-size-11" dir="ltr">{{ $setting?->email ?? '' }}</span>
                </div>
            </div>

            <div class="text-left" dir="ltr">
                <div class="invoice-status-stamp mb-2">
                    @php $statusVal = $booking->status instanceof \App\Enums\Booking\BookingStatusEnum ? $booking->status->value : (string) $booking->status; @endphp
                    @if($statusVal === 'completed')
                        COMPLETED / مكتمل
                    @elseif($statusVal === 'active')
                        ACTIVE / جاري
                    @elseif($statusVal === 'cancelled')
                        CANCELLED / ملغي
                    @else
                        PENDING / قيد الانتظار
                    @endif
                </div>
                <div class="font-size-13 text-muted"><strong>INVOICE #:</strong> {{ $booking->booking_number }}</div>
                <div class="font-size-13 text-muted"><strong>DATE:</strong> {{ $booking->created_at?->format('Y-m-d') }}</div>
            </div>
        </div>

        <!-- Parties Information -->
        <div class="row mb-4">
            <div class="col-6">
                <h6 class="font-weight-bold text-primary mb-2"><i class="fa fa-user ml-1"></i>بيانات العميل المستأجر (Billed To):</h6>
                <div class="font-size-14 text-dark font-weight-bold mb-1">{{ $booking->user?->name ?? '-' }}</div>
                <div class="font-size-13 text-muted mb-1"><i class="fa fa-phone ml-1"></i><span dir="ltr">{{ $booking->user?->phone ?? '-' }}</span></div>
                @if($booking->user?->email)
                    <div class="font-size-13 text-muted mb-1"><i class="fa fa-envelope-o ml-1"></i>{{ $booking->user->email }}</div>
                @endif
                <div class="font-size-13 text-muted mb-1">
                    <strong>رقم الهوية / الإقامة / الحدود:</strong> {{ $booking->user?->id_number ?: ($booking->user?->border_entry_number ?: '-') }}
                </div>
                <div class="font-size-13 text-muted">
                    <strong>رقم رخصة القيادة:</strong> {{ $booking->user?->driving_license_number ?: ($booking->user?->license_number ?: '-') }}
                </div>
            </div>

            <div class="col-6">
                <h6 class="font-weight-bold text-primary mb-2"><i class="fa fa-calendar ml-1"></i>بيانات مدة الإيجار والفروع:</h6>
                <div class="font-size-13 text-muted mb-1">
                    <strong>فرع الاستلام:</strong> {{ $booking->pickupBranch?->name_ar ?? '-' }} ({{ $booking->pickupBranch?->city?->name_ar ?? '-' }})
                </div>
                <div class="font-size-13 text-muted mb-1">
                    <strong>موعد الاستلام:</strong> {{ $booking->pickup_at?->format('Y-m-d H:i') ?? '-' }}
                </div>
                <div class="font-size-13 text-muted mb-1">
                    <strong>فرع التسليم:</strong> {{ $booking->returnBranch?->name_ar ?? '-' }} ({{ $booking->returnBranch?->city?->name_ar ?? '-' }})
                </div>
                <div class="font-size-13 text-muted mb-1">
                    <strong>موعد التسليم:</strong> {{ $booking->return_at?->format('Y-m-d H:i') ?? '-' }}
                </div>
                <div class="font-size-13 text-dark font-weight-bold mt-2">
                    <strong>إجمالي المدة:</strong> {{ $booking->total_days }} يوم
                </div>
            </div>
        </div>

        <!-- Vehicle Details Banner -->
        <div class="p-3 rounded bg-light border mb-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                @php
                    $defaultCarImg = asset('Admin/imgs/default.jpg');
                    $carImg = $booking->car?->primaryImage?->image_path ? get_file($booking->car->primaryImage->image_path) : $defaultCarImg;
                @endphp
                <img src="{{ $carImg }}" style="width: 70px; height: 48px; object-fit: cover; border-radius: 6px;" class="ml-3"
                    onerror="if (this.src !== '{{ $defaultCarImg }}') { this.src = '{{ $defaultCarImg }}'; }" alt="car">
                <div>
                    <h5 class="font-weight-bold text-dark mb-0">{{ $booking->car?->name_ar ?? 'سيارة محذوفة' }}</h5>
                    <span class="text-muted font-size-13" dir="ltr">({{ $booking->car?->name_en ?? '' }})</span>
                </div>
            </div>
            <div class="text-left font-size-13 text-muted">
                <div><strong>الماركة:</strong> {{ $booking->car?->brand?->name_ar ?? '-' }}</div>
                <div><strong>سنة الصنع:</strong> {{ $booking->car?->modelYear?->year ?? '-' }}</div>
            </div>
        </div>

        <!-- Billing Table -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered invoice-table mb-0 text-center">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>بيان الخدمة / السيارة</th>
                        <th>السعر اليومي</th>
                        <th>عدد الأيام</th>
                        <th>المجموع الفرعي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td class="text-right font-weight-bold">
                            تأجير سيارة: {{ $booking->car?->name_ar }} ({{ $booking->car?->name_en }})
                        </td>
                        <td>{{ number_format((float) $booking->daily_price, 2) }} ر.س</td>
                        <td>{{ $booking->total_days }} يوم</td>
                        <td class="font-weight-bold">{{ number_format((float) $booking->subtotal, 2) }} ر.س</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Totals Summary Table -->
        <div class="row justify-content-end mb-4">
            <div class="col-md-5">
                <table class="table table-sm table-borderless font-size-14 mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted py-2">المجموع الفرعي (Subtotal):</td>
                            <td class="text-left font-weight-bold py-2">{{ number_format((float) $booking->subtotal, 2) }} ر.س</td>
                        </tr>
                        @if((float) $booking->discount > 0)
                            <tr>
                                <td class="text-danger py-2">
                                    الخصم (Discount) @if($booking->coupon_code) [{{ $booking->coupon_code }}] @endif:
                                </td>
                                <td class="text-left font-weight-bold text-danger py-2">- {{ number_format((float) $booking->discount, 2) }} ر.س</td>
                            </tr>
                        @endif
                        <tr class="border-top">
                            <td class="font-weight-bold text-dark font-size-16 py-3">المبلغ الإجمالي (Total):</td>
                            <td class="text-left font-weight-bold text-success font-size-18 py-3">{{ number_format((float) $booking->total, 2) }} ر.س</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($booking->notes)
            <div class="p-3 rounded bg-light border mb-4 font-size-13">
                <strong class="d-block mb-1 text-muted">ملاحظات:</strong>
                <p class="mb-0 text-dark">{{ $booking->notes }}</p>
            </div>
        @endif

        <!-- Invoice Footer & Signatures -->
        <div class="row border-top pt-4 mt-4 font-size-13 text-center">
            <div class="col-6">
                <span class="text-muted d-block mb-4">توقيع المستأجر / العميل</span>
                <div style="border-bottom: 1px dashed #cbd5e1; width: 60%; margin: 0 auto;"></div>
            </div>
            <div class="col-6">
                <span class="text-muted d-block mb-4">ختم وتوقيع الشركة المسؤولة</span>
                <div style="border-bottom: 1px dashed #cbd5e1; width: 60%; margin: 0 auto;"></div>
            </div>
        </div>

        <div class="text-center font-size-11 text-muted border-top pt-3 mt-4">
            شكراً لتعاملكم مع {{ $setting?->name_ar ?? 'بن هادي' }} — هذه الفاتورة تم إصدارها إلكترونياً وتعتبر ملزمة لكافة شروط عقد التأجير.
        </div>
    </div>
@endsection
