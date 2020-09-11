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
                            <th align="left">Image</th>
                            <th align="left">Product Name</th>
                            <th align="left" width="10%">Category</th>
                            <th width="5%">Unit Stock</th>
                            <th width="5%">Unit Type</th>
                            <th width="5%">Price</th>
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
                        data.search_pnm = $("#search-frm input[name='search_pnm']").val();
                        data.search_ut = $("#search-frm input[name='search_ut']").val();
                        data.category = $("#search-frm select[name='category']").val();
                        data.search_status = $("#search-frm select[name='search_status']").val();  
                    }
                },
                lengthMenu:
                [
                [5,25,50,100,150,200],
                [5,25,50,100,150,200]
                ],
                "order": [[ 0, "desc" ]],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'picture', name: 'picture'},
                { data: 'product_name', name: 'product_name'},
                { data: 'catName' , name: 'categories.name'},
                { data: 'units_in_stock' , name: 'units_in_stock'},
                { data: 'units_stock_type' , name: 'units_stock_type'},
                { data: 'unity_price' , name: 'unity_price'},
                { data: 'status', name: 'status' },
                { data: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endsection
