@extends('layouts.admin.app')

@section('page_title')
    لوحة التحكم
@endsection

@push('admin_css')
    @include('Admin.parts.index-css')
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Hero Welcome Banner -->
        <div class="dashboard-hero">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="hero-badge">
                            <span class="hero-pulse"></span>
                            لوحة المتابعة المباشرة
                        </span>
                        <span class="text-muted small">{{ now()->translatedFormat('l، d F Y') }}</span>
                    </div>
                    <h1 class="hero-title">
                        مرحباً بك، {{ auth('admin')->user()->name ?? 'مدير النظام' }} 👋
                    </h1>
                    <p class="hero-subtitle">
                        نظرة شاملة على مؤشرات الأداء، حركة الحجوزات، وأسطول السيارات في منصة بن هادي
                    </p>
                </div>
                <div class="d-flex flex-wrap align-items-center mt-3 mt-md-0" style="gap: 6px;">
                    <a href="{{ route('cars.index') }}" class="quick-action-btn quick-action-primary">
                        <i class="fe fe-truck"></i>
                        <span>إدارة أسطول السيارات</span>
                    </a>
                    <a href="{{ route('bookings.index') }}" class="quick-action-btn quick-action-light">
                        <i class="fe fe-clipboard"></i>
                        <span>إدارة الحجوزات</span>
                    </a>
                    <a href="{{ route('cars.details_catalog') }}" class="quick-action-btn quick-action-light">
                        <i class="fe fe-layers"></i>
                        <span>كتالوج السيارات</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top 4 KPI Cards -->
<div class="row">
    <!-- Total Revenue -->
    <div class="col-sm-6 col-lg-3">
        <div class="kpi-card kpi-revenue p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">إجمالي الإيرادات</div>
                    <div class="kpi-value text-success">
                        {{ number_format($stats['totals']['revenue'], 2) }}
                        <small style="font-size: 14px; font-weight: 600;">ر.س</small>
                    </div>
                    <p class="kpi-subtext">
                        <i class="fe fe-check-circle text-success"></i>
                        <span>الحجوزات النشطة والمكتملة</span>
                    </p>
                </div>
                <div class="kpi-icon-wrap icon-revenue">
                    <i class="fe fe-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Bookings -->
    <div class="col-sm-6 col-lg-3">
        <div class="kpi-card kpi-bookings p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">إجمالي الحجوزات</div>
                    <div class="kpi-value">
                        {{ number_format($stats['totals']['bookings']) }}
                    </div>
                    <p class="kpi-subtext">
                        <span class="badge badge-success px-2 py-1" style="font-size: 10px;">{{ $stats['booking_statuses']['active'] ?? 0 }} نشطة</span>
                        <span class="badge badge-warning px-2 py-1" style="font-size: 10px;">{{ $stats['booking_statuses']['pending'] ?? 0 }} انتظار</span>
                    </p>
                </div>
                <div class="kpi-icon-wrap icon-bookings">
                    <i class="fe fe-clipboard"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Fleet Cars -->
    <div class="col-sm-6 col-lg-3">
        <div class="kpi-card kpi-cars p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">أسطول السيارات</div>
                    <div class="kpi-value">
                        {{ number_format($stats['totals']['cars']) }}
                    </div>
                    <p class="kpi-subtext">
                        <span class="badge badge-primary px-2 py-1" style="font-size: 10px; background-color: #76C419 !important;">{{ $stats['car_statuses']['available'] ?? 0 }} متاحة</span>
                        <span class="badge badge-info px-2 py-1" style="font-size: 10px;">{{ $stats['car_statuses']['rented'] ?? 0 }} مؤجرة</span>
                    </p>
                </div>
                <div class="kpi-icon-wrap icon-cars">
                    <i class="fa fa-car"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="col-sm-6 col-lg-3">
        <div class="kpi-card kpi-users p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">العملاء المسجلين</div>
                    <div class="kpi-value">
                        {{ number_format($stats['totals']['users']) }}
                    </div>
                    <p class="kpi-subtext">
                        <i class="fe fe-map-pin text-warning"></i>
                        <span>عبر {{ $stats['totals']['branches'] }} فرعاً معتمداً</span>
                    </p>
                </div>
                <div class="kpi-icon-wrap icon-users">
                    <i class="fe fe-users"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Charts Section -->
