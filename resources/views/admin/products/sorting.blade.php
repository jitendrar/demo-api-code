@extends('admin.layouts.layout')
@section('content')

<div class="container">
    <div class="page-content-inner">
        <!-- @include($moduleViewName.".search")  -->
        <div class="clearfix"></div>    
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"> <i class="fa fa-list"></i>{{ $module_title }} </div>
            </div>
            <div class="portlet-body">
                <div class="clearfix">&nbsp;</div>
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                    <thead>
                        <tr>
                            <th width="1%">Id</th>
                            <th width="1%">Display Order No.</th>
                            <th align="left">Image</th>
                            <th align="left">Product Name</th>
                            <th align="left" width="10%">Category</th>
                            <th width="5%">Unit Stock</th>
                            <th width="5%">Unit Type</th>
                            <th width="5%">Price</th>
                        </tr>
                    </thead>
                    <tbody id="tablecontents">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<script type="text/javascript">
     $(document).ready(function(){
      $( "#tablecontents" ).sortable({
        items: "tr",
        cursor: 'move',
        opacity: 0.6,
        update: function() {
          sendOrderToServer();
        }
      });

      function sendOrderToServer() {
          var productorderarr = [];
          $('tr.row1').each(function(index,element) {
            productorderarr.push({
              id: $(this).attr('data-id'),
              position: index+1
            });
          });
          var token = $('meta[name="csrf-token"]').attr('content');
          $.ajax({
            type: "POST", 
            dataType: "json", 
            url: "{{ route('products.sortingupdate') }}",
            data: {productorderarr: productorderarr,_token: token},
            success: function(response) {
              $('#server-side-datatables').DataTable().draw();
            }
          });
        }

            $("#search-frm").submit(function(){
                oTableCustom.draw();
                return false;
            });
            $.fn.dataTableExt.sErrMode = 'throw';
            var oTableCustom = $('#server-side-datatables').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                bPaginate: false,
                ajax: {
                    "url": "{!! route($moduleRouteText.'.getsortdata') !!}",
                    "data": function ( data ) {
                        data.search_id = $("#search-frm input[name='search_id']").val();
                        data.search_pnm = $("#search-frm input[name='search_pnm']").val();
                        data.search_ut = $("#search-frm input[name='search_ut']").val();
                        data.category = $("#search-frm select[name='category']").val();
                    }
                },
                lengthMenu: [ [25,50,100,150,200], [25,50,100,150,200] ],
                order: [[1, "ASC" ]],
                createdRow : function( row, data, dataIndex ) {
                                $(row).addClass('row1');
                                $(row).attr('data-id', data.id);
                              },
                columns: [
                          {data: 'id', name: 'id'},
                          {data: 'display_order', name: 'display_order'},
                          {data: 'picture', name: 'picture'},
                          { data: 'product_name', name: 'product_name'},
                          { data: 'catName' , name: 'id'},
                          { data: 'units_in_stock' , name: 'units_in_stock'},
                          { data: 'units_stock_type' , name: 'units_stock_type'},
                          { data: 'unity_price' , name: 'unity_price'},
            ]
        });
    });
</script>
@endsection
