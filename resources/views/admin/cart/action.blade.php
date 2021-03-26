<div class="custom-flexBox">
@if(isset($isView) && $isView)

@endif

@if(isset($isDelete) && $isDelete)
@if(($row->order_status != 'Delivered'))
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy',['order' => $row->id]) }}" class="btn btn-xs btn-danger btn-delete-record" title="Delete">
    <i class="fa fa-trash-o"></i>
</a>@endif
@endif

@if(isset($isProductDetail) && $isProductDetail)
<a data-id="{{ $row->user_id }}" class="btn btn-xs btn-primary show-order-detail" title="Order Detail">
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
			<a data-row="{{ $row->id }}" class="btn btn-info btn-xs change-status" data-id=" {{ $row->id }}" title="Make Delivered" href="javascipt:;" data-msg="Are you sure want to change status as delivered with payment?" id="DeliveredWithPayment">Delivered With Payment</a>
		</li>
		<li>
		 	<a data-row="{{ $row->id }}" class="btn btn-default btn-xs change-status" data-id="{{ $row->id }} " title="Make Cancel" href="javascipt:;" data-msg = "Are you sure want to change status as cancel?" id="cancel">Cancel</a>
		</li>
	</ul>
</div>
@endif

</div>
