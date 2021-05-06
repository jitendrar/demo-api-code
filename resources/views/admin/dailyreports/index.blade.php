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
                  &nbsp;                     
                    <div class="caption pull-right " >
                        <i class="fa fa-bookmark"></i>
                        <span class="caption-subject bold">Total {{ $total_profit_loss_amount }}</span>
                    </div>&nbsp;
                     <div class="caption pull-right " >
                        <i class="fa fa-bookmark"></i>
                        <span class="caption-subject bold">Total Bill Amount: {{ $total_billing_amount }}</span>
                    </div>&nbsp;
                     <div class="caption pull-right " >
                        <i class="fa fa-bookmark"></i>
                        <span class="caption-subject bold">Total Collection Amount: {{ $total_collection_amount }}</span>
                    </div>&nbsp;


            </div>
            <div class="portlet-body">
                <div class="clearfix">&nbsp;</div>
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Collection Amount</th>
                            <th>Refund Amount</th>
                            <th>Final Collection Amount</th>
                            <th>Purchase Bill Price</th>
                            <th>Profit & Loss</th>
                            
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
    var oTableCustom = oTableCustom;
    $(document).ready(function()
    {
        $(".search_date_picker").datepicker({
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd'
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
                    "data": function ( data ) {
                        data.search_start_date = $("#search-frm input[name='search_start_date']").val();
                        data.search_end_date = $("#search-frm input[name='search_end_date']").val();
                    }
                },
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    $(nRow).attr('id', 'tr-'+aData['id']);
                },
                lengthMenu:
                [
                [50,100,150,200],
                [50,100,150,200]
                ],
                "order": [[ 0, "DESC" ]],
                columns: [
                        {data: 'bill_date'},
                        { data: 'collection_amount', name: 'collection_amount',className:'detail-td'},
                        { data: 'refund_amount',  name: 'refund_amount', className:'detail-td'},
                        { data: 'total_amount', name: 'total_amount'},
                        { data: 'purchase_bill_amount', name: 'purchase_bill_amount'},
                        { data: 'profit_loss', name: 'profit_loss'},
                ]
        });
        $(document).on('click','.zoomimage',function(e){
            var images = $(this).attr('src');
            var img='<img  src="'+images+'" class="img-rounded zoomimage" width="100%" height="100%" border="2" align="middle">';
            $("#modalimages").html(img);
            $("#view-billing-images").modal();
        });

    });
</script>
@endsection

