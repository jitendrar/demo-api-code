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
         
        <div class="clearfix"></div>    
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list"></i>{{ $module_title }}
                </div>
             
            </div>
            <div class="portlet-body">
                <div class="clearfix">&nbsp;</div>
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                    <thead>
                        <tr>
                            
                            <th width="2%">Action</th>
                            <th align="left" width="22%">Name</th>
                            <th align="left" width="15%">Total + Delivery Charge</th>
                            <th align="left" width="22%">Date</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin/modal_include')

@endsection

@section('scripts')

<script type="text/javascript">
    var oTableCustom = oTableCustom;
    var orderDetailURL = "{{ url('admin/cart/detail') }}";
    var adminplaceorder = "{{ url('admin/cart/placeorder') }}";

    $(document).ready(function(){
     
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
                    }
                },
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    $(nRow).attr('id', 'tr-'+aData['user_id']);
                },
                lengthMenu:
                [
                [100,150,200],
                [100,150,200]
                ],
                "order": [[ 0, "desc" ]],
                columns: [
                    { data: 'action', orderable: false, searchable: false,className:'detail-td'},
                    { data: 'userName', name: 'userName'},
                    { data: 'totalPrice' , name: 'totalPrice',className:'totalprice_td',orderable: false},
                    { data: 'updatedat', name: 'updatedat'},
                ]
        });
             $(document).on("click",".btn-cart-to-order",function(){
        var text = 'Are You sure you wish to place order ?';

        if (confirm(text))
        {
            var url = $(this).attr('href');
            var id = $(this).data('id');
            var userid = $(this).data('userid');
            var url         = adminplaceorder + '/'+id;
             $('#AjaxLoaderDiv').fadeIn(100);
                var currentOBJ = $(this);
                currentOBJ.attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {action:url, id: id,user_id:userid, _token: $("input[name='_token']").val()},
                    success: function (result)
                    {
                        currentOBJ.attr("disabled", false);
                        $('#AjaxLoaderDiv').fadeOut(100);
                        if (result.status == 1)
                        {
                            $.bootstrapGrowl(result.message, {type: 'success', delay: 5000});
                             oTableCustom.draw();
                        }
                        else
                        {
                            $.bootstrapGrowl(result.message, {type: 'danger', delay: 5000});
                        }
                    },
                    error: function (error)
                    {
                        currentOBJ.attr("disabled", false);
                        $('#AjaxLoaderDiv').fadeOut(100);
                        $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 5000});
                    }
                });

        return false;

        }

        return false;

    });

    });



</script>
<script type="text/javascript" src="{{ asset('js/order.js?5215') }}" ></script>

@endsection