<div class="row">
    <!-- Monthly Revenue & Bookings Area Chart -->
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="dashboard-card-header d-flex align-items-center justify-content-between flex-wrap pb-2">
                <h3 class="dashboard-card-title mb-0">
                    <i class="fe fe-trending-up"></i>
                    <span>حركة الإيرادات والحجوزات (آخر 6 أشهر)</span>
                </h3>
                <span class="badge-primary-light px-3 py-1 mt-1 mt-sm-0">مؤشر مباشر</span>
            </div>
            <div class="card-body pt-3 pb-3 px-3">
                <div id="monthlyRevenueChart" style="min-height: 280px;"></div>
                
                <!-- Chart Legend Pills -->
                <div class="d-flex flex-wrap align-items-center justify-content-center mt-2" style="gap: 16px;">
                    <button type="button" class="revenue-legend-btn px-3 py-1 rounded-pill" data-series="الإيرادات بالريال" title="إظهار / إخفاء الإيرادات بالريال" style="background: #F5FDEA; font-size: 12px; font-weight: 700; color: #2E5A1C; border: 1px solid #D8F2B5;">
                        <span class="legend-indicator rounded-circle ml-2" style="width: 10px; height: 10px; background-color: #76C419; display: inline-block;"></span>
                        <span>الإيرادات بالريال</span>
                    </button>
                    <button type="button" class="revenue-legend-btn px-3 py-1 rounded-pill" data-series="عدد الحجوزات" title="إظهار / إخفاء عدد الحجوزات" style="background: #E0F2FE; font-size: 12px; font-weight: 700; color: #0369a1; border: 1px solid #bae6fd;">
                        <span class="legend-indicator rounded ml-2" style="width: 10px; height: 10px; background-color: #0284c7; display: inline-block;"></span>
                        <span>عدد الحجوزات</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Fleet Status Donut Chart & Operational Ratios -->
    <div class="col-lg-4">
        <div class="dashboard-card h-100">
            <div class="dashboard-card-header">
                <h3 class="dashboard-card-title">
                    <i class="fe fe-pie-chart"></i>
                    <span>حالة أسطول السيارات</span>
                </h3>
            </div>
            <div class="card-body">
                <div id="carStatusDonutChart" style="height: 220px;"></div>
                
                <!-- Status Legend Pills -->
                <div class="d-flex flex-wrap align-items-center justify-content-center mt-3 pb-3 border-bottom" style="gap: 8px;">
                    <button type="button" class="donut-legend-btn px-2 py-1 rounded" data-status="متاحة" title="إظهار / إخفاء حالة متاحة" style="background: #F5FDEA; font-size: 11.5px; font-weight: 700; color: #2E5A1C; border: 1px solid #D8F2B5;">
                        <span class="rounded-circle ml-1" style="width: 8px; height: 8px; background-color: #76C419; display: inline-block;"></span>
                        <span>متاحة ({{ $stats['car_statuses']['available'] ?? 0 }})</span>
                    </button>
                    <button type="button" class="donut-legend-btn px-2 py-1 rounded" data-status="مؤجرة" title="إظهار / إخفاء حالة مؤجرة" style="background: #E0F2FE; font-size: 11.5px; font-weight: 700; color: #0369a1; border: 1px solid #bae6fd;">
                        <span class="rounded-circle ml-1" style="width: 8px; height: 8px; background-color: #0284c7; display: inline-block;"></span>
                        <span>مؤجرة ({{ $stats['car_statuses']['rented'] ?? 0 }})</span>
                    </button>
                    <button type="button" class="donut-legend-btn px-2 py-1 rounded" data-status="صيانة" title="إظهار / إخفاء حالة صيانة" style="background: #FEF3C7; font-size: 11.5px; font-weight: 700; color: #b45309; border: 1px solid #fde68a;">
                        <span class="rounded-circle ml-1" style="width: 8px; height: 8px; background-color: #f59e0b; display: inline-block;"></span>
                        <span>صيانة ({{ $stats['car_statuses']['maintenance'] ?? 0 }})</span>
                    </button>
                    <button type="button" class="donut-legend-btn px-2 py-1 rounded" data-status="محجوزة" title="إظهار / إخفاء حالة محجوزة" style="background: #F3E8FF; font-size: 11.5px; font-weight: 700; color: #6d28d9; border: 1px solid #e9d5ff;">
                        <span class="rounded-circle ml-1" style="width: 8px; height: 8px; background-color: #7c3aed; display: inline-block;"></span>
                        <span>محجوزة ({{ $stats['car_statuses']['reserved'] ?? 0 }})</span>
                    </button>
                </div>
                
                <div class="pt-3">
                    <!-- Utilization Rate -->
                    <div class="metric-progress-item">
                        <div class="metric-progress-header">
                            <span>معدل تشغيل الأسطول (مؤجرة)</span>
                            <span class="text-success font-weight-bold">{{ $stats['rates']['utilization_rate'] }}%</span>
                        </div>
                        <div class="progress-custom">
                            <div class="progress-bar progress-bar-figma" role="progressbar" style="width: {{ min(100, $stats['rates']['utilization_rate']) }}%;"></div>
                        </div>
                    </div>

                    <!-- Completion Rate -->
                    <div class="metric-progress-item">
                        <div class="metric-progress-header">
                            <span>نسبة إتمام الحجوزات</span>
                            <span class="text-primary font-weight-bold">{{ $stats['rates']['completion_rate'] }}%</span>
                        </div>
                        <div class="progress-custom">
                            <div class="progress-bar" role="progressbar" style="width: {{ min(100, $stats['rates']['completion_rate']) }}%; background-color: #0284c7;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lower Data Section: Latest Bookings & Top Cars -->
