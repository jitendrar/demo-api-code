jQuery(document).ready(function(){

    DisplayAddProducttotalPrice();
    
    /*
    getusersdetails
    users/getusersdetails
    */
    $(document).on("change",".Suserid",function(){
        var id = $(this).val();
        var url = '/admin/users/getusersdetails';
        DisplayUsersDetails(url, id);
    });

    $(document).on("keyup","#delivery_charge",function(){
        DisplayAddProducttotalPrice();
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
        
    });
    $(document).on('click','.add-product-qnt-cal-btn',function(e){
        var data_type = $(this).attr('data-type');
        var data_quantity = $(this).data('quantity');
        var room = $(this).data('room');
        $('#'+data_quantity).removeClass('validateBorder');
        var qty = $('#'+data_quantity).val();
        var qty = parseInt(qty);
        if(data_type == 'dec'){
            var newQt = (qty - 1);
        }else{
            var newQt = (qty + 1);
        }
        if(room && newQt <= 0) {
            remove_education_fields(room);
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
                  var checked = '';
                  if(V.primary_address == 1) {
                    checked = 'checked';
                  }
                  var Checkbox = `<div style="width: 100%">
                                    <input type="radio" `+checked+` id="address_id-`+K+`" name="address_id" value="`+V.id+`">
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
                DisplayAddProducttotalPrice();
            }
       }
    });
}

function DisplayAddProducttotalPrice() {
    var Tprice = 0;
    var Tprice = $("#delivery_charge").val();
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