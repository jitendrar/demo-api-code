<div class="custom-flexBox">
@if(isset($isView) && $isView)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.show',['order' => $row->id]) }}" class="btn btn-xs btn-success" title="View">
    <i class="fa fa-eye"></i>
</a>
@endif

@if(isset($isDelete) && $isDelete)
@if(($row->order_status != 'Delivered'))
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy',['order' => $row->id]) }}" class="btn btn-xs btn-danger btn-delete-record" title="Delete">
    <i class="fa fa-trash-o"></i>
</a>@endif
@endif
</div>