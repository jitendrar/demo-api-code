@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <div class="page-content-inner">

        <div class="portlet box green form-fit">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-product"></i>Product Detail
                </div>
                    <a href="{{ $list_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-arrow-left"></i>Back</a>
            </div>
            <div class="portlet-body form form-bordered">
                <form class="form-horizontal form-bordered">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Images:</label>
                        <div class="col-md-9">
                            <p><?php 
                                if(!empty($primaryImg)){ ?>
                                    <img src="{{ $primaryImg }}" width="200px" height="200px">
                                    <?php
                                }else{ ?>
                                    <img src="{{ asset('images/coming_soon.png')}}">
                                <?php }
                                ?> 
                            @foreach($productImg as $img)
                                <img src="{{ asset($img->src) }}" width="80px" height="80px">
                            @endforeach
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Product Name:</label>
                        <div class="col-sm-9">
                            <p> {{  $product->product_name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description:</label>
                        <div class="col-sm-9">
                            <p> {{  $product->description }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Product stock:</label>
                        <div class="col-sm-9">
                            <p> {{ $product->units_in_stock }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Stock Type:</label>
                        <div class="col-sm-9">
                            <p> {{ $product->units_stock_type }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Price:</label>
                        <div class="col-sm-9">
                            <p> {{ $product->unity_price }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category:</label>
                        <div class="col-sm-9">
                            <p> {{ $category }}</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection