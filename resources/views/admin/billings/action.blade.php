<div class="custom-flexBox">
@if(isset($isDelete) && $isDelete)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy', $row->id) }}" class="btn btn-xs btn-danger btn-delete-bill" title="Delete">
    <i class="fa fa-trash-o"></i>
</a>
@endif
</div>