<div class="row">
    <!-- Latest Bookings Table -->
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h3 class="dashboard-card-title">
                    <i class="fe fe-clock"></i>
                    <span>أحدث طلبات الحجز</span>
                </h3>
                <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-primary-light">
                    عرض الكل ({{ $stats['totals']['bookings'] }})
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dashboard mb-0">
                        <thead>
                            <tr>
                                <th>رقم الحجز</th>
                                <th>العميل</th>
                                <th>السيارة</th>
                                <th>تاريخ الاستلام</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th class="text-center">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['latest_bookings'] as $booking)
                                <tr>
                                    <td>
                                        <span class="font-weight-bold text-dark">#{{ $booking->booking_number }}</span>
                                    </td>
                                    <td>
                                        <div class="font-weight-600 text-dark">{{ $booking->user?->name ?? 'عميل غير مسجل' }}</div>
                                        <div class="small text-muted" dir="ltr">{{ $booking->user?->phone ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($booking->car?->primaryImage)
                                                <img src="{{ get_file($booking->car->primaryImage->image_path) }}" class="car-thumb-sm" alt="car">
                                            @else
                                                <div class="car-thumb-sm d-flex align-items-center justify-content-center text-muted">
                                                    <i class="fa fa-car"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-600 text-dark">{{ $booking->car?->name ?? 'سيارة' }}</div>
                                                <small class="text-muted">{{ $booking->car?->brand?->name ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-dark">{{ $booking->pickup_at?->format('Y-m-d') ?? '-' }}</div>
                                        <small class="text-muted">{{ $booking->pickup_at?->format('h:i A') ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-success">{{ number_format((float)$booking->total, 2) }} ر.س</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusVal = $booking->status instanceof \App\Enums\Booking\BookingStatusEnum ? $booking->status->value : (string) $booking->status;
                                        @endphp
                                        <span class="status-badge {{ $statusVal }}">
                                            {{ $booking->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-action-view" title="عرض التفاصيل">
                                            <i class="fe fe-eye"></i>
                                        </a>
                                        <a href="{{ route('bookings.invoice', $booking->id) }}" class="btn btn-sm btn-action-details" title="الفاتورة">
                                            <i class="fe fe-file-text"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fe fe-info ml-1"></i> لا توجد حجوزات مسجلة حالياً
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Rented Cars & Quick Access -->
    <div class="col-lg-4">
        <!-- Top Rented Cars Card -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h3 class="dashboard-card-title">
                    <i class="fe fe-award"></i>
                    <span>السيارات الأكثر طلباً</span>
                </h3>
                <a href="{{ route('cars.index') }}" class="btn btn-sm btn-primary-light">
                    الأسطول
                </a>
            </div>
            <div class="card-body p-3">
                @forelse($stats['top_cars'] as $index => $car)
                    <div class="car-rank-item">
                        <div class="car-rank-badge">
                            {{ $index + 1 }}
                        </div>
                        @if($car->primaryImage)
                            <img src="{{ get_file($car->primaryImage->image_path) }}" class="car-thumb-sm" alt="{{ $car->name }}">
                        @else
                            <div class="car-thumb-sm d-flex align-items-center justify-content-center text-muted">
                                <i class="fa fa-car"></i>
                            </div>
                        @endif
                        <div class="car-rank-info">
                            <div class="car-rank-name">{{ $car->name }}</div>
                            <div class="car-rank-meta">
                                <span>{{ $car->brand?->name }}</span> • 
                                <span class="text-success font-weight-600">{{ number_format((float)$car->daily_price, 0) }} ر.س/يوم</span>
                            </div>
                        </div>
                        <div class="car-rank-count text-left" dir="ltr">
                            {{ $car->bookings_count }}
                            <small class="text-muted d-block" style="font-size: 10px;">حجز</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        لا توجد سيارات محجوزة بعد
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Branches Network Summary -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h3 class="dashboard-card-title">
                    <i class="fe fe-map-pin"></i>
                    <span>الفروع والمدن</span>
                </h3>
                <a href="{{ route('branches.index') }}" class="btn btn-sm btn-primary-light">
                    الفروع ({{ $stats['totals']['branches'] }})
                </a>
            </div>
            <div class="card-body p-3">
                @forelse($stats['branches_summary'] as $branch)
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                        <div>
                            <div class="font-weight-600 text-dark" style="font-size: 13px;">{{ $branch->name_ar }}</div>
                            <small class="text-muted"><i class="fe fe-map ml-1"></i>{{ $branch->city?->name_ar ?? '-' }}</small>
                        </div>
                        <span class="badge badge-primary-light">
                            {{ $branch->cars_count }} سيارة
                        </span>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted small">
                        لا توجد فروع مسجلة
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin_js')
<script>
$(document).ready(function () {
    // 1. Monthly Revenue & Bookings Apex Area Chart
    const monthlyLabels = @json($stats['monthly_chart']['labels']);
    const revenueData = @json($stats['monthly_chart']['revenue']);
    const bookingsData = @json($stats['monthly_chart']['bookings']);

    const revenueChartOptions = {
        series: [
            {
                name: 'الإيرادات بالريال',
                type: 'area',
                data: revenueData
            },
            {
                name: 'عدد الحجوزات',
                type: 'column',
                data: bookingsData
            }
        ],
        chart: {
            height: 275,
            type: 'line',
            toolbar: { show: false },
            fontFamily: 'Cairo, sans-serif',
            dropShadow: {
                enabled: true,
                top: 4,
                left: 0,
                blur: 8,
                opacity: 0.08
            }
        },
        colors: ['#76C419', '#0284c7'],
        stroke: {
            curve: 'smooth',
            width: [3, 0]
        },
        fill: {
            type: ['gradient', 'solid'],
            gradient: {
                shade: 'light',
                type: "vertical",
                shadeIntensity: 0.5,
                gradientToColors: ['#87C826'],
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            type: 'category',
            categories: monthlyLabels,
            tickPlacement: 'between',
            labels: {
                rotate: 0,
                show: true,
                trim: false,
                hideOverlappingLabels: false,
                style: {
                    colors: '#64748b',
                    fontSize: '12px',
                    fontFamily: 'Cairo, sans-serif',
                    fontWeight: 600
                }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: [
            {
                seriesName: 'الإيرادات بالريال',
                show: true,
                labels: {
                    offsetX: -5,
                    formatter: function (val) {
                        if (val >= 1000000) {
                            return (val / 1000000).toFixed(1) + ' م';
                        }
                        if (val >= 1000) {
                            return (val / 1000).toLocaleString() + ' ألف';
                        }
                        return Math.round(val);
                    },
                    style: { colors: '#64748b', fontSize: '11px', fontFamily: 'Cairo, sans-serif' }
                }
            },
            {
                seriesName: 'عدد الحجوزات',
                show: false,
                opposite: true
            }
        ],
        legend: {
            show: false
        },
        tooltip: {
            shared: true,
            intersect: false,
            theme: 'light',
            y: {
                formatter: function (y, { seriesIndex }) {
                    if (typeof y !== "undefined") {
                        return seriesIndex === 0 ? y.toLocaleString() + " ر.س" : y + " حجز";
                    }
                    return y;
                }
            }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4,
            padding: {
                top: 15,
                right: 25,
                bottom: 5,
                left: 35
            }
        }
    };

    const revenueChart = new ApexCharts(document.querySelector("#monthlyRevenueChart"), revenueChartOptions);
    revenueChart.render();

    // 2. Car Fleet Status Donut Chart
    const carStatuses = @json($stats['car_statuses']);
    const donutLabels = ['متاحة', 'مؤجرة', 'صيانة', 'محجوزة'];
    const donutSeries = [
        carStatuses['available'] || 0,
        carStatuses['rented'] || 0,
        carStatuses['maintenance'] || 0,
        carStatuses['reserved'] || 0
    ];

    const totalCarsCount = donutSeries.reduce((a, b) => a + b, 0);

    const donutChartOptions = {
        series: totalCarsCount > 0 ? donutSeries : [1],
        labels: totalCarsCount > 0 ? donutLabels : ['لا توجد سيارات'],
        chart: {
            type: 'donut',
            height: 220,
            fontFamily: 'Cairo, sans-serif'
        },
        colors: totalCarsCount > 0 ? ['#76C419', '#0284c7', '#f59e0b', '#7c3aed'] : ['#e2e8f0'],
        legend: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'إجمالي الأسطول',
                            fontFamily: 'Cairo, sans-serif',
                            fontSize: '13px',
                            fontWeight: 600,
                            color: '#64748b',
                            formatter: function () {
                                return totalCarsCount;
                            }
                        },
                        value: {
                            show: true,
                            fontFamily: 'Cairo, sans-serif',
                            fontSize: '22px',
                            fontWeight: 700,
                            color: '#1e293b',
                            offsetY: 6,
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            theme: 'light'
        }
    };

    const carDonutChart = new ApexCharts(document.querySelector("#carStatusDonutChart"), donutChartOptions);
    carDonutChart.render();

    // 3. Interactive Legend Click Handlers
    $(document).on('click', '.revenue-legend-btn', function (e) {
        e.preventDefault();
        const seriesName = $(this).data('series');
        if (revenueChart) {
            revenueChart.toggleSeries(seriesName);
            $(this).toggleClass('is-collapsed');
        }
    });

    $(document).on('click', '.donut-legend-btn', function (e) {
        e.preventDefault();
        const statusLabel = $(this).data('status');
        if (carDonutChart && totalCarsCount > 0) {
            carDonutChart.toggleSeries(statusLabel);
            $(this).toggleClass('is-collapsed');
        }
    });
});
</script>
@endpush