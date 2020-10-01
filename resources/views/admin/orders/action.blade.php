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
			<a data-row="{{ $row->id }}" class="btn btn-info btn-xs change-status" data-id=" {{ $row->id }}" title="Make Delivered" href="javascipt:;" data-msg="Are you sure want to change status as delivered?" id="delivered">Delivered</a>
		</li>
		<li>
		 	<a data-row="{{ $row->id }}" class="btn btn-default btn-xs change-status" data-id="{{ $row->id }} " title="Make Cancel" href="javascipt:;" data-msg = "Are you sure want to change status as cancel?" id="cancel">Cancel</a>
		</li>
	</ul>
</div>
@if(empty($row->delivery_master_id))
<a data-id="{{ $row->id }}" class="btn btn-xs btn-primary assign-delivery-boy" title="Assign Delivery Boy" data-row ="{{$row->delivery_master_id }}">
    <i class="fa fa-plus"></i>
</a>
@endif
@endif
</div>
