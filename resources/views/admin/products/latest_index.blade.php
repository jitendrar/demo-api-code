@extends('admin.layouts.layout')

@section('content')

<div class="container">
    <div class="page-content-inner">
        <div class="clearfix"></div>    
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list"></i>Latest Price Updated Products </div>
            </div>
            <div class="portlet-body">
                <div class="clearfix">&nbsp;</div>
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                    <thead>
                        <tr>
                            <th width="1%">Id</th>
                            <th align="left">Image</th>
                            <th align="left">Product Name</th>
                            <th align="left" width="10%">Category</th>
                            <th width="5%">Unit Stock</th>
                            <th width="5%">Unit Type</th>
                            <th width="5%">Price</th>
                            <th width="">Updated Date</th>
                            <th width="3%">status</th>
                           
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
                    "url": "{!! route($moduleRouteText.'.latestdata') !!}",
                    "data": function ( data )
                    {
                        data.search_id = $("#search-frm input[name='search_id']").val();
                        data.search_pnm = $("#search-frm input[name='search_pnm']").val();
                        data.search_ut = $("#search-frm input[name='search_ut']").val();
                        data.category = $("#search-frm select[name='category']").val();
                        data.search_status = $("#search-frm select[name='search_status']").val();  
                    }
                },
                lengthMenu:
                [
                [100,150,200],
                [100,150,200]
                ],
                "order": [[ 0, "desc" ]],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'picture', name: 'picture'},
                { data: 'product_name', name: 'product_name'},
                { data: 'catName' , name: 'id'},
                { data: 'units_in_stock' , name: 'units_in_stock'},
                { data: 'units_stock_type' , name: 'units_stock_type'},
                { data: 'unity_price' , name: 'unity_price'},
                { data: 'updated_at' , name: 'updated_at'},
                { data: 'status', name: 'status' },
               
            ]
        });
    });
</script>
@endsection
