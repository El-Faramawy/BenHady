@extends('layouts.admin.app')
@section('page_title')
    المميزات والخصائص
@endsection
@section('content')
    <x-list.card>
        <x-list.card-header name="المميزات والخصائص" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">#</th>
            <th class="text-white">الأيقونة</th>
            <th class="text-white">الاسم بالعربية</th>
            <th class="text-white">الاسم بالإنجليزية</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="المميزات والخصائص" :save_button="true"></x-list.modal>
@endsection
@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'id', name: 'id'},
            {data: 'icon_preview', name: 'icon_preview', orderable: false, searchable: false},
            {data: 'name_ar', name: 'name_ar'},
            {data: 'name_en', name: 'name_en'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'features'])
@endpush
