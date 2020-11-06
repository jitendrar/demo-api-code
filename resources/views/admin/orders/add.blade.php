@extends('admin.layouts.layout')

@section('content')
<style type="text/css">
.OrderDetailsForm {
	background-color: #f7f8f9 !important;
}
.validateBorder{
	border-width: 1px !important;
	box-shadow: inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(147,161,187,0.6) !important;
	transition: border-color ease-in-out 0.15s,box-shadow ease-in-out 0.15s !important;
	border-color: #93a1bb !important;
}
</style>
<div class="container">
    <div class="page-content-inner">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i>Create Order
                </div>
                <a class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;" href="{{ $list_url }}">Back</a>
            </div>
            <div class="portlet-body">
	            <div class="form-body">
		            {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url],'class' => 'form-horizontal', 'id' => 'submit-form', 'redirect-url'=>$list_url]) !!}
		            <fieldset class="scheduler-border">
		                <legend class="scheduler-border">User Details</legend>
			                
		                <div class="row">
		                    <div class="col-md-6">
		                        <div class="form-group">
		                        	<label class="col-md-3 control-label">User Name:<span class="required">*</span></label>
		                            <div class="col-md-9">
		                                {!! Form::select('user_id',[''=>'Search User']+$users,null,['class'=>'form-control search-select Suserid', 'id'=>'UserId', 'required'=>'required']) !!}
		                                <a href="{{ $user_add_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-plus"></i> {{ $userAddBtnName }}</a>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-md-6">
		                        <div class="form-group">
		                            <label class="col-md-3 control-label">Balance:</label>
		                            <div class="col-md-9">
		                                {!! Form::text('balance',null,['class'=>'form-control','placeholder'=>'Balance','readonly'=>'readonly', 'id' => 'availablebalance']) !!}
		                            </div>
		                        </div>
		                    </div>
		                </div>

		                <div class="row">
		                    <div class="col-md-12">
		                    	<div class="row">
		                    		<div class="col-md-6">
				                        <div class="form-group">
				                            <label class="col-md-3 control-label">Delivery Address:</label>
				                            <div class="col-md-9" id="AddAdressForOrder">
				                            </div>
				                        </div>
				                    </div>
		                    	</div>
		                    </div>
		                </div>

		                <input type="hidden" name="timeslot" id="timeslotjson" value="{{json_encode($timeslot)}}">
		                
		                <div class="row">
		                    <div class="col-md-6">
		                        <div class="form-group">
		                        	<label class="col-md-3 control-label">Delivery Date : </label>
		                            <div class="col-md-9">
		                            	{!! Form::text('delivery_date',null,['class'=>'form-control','placeholder'=>'Delivery Date','id'=>'DeliveryDate']) !!}
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-md-6">
		                        <div class="form-group">
		                            <label class="col-md-3 control-label">Delivery Time:</label>
		                            <div class="col-md-9">
		                                {!! Form::select('delivery_time',[''=>'Search User'],null,['class'=>'form-control search-select', 'id'=>'delivery_time', 'required'=>'required']) !!}
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </fieldset>

		            <fieldset>
						<legend>Order Details</legend>
				            <div class="row">
			                    <div class="col-md-6">
			                        <div class="form-group">
			                        	<label class="col-md-3 control-label">Delivery Charge : </label>
			                            <div class="col-md-9">
			                            	{!! Form::text('delivery_charge',$delivery_charge,['class'=>'form-control', 'id' =>'delivery_charge', 'placeholder' => 'Delivery Charge']) !!}
			                            </div>
			                        </div>
			                    </div>
			                    <div class="col-md-6">
			                        <div class="form-group">
			                            
			                        </div>
			                    </div>
			                </div>

				            <div id="OrderDetailsForm" class="OrderDetailsForm">
					            <div class="row">
					            	<div class="col-md-6">
				                        <div class="form-group">
				                        	<label class="col-md-3 control-label">Find Category:<span class="required">*</span></label>
				                            <div class="col-md-9">
				                                 {!! Form::select('category[]',[''=>'--- Select --- ']+$categories,null,['class'=>'Scategory form-control  select_category search-select', 'id' => 'category-0', 'required'=>'required', 'data-product' => 'product-0']) !!}
				                            </div>
				                        </div>
				                    </div>
				                    <div class="col-md-6">
				                        <div class="form-group">
				                        	<label class="col-md-3 control-label">Find Product:<span class="required">*</span></label>
				                            <div class="col-md-9">
				                                 {!! Form::select('product[]',[''=>'Search Product'],null,['class'=>'Sproduct form-control  select_product search-select', 'id'=>'product-0', 'required' =>'required', 'data-stocktype' => 'stocktype-0', 'data-stockprice' => 'stockprice-0']) !!}
				                            </div>
				                        </div>
				                    </div>
					            </div>

					            <div class="row">
					            	<div class="col-md-6">
				                        <div class="form-group">
				                        	<label class="col-md-3 control-label">Stock Type :<span class="required">*</span></label>
				                            <div class="col-md-9">
				                                 {!! Form::text('StockType[]',null, ['class'=>'form-control', 'placeholder'=>'Stock Type', 'readonly'=>'readonly', 'id' => 'stocktype-0']) !!}
				                            </div>
				                        </div>
				                    </div>
				                    <div class="col-md-6">
				                        <div class="form-group">
				                        	<label class="col-md-3 control-label">Stock Price :<span class="required">*</span></label>
				                            <div class="col-md-9">
				                                 {!! Form::text('StockPrice[]', null, ['class'=>'form-control StockPriceForCount', 'placeholder'=>'Stock Price', 'readonly'=>'readonly', 'id' => 'stockprice-0']) !!}
				                            </div>
				                        </div>
				                    </div>
					            </div>

					            <div class="row">
					            	<div class="col-md-6">
				                        <div class="form-group">
				                        	<label class="col-md-3 control-label">Quantity :<span class="required">*</span></label>
				                            <div class="col-md-9">
				                                 <div class="col-md-4" style="margin-left: -14px;">
													<input id="quantity-0" readonly="readonly" data-stockprice="stockprice-0" type="text" value="1" name="quantity[]" class="form-control QuantityForCount" style="width: 60px;" required="required" >
						                        </div>
						                        <div class="col-md-2" style="width: 10%;margin: 5px 0px 0px -50px;">
						                            <span class="input-group-btn">
						                                <button data-quantity="quantity-0" class="btn-xs add-product-qnt-cal-btn btn red bootstrap-touchspin-down btn-qty-minus pull-right" type="button" data-type="dec">-</button>
						                            </span>
						                        </div>
						                        <div class="col-md-2" style="width: 10%;margin: 5px 0px 0px -20px;">
						                            <span class="input-group-btn">
						                                <button data-quantity="quantity-0" class="btn-xs add-product-qnt-cal-btn btn blue bootstrap-touchspin-up btn-qty-plus pull-left"  type="button" data-type="inc">+</button>
						                            </span>
						                        </div>

				                            </div>
				                        </div>
				                    </div>
				                    <div class="col-md-6">
				                        <div class="form-group">
				                        	<label class="col-md-3 control-label"></label>
				                            <div class="col-md-9">

				                            </div>
				                        </div>
				                    </div>
					            </div>
				            </div>
		             		
		             		<div class="col-md-12" style="margin-top: 15px;">
		                 		<div id="daily_task">
		                 		</div>
		             		</div>

			                <div class="fix_div" style="margin-top: 5px;">
			                	<a href="javascript:;" data-repeater-create class="btn btn-success mt-repeater-add" style="margin-left: 15px;" title="Add New Task" onclick="daily_task();">
			                		<i class="fa fa-plus"></i></a>
			                </div>
		            </fieldset>

		            <fieldset>
		            	<legend>Total Details</legend>
				            <div class="row">
				                <div class="col-md-12">
				                    <div class="col-md-6">
				                        <div class="form-group">
				                            Total Price :: <span id="TotalPriceOfProduct" > </span>
				                        </div>
				                    </div>
				                </div>
				            </div>
			        </fieldset>

		            <div class="form-actions">
		                <div class="row">
		                    <div class="col-md-12" align="center">
		                        <button type="button" class="btn btn-success" id="submitBtn">{!! $buttonText !!}</button>
		                        <a href="{{ $list_url }}" class="btn btn-default"><i class="fa fa-remove"></i>Cancel</a>
		                    </div>
		                </div>
		            </div>
	            </div>
	            {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/jquery-ui.js?786') }}" ></script>
<link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet" id="style_components" type="text/css" />
<script type="text/javascript" src="{{ asset('js/create-order.js?786') }}" ></script>
<script type="text/javascript" src="{{ asset('js/add-more-create-order.js?786') }}" ></script>

<script type="text/javascript">
	$("#DeliveryDate").datepicker({
		numberOfMonths: 1,
		minDate: 0,
		dateFormat: 'dd-mm-yy',
		onSelect: function (selected) {

			var timeslot = $("#timeslotjson").val();
			var timeslot = $.parseJSON(timeslot);
			var from 	 = $(this).val().split("-");
			var selected = new Date(from[2], from[1] - 1, from[0]);

			var fullDate 	= new Date();
			var curr_hour = fullDate.getHours();

			var currentDate = new Date();
			currentDate.setHours(0, 0, 0, 0);

			let selectStartScan = $('#delivery_time');
			selectStartScan.empty();
			selectStartScan.append('<option selected="true" value=""> --- Select ---</option>');
			selectStartScan.prop('selectedIndex', 0);
			$.each(timeslot, function (key, value) {
				if(selected <= currentDate ) {
					if(key >= curr_hour){
						selectStartScan.append($('<option></option>').attr('value', key).text(value));
					}
				} else {
					selectStartScan.append($('<option></option>').attr('value', key).text(value));
				}
			});
		}
	});
</script>
@endsection