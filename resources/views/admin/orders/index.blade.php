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
                    <i class="fa fa-list"></i>{{ $module_title }} </div>
            </div>
            <div class="portlet-body">
                <div class="clearfix">&nbsp;</div>
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                    <thead>
                        <tr>
                            <th width="1%">Id</th>
                            <th width="5%">Order No</th>
                            <th align="left" width="15%">User Name</th>
                            <th align="left">Address</th>
                            <th align="left" width="15%">Total + Delivery Charge</th>
                            <th width="5%">status</th>
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
<input type="hidden" name="change_status_url" id="change_status_url" value="{{ url('admin/orders/changeStatus/') }}">
<div class="modal fade bs-modal-lg" id="assign-delivery-boy" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Assign Delivery Boy</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Find Delivery Boy By Name</label>
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::select('load_delivery_user_id',[''=>'Search User']+$deliveryUsers,null,['class'=>'form-control  select_user search-select']) !!}                     
                            </div>                            
                        </div>                                
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            {!! csrf_field() !!}
            <input type="hidden" name="load_delivery_user_id" id="load_delivery_user_id" />
            <button data-href="" type="button" class="btn btn-primary btn-submit-assign-driver">Assign</button>
        </div>
      </div>
      
    </div>
</div>
@endsection

@section('scripts')

<script type="text/javascript">
    function qntCalculation(id,qty,data_type,main_order_id)
    {
            var order_id = $('#cart_id').attr('data-id');
            var url = "{{ url('admin/changeQty') }}" + '/'+id;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url:url,
                method:"POST",
                data:{ 'qty' : qty ,'id':id ,'order_id':order_id,'data_type':data_type },
                success:function(data){
                    if(data.status == 1){
                         $('#productPrice_'+id).val(parseFloat(data.data).toFixed(2));
                         $('#total_price').val(parseFloat(data.total_price).toFixed(2));
                         $('#qty_'+id).val(data.req_qtn);
                         console.log(data.price_del_charge);
                         $('#tr-'+main_order_id).find('.totalprice_td').html(parseFloat(data.price_del_charge).toFixed(2));
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

        $(document).on('click','.show-order-detail',function(){
            var tr = $(this).closest('tr');
            var id = $(this).attr('data-id');
            var url = "{{ url('admin/orders/detail') }}" + '/'+id;
                if($(".open-order-details-cls").is(":visible")){
                    $('.order_detail_tr').remove();
                    return false;
                }else{
                $('.order_detail_tr').remove();
                var row = oTableCustom.row(tr);
                 $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        url:url,
                        method:"POST",
                        data:$(this).serialize(),
                        dataType:"json",
                        success:function(data){
                        jQuery('<tr class="order_detail_tr"><td colspan="10">'+data.html+'</td></tr>').insertAfter($('#tr-'+id).closest('tr'));
                       }
                    })
                }
        });
        
        $("#search-frm").submit(function(){
            oTableCustom.draw();
            return false;
        });
        
        $.fn.dataTableExt.sErrMode = 'throw';

        var oTableCustom = $('#server-side-datatables').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    "url": "{!! route($moduleRouteText.'.data') !!}",
                    "data": function ( data )
                    {
                        data.search_id = $("#search-frm input[name='search_id']").val();
                        data.search_fnm = $("#search-frm select[name='search_fnm']").val();
                        data.search_oid = $("#search-frm input[name='search_oid']").val();
                        data.search_status = $("#search-frm select[name='search_status']").val();  
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
                    { data: 'first_name', name: 'users.first_name'},
                    { data: 'address_line_1' , name: 'addresses.address_line_1'},
                    { data: 'totalPrice' , name: 'totalPrice',className:'totalprice_td',orderable: false},
                    { data: 'order_status', name: 'order_status'},
                    { data: 'action', orderable: false, searchable: false,className:'detail-td'},
                ]
        });

        $(document).on("click",".assign-delivery-boy",function(){
            var id = $(this).data("id");
            var row = $(this).data("row");
            $("#assign-delivery-boy #load_delivery_user_id").val(id);
            $(".select_user").val(row).trigger("change");
            $("#assign-delivery-boy").modal();
        });

        $(document).on("click",".btn-submit-assign-driver",function(){

            $order_id = $("#assign-delivery-boy #load_delivery_user_id").val();
            var id  = $("#assign-delivery-boy #load_delivery_user_id").val();
            var url = "{{ url('admin/orders/assign-delivery-boy') }}" + '/'+id;
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
            qntCalculation(date_id,qty,data_type,main_order_id );
        });
         

        $(document).on('click','.remove_prod',function(){
            //e.preventDefault();
            var confirm = deleteFunction();
            if(confirm == true){
            var id = $(this).attr('data-id');
            var url = "{{ url('admin/deleteProduct') }}" + '/'+id;

             $.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url:url,
                method:"POST",
                data:{'id':id},
                success:function(result){
                    $('#AjaxLoaderDiv').fadeOut(100);
                    if (result.status == 1)
                    {
                        $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                    }
                    else
                    {
                        $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
                    }
                    oTableCustom.draw();
                }
            });

            }
            return false;
        });
    });
    
    function deleteFunction(){
        var r = confirm("are you sure want to delete?");
        return r;
    }
</script>
@endsection
