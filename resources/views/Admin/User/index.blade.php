@extends('layouts.admin.app')
@section('page_title')
    المستخدمين
@endsection
@section('content')
    <x-list.card>
        <x-list.card-header name="المستخدمين" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">الاسم والبريد</th>
            <th class="text-white">الهاتف</th>
            <th class="text-white">نوع المستخدم</th>
            <th class="text-white">التوثيق</th>
            <th class="text-white">المحفظة</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تاريخ التسجيل</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="المستخدمين" :save_button="true"></x-list.modal>

    <!-- Modal for User Profile & Details -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 92vw; width: 1300px;" role="document">
            <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header bg-primary text-white d-flex align-items-center justify-content-between py-3 px-4">
                    <h5 class="modal-title font-weight-bold mb-0 text-white" id="userDetailsModalTitle">
                        <i class="fa fa-user-circle-o ml-2"></i>ملف وتفاصيل العميل
                    </h5>
                    <div class="btn btn-sm btn-icon btn-active-color-primary text-white close_user_modal" style="cursor: pointer" data-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.8" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"/>
                                <rect opacity="0.8" x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body p-4" id="user-details-load" style="background: #f8fafc; max-height: 85vh; overflow-y: auto;">
                    <!-- Dynamic Ajax Content -->
                </div>
                <div class="modal-footer bg-white border-top py-2 px-4">
                    <button type="button" class="btn btn-light px-4 close_user_modal" data-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'name_email', name: 'name'},
            {data: 'phone_formatted', name: 'phone'},
            {data: 'type_badge', name: 'type'},
            {data: 'verifications', name: 'verifications', orderable: false, searchable: false},
            {data: 'wallet_formatted', name: 'wallet'},
            {data: 'status', name: 'status'},
            {data: 'created_at_formatted', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        $(document).on('click', '.user-details-btn', function () {
            var id = $(this).data('id');
            $('#user-details-load').html(loader);
            $('#userDetailsModal').modal('show');
            var url = "{{ route('users.show', ':id') }}".replace(':id', id);
            setTimeout(function () {
                $('#user-details-load').load(url);
            }, 100);
        });

        $(document).on('click', '.close_user_modal, #userDetailsModal [data-dismiss="modal"]', function (e) {
            e.preventDefault();
            $('#userDetailsModal').modal('hide');
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'users'])
@endpush
