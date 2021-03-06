jQuery(document).ready(function(){
    $(document).on('click','.show-order-detail',function(){
        var tr = $(this).closest('tr');
        var id = $(this).attr('data-id');
        var url =orderDetailURL + '/'+id;
            if($(".open-order-details-cls").is(":visible")){
                 var closest_id = $(".open-order-details-cls").attr('data-cid');
                 if(id==closest_id){
                    $('.order_detail_tr').remove();
                 }else{
                    $('.order_detail_tr').remove();
                    getOrderDetails(url,id);
                 }
                
            }else{
                $('.order_detail_tr').remove();
                // var row = oTableCustom.row(tr);
                getOrderDetails(url,id);
            }
    });
    $(document).on("click",".assign-delivery-boy",function(){
        var id = $(this).data("id");
        var row = $(this).data("row");
        $("#assign-delivery-boy #load_delivery_user_id").val(id);
        $(".select_user").val(row).trigger("change");
        $("#assign-delivery-boy").modal();
    });

    $(document).on("click",".assign-delivery-users",function(){

        var id = $.map($('input[name="assigndeliveryuser[]"]:checked'), function(c){return c.value; })
        if(id==''){
             $.bootstrapGrowl('Please Select Order', {type: 'danger', delay: 4000});
            return false;
        }
        var row = '';
        $("#assign-delivery-boy #load_delivery_user_id").val(id);
        $(".select_user").val(row).trigger("change");
        $("#assign-delivery-boy").modal();
    });

    $(document).on("click",".add-money-from-order",function(){
        var id = $(this).data("id");
        var row = $(this).data("row");
        $("#add-money-from-order #order_id").val(id);
        $("#add-money-from-order").modal();
    }); 

    $(document).on("click",".btn-submit-product",function(){
        $order_id = $('#add-product #item_id').val();
        var id = $('#add-product #item_id').val();
        var product_id = $('#add-product #product').val();
        var category_id = $('#add-product #category').val();
        var qty = $('#add-product #quantity').val();
        var url = addNewProductURL + '/' + id;
       // alert(url);
        if(AddProductValidation()){
            $('#AjaxLoaderDiv').fadeIn(100);
            var currentOBJ = $(this);
            currentOBJ.attr("disabled", true);
            $.ajax({
                type: "POST",
                url: url,
                data: {action: url,id: id,quantity: qty,product_id:product_id,category_id:category_id, _token: $("input[name='_token']").val()},
                success: function (result)
                {
                    currentOBJ.attr("disabled", false);
                    $('#AjaxLoaderDiv').fadeOut(100);
                    if (result.status == 1)
                    {
                        var url = orderDetailURL + '/'+id;
                        if($(".open-order-details-cls").is(":visible")){
                            $('.order_detail_tr').remove();
                        }
                         getOrderDetails(url,id);
                        $('#tr-'+id).find('.totalprice_td').html(parseFloat(result.price_del_charge).toFixed(2));
                        $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                        $('#add-product').modal('toggle');
                    }
                    else
                    {
                        $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
                    }
                },
                error: function (error)
                {
                    currentOBJ.attr("disabled", false);
                    $('#AjaxLoaderDiv').fadeOut(100);
                    $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
                }
            });
        }
        return false;
    })
    $(document).on("click",".btn-submit-assign-driver",function(){

        $order_id = $("#assign-delivery-boy #load_delivery_user_id").val();
        var id  = $("#assign-delivery-boy #load_delivery_user_id").val();
        var url = assignDeliveryBoyURL + '/'+id;
        $delivery_boy_id = $("#assign-delivery-boy .search-select").val();
        //var url = $(this).data("href");
        $('#AjaxLoaderDiv').fadeIn(100);
        var currentOBJ = $(this);
        currentOBJ.attr("disabled", true);        
        $.ajax({
            type: "POST",
            url: url,
            data: {action:url, id: $order_id, _token: $("input[name='_token']").val(),delivery_boy_id: $delivery_boy_id},
            success: function (result)
            {
                currentOBJ.attr("disabled", false);
                $('#AjaxLoaderDiv').fadeOut(100);
                if (result.status == 1)
                {
                    $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                    // window.location = result.redirect_url;
                     $("#assign-delivery-boy").modal('hide');
                     oTableCustom.draw();
                }
                else
                {
                    $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
                }
            },
            error: function (error)
            {
                currentOBJ.attr("disabled", false);
                $('#AjaxLoaderDiv').fadeOut(100);
                $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
            }
        });

        return false;
    });

    $(document).on('click','.qnt-cal-btn',function(e){
          var date_id = $(this).attr('data-id');
          var main_order_id = $('#qty_'+date_id).attr('data-main-id');
          var data_type = $(this).attr('data-type');
          var qty = $('#qty_'+date_id).val();
          if(data_type == 'dec'){
            var newQt = qty - 1;
            if(newQt < 1){
                var confirm = deleteFunction();
                if(confirm == true){
                    qntCalculation(date_id,qty,data_type,main_order_id );
                }else{
                    return false;
                }
            }else{
                qntCalculation(date_id,qty,data_type,main_order_id );
            }
          }else{
            qntCalculation(date_id,qty,data_type,main_order_id );
          }
    });

    $(document).on('click','.remove_prod',function(){
        var confirm = deleteFunction();
        if(confirm == true){
        var id = $(this).attr('data-id');
        var main_order_id = $(this).attr('main-data-id');
        var url = removeProductURL + '/'+id;

         $.ajax({
            headers: {
                //'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url:url,
            method:"POST",
            data:{'id':id,_token: $('input[name="_token"]').val()},
            success:function(result){
                $('#AjaxLoaderDiv').fadeOut(100);
                if (result.status == 1)
                {
                     $('#total_price').val(parseFloat(result.total_price).toFixed(2));
                     $('#tr-'+main_order_id).find('.totalprice_td').html(parseFloat(result.price_del_charge).toFixed(2));
                     $('#order_lines_'+id).remove();
                    $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                }
                else
                {
                    $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
                }
            }
        });

        }
        return false;
    });

    /*
    add product 
    */
    $(document).on("click",".add_product",function(){
        var id = $(this).data("id");
        $('#add-product #item_id').val(id);
        $('#add-product').modal();
        var id = $("#category").val();
        var url = 'products/getproductlist';
        getProductListByCategory(url, id);
    });

    $(document).on("change","#category",function(){
        var id = $(this).val();
        var url = 'products/getproductlist';
        getProductListByCategory(url, id);
    });
    $(document).on("change","#product",function(){
        var id = $(this).val();
        var url = 'products/getproductdetails';
        getProductDetailsByID(url, id);
    });
    $(document).on('click','.add-product-qnt-cal-btn',function(e){
        var data_type = $(this).attr('data-type');
        var qty = $('#quantity').val();
        var qty = parseInt(qty);
        if(data_type == 'dec'){
            var newQt = (qty - 1);
        }else{
            var newQt = (qty + 1);
        }
        $('#quantity').val(newQt);
        DisplayAddProducttotalPrice();
    });

});

function deleteFunction(){
    var r = confirm("are you sure want to delete?");
    return r;
}
function getOrderDetails(url,id){

    $.ajax({
        headers: {
            //'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url:url,
        method:"POST",
        data:{ 'id': id ,_token: $('input[name="_token"]').val()},
        dataType:"json",
        success:function(data){
        jQuery('<tr class="order_detail_tr"><td colspan="10">'+data.html+'</td></tr>').insertAfter($('#tr-'+id).closest('tr'));
       }
    });
}
function qntCalculation(id,qty,data_type,main_order_id)
{
        var order_id = $('#cart_id').attr('data-id');
        var url = changeQtyURL + '/'+id;
        $.ajax({
            headers: {
                //'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url:url,
            method:"POST",
            data:{ 'qty' : qty ,'id':id ,'order_id':order_id,'data_type':data_type,_token: $('input[name="_token"]').val()},
            success:function(data){
                if(data.status == 1){
                     $('#productPrice_'+id).val(parseFloat(data.data).toFixed(2));
                     $('#ProductDiscountPrice_'+id).html(parseFloat(data.ProductDiscountPrice).toFixed(2));

                     $('#total_price').val(parseFloat(data.total_price).toFixed(2));
                     $('#qty_'+id).val(data.req_qtn);
                     $('#tr-'+main_order_id).find('.totalprice_td').html(parseFloat(data.price_del_charge).toFixed(2));
                     $.bootstrapGrowl(data.message, {type: 'success', delay: 4000});
                } else if(data.status == 2){
                     $('#total_price').val(parseFloat(data.total_price).toFixed(2));
                     $('#tr-'+main_order_id).find('.totalprice_td').html(parseFloat(data.price_del_charge).toFixed(2));
                     $('#order_lines_'+id).remove();
                     $.bootstrapGrowl(data.message, {type: 'success', delay: 4000});
                }else
                {
                    $.bootstrapGrowl(data.message, {type: 'danger', delay: 4000});
                }

                 
            },
            error: function (error)
            {
                console.log(error);
            }
        });
        return false;
}

function getProductListByCategory(url,id){
    $.ajax({
        headers: {
            //'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url:url,
        method:"POST",
        data:{ 'id': id ,_token: $('input[name="_token"]').val()},
        dataType:"json",
        beforeSend: function( xhr ) {
            let selectStartScan = $('#product');
            selectStartScan.empty();
            $("#StockTypePrice").html('');
            $("#TotalPriceOfProduct").html('');
            $("#unity_price").val(0);
            $("#quantity").val(0);
        },
        success:function(data){
            var respdata = data;
            if(respdata.status == 1) {
                let selectStartScan = $('#product');
                selectStartScan.empty();
                selectStartScan.append('<option selected="true" value=""> --- Select ---</option>');
                selectStartScan.prop('selectedIndex', 0);
                $.each(respdata.data, function (key, value) {
                    selectStartScan.append($('<option></option>').attr('value', key).text(value));
                });
            }
       }
    });
}

function getProductDetailsByID(url,id){
    $.ajax({
        headers: {
            //'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url:url,
        method:"POST",
        data:{ 'id': id ,_token: $('input[name="_token"]').val()},
        dataType:"json",
        beforeSend: function( xhr ) {
            $("#StockTypePrice").html('');
            $("#TotalPriceOfProduct").html('');
            $("#unity_price").val(0);
            $("#quantity").val(0);
        },
        success:function(data){
            var respdata = data;
            if(respdata.status == 1) {
                // var StockTypePrice = respdata.data.units_in_stock+' '+??????respdata.data.units_stock_type+' / '+??????respdata.data.unity_price;
                var StockTypePrice = respdata.data.units_in_stock+" "+respdata.data.units_stock_type+" / ??? "+respdata.data.unity_price;
                $("#StockTypePrice").html(StockTypePrice);
                $("#TotalPriceOfProduct").html('');
                $("#unity_price").val(respdata.data.unity_price);
                $("#quantity").val(0);
            }
       }
    });
}

function DisplayAddProducttotalPrice() {
    var quantity = $("#quantity").val();
    var quantity = parseInt(quantity);
    var unity_price =  $("#unity_price").val();
    var total = " ??? "+(quantity*unity_price).toFixed(2);
    $("#TotalPriceOfProduct").html(total);
}

function AddProductValidation() {
    var product_id = $('#add-product #product').val();
    var err = '';
    if(product_id > 0) {
        var qty = $('#add-product #quantity').val();
        if(qty <= 0) {
            err = 'Please add quantity more than one';
        }
    } else {
        err = 'Please select at least one product';
    }
    if(err != ''){
        alert(err)
        return false;
    } else {
        return true;
    }
}

$(document).ready(function() {
    $(".search_date_picker").datepicker({
        numberOfMonths: 1,
         changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});