<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js?{{time()}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js?{{time()}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js?{{time()}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    var loader = ` <div class="linear-background">
                            <div class="inter-crop"></div>
                            <div class="inter-right--top"></div>
                            <div class="inter-right--bottom"></div>
                        </div>
        `;
    $(function () {
        var table = $("#exportexample").DataTable({
            bLengthChange: true,
            serverSide: true,
            ajax: window.location.href,
            columns: columns,
            // order: [
            //     [0, "desc"]
            // ],
            "language": {
                paginate: {
                    previous: "<i class='simple-icon-arrow-left'></i>",
                    next: "<i class='simple-icon-arrow-right'></i>"
                },
                "sProcessing": "جاري التحميل ..",
                "sLengthMenu": "اظهار _MENU_ سجل",
                "sZeroRecords": "لا يوجد نتائج",
                "sInfo": "اظهار _START_ الى  _END_ من _TOTAL_ سجل",
                "sInfoEmpty": "لا نتائج",
                "sInfoFiltered": "للبحث",
                "sSearch": "بحث :    ",
                "oPaginate": {
                    "sPrevious": "السابق",
                    "sNext": "التالي",
                }
            },
            dom: 'lBfrtip',
            buttons: [
                'colvis',
                'excel',
                'print',
                'copy',
                'csv',
            ],
            searching: true,
            ordering:false,
            destroy: true,
            info: false,
            lengthChange: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options
            pageLength: 10,

        });

        table.buttons().container().appendTo( '#exportexample_wrapper .col-md-6:eq(0)' );

    });

    $('#exportexample').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    });


    function handleAjaxError(data) {
        if (data.status === 500) {
            my_toaster('هناك خطأ ما', 'error');
            return;
        }
        if (data.status === 422) {
            var res = data.responseJSON || (data.responseText ? $.parseJSON(data.responseText) : {});
            var hasErrors = false;
            if (res.errors) {
                if (Array.isArray(res.errors)) {
                    $.each(res.errors, function(k, v) { my_toaster(v, 'error'); hasErrors = true; });
                } else if ($.isPlainObject(res.errors)) {
                    $.each(res.errors, function(key, val) {
                        if (Array.isArray(val)) {
                            $.each(val, function(i, msg) { my_toaster(msg, 'error'); hasErrors = true; });
                        } else if (typeof val === 'string') {
                            my_toaster(val, 'error');
                            hasErrors = true;
                        }
                    });
                }
            }
            if (!hasErrors && res.message) {
                my_toaster(res.message, 'error');
            }
        }
    }

    $(document).off('click', '#addBtn').on('click', '#addBtn', function () {
        $('#form-load').html(loader);
        $('#Modal').modal('show');

        setTimeout(function (){
            $('#form-load').load("{{route("$url.create")}}");
        }, 100);
    });

    $(document).off('submit', 'form#form').on('submit', 'form#form', function (e) {
        e.preventDefault();
        var $form = $(this);

        if ($form.data('is_submitting')) {
            return false;
        }
        $form.data('is_submitting', true);

        var $btn = $('#submit');
        var formData = new FormData(this);
        var url = $form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#global-loader').show();
                $btn.prop('disabled', true);
            },
            complete: function () {
            },
            success: function (data) {
                window.setTimeout(function () {
                    $('#global-loader').hide();
                    $btn.prop('disabled', false);
                    $form.data('is_submitting', false);
                    $('#Modal').modal('hide');
                    my_toaster(data.message);
                    $('#exportexample').DataTable().ajax.reload(null, false);
                }, 100);
            },
            error: function (data) {
                $('#global-loader').hide();
                $btn.prop('disabled', false);
                $form.data('is_submitting', false);
                $('#form-load > .linear-background').hide(loader);
                $btn.html('حفظ');
                $('#form').show();
                handleAjaxError(data);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
    $(document).on('click', '.delete', function () {
        var id = $(this).data('id');
        swal.fire({
            title: "هل أنت متأكد من الحذف؟",
            text: "لا يمكنك التراجع بعد ذلك؟",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "موافق",
            cancelButtonText: "الغاء",
            okButtonText: "موافق",
            closeOnConfirm: false
        }).then((result) => {
            if (!result.isConfirmed){
                return true;
            }


            var url = '{{ route("$url.destroy",":id") }}';
            url = url.replace(':id',id)
            console.log(url);
            $.ajax({
                url: url,
                type: 'DELETE',
                beforeSend: function(){
                    $('#global-loader').show()
                },
                success: function (data) {
                    window.setTimeout(function() {
                        $('#global-loader').hide()
                        my_toaster(data.message , 'info')
                        $('#exportexample').DataTable().ajax.reload(null, false);
                    }, 500);
                }, error: function (data) {
                    $('#global-loader').hide()
                    handleAjaxError(data);
                }
            });
        });
    });


    $(document).on('click', '#multiDeleteBtn', function () {
        var allVals = [];
        $(".sub_chk:checked").each(function() {
            allVals.push($(this).attr('data-id'));
        });
        if(allVals.length <=0)
        {
            my_toaster("يجب تحديد المراد حذفه",'warning')
        }
        else {
            swal.fire({
                title: "هل أنت متأكد من الحذف؟",
                text: "لا يمكنك التراجع بعد ذلك؟",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "موافق",
                cancelButtonText: "الغاء",
                okButtonText: "موافق",
                closeOnConfirm: false
            }).then((result) => {
                if (!result.isConfirmed){
                    return true;
                }
                var url = '{{ route("$url.multiDelete") }}';
                var ids = allVals.join(",");
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {'ids': ids},
                    beforeSend: function () {
                        $('#global-loader').show()
                    },
                    success: function (data) {

                        window.setTimeout(function () {
                            $('#global-loader').hide()
                            my_toaster(data.message, 'info')
                            $('#exportexample').DataTable().ajax.reload(null, false);
                        }, 100);
                    }, error: function (data) {
                        $('#global-loader').hide()
                        handleAjaxError(data);
                    }

                });
            });
        }

    });

    $(document).off('click', '#editBtn').on('click', '#editBtn', function () {
        var id = $(this).data('id');
        $('#form-load').html(loader);
        $('#Modal').modal('show');

        var url = "{{route("$url.edit",':id')}}";
        url = url.replace(':id', id);

        setTimeout(function (){
            $('#form-load').load(url);
        }, 100);
    });

    $(document).on('change', 'input[type="file"]', function () {
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var $parent = $(input).closest('.form-group, .col-sm-6, .col-sm-12, div');
                var $img = $parent.find('img.preview-img, img').first();
                if ($img.length) {
                    $img.attr('src', e.target.result);
                } else {
                    $parent.append('<div class="mt-2"><img src="' + e.target.result + '" class="preview-img rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #e2e8f0;" alt="preview"></div>');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    $(document).on('click', 'table td img', function (e) {
        var parentLink = $(this).closest('a');
        if (!parentLink.length && this.src) {
            window.open(this.src, '_blank');
        }
    });

</script>
