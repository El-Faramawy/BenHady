@extends('layouts.admin.app')
@section('page_title')
    السيارات
@endsection

@push('admin_css')
    <link href="{{url('Admin')}}/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <x-list.card>
        <x-list.card-header name="السيارات" :add_button="true" :delete_button="true">
            <a href="{{ route('cars.details_catalog') }}" class="btn btn-info ml-2 font-weight-bold">
                <i class="fe fe-grid ml-1"></i>كتالوج وتفاصيل السيارات
            </a>
        </x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">الصورة</th>
            <th class="text-white">اسم السيارة</th>
            <th class="text-white">الماركة والقسم</th>
            <th class="text-white">الفرع</th>
            <th class="text-white">الموديل</th>
            <th class="text-white">السعر اليومي</th>
            <th class="text-white">المقاعد</th>
            <th class="text-white">حالة السيارة</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="السيارات" :save_button="true"></x-list.modal>
@endsection

@push('admin_js')
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/fileupload.js"></script>
    <script src="{{url('Admin')}}/assets/plugins/fileuploads/js/file-upload.js"></script>
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'primary_img', name: 'primary_img', orderable: false, searchable: false},
            {data: 'name_formatted', name: 'name_ar'},
            {data: 'brand_category', name: 'brand.name_ar'},
            {data: 'branch_name', name: 'branch.name_ar'},
            {data: 'model_year_val', name: 'modelYear.year'},
            {data: 'daily_price_formatted', name: 'daily_price'},
            {data: 'seats_formatted', name: 'seats'},
            {data: 'car_status_badge', name: 'status'},
            {data: 'is_active_badge', name: 'is_active'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        // Re-initialize dropify when modal opens
        $('#Modal').on('shown.bs.modal', function () {
            $('.dropify').dropify({
                messages: {
                    'default': 'اسحب وأفلت الصورة هنا أو اضغط للاختيار',
                    'replace': 'اسحب وأفلت أو اضغط للاستبدال',
                    'remove':  'حذف',
                    'error':   'حدث خطأ في الملف'
                }
            });
        });

        $(function () {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('create') === '1' || urlParams.get('action') === 'create') {
                setTimeout(function() {
                    $('#addBtn').trigger('click');
                }, 300);
            }
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'cars'])
@endpush
