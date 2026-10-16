@extends('layouts.admin.app')
@section('page_title')
    الفروع
@endsection
@section('content')
    <x-list.card>
        <x-list.card-header name="الفروع" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">#</th>
            <th class="text-white">المدينة</th>
            <th class="text-white">الاسم بالعربية</th>
            <th class="text-white">الاسم بالإنجليزية</th>
            <th class="text-white">الهاتف</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="الفروع" :save_button="true"></x-list.modal>
@endsection
@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'id', name: 'id'},
            {data: 'city_name', name: 'city_name'},
            {data: 'name_ar', name: 'name_ar'},
            {data: 'name_en', name: 'name_en'},
            {data: 'phone', name: 'phone'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'branches'])
@endpush
