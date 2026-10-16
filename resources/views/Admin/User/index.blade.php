@extends('layouts.admin.app')
@section('page_title') المستخدمين @endsection
@section('content')
<x-list.card>
    <x-list.card-header name="المستخدمين" :add_button="false" :delete_button="true"></x-list.card-header>
    <x-list.card-body :responsive="true">
        <th class="text-white"><input type="checkbox" id="master"></th>
        <th class="text-white">#</th>
        <th class="text-white">الصورة</th>
        <th class="text-white">الاسم</th>
        <th class="text-white">رقم الهاتف</th>
        <th class="text-white">المدينة</th>
        <th class="text-white">العمر</th>
        <th class="text-white">النقاط</th>
        <th class="text-white">النوع</th>
        <th class="text-white">الحالة</th>
        <th class="text-white">تحكم</th>
    </x-list.card-body>
</x-list.card>

@endsection
@push('admin_js')

    <script>
        var  columns =[
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'id', name: 'id'},
            {data: 'image', name: 'image'},
            {data: 'name', name: 'name'},
            {data: 'phone', name: 'phone'},
            {data: 'city', name: 'city'},
            {data: 'age', name: 'age'},
            {data: 'points', name: 'points'},
            {data: 'gender', name: 'gender'},
            {data: 'active', name: 'active'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax',['url'=>'users'])

@endpush
