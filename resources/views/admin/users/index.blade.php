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
                            <th align="left">Name</th>
                            <th align="left" width="10%">Phone No</th>
                            <th align="left" width="10%">Balance</th>
                            <th width="3%">Status</th>
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

<!-- Modal -->
<div class="modal fade" id="add-money-model" role="dialog">
<div class="modal-dialog">
  <!-- Modal content-->
<div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Add Money</h4>
    </div>
    <div class="modal-body">

        <form class="form-group" id="add-money-form" action="" method="post" redirect-url= "{{ $list_url }}">
            @csrf
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>Add Money<span class="required">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter Amount" data-required="true"/>
                            <span class="input-group-addon"><span class="fa fa-money"></span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>Description<span class="required">*</span></label>
                        <div class="input-group">
                             {!! Form::textarea('description',null,['class'=>'form-control','cols' =>100,'rows' =>5,'maxlength' => "400",'placeholder' =>'Description']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="addmoneyBtn">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
  
</div>
</div>
@section('scripts')


<script type="text/javascript">
    function openAddMoneyModel(id)
    {
      var url = "{{ url('admin/addmoney') }}" + '/' + id;
      jQuery('#add-money-model').modal();
      jQuery('#add-money-form').attr('action',url);

    }
     $(document).ready(function(){
            $('#add-money-form').submit(function () {
                if (true)
                {
                    $('#addmoneyBtn').attr('disabled',true);
                    $('#AjaxLoaderDiv').fadeIn('slow');

                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        url: $(this).attr("action"),
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        enctype: 'multipart/form-data',
                        success: function (result)
                        {
                            $('#addmoneyBtn').attr('disabled',false);
                            $('#AjaxLoaderDiv').fadeOut('slow');
                            if (result.status == 1)
                            {
                                $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                                window.location = $('#add-money-form').attr('redirect-url');
                            }
                            else
                            {
                                $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
                            }
                        },
                        error: function (error)
                        {
                            $('#addmoneyBtn').attr('disabled',false);
                            $('#AjaxLoaderDiv').fadeOut('slow');
                            $.bootstrapGrowl(internalServerERR, {type: 'danger', delay: 4000});
                        }
                    });
                }
                return false;
            });

            $(document).on('click','.show-wallet-history',function(){
            var tr = $(this).closest('tr');
            var id = $(this).attr('data-id');
            var url = "{{ url('admin/users/wallet_history') }}" + '/'+id;
            $('.wallet_history_tr').remove();
            var row = oTableCustom.row(tr);
             $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url:url,
                    method:"POST",
                    data:$(this).serialize(),
                    dataType:"json",
                    success:function(data){
                     jQuery('<tr class="wallet_history_tr"><td colspan="8">'+data.html+'</td></tr>').insertAfter($('#tr-'+id).closest('tr'));
                   },
                   error: function (error)
                    {
                        console.log(error);
                    }
                })
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
                    "data": function ( data )
                    {
                        data.search_id = $("#search-frm input[name='search_id']").val();
                        data.search_fnm = $("#search-frm select[name='search_fnm']").val();
                        data.search_pno = $("#search-frm input[name='search_pno']").val();
                        data.search_status = $("#search-frm select[name='search_status']").val();       
                    }
                },
                 "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    $(nRow).attr('id', 'tr-'+aData['id']);
                },
                lengthMenu:
                [
                [25,50,100,150,200],
                [25,50,100,150,200]
                ],
                "order": [[ "0", "desc" ]],
                columns: [
                    {data: 'id', name: 'id'},
                    { data: 'first_name', name: 'first_name'},
                    { data: 'phone' , name: 'phone'},
                    { data: 'balance' , name: 'balance',orderable: false, searchable: false },
                    { data: 'status', name: 'status' },
                    { data: 'action', orderable: false, searchable: false},
                ]
        });
    });
</script>
@endsection
