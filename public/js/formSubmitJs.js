$(document).ready(function () {
    $('#submit-form').submit(function () {
        if (true)
        {
            $('#submitBtn').attr('disabled',true);
            $('#AjaxLoaderDiv').fadeIn('slow');

            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                contentType: false,
                processData: false,
                enctype: 'multipart/form-data',
                success: function (result)
                {
                    $('#submitBtn').attr('disabled',false);
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    if (result.status == 1)
                    {
                        $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                        window.location = $('#submit-form').attr('redirect-url');
                    }
                    else
                    {
                        $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
                    }
                },
                error: function (error)
                {
                    $('#submitBtn').attr('disabled',false);
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    $.bootstrapGrowl(internalServerERR, {type: 'danger', delay: 4000});
                }
            });
        }
        return false;
    });
    $(document).on('click', '.btn-delete-record', function () {

        $text = deleteConfirmMSG;

        if (confirm($text))
        {
            $url = $(this).attr('href');
            $('#global_delete_form').attr('action', $url);
            $('#global_delete_form #delete_id').val($(this).data('id'));
            $('#global_delete_form').submit();
        }

        return false;
    });
    $('#module-form').submit(function () {
        //if ($(this).parsley('isValid'))
        if (true)
        {
            $('#submitBtn').attr('disabled',true);
            $('#AjaxLoaderDiv').fadeIn('slow');

            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                contentType: false,
                processData: false,
                enctype: 'multipart/form-data',
                success: function (result)
                {
                    $('#submitBtn').attr('disabled',false);
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    if (result.status == 1)
                    {
                        $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                        window.location = $('#module-form').attr('redirect-url');
                    }
                    else
                    {
                        $.bootstrapGrowl(result.msg, {type: 'danger error-msg', delay: 4000});
                    }
                },
                error: function (error)
                {
                    $('#submitBtn').attr('disabled',false);
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    $.bootstrapGrowl(internalServerERR, {type: 'danger error-msg', delay: 4000});
                }
            });
        }
        return false;
    });
     $(document).on('click','.togglerclick',function(){
        $.ajax({
            type: "GET",
            url: http_toggleChange_js,
            contentType: false,
            processData: false,
            success: function (result)
            {
            },
            error: function (error)
            {   
            }
        });
    }); 

});