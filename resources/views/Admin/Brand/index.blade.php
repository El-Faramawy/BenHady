@extends('layouts.admin.app')
@section('page_title')
    الماركات
@endsection
@section('content')
    <x-list.card>
        <x-list.card-header name="الماركات" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">الشعار</th>
            <th class="text-white">الاسم</th>
            <th class="text-white">الترتيب</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="الماركات" :save_button="true"></x-list.modal>
@endsection
@push('admin_js')
    <script>
        var columns = [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'logo', name: 'logo', orderable: false, searchable: false },
            { data: 'name_ar', name: 'name_ar' },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'brands'])
@endpush