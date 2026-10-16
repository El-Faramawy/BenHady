@extends('layouts.admin.app')
@section('page_title')
    الأسئلة الشائعة
@endsection
@section('content')
    <x-list.card>
        <x-list.card-header name="الأسئلة الشائعة" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">#</th>
            <th class="text-white">السؤال بالعربية</th>
            <th class="text-white">السؤال بالإنجليزية</th>
            <th class="text-white">الترتيب</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="الأسئلة الشائعة" :save_button="true"></x-list.modal>
@endsection
@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'id', name: 'id'},
            {data: 'question_ar', name: 'question_ar'},
            {data: 'question_en', name: 'question_en'},
            {data: 'sort_order', name: 'sort_order'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'faqs'])
@endpush
