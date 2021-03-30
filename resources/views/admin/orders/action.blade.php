<div class="custom-flexBox">
@if(isset($isView) && $isView)
<!-- <a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.show',['order' => $row->id]) }}" class="btn btn-xs btn-success" title="View">
    <i class="fa fa-eye"></i>
</a> -->
@endif

@if(isset($isDelete) && $isDelete)
@if(($row->order_status != 'Delivered'))
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy',['order' => $row->id]) }}" class="btn btn-xs btn-danger btn-delete-record" title="Delete">
    <i class="fa fa-trash-o"></i>
</a>@endif
@endif

@if(isset($isProductDetail) && $isProductDetail)
@php $classspecial = ''; @endphp
	@if(!empty($row->special_information))
	@php $classspecial = 'special-info'; @endphp
	@endif
	<a data-id="{{ $row->id }}" class="btn btn-xs btn-primary show-order-detail {{ $classspecial }} " title="Order Detail">
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
		<li>
			<a data-row="{{ $row->id }}" class="btn btn-warning btn-xs change-status" data-id=" {{ $row->id }}" title="Make Delivered" href="javascipt:;" data-msg="Are you sure want to change status as delivered with payment?" id="DeliveredWithPayment">Delivered With Payment</a>
		</li>
	</ul>
</div>
@endif
@if($row->order_status != 'C')
<a data-id="{{ $row->id }}" class="btn btn-xs btn-primary add-money-from-order" title="Add Money into User">
	<i class="fa fa-plus"> Add Money</i>
</a>
@endif
</div>
