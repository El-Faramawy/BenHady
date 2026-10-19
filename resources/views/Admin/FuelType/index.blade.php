@extends('layouts.admin.app')
@section('page_title')
    أنواع الوقود
@endsection
@section('content')
    <x-list.card>
        <x-list.card-header name="أنواع الوقود" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">الاسم</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="أنواع الوقود" :save_button="true"></x-list.modal>
@endsection
@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'name_ar', name: 'name_ar'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'fuel_types'])
@endpush
