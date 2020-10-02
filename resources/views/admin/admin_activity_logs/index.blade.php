@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <div class="page-content-inner">
        @include($moduleViewName.".search") 
        <div class="clearfix"></div>    
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-users"></i>{{ $module_title }} 
                </div>
            </div>
            <div class="portlet-body">
                <div class="clearfix">&nbsp;</div>
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                    <thead>
                        <tr>
                            <th width="1%">ID</th>
                            <th width="20%">Activity Type</th>
                            <th width="20%">Name</th>
                            <th width="10%">Action Id</th>
                            <th width="30%">Remark</th>
                            <th align="left" width="20%">Date</th>
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
                        data.action_id = $("#search-frm input[name='action_id']").val();
                        data.activity_type = $("#search-frm select[name='activity_type']").val();
                        data.search_start_date = $("#search-frm input[name='search_start_date']").val();
                        data.search_end_date = $("#search-frm input[name='search_end_date']").val();
                    }
                },
                lengthMenu:
                [
                [25,50,100,150,200],
                [25,50,100,150,200]
                ],
                "order": [[ 0, "ASC" ]],
                columns: [
                    {data: 'id', name: 'id'},
                    { data: 'type_name', name: 'admin_action.title' },
                    { data: 'user_name', name: 'users.firstname' },
                    { data: 'action_id', name: 'action_id' },
                    { data: 'remark', name: 'remark' },
                    { data: 'date', name: 'created_at' },
                ]
            });
        });
    </script>
@endsection