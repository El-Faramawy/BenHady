<script>
    var loader = `
        <div class="text-center py-5 my-4">
            <div class="spinner-border text-success" role="status" style="width: 2.75rem; height: 2.75rem;">
                <span class="sr-only">جاري التحميل...</span>
            </div>
            <div class="mt-3 text-muted font-size-13 font-weight-600">جاري تحميل البيانات...</div>
        </div>
    `;

    var createFormCache = null;
    var isPreloadingCreate = false;

    function preloadCreateForm() {
        if (isPreloadingCreate) return;
        isPreloadingCreate = true;
        $.ajax({
            url: "{{ route("$url.create") }}",
            type: 'GET',
            success: function (html) {
                createFormCache = html;
                isPreloadingCreate = false;
            },
            error: function () {
                isPreloadingCreate = false;
            }
        });
    }

    function initModalPlugins() {
        if ($.fn.select2) {
            $('#Modal .select2-user').each(function () {
                if (!$(this).data('select2')) {
                    $(this).select2({
                        dropdownParent: $('#Modal'),
                        placeholder: 'ابحث باسم أو هاتف العميل...',
                        allowClear: true,
                        width: '100%',
                        dir: 'rtl'
                    });
                }
            });
            $('#Modal .select2-basic, #Modal .select2').each(function () {
                if (!$(this).data('select2')) {
                    $(this).select2({
                        dropdownParent: $('#Modal'),
                        width: '100%',
                        dir: 'rtl'
                    });
                }
            });
        }
        if ($.fn.tooltip) {
            $('#Modal [data-toggle="tooltip"]').tooltip();
        }
        if (typeof window.onModalLoaded === 'function') {
            window.onModalLoaded();
        }
    }

    $(function () {
        preloadCreateForm();
        var cleanCellText = function (data) {
            if (typeof data === 'string') {
                return $('<div>').html(data).text().replace(/\s+/g, ' ').trim();
            }
            return data;
        if ($.fn.dataTable && $.fn.dataTable.ext && $.fn.dataTable.ext.buttons && $.fn.dataTable.ext.buttons.collection) {
            if (!$.fn.dataTable.ext.buttons.collection._rtlPositionPatched) {
                $.fn.dataTable.ext.buttons.collection._rtlPositionPatched = true;
                var origCollectionAction = $.fn.dataTable.ext.buttons.collection.action;
                $.fn.dataTable.ext.buttons.collection.action = function (e, dt, button, config) {
                    origCollectionAction.call(this, e, dt, button, config);

                    var reposition = function () {
                        if (!config._collection || !config._collection.length) return;
                        var $btn = $(button);
                        if (!$btn.length) return;

                        var btnOffset = $btn.offset();
                        var btnWidth = $btn.outerWidth();
                        var btnHeight = $btn.outerHeight();
                        var colWidth = config._collection.outerWidth() || 240;
                        var winWidth = $(window).width();

                        // In RTL, align right edge of collection with right edge of button:
                        var targetLeft = (btnOffset.left + btnWidth) - colWidth;

                        // Restrict from overlapping right sidebar:
                        var $sidebar = $('.app-sidebar:visible');
                        var rightLimit = winWidth;
                        if ($sidebar.length) {
                            var sOffset = $sidebar.offset();
                            if (sOffset.left > 100 && sOffset.left < winWidth) {
                                rightLimit = sOffset.left;
                            }
                        }

                        if (targetLeft + colWidth > rightLimit - 15) {
                            targetLeft = rightLimit - 15 - colWidth;
                        }
                        if (targetLeft < 15) {
                            targetLeft = 15;
                        }

                        config._collection.css({
                            'position': 'absolute',
                            'top': Math.round(btnOffset.top + btnHeight + 4) + 'px',
                            'left': Math.round(targetLeft) + 'px',
                            'right': 'auto',
                            'z-index': '99999'
                        });
                    };

                    reposition();
                    setTimeout(reposition, 10);
                    setTimeout(reposition, 60);
                    setTimeout(reposition, 150);
                };
            }
        }

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
                    previous: "<i class='fa fa-angle-right ml-1'></i> السابق",
                    next: "التالي <i class='fa fa-angle-left mr-1'></i>"
                },
                "sProcessing": "جاري التحميل ..",
                "sLengthMenu": "اظهار _MENU_ سجل",
                "sZeroRecords": "لا يوجد نتائج",
                "sInfo": "اظهار _START_ الى  _END_ من _TOTAL_ سجل",
                "sInfoEmpty": "لا نتائج",
                "sInfoFiltered": "للبحث",
                "sSearch": "بحث :    ",
                "oPaginate": {
                    "sPrevious": "<i class='fa fa-angle-right ml-1'></i> السابق",
                    "sNext": "التالي <i class='fa fa-angle-left mr-1'></i>"
                },
                "buttons": {
                    "colvis": "تحديد الأعمدة",
                    "colvisRestore": "إعادة التعيين",
                    "copy": "نسخ",
                    "excel": "Excel",
                    "csv": "CSV",
                    "print": "طباعة"
                }
            },
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'colvis',
                    text: '<i class="fe fe-columns ml-1"></i>الأعمدة',
                    className: 'btn btn-outline-success',
                    columns: ':not(.no-colvis):not(:first-child):not(:last-child)'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o ml-1"></i>Excel',
                    className: 'btn btn-outline-success',
                    title: document.title,
                    exportOptions: {
                        columns: ':visible:not(.no-export):not(:first-child):not(:last-child)',
                        format: {
                            header: cleanCellText,
                            body: cleanCellText
                        }
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o ml-1"></i>CSV',
                    className: 'btn btn-outline-success',
                    bom: true,
                    charset: 'utf-8',
                    exportOptions: {
                        columns: ':visible:not(.no-export):not(:first-child):not(:last-child)',
                        format: {
                            header: cleanCellText,
                            body: cleanCellText
                        }
                    }
                },
                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-copy ml-1"></i>نسخ',
                    className: 'btn btn-outline-success',
                    exportOptions: {
                        columns: ':visible:not(.no-export):not(:first-child):not(:last-child)',
                        format: {
                            header: cleanCellText,
                            body: cleanCellText
                        }
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print ml-1"></i>طباعة',
                    className: 'btn btn-outline-success',
                    exportOptions: {
                        columns: ':visible:not(.no-export):not(:first-child):not(:last-child)'
                    },
                    customize: function ( win ) {
                        $(win.document.body).css( 'direction', 'rtl' );
                        $(win.document.body).css( 'font-family', 'Cairo, sans-serif' );
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' )
                            .css( 'direction', 'rtl' );
                    }
                }
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

        // Dynamic positioning for ColVis and button collections in RTL layout
        function adjustCollectionPosition() {
            var $col = $('div.dt-button-collection:visible');
            if (!$col.length) return;

            var $btn = $('.buttons-collection.active, .buttons-colvis.active, .buttons-collection:focus, .buttons-colvis:focus');
            if (!$btn.length) {
                $btn = $('.buttons-colvis, .buttons-collection').last();
            }

            if ($btn.length && $btn.is(':visible')) {
                var btnOffset = $btn.offset();
                var btnWidth = $btn.outerWidth();
                var btnHeight = $btn.outerHeight();
                var colWidth = $col.outerWidth() || 240;
                var winWidth = $(window).width();

                // In RTL, align right edge of collection with right edge of button:
                var targetLeft = (btnOffset.left + btnWidth) - colWidth;

                // Restrict dropdown from overflowing into the right sidebar
                var $sidebar = $('.app-sidebar:visible');
                var rightLimit = winWidth;
                if ($sidebar.length) {
                    var sOffset = $sidebar.offset();
                    if (sOffset.left > 100 && sOffset.left < winWidth) {
                        rightLimit = sOffset.left;
                    }
                }

                if (targetLeft + colWidth > rightLimit - 10) {
                    targetLeft = rightLimit - 10 - colWidth;
                }
                if (targetLeft < 15) {
                    targetLeft = 15;
                }

                $col.css({
                    'left': Math.round(targetLeft) + 'px',
                    'top': Math.round(btnOffset.top + btnHeight + 4) + 'px',
                    'right': 'auto',
                    'position': 'absolute',
                    'z-index': '99999'
                });
            }
        }

        $(document).on('click', '.buttons-colvis, .buttons-collection', function() {
            setTimeout(adjustCollectionPosition, 10);
            setTimeout(adjustCollectionPosition, 50);
        });

        if (window.MutationObserver) {
            var dtObserver = new MutationObserver(function(mutations) {
                for (var i = 0; i < mutations.length; i++) {
                    if (mutations[i].addedNodes.length) {
                        var $col = $('div.dt-button-collection:visible');
                        if ($col.length) {
                            adjustCollectionPosition();
                            break;
                        }
                    }
                }
            });
            dtObserver.observe(document.body, { childList: true, subtree: false });
        }

        $(window).on('resize scroll', function() {
            if ($('div.dt-button-collection:visible').length) {
                adjustCollectionPosition();
            }
        });
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
        $('#Modal').modal('show');
        if (createFormCache) {
            $('#form-load').html(createFormCache);
            initModalPlugins();
        } else {
            $('#form-load').html(loader);
            $('#form-load').load("{{route("$url.create")}}", function (response) {
                createFormCache = response;
                initModalPlugins();
            });
        }
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
                    createFormCache = null;
                    preloadCreateForm();
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

        var url = "{{route("$url.edit",':id')}}".replace(':id', id);
        $('#form-load').load(url, function () {
            initModalPlugins();
        });
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
