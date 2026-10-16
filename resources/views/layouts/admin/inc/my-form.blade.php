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
</script>
