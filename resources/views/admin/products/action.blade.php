<div class="custom-flexBox">
@if(isset($isEdit) && $isEdit)
<a href="{{ route($currentRoute.'.edit',['product' => $row->id]) }}" class="btn btn-xs btn-primary" width="10px" title="Edit">
<i class="fa fa-edit"></i>
</a>
@endif
@if(isset($isView) && $isView)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.show',['product' => $row->id]) }}" class="btn btn-xs btn-success" title="View">
    <i class="fa fa-eye"></i>
</a>
@endif

@if(isset($isDelete) && $isDelete)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy',['product' => $row->id]) }}" class="btn btn-xs btn-danger btn-delete-record" title="Delete">
    <i class="fa fa-trash-o"></i>
</a>
@endif


@if(isset($isStatus) && $isStatus)
<a data-id="{{ $row->id }}" href="{{ route('changeStatus',['id' => $row->id,'status'=>$row->status]) }}" class="btn btn-xs  btn-default active" title="status">
    <i class="fa fa-check-circle"></i>
</a>
@endif

</div>