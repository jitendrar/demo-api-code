<div class="custom-flexBox">
@if(isset($isEdit) && $isEdit)
<a href="{{ route($currentRoute.'.edit',['category' => $row->id]) }}" class="btn btn-xs btn-primary" width="10px" title="Edit">
<i class="fa fa-edit"></i>
</a>
@endif
@if(isset($isView) && $isView)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.show',['category' => $row->id]) }}" class="btn btn-xs btn-success" title="View">
    <i class="fa fa-eye"></i>
</a>
@endif

@if(isset($isDelete) && $isDelete)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy',['category' => $row->id]) }}" class="btn btn-xs btn-danger btn-delete-record" title="Delete">
    <i class="fa fa-trash-o"></i>
</a>
@endif
</div>