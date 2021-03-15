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
                </div>
            <div class="portlet-body">
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables" width="100%">
                    <thead>
                        <tr>
                            <th width="1%">Id</th>
                            <th width="5%">Order No</th>
                            <th align="left" width="20%">Name</th>
                            <th align="left" width="15%">Total + Delivery Charge</th>
                            <th align="left">Delivery User</th>
                            <th width="5%" id="status-id">status</th>
                            <th width="25%">Action</th>
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
            [25,50,100,150,200],
            [25,50,100,150,200]
            ],
            "order": [[ 0, "desc" ]],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'order_number', name: 'order_number'},
                { data: 'userName', name: 'userName'},
                { data: 'totalPrice' , name: 'totalPrice',className:'totalprice_td',orderable: false},
                { data: 'deliveryUser' , name: 'deliveryUser',orderable: false},
                { data: 'order_status', name: 'order_status',className:'order_status_td'},
                { data: 'action', orderable: false, searchable: false,className:'detail-td'},
            ]
    });
});
</script>
<script type="text/javascript" src="{{ asset('js/order.js?48212125') }}" ></script>
@endsection
