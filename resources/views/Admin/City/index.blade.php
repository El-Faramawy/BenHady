@extends('layouts.admin.app')
@section('page_title')
     المدن
@endsection
@section('content')
    <x-list.card>
    <x-list.card-header name="المدن" :add_button="true" :delete_button="true"></x-list.card-header>
    <x-list.card-body :responsive="true">
        <th class="text-white"><input type="checkbox" id="master"></th>
        <th class="text-white">#</th>
        <th class="text-white">الاسم</th>
        <th class="text-white">الكود</th>
        <th class="text-white">تحكم</th>
    </x-list.card-body>
</x-list.card>

<x-list.modal name="المدن" :save_button="true" ></x-list.modal>

@endsection
@push('admin_js')

    <script>
        var  columns =[
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'id', name: 'id'},
            {data: 'country', name: 'country'},
            {data: 'name_ar', name: 'name_ar'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax',['url'=>'cities'])

@endpush
