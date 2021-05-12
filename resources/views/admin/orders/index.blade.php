@extends('admin.layouts.layout')
@section('styles')
    <style type="text/css">
        .change-status{
            size: 8px;
        }
        .dropdown-menu>li>a {
            padding: 3px 3px;
        }
        table.dataTable.no-footer {
            border-bottom: inherit;
            overflow-y: visible;
        }
        .table-scrollable {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
            border: 1px solid #e7ecf1;
            margin: 10px 0!important;
        }
    </style>
@endsection
@section('content')
<div class="container">
    <div class="page-content-inner">
        @include($moduleViewName.".search") 
        <div class="clearfix"></div>    
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list"></i>{{ $module_title }}
                </div>

                @if($btnAdd)
                    <a href="{{ $add_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-plus"></i> {{ $addBtnName }}</a>
                @endif

                @if($summary)
                <form method="get" id="GetSummaryDataForm" action="{{ $summary }}">
                  <input type="hidden" name="hdaction" id="hdaction">
                  <input type="hidden" name="search_start_date_a" id="search_start_date_a">
                  <input type="hidden" name="search_end_date_a" id="search_end_date_a">
                  <input type="button" id="summaryBtnName" class="btn btn-default pull-right btn-sm mTop5" value="{{ $summaryBtnName }} " style="margin-top: 5px;margin-right: 5px;">
                  
                  <input type="button" id="todaysummaryBtnName" class="btn btn-default pull-right btn-sm mTop5" value="{{ $todaysummaryBtnName }} " style="margin-top: 5px;margin-right: 5px;">
                  
                  <input type="button" id="TodayOrderSummaryDetails" class="btn btn-default pull-right btn-sm mTop5" value="Today Order Summary Details" style="margin-top: 5px;margin-right: 5px;">
                </form>
                   
                @endif
                <a data-id="1" style="margin-top: 5px;margin-right: 5px;" class="btn btn-primary pull-right btn-sm mTop5 assign-delivery-users" title="Assign Delivery Boy" data-row ="1" >Assign Delivery User</a>
            </div>
            <div class="portlet-body">
                <div class="clearfix">&nbsp;</div>
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                    <thead>
                        <tr>
                            <th width="6%"></th>
                            <th width="6%">Order No</th>
                            <th width="20%">Action</th>
                            <th align="left" width="22%">Name</th>
                            <th align="left" width="15%">Total + Delivery Charge</th>
                            <th align="left">Delivery User</th>
                            <th width="5%">status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="change_status_url" id="change_status_url" value="{{ url('admin/orders/changeStatus/') }}">
@include('admin/modal_include')

@endsection

@section('scripts')

<script type="text/javascript">

$(document).ready(function(){
    $("#todaysummaryBtnName").click(function(){
        $("#hdaction").val('TodayData');
        $('#GetSummaryDataForm').submit();
    });

    $("#TodayOrderSummaryDetails").click(function(){
        $("#hdaction").val('DetailsOfPOS');
        $('#GetSummaryDataForm').submit();
    });


    $("#summaryBtnName").click(function(){
        // $("#search-frm").serialize();
        $("#hdaction").val('PendingOrderAllSumary');
        search_start_date_a   = $("#search-frm input[name='search_start_date']").val();
        search_end_date_a     = $("#search-frm input[name='search_end_date']").val();
        $("#search_start_date_a").val(search_start_date_a);
        $("#search_end_date_a").val(search_end_date_a);
        $('#GetSummaryDataForm').submit();
    });
});

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
            var status_name     = $(this).attr('id');
            if(confirm($(this).data('msg'))){ 
                var _token          = $('input[name="_token"]').val();
                var target_path     = $('#change_status_url').val();
                var _row            = $(this).data('row');
                $.ajax({
                    "url": target_path+'/'+target_id,
                    "type": "POST",
                    data: { '_token':_token , 'id' : target_id , 'status_name' : status_name},
                    success: function(result){
                        if(result.status == true) {
                            $.bootstrapGrowl(result.message, {type: 'success', delay: 4000});
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
        
        $("#search-frm").submit(function(){
            oTableCustom.draw();
            return false;
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

        
        $.fn.dataTableExt.sErrMode = 'throw';

         oTableCustom = $('#server-side-datatables').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    "url": "{!! route($moduleRouteText.'.data') !!}",
                    "data": function ( data )
                    {
                        data.search_id = $("#search-frm input[name='search_id']").val();
                        data.search_fnm = $("#search-frm select[name='search_fnm']").val();
                        data.search_delivery_user = $("#search-frm select[name='search_delivery_user']").val();
                        data.search_oid = $("#search-frm input[name='search_oid']").val();
                        data.search_status = $("#search-frm select[name='search_status[]']").val();  
                        data.search_start_date = $("#search-frm input[name='search_start_date']").val();
                        data.search_end_date = $("#search-frm input[name='search_end_date']").val();
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
                    {data: 'checkbox', name: 'checkbox',orderable: false, searchable: false,},
                    {data: 'order_number', name: 'order_number'},
                    { data: 'action', orderable: false, searchable: false,className:'detail-td'},
                    { data: 'userName', name: 'userName'},
                    { data: 'totalPrice' , name: 'totalPrice',className:'totalprice_td',orderable: false},
                    { data: 'deliveryUser' , name: 'deliveryUser',orderable: false},
                    { data: 'order_status', name: 'order_status',className:'order_status_td'},
                ]
        });
    });

    $('#add-money-from-order').on('hidden.bs.modal', function () {
      $("#transaction_method").val(0);
      $("#amount").val("");
      $("#description").val("");
  });
    
</script>
<script type="text/javascript" src="{{ asset('js/order.js?124') }}" ></script>
@endsection

