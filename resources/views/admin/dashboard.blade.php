@extends('admin.layouts.layout')
@section('content')

<div class="container">
    <div class="page-content-inner">

<div class="row">
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green" href="{!! route('users.index') !!}">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $total_User }}">0</span>
                </div>
                <div class="desc">Total User</div>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 yellow" href="{!! route('products.index') !!}">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $totalProducts }}">0</span>
                </div>
                <div class="desc">Total Available Products</div>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 red" href="{!! route('categories.index') !!}">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $totalCategories }}">0</span>
                </div>
                <div class="desc">Total Categories</div>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green" href="{!! route('orders.index') !!}">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $total_pending_orders }} ">0</span>
                </div>
                <div class="desc">Total Pending  Orders</div>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 yellow" href="{!! route('orders.index') !!}">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $total_orders }}">0</span> 
                </div>
                <div class="desc">Total Orders</div>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 red" href="{!! route('products.index') !!}">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $total_inactive_products }}">0</span> 
                </div>
                <div class="desc">Comming Soon Products</div>
            </div>
        </a>
    </div>
    <div class="col-md-12">
        <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bookmark"></i>
                        <span class="caption-subject bold">Orders</span>
                    </div>
                  
                    <div class="caption pull-right  text-right" >
                        <i class="fa fa-bookmark"></i>
                        <span class="caption-subject bold">Pending Order Amount + Delivery Charge: {{ $tota_pending_amount }}</span>
                    </div>    
                 
                    

                </div>
                
            <div class="portlet-body">
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">Order No</th>
                            <th width="25%">Action</th>
                            <th align="left" width="20%">Name</th>
                            <th align="left" width="15%">Total + Delivery Charge</th>
                            <th align="left">Delivery User</th>
                            <th width="5%" id="status-id">status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>    
            </div>
        </div>
    </div>
</div>
</div>
</div>
<input type="hidden" name="change_status_url" id="change_status_url" value="{{ url('admin/orders/changeStatus/') }}">
@include('admin/modal_include')
@endsection
@section('scripts')
<script type="text/javascript">
    var oTableCustom = oTableCustom;
    var orderDetailURL = "{{ url('admin/orders/detail') }}";
    var changeQtyURL = "{{ url('admin/changeQty') }}"
    var removeProductURL = "{{ url('admin/deleteProduct') }}";
    var assignDeliveryBoyURL = "{{ url('admin/orders/assign-delivery-boy') }}";
    var addNewProductURL = "{{ url('admin/orders/add-new-product') }}";
    var addmoneyfromorder = "{{ url('admin/orders/add-money-from-order') }}";

$(document).ready(function(){
    $('body').on('click', '.change-status', function(event){
            var target_id       = $(this).attr('data-id');
            var status_name       = $(this).attr('id');
            if(confirm($(this).data('msg'))){ 
                var _token          = $('input[name="_token"]').val();
                var target_path     = $('#change_status_url').val();
                var _row            = $(this).data('row');
                $.ajax({
                    "url": target_path+'/'+target_id,
                    "type": "POST",
                    data: { '_token':_token , 'id' : target_id , 'status_name' : status_name},
                    success: function(result){
                        if(result.status == true)
                        {   
                            if(result.html == ''){
                                if(result.data == 'delivered'){
                                    if($("#status"+target_id).html() == 'Pending'){
                                        $("#status"+target_id).html('Delivered');
                                        $("#status"+target_id).addClass('btn-success');    
                                        $("#status"+target_id).removeClass('btn-info');
                                    }
                                }else{
                                    if($("#status"+target_id).html() == 'Pending'){
                                        $("#status"+target_id).html('Cancel');
                                        $("#status"+target_id).addClass('btn-default');    
                                        $("#status"+target_id).removeClass('btn-info');
                                    }
                                }
                            }else{
                                $( "#status"+target_id ).parent().html(result.html);    
                            } 
                           oTableCustom.draw();
                        }
                    }
                });
            }
            event.preventDefault();
        });
    $.fn.dataTableExt.sErrMode = 'throw';
    oTableCustom = $('#server-side-datatables').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                "url": "{!!  route('orderData') !!}",
                "data": function ( data )
                {
                    data.name = 'dashboard';
                }
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('id', 'tr-'+aData['id']);
            },
            lengthMenu:
            [
            [100,150,200],
            [100,150,200]
            ],
            "order": [[ 0, "desc" ]],
            columns: [
                {data: 'order_number', name: 'order_number'},
                { data: 'action', orderable: false, searchable: false,className:'detail-td'},
                { data: 'userName', name: 'userName'},
                { data: 'totalPrice' , name: 'totalPrice',className:'totalprice_td',orderable: false},
                { data: 'deliveryUser' , name: 'deliveryUser',orderable: false},
                { data: 'order_status', name: 'order_status',className:'order_status_td'},
            ]
    });

    $('#add-money-from-order').on('hidden.bs.modal', function () {
      $("#transaction_method").val(0);
      $("#amount").val("");
      $("#description").val("");
  });
    
    $(document).on("click",".btn-submit-add-money",function(){

        $amount         = $("#add-money-from-order #amount").val();
        $description    = $("#add-money-from-order #description").val();
        $transaction_method    = $("#add-money-from-order #transaction_method").val();
        var id          = $("#add-money-from-order #order_id").val();
        var url         = addmoneyfromorder + '/'+id;
        $('#AjaxLoaderDiv').fadeIn(100);
        var currentOBJ = $(this);
        currentOBJ.attr("disabled", true);
        $.ajax({
            type: "POST",
            url: url,
            data: {action:url, id: id, amount: $amount, description: $description, transaction_method: $transaction_method, _token: $("input[name='_token']").val()},
            success: function (result)
            {
                currentOBJ.attr("disabled", false);
                $('#AjaxLoaderDiv').fadeOut(100);
                if (result.status == 1)
                {
                    $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                    // window.location = result.redirect_url;
                      $("#add-money-from-order").modal('hide');
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
    
});
</script>
<script type="text/javascript" src="{{ asset('js/order.js?48212125') }}" ></script>
@endsection
