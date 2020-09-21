<div class="custom-flexBox">
@if(isset($isEdit) && $isEdit)
<a href="{{ route($currentRoute.'.edit',['Order' => $row->id]) }}" class="btn btn-sm green" width="10px" title="Edit">
<i class="fa fa-edit"></i>
</a>
@endif
@if(isset($isView) && $isView)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.show',['Order' => $row->id]) }}" class="btn btn-sm yellow" title="View">
    <i class="fa fa-eye"></i>
</a>
@endif

@if(isset($isDelete) && $isDelete)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy',['Order' => $row->id]) }}" class="btn btn-sm red btn-delete-record" title="Delete">
    <i class="fa fa-trash-o"></i>
</a>
@endif
</div>