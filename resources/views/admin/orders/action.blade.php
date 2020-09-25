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

@if(isset($isProductDetail) && $isProductDetail)
<a data-id="{{ $row->id }}" class="btn btn-xs btn-primary show-order-detail" title="Order Detail">
    <i class="fa fa-eye"></i>
</a>
@endif

@if(isset($isStatus) && $isStatus)
<div class="btn-group">
<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Status
<i class="fa fa-angle-down"></i>
</button>
<ul class="dropdown-menu" role="menu" style="min-width: 80px !important;">
<li>
<a data-row="{{ $row->id }}" class="btn btn-info btn-xs change-status" data-id=" {{ $row->id }}" title="Make Delivered" href="javascipt:;" data-msg="Are you sure want to change status as delivered?" id="status{{$row->id}}">Delivered</a>
</li>
<li>
 <a data-row="{{ $row->id }}" class="btn btn-default btn-xs" data-id="{{ $row->id }} " title="Make Cancel" href="#" id="status'.$row->id.'">Cancel</a>
</li>
</ul>
</div>
@endif
</div>
