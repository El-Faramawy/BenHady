@extends('layouts.admin.app')
@section('page_title')
    الحجوزات
@endsection

@push('admin_css')
    <link href="{{url('Admin')}}/assets/plugins/select2/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <!-- Filter Tabs (All / Active / Closed) -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <ul class="nav booking-filter-tabs" id="bookingStatusTabs">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);" data-filter="all">
                        <i class="fe fe-list ml-1"></i>جميع الحجوزات
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);" data-filter="active">
                        <i class="fe fe-activity ml-1"></i>الحجوزات النشطة (قيد الانتظار والنشطة)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);" data-filter="closed">
                        <i class="fe fe-check-circle ml-1"></i>الحجوزات المنتهية (المكتملة والملغاة)
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <x-list.card>
        <x-list.card-header name="الحجوزات" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">رقم الحجز</th>
            <th class="text-white">العميل</th>
            <th class="text-white">السيارة</th>
            <th class="text-white">الفروع</th>
            <th class="text-white">فترة الحجز</th>
            <th class="text-white">المبلغ الإجمالي</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تاريخ الإنشاء</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="الحجوزات" :save_button="true"></x-list.modal>
@endsection

@push('admin_js')
    <script src="{{url('Admin')}}/assets/plugins/select2/select2.full.min.js"></script>
    <script>
        var currentStatusFilter = 'all';

        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'booking_number_badge', name: 'booking_number'},
            {data: 'user_info', name: 'user.name'},
            {data: 'car_info', name: 'car.name_ar'},
            {data: 'branches_formatted', name: 'pickupBranch.name_ar'},
            {data: 'period_formatted', name: 'pickup_at'},
            {data: 'pricing_formatted', name: 'total'},
            {data: 'status_badge', name: 'status'},
            {data: 'created_at_formatted', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        // Filter tab click handler
        $(document).on('click', '#bookingStatusTabs .nav-link', function () {
            $('#bookingStatusTabs .nav-link').removeClass('active');
            $(this).addClass('active');
            currentStatusFilter = $(this).data('filter');

            var newUrl = window.location.pathname + '?status_filter=' + currentStatusFilter;
            $('#exportexample').DataTable().ajax.url(newUrl).load();
        });

        // Initialize Select2 when create/edit form loads inside #Modal
        $(document).ajaxComplete(function(event, xhr, settings) {
            if (settings.url && (settings.url.indexOf('/bookings/create') !== -1 || settings.url.indexOf('/edit') !== -1)) {
                if ($.fn.select2) {
                    $('.select2-user').select2({
                        dropdownParent: $('#Modal'),
                        placeholder: 'ابحث باسم أو هاتف العميل...',
                        allowClear: true,
                        width: '100%',
                        dir: 'rtl'
                    });
                }
            }
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'bookings'])
@endpush
