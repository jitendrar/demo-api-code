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
                <th>Delivery Charge:</th>
                <td>{{ $order->delivery_charge }}</td>
                @if(!empty($order->assign_delivery_boy_id))
                <th>Delivery User Name:</th>
                    <td>{{ $deliveryUser->first_name.' '.$deliveryUser->last_name}}</td>
                @endif
            </tr>
            <tr>
                <th>Delivery Date:</th>
                <td>{{ date('Y-m-d',strtotime($order->delivery_date)) }}</td>
                <th>Delivery Time:</th>
                <td> {{ date('h:i:s a',strtotime($order->delivery_time)) }}</td>
            </tr>
            <tr>
                <th>Actual Delivery Date:</th>
                <td>{{ date('Y-m-d',strtotime($order->actual_delivery_date)) }}</td>
                <th>Actual Delivery Time:</th>
                <td> {{ date('h:i:s a',strtotime($order->actual_delivery_time)) }} </td>
            </tr>
        </table>
        <div class="clearfix">&nbsp;</div>
        <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
            <thead>
                <tr class="bold">
                    <th width="5%">Id</th>
                    <th width="20">Product Name</th>
                    <th width="20%">Price</th>
                    <th width="15%">Quantity</th>
                    <th width="20%">Discount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderDetail as $detail)
                <tr>
                    <td width="5%">{{ $detail->id }}</td>
                    <td width="20">{{$detail->product->product_name ?? ''}}</td>
                    <td width="20%">{{number_format(($detail->price),2)}}</td>
                    <td width="15%">{{$detail->quantity}}</td>
                    <td width="20%">{{number_format(($detail->discount),2)}}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" align="center">Total Price</td>
                    <td colspan="3">{{ number_format($totalPrice,2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>