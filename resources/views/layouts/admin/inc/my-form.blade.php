<script>
    $(document).on('submit', '.my_form', function (event) {
        event.preventDefault();
        var $form = $(this);
        var form_data = new FormData(this);
        var url = $form.attr('action');
        $.ajax({
            type: 'POST',
            url: url,
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $('#global-loader').show()
            },
            success: function (data) {
                window.setTimeout(function() {
                    $('#global-loader').hide()
                    if(data.message != null){
                        my_toaster(data.message)
                    }
                    if(data.url != null){
                        location.href = data.url;
                    }
                    if(data.reset_form === 'true'){
                        $('.my_form')[0].reset();
                    }
                }, 500);
            }, error: function (data) {
                $('#global-loader').hide()
                var rawErrors = data.responseJSON ? (data.responseJSON.errors || data.responseJSON.messages || {}) : {};
                var errorList = Array.isArray(rawErrors) ? rawErrors : Object.values(rawErrors);
                $( errorList ).each(function(index, message ) {
                    if (Array.isArray(message)) {
                        $(message).each(function(i, m) { my_toaster(m, 'error'); });
                    } else if (typeof message === 'string') {
                        my_toaster(message, 'error');
                    }
                });
            }
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
</script>
