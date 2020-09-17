<div class="portlet box green form-fit">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa$order"></i>Order Detail
        </div>
            <a href="{{ $list_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-arrow-left"></i>Back</a>
    </div>
    <div class="portlet-body">
        <div class="clearfix">&nbsp;</div>
        <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
            <thead>
                <tr class="bold">
                    <th width="5%">Id</th>
                    <th width="15%">Order Id</th>
                    <th width="20">Product Name</th>
                    <th width="20%">Price</th>
                    <th width="20%">Quantity</th>
                    <th width="20%">Discount</th>
                </tr>
            </thead>
            @foreach($orderDetail as $detail)
            <tbody>
                <td width="5%">{{ $detail->id }}</td>
                <td width="15%">{{$detail->order_id}}</td>
                <td width="20">{{$detail->product->product_name}}</td>
                <td width="20%">{{$detail->price}}</td>
                <td width="20%">{{$detail->quantity}}</td>
                <td width="20%">{{$detail->discount}}</td>
            </tbody>
            @endforeach
        </table>
    </div>
</div>