$(document).ready(function () {
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