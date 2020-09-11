@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <div class="page-content-inner">

        <div class="portlet box green form-fit">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa$order"></i>Order Detail
                </div>
                    <a href="{{ $list_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-arrow-left"></i>Back</a>
            </div>
            <div class="portlet-body form form-bordered">
                <form class="form-horizontal form-bordered">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">User Name:</label>
                        <div class="col-sm-9">
                            <p> {{  $user->first_name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Price:</label>
                        <div class="col-sm-9">
                            <p> {{  $order->total_price }}</p>
                        </div>
                    </div>
                    @if(isset($address) && !empty($order->address_id))
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Address:</label>
                        <div class="col-sm-9">
                            <p> {{ $address->address }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Delivery Charge:</label>
                        <div class="col-sm-9">
                            <p> {{ $order->delivery_charge }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Delivery Date:</label>
                        <div class="col-sm-9">
                            <p> {{ $order->delivery_date }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Info:</label>
                        <div class="col-sm-9">
                            <p> {{ $order->special_information }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Order Status:</label>
                        <div class="col-sm-9">
                            <p> {{ $order->order_status }}</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection