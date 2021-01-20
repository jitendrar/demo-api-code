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
                            <th width="5%">Quantity</th>
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

            $(document).on('click', '.btn-delete-offer', function () {

                $text = 'Are You sure you wish to delete offer ?';

                if (confirm($text))
                {
                    $url = $(this).attr('href');
                    $('#global_delete_form').attr('action', $url);
                    $('#global_delete_form #delete_id').val($(this).data('id'));
                    $('#global_delete_form').submit();
                }

                return false;
            });

            $(document).on('click', '.change-offer-status', function () {
                
                $status = $(this).data('status');
                if($status == 1){
                    $text = 'Are You sure you wish to inactive offer ?';
                }else{
                    $text = 'Are You sure you wish to active offer ?';
                }

                if (confirm($text))
                {
                    $url = $(this).attr('href');
                    window.location = $url;
                }

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
                        data.search_product_name = $("#search-frm input[name='search_product_name']").val();
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
                {data: 'picture', name: 'picture'},
                { data: 'product_name', name: 'product_translations.product_name'},
                { data: 'quantity' , name: 'quantity'},
                { data: 'status', name: 'status' },
                { data: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endsection
