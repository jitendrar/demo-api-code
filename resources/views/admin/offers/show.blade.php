@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <div class="page-content-inner">

        <div class="portlet box green form-fit">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-product"></i>Offer
                </div>
                    <a href="{{ $list_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-arrow-left"></i>Back</a>
            </div>
            <div class="portlet-body form form-bordered">
                <form class="form-horizontal form-bordered">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Image:</label>
                        <div class="col-md-9">
                            <p>
                                <img src="{{ $formObj->picture }}" width="80px" height="80px">
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Product Name:</label>
                        <div class="col-sm-9">
                            <p> {{  isset($products[$formObj->product_id])?$products[$formObj->product_id]:'' }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Quantity</label>
                        <div class="col-sm-9">
                            <p> {{ $formObj->quantity }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description:</label>
                        <div class="col-sm-9">
                            <p> {{  $formObj->description }}</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-product"></i>Offer Detail
                </div>
            </div>
            <div class="portlet-body form form-bordered">
                <form class="form-horizontal form-bordered">
                     @if(isset($editOfferProducts) && !empty($editOfferProducts))
                        @foreach($editOfferProducts as $key=>$offerProduct)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Product Name:</label>
                                    <div class="col-sm-9">
                                        <p> {{ isset($products[$offerProduct->product_id])?$products[ $offerProduct->product_id]:'' }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Quantity:</label>
                                    <div class="col-sm-9">
                                        <p> {{ $offerProduct->quantity }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

@endsection