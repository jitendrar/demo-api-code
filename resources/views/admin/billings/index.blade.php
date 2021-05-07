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
                &nbsp;                     
                    <div class="caption pull-right " >
                        <i class="fa fa-bookmark"></i>
                        <span class="caption-subject bold">Total <label id="total_profit_loss_amount"></label> </span>
                    </div>&nbsp;
                     <div class="caption pull-right " >
                        <i class="fa fa-bookmark"></i>
                        <span class="caption-subject bold">Total Bill Amount: <label id="total_billing_amount"></label> </span>
                    </div>&nbsp;
                     <div class="caption pull-right " >
                        <i class="fa fa-bookmark"></i>
                        <span class="caption-subject bold">Total Collection Amount: <label id="total_collection_amount"></label> </span>
                    </div>&nbsp;

            </div>
            <div class="portlet-body">
                <div class="clearfix">&nbsp;</div>
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                    <thead>
                        <tr>
                            <th width="10%">Bill Date</th>
                            <th width="10%">Bill Price</th>
                            <th width="10%">Collection Amount</th>
                            <th width="30%">Bill Image</th>
                            <th align="left" width="50%">Description</th>
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

<div class="modal fade bs-modal-lg" id="view-billing-images" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">View Bill</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="modalimages">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            {!! csrf_field() !!}
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
                drawCallback: function(){
                      $("#total_collection_amount").text(this.api().ajax.json().total_collection_amount);
                      $("#total_billing_amount").text(this.api().ajax.json().total_billing_amount);
                      $("#total_profit_loss_amount").text(this.api().ajax.json().total_profit_loss_amount);

                },

                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    $(nRow).attr('id', 'tr-'+aData['id']);
                },
                lengthMenu:
                [
                [25,50,100,150,200],
                [25,50,100,150,200]
                ],
                "order": [[ 0, "DESC" ]],
                columns: [
                        {data: 'bill_date', name: 'bill_date'},
                        { data: 'total', className:'detail-td'},
                        { data: 'collection_amount', className:'detail-td'},
                        { data: 'picture', name: 'picture'},
                        { data: 'description' , name: 'description',orderable: false},
                        { data: 'action', orderable: false, searchable: false},
                ]
        });
        $(document).on('click','.zoomimage',function(e){
            var images = $(this).attr('src');
            var img='<img  src="'+images+'" class="img-rounded zoomimage" width="100%" height="100%" border="2" align="middle">';
            $("#modalimages").html(img);
            $("#view-billing-images").modal();
        });

        $(document).on('click', '.btn-delete-bill', function () {

            $text = 'Are You sure you wish to delete this bill ?';

            if (confirm($text))
            {
                $url = $(this).attr('href');
                $('#global_delete_form').attr('action', $url);
                $('#global_delete_form #delete_id').val($(this).data('id'));
                $('#global_delete_form').submit();
            }

            return false;
        });
    });
</script>
@endsection

