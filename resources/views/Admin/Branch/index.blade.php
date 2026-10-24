@extends('layouts.admin.app')
@section('page_title')
    الفروع
@endsection
@section('content')
    <x-list.card>
        <x-list.card-header name="الفروع" :add_button="true" :delete_button="true"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white"><input type="checkbox" id="master"></th>
            <th class="text-white">المدينة</th>
            <th class="text-white">الاسم</th>
            <th class="text-white">الهاتف</th>
            <th class="text-white">الحالة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="الفروع" :save_button="true"></x-list.modal>

    <!-- Modal for Branch Working Hours -->
    <div class="modal fade" id="workingHoursModal" tabindex="-1" role="dialog" aria-labelledby="workingHoursModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 92vw; width: 1350px;" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="workingHoursModalTitle">
                        <i class="fa fa-clock-o ml-2"></i>مواعيد عمل الفرع
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4" id="working-hours-load">
                    <!-- Dynamic Ajax Content -->
                </div>
            </div>
        </div>
    </div>
@endsection
@push('admin_js')
    <script>
        var columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'city_name', name: 'city_name'},
            {data: 'name_ar', name: 'name_ar'},
            {data: 'phone', name: 'phone'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        $(document).on('click', '.working-hours-btn', function () {
            var id = $(this).data('id');
            $('#working-hours-load').html(loader);
            $('#workingHoursModal').modal('show');
            var url = "{{ route('branches.working_hours.edit', ':id') }}".replace(':id', id);
            setTimeout(function () {
                $('#working-hours-load').load(url);
            }, 100);
        });

        $(document).off('submit', '#workingHoursForm').on('submit', '#workingHoursForm', function (e) {
            e.preventDefault();
            var $form = $(this);

            if ($form.data('is_submitting')) {
                return false;
            }
            $form.data('is_submitting', true);

            var $btn = $form.find(':submit');
            var url = $form.attr('action');
            var formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#global-loader').addClass('active');
                    $btn.prop('disabled', true);
                },
                success: function (data) {
                    $('#global-loader').removeClass('active');
                    $btn.prop('disabled', false);
                    $form.data('is_submitting', false);
                    $('#workingHoursModal').modal('hide');
                    my_toaster(data.message || 'تم التعديل بنجاح');
                },
                error: function (data) {
                    $('#global-loader').removeClass('active');
                    $btn.prop('disabled', false);
                    $form.data('is_submitting', false);
                    handleAjaxError(data);
                }
            });
        });
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'branches'])
@endpush
