<div class="custom-flexBox">
@if(isset($isEdit) && $isEdit)
<a href="{{ route($currentRoute.'.edit',['offer' => $row->id]) }}" class="btn btn-xs btn-primary" width="10px" title="Edit">
<i class="fa fa-edit"></i>
</a>
@endif
@if(isset($isView) && $isView)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.show',['offer' => $row->id]) }}" class="btn btn-xs btn-success" title="View">
    <i class="fa fa-eye"></i>
</a>
@endif

@if(isset($isDelete) && $isDelete)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy',['offer' => $row->id]) }}" class="btn btn-xs btn-danger btn-delete-offer" title="Delete">
    <i class="fa fa-trash-o"></i>
</a>
@endif
@if(isset($isStatus) && $isStatus)
<a data-id="{{ $row->id }}" href="{{ route('changeOfferStatus',['id' => $row->id,'status'=>$row->status]) }}" data-status="{{ $row->status }}" class="btn btn-xs  btn-default active change-offer-status" title="status">
    <i class="fa fa-check-circle"></i>
</a>
@endif
</div>