jQuery(document).ready(function(){
    $(document).on('click','.show-order-detail',function(){
        var tr = $(this).closest('tr');
        var id = $(this).attr('data-id');
        var url =orderDetailURL + '/'+id;
            if($(".open-order-details-cls").is(":visible")){
                $('.order_detail_tr').remove();
                return false;
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
                    window.location = result.redirect_url;
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
    getusersdetails
    users/getusersdetails
    */
    $(document).on("change",".Suserid",function(){
        var id = $(this).val();
        var url = '/admin/users/getusersdetails';
        DisplayUsersDetails(url, id);
    });

    /*
    add product 
    */
    $(document).on("change",".Scategory",function(){
        var id = $(this).val();
        var data_product = $(this).data('product');
        var url = '/admin/products/getproductlist';
        getProductListByCategory(url, id, data_product);
        DisplayAddProducttotalPrice();
    });

    $(document).on("change",".Sproduct",function(){
        var id = $(this).val();
        var data_stockType = $(this).data('stocktype');
        var data_stockPrice = $(this).data('stockprice');
        var url = '/admin/products/getproductdetails';
        getProductDetailsByID(url, id, data_stockType, data_stockPrice);
        DisplayAddProducttotalPrice();
    });
    $(document).on('click','.add-product-qnt-cal-btn',function(e){
        var data_type = $(this).attr('data-type');
        var data_quantity = $(this).data('quantity');
        $('#'+data_quantity).removeClass('validateBorder');
        var qty = $('#'+data_quantity).val();
        var qty = parseInt(qty);
        if(data_type == 'dec'){
            var newQt = (qty - 1);
        }else{
            var newQt = (qty + 1);
        }
        $('#'+data_quantity).val(newQt);
        DisplayAddProducttotalPrice();
    });

    $("#submitBtn").click(function() {
        if(validatesearchform()) {
            $("#submit-form").submit();
        }
    });
});

function validatesearchform() {
    var err = '';
    var UserId = $("#UserId").val();
    if(UserId>0) {
        if($('input[name="address_id"]').length){
            var address_id = $('input[name="address_id"]:checked').val();
            if(address_id > 0) {
                var DeliveryDate = $("#DeliveryDate").val();
                if(!DeliveryDate){
                    $("#DeliveryDate").focus();
                    err = "Please select Delivery Date";
                } else {
                    var delivery_time = $("#delivery_time").val();
                    if(!delivery_time){
                        $("#delivery_time").focus();
                        err = "Please select Delivery Time ";
                    }
                }
            } else {
                err = "Please select at-least one delivery address.";
            }
        } else {
            err = "Delivery address is not available for this user. Please first add address for the user.";
        }
    } else {
        $("#UserId").focus();
        err = "Please select user.";
    }

    if(err =='') {
        $(".Scategory").each(function() {
            var Scategory = parseInt($(this).val());
            if(Scategory <= 0 || isNaN(Scategory)) {
                $(this).focus();
                err = "Please select at-least one Category";
                return false;
            }
        });
    }

    if(err =='') {
        $(".Sproduct").each(function() {
            var Sproduct = parseInt($(this).val());
            if(Sproduct <= 0 || isNaN(Sproduct)) {
                $(this).focus();
                err = "Please select at-least one Product";
                return false;
            }
        });
    }
    
    if(err =='') {
        $(".QuantityForCount").each(function() {
            var CurrentQuantity = parseInt($(this).val());
            if(CurrentQuantity <= 0) {
                $(this).addClass('validateBorder');
                err = "Please enter at-least one quantity";
                return false;
            }
        });
    }

    if(err!='') {
        alert(err);
        return false;
    }
    return true;
}

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

function DisplayUsersDetails(url,id){
    $.ajax({
        headers: {
            //'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url:url,
        method:"POST",
        data:{ 'id': id ,_token: $('input[name="_token"]').val()},
        dataType:"json",
        beforeSend: function( xhr ) {
            $("#availablebalance").val(0);
            $("#AddAdressForOrder").html("");
        },
        success:function(data){
            var respdata = data;
            if(respdata.status == 1) {
                var availablebalance = respdata.data.users.balance;
                $("#availablebalance").val(availablebalance);
                $.each(respdata.data.address, function( K, V ) {
                  var Checkbox = `<div style="width: 100%">
                                    <input type="radio" id="address_id-`+K+`" name="address_id" value="`+V.id+`">
                                    <label for="address_id-`+K+`">
                                        `+V.address_line_1+`, `+V.address_line_2+`, `+V.city+`, `+V.zipcode+`
                                    </label>
                                  </div>`;
                  $("#AddAdressForOrder").append(Checkbox);
                });
            }
       }
    });
}

function getProductListByCategory(url,id,data_product_id){
    $.ajax({
        headers: {
            //'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url:url,
        method:"POST",
        data:{ 'id': id ,_token: $('input[name="_token"]').val()},
        dataType:"json",
        beforeSend: function( xhr ) {
            let selectStartScan = $('#'+data_product_id);
            selectStartScan.empty();
            $("#StockTypePrice").html('');
            $("#quantity").val(0);
        },
        success:function(data){
            var respdata = data;
            if(respdata.status == 1) {
                let selectStartScan = $('#'+data_product_id);
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

function getProductDetailsByID(url,id,data_stockType_id, data_stockPrice_id){
    $.ajax({
        headers: {
            //'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url:url,
        method:"POST",
        data:{ 'id': id ,_token: $('input[name="_token"]').val()},
        dataType:"json",
        beforeSend: function( xhr ) {
            $("#"+data_stockType_id).html('');
            $("#"+data_stockPrice_id).val(0);
            $("#quantity").val(0);
        },
        success:function(data){
            var respdata = data;
            if(respdata.status == 1) {
                var StockType = respdata.data.units_in_stock+' '+respdata.data.units_stock_type;
                var StockPrice = respdata.data.unity_price;
                $("#"+data_stockType_id).val(StockType);
                $("#"+data_stockPrice_id).val(StockPrice);
                
                $("#quantity").val(0);
            }
       }
    });
}

function DisplayAddProducttotalPrice() {
    var Tprice = 0;
    $(".QuantityForCount").each(function() {
        var CurrentQuantity = parseInt($(this).val());
        var data_stockPrice = $(this).data('stockprice');
        var CurrentPrice = $("#"+data_stockPrice).val();
        var CurrentTotal  = (CurrentQuantity*CurrentPrice).toFixed(2);
        Tprice = (parseFloat(Tprice)+parseFloat(CurrentTotal)).toFixed(2);
    });
    var total = " ₹ "+Tprice;
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