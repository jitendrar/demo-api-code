@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <div class="page-content-inner">
        @include($moduleViewName.".search") 
        <div class="clearfix"></div>    
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list"></i>{{ $module_title }} </div>
                    @if($btnAdd)
                        <a href="{{ $add_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-plus"></i> {{ $addBtnName }}</a>
                    @endif
            </div>
            <div class="portlet-body">
                <div class="clearfix">&nbsp;</div>
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                    <thead>
                        <tr>
                            <th width="1%">Id</th>
                            <th width="3%">Order No</th>
                            <th align="left">User Name</th>
                            <th align="left">Product Name</th>
                            <th align="left">Address</th>
                            <th align="left" width="5%">Total Price</th>
                            <th align="left" width="5%">Delivery Charge</th>
                            <th width="3%">Quantity</th>
                            <th width="3%">status</th>
                            <th align="left" width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
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
                        data.search_fnm = $("#search-frm input[name='search_fnm']").val();
                        data.search_pnm = $("#search-frm input[name='search_pnm']").val();
                        data.search_oid = $("#search-frm input[name='search_oid']").val();
                        data.search_status = $("#search-frm select[name='search_status']").val();  
                    }
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
                { data: 'product_name', name: 'product_translations.product_name'},
                { data: 'address_line_1' , name: 'addresses.address_line_1'},
                { data: 'total_price' , name: 'total_price'},
                { data: 'delivery_charge' , name: 'delivery_charge'},
                { data: 'quantity', name: 'order_details.quantity'},
                { data: 'order_status', name: 'order_status'},
                { data: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endsection
