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
                            <th align="left" width="20%">User Name</th>
                            <th align="left">Address</th>
                            <th align="left" width="15%">Total + Delivery Charge</th>
                            <th width="5%">status</th>
                            <th width="20%">Action</th>
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
@endsection

@section('scripts')

<script type="text/javascript">
     $(document).ready(function(){
        $('body').on('click', '.change-status', function(event){
        var target_id       = $(this).attr('data-id');
        if(confirm($(this).data('msg'))){ 
            var _token          = $('input[name="_token"]').val();
            var target_path     = $('#change_status_url').val();
            var _row            = $(this).data('row');
            $.ajax({
                "url": target_path+'/'+target_id,
                "type": "POST",
                data: { '_token':_token , 'id' : target_id},
                success: function(result){
                    if(result.status == true)
                    {   
                        if(result.html == ''){ 
                            if($("#status"+target_id).html() == 'Pending'){
                                $("#status"+target_id).html('Delivered');
                                $("#status"+target_id).addClass('btn-success');    
                                $("#status"+target_id).removeClass('btn-info');
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
                { data: 'totalPrice' , name: 'totalPrice'},
                { data: 'order_status', name: 'order_status'},
                { data: 'action', orderable: false, searchable: false,className:'detail-td'},
            ]
        });

    });
</script>
@endsection
