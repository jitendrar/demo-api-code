<div class="portlet box green form-fit open-order-details-cls open-order-details-cls-{{$order->id}}" id="order-detail">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa$order"></i>Order Detail
        </div>
           <!--  <a href="{{ $list_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-arrow-left"></i>Back</a> -->
    </div>
    <div class="portlet-body">
        <div class="clearfix">&nbsp;</div>
        <table>
            <tr>
                <th>Address</th>
                <td>{{ $address->address_line_1??'' }} ,{{ $address->address_line_2??'' }}, {{ $address->city??'' }} , {{ $address->zipcode??'' }}</td>
            </tr>
            <tr>
                <th>Delivery Charge:</th>
                <td>{{ $order->delivery_charge }}</td>
                @if(!empty($order->delivery_master_id))
                <th>Delivery User Name:</th>
                    <td>{{ $deliveryUser->first_name.' '.$deliveryUser->last_name}}</td>
                @endif
            </tr>
            <tr>
                <th>Delivery Date:</th>
                <td>{{ date('d-m-Y',strtotime($order->delivery_date)) }}</td>
                <th>Delivery Time:</th>
                <td> {{ $order->delivery_time }}</td>
            </tr>
            <tr>
                <th>Actual Delivery Date:</th>
                <td>
                    <?php
                        if(!empty($order->actual_delivery_date)){
                            echo date('d-m-Y',strtotime($order->actual_delivery_date));
                        } else {
                            echo "-";
                        }
                    ?>
                </td>
                <th>Actual Delivery Time:</th>
                <td>
                    <?php
                        if(!empty($order->actual_delivery_time)){
                            $time = $order->actual_delivery_date.' '.$order->actual_delivery_time;
                            echo date('h:i:s A',strtotime($time));
                        } else {
                            echo "-";
                        }
                    ?>
                </td>
            </tr>
        </table>
        <div class="clearfix">&nbsp;</div>
        <form name="cart" class="form-group">
        <div class="portlet-title">
            @if($order->order_status != 'D')
            <div class="caption">
                <a data-id="{{ $order->id }}" class="btn btn-sm btn-primary pull-right add_product" title="Add Product" data-row ="{{ $order->id }}">
                     <i class="fa fa-plus"></i>Add Product
                </a>
            </div>
            @endif
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>
        <table class="table table-bordered table-striped table-condensed flip-content" id="cart_id" name="cart" data-id="{{ $order->id }}"> 
            <thead>
                <tr class="bold">
                    @if($order->order_status != 'D')
                    <th></th>
                    @endif
                    <!-- <th width="5%">Id</th> -->
                    <th width="20">Product Name</th>
                    <th width="15%">Price</th>
                    <th width="15%">Quantity</th>
                    <th width="20%">Discount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderDetail as $detail)
                <?php
                    $FreeOfferproductClass = '';
                    if($detail->is_offer == 1) {
                        $FreeOfferproductClass = 'FreeOfferproductClass';
                    }
                ?>
                <?php $image = App\Product::getAttachment($detail->product_id);?>
                <tr name="order_lines" data-id="{{$detail->id}}" id="order_lines_{{$detail->id}}" class="{{$FreeOfferproductClass}}">
                    @if($order->order_status != 'D')
                    <td width="5%"><button type="button" name="remove_prod" data-id ="{{ $detail->id }}" class="btn btn-danger btn-sm remove_prod" id="remove_prod" main-data-id ="{{  $detail->order_id }}"><i class="fa fa-minus"></i></button></td>
                    @endif
                    <!-- <td width="5%">{{ $detail->id }}</td> -->
                    <td width="20">
                        <?php echo $detail->product->product_name.' ('.$detail->details_units_in_stock_total.'/'.$detail->details_units_stock_type.') ('.$detail->product->units_in_stock.'/'.$detail->product->units_stock_type.' - ₹'.$detail->product->unity_price.')'; ?>
                        <img src="{{ $image }}" border="2" width="50" height="50" class="img-rounded thumbnail zoom" align="center" />
                    </td>
                    <td width="15%"><input type="number" name="price" value="{{ $detail->price }}" class="form-control total_product_price" id="productPrice_{{ $detail->id }}" disabled="disabled"></td>
                    <td width="25%">
                        <div class="col-md-4">
                            @if($order->order_status != 'D')
                            <input type="hidden" name="unit_price" class="form-control qty-price">
                             <span class="input-group-btn">
                                    <button class="btn-xs qnt-cal-btn btn red bootstrap-touchspin-down btn-qty-minus pull-right" data-id="{{ $detail->id }}" type="button" data-type="dec">-</button>
                                </span>
                            <span class="input-group-btn">
                                <button class="btn-xs qnt-cal-btn btn blue bootstrap-touchspin-up btn-qty-plus pull-left" data-id="{{ $detail->id }}" type="button" data-type="inc">+</button>
                            </span>
                            @endif
                            <input id="qty_{{ $detail->id }}" data-main-id="{{  $detail->order_id }}" type="text" data-id="{{ $detail->id }}" value="{{ $detail->quantity}}" <?php if ($order->order_status == 'D'){ ?> disabled <?php   } ?> name="qty" class="form-control  qty-input">
                        </div>
                    </td>
                    <td width="20%" id="ProductDiscountPrice_{{ $detail->id }}" >{{number_format(($detail->discount),2)}}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" align="center">Total Price</td>
                    <td colspan="4"><input type="number" name="total_price" value="{{ $totalPrice }}" class="form-control" id="total_price" disabled="disabled"></td>
                </tr>
            </tbody>
        </table>
    </form>
    </div>
</div>