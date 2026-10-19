<script>
    function my_form(form) {
        if (!form) return false;
        var $form = $(form);

        if ($form.data('is_submitting')) {
            return false;
        }
        $form.data('is_submitting', true);

        var $btn = $form.find(':submit, button.save_setting_btn, button.save_policy_btn');
        var form_data = new FormData(form);
        var url = $form.attr('action');
        var method = ($form.attr('method') || 'POST').toUpperCase();

        $.ajax({
            type: method,
            url: url,
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#global-loader').show();
                $btn.prop('disabled', true);
            },
            success: function (data) {
                window.setTimeout(function () {
                    $('#global-loader').hide();
                    $btn.prop('disabled', false);
                    $form.data('is_submitting', false);

                    if (data && data.message != null) {
                        if (typeof my_toaster === 'function') {
                            my_toaster(data.message, 'success');
                        } else {
                            alert(data.message);
                        }
                    }
                    if (data && data.url != null) {
                        location.href = data.url;
                    }
                    if (data && data.reset_form === 'true') {
                        $form[0].reset();
                    }
                }, 300);
            },
            error: function (data) {
                $('#global-loader').hide();
                $btn.prop('disabled', false);
                $form.data('is_submitting', false);

                if (typeof handleAjaxError === 'function') {
                    handleAjaxError(data);
                    return;
                }

                var rawErrors = data.responseJSON ? (data.responseJSON.errors || data.responseJSON.messages || {}) : {};
                var errorList = Array.isArray(rawErrors) ? rawErrors : Object.values(rawErrors);
                var shown = false;

                $(errorList).each(function (index, message) {
                    if (Array.isArray(message)) {
                        $(message).each(function (i, m) {
                            if (typeof my_toaster === 'function') {
                                my_toaster(m, 'error');
                            }
                            shown = true;
                        });
                    } else if (typeof message === 'string') {
                        if (typeof my_toaster === 'function') {
                            my_toaster(message, 'error');
                        }
                        shown = true;
                    }
                });

                if (!shown && data.responseJSON && data.responseJSON.message) {
                    if (typeof my_toaster === 'function') {
                        my_toaster(data.responseJSON.message, 'error');
                    }
                    shown = true;
                }

                if (!shown) {
                    if (typeof my_toaster === 'function') {
                        my_toaster('حدث خطأ أثناء الحفظ، يرجى المحاولة مرة أخرى', 'error');
                    }
                }
            }
        });

        return false;
    }

    // Submit handler for individual classes as well as .my_form
    $(document).off('submit.my_form').on('submit.my_form', '.my_form, .setting_form, .policy_form, .rental_policy_form', function (event) {
        event.preventDefault();
        my_form(this);
    });

    // Click handler only for explicit non-submit trigger buttons:
    $(document).off('click.my_form_btn').on('click.my_form_btn', 'button[type="button"].save_setting_btn, button[type="button"].save_policy_btn', function (event) {
        event.preventDefault();
        var form = $(this).closest('form')[0];
        if (form) {
            my_form(form);
        }
    });

    $(document).off('change.preview_img').on('change.preview_img', 'input[type="file"]', function () {
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var $parent = $(input).closest('.form-group, .col-sm-6, .col-sm-12, div');
                var $img = $parent.find('img.preview-img, img').first();
                if ($img.length && !$parent.hasClass('dropify-wrapper')) {
                    $img.attr('src', e.target.result);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>
