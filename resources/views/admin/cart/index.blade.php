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
                            
                            <th width="1%">Action</th>
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
                    { data: 'created_at', name: 'created_at'},
                ]
        });
    });
</script>
<script type="text/javascript" src="{{ asset('js/order.js?5215') }}" ></script>

@endsection

