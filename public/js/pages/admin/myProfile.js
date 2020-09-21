$(document).ready(function(){
	jQuery('#reset_password_link').click(function(){
        jQuery('#passwordId').val('');
        jQuery('#passwordConfirmationId').val('');
    
        if(jQuery(this).is(':checked')){
           jQuery('#reset_password_form').slideDown();
        }else{
           jQuery('#reset_password_form').slideUp();
        }
    });

    $('#password-form').submit(function () {
        if ($(this).parsley('isValid'))
        {
            $('#formSubmit3').attr('disabled',true);
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
                    $('#formSubmit3').attr('disabled',false);
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    if (result.status == 1)
                    {
                        $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                        window.location = $('#password-form').attr('redirect-url');
                    }
                    else
                    {
                        $.bootstrapGrowl(result.msg, {type: 'danger error-msg', delay: 4000});
                    }
                },
                error: function (error)
                {
                    $('#formSubmit3').attr('disabled',false);
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    $.bootstrapGrowl(internalServerERR, {type: 'danger error-msg', delay: 4000});
                }
            });
        }
        return false;
    });
    $('#avatar-form').submit(function () {
        if ($(this).parsley('isValid'))
        {
            $('#formSubmit2').attr('disabled',true);
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
                    $('#formSubmit2').attr('disabled',false);
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    if (result.status == 1)
                    {
                        $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                        window.location = $('#avatar-form').attr('redirect-url');
                    }
                    else
                    {
                        $.bootstrapGrowl(result.msg, {type: 'danger error-msg', delay: 4000});
                    }
                },
                error: function (error)
                {
                    $('#formSubmit2').attr('disabled',false);
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    $.bootstrapGrowl(internalServerERR, {type: 'danger error-msg', delay: 4000});
                }
            });
        }
        return false;
    });
});
