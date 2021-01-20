@extends('admin.layouts.layout')

@section('styles')
    <link href="{{ asset('css/admin/dropify/dist/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('js/admin/cleditor/jquery.cleditor.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container">
    <div class="page-content-inner">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    @if($isEdit == 0)
                        <i class="fa fa-list"></i>Add Offer
                    @else
                        <i class="fa fa-list"></i>Edit Offer
                    @endif
                </div>
                <a class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;" href="{{ $list_url }}">Back</a>
            </div>
            <div class="portlet-body">
            {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'form-horizontal', 'id' => 'submit-form','redirect-url'=>$list_url]) !!}
            <div class="form-body form">
                <div class="clearfix">&nbsp;</div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Product:<span class="required">*</span></label>
                            <div class="col-md-9">
                                 {!! Form::select('product_id',[''=>'Search Product']+$products,null,['class'=>'form-control search-select', 'required'=>'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Quantity : </label>
                            <div class="col-md-9">
                                {!! Form::text('quantity',null,['class'=>'form-control','placeholder'=>'Quantity']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="col-md-3 control-label"> Description
                            </label>
                            <div class="col-md-6">
                                {!! Form::textarea('description',null,['class' => 'form-control ckeditor']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Picture: <span class="required">*</span></label>
                            <div class="col-md-9">
                                <div class="pull-left">
                                    <?php if(isset($formObj->id) && $formObj->id != 0){ ?>
                                        <input type="file" class="dropify" data-default-file="{{ $formObj->picture }}" name="picture" accept="image/*" data-max-file-size="4M" >
                                    <?php
                                    }else{ ?>
                                        <input type="file" class="dropify" name="picture" accept="image/*" data-max-file-size="4M" >
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="clearfix">&nbsp;</div>
                <div id="add_more_offer_products">
                    @if(isset($isEdit) && $isEdit == 1 && isset($editOfferProducts) && !empty($editOfferProducts))
                        @foreach($editOfferProducts as $key=>$offerProduct)
                            <div class="row removeProductDiv">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Product:<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            {!! Form::select('offer_product_id[]',[''=>'Search Product']+$products,$offerProduct->product_id,['class'=>'form-control search-select', 'required'=>'required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Quantity : </label>
                                        <div class="col-md-9">
                                            {!! Form::text('offer_quantity[]',$offerProduct->quantity,['class'=>'form-control','placeholder'=>'Quantity']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    @if( $key !== 0)
                                        <button class="btn btn-danger deleteProductRow" type="button">
                                            <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Product:<span class="required">*</span></label>
                                    <div class="col-md-9">
                                        {!! Form::select('offer_product_id[]',[''=>'Search Product']+$products,null,['class'=>'form-control search-select', 'required'=>'required']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Quantity : </label>
                                    <div class="col-md-9">
                                        {!! Form::text('offer_quantity[]',null,['class'=>'form-control','placeholder'=>'Quantity']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-xm pull-right btn-primary" id="add-offer-btn"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                 <hr>
                <div class="row">
                    <div class="col-md-12" align="center">
                        <button type="submit" class="btn btn-primary" id="submitBtn">{!! $buttonText !!}</button>
                        <a href="{{ $list_url }}" class="btn default"><i class="fa fa-remove"></i>Cancel</a>
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
<script src="{{ asset('js/admin/cleditor/jquery.cleditor.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/admin/cleditor/jquery.cleditor.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('css/admin/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ asset('/js/dropify.js') }}"></script>
<script src="{{ asset('js/pages/admin/inputmask.js?45413') }}"></script>
<script type="text/javascript">
    function serach_product_select2() {
        $(".search-select").select2({
            placeholder: "Search Product",
            allowClear: true,
            width: null
        });
    }
    $(document).ready(function(){
        $(document).on("click" ,"#add-offer-btn", function(){

            var html = '<div class="row removeProductDiv"><div class="col-md-6"><div class="form-group"><label class="col-md-3 control-label">Product:<span class="required">*</span></label><div class="col-md-9"><select name="offer_product_id[]" class="form-control search-select">';

                var combo = $("<select></select>");
                combo.append("<option value=''>Search Product</option>");
                @foreach($products as $k => $v)
                    combo.append("<option value='{{ $k }}'>{{ $v }}</option>");
                @endforeach

                var option = combo.html();

            var htmlDiv = html+option+'</select></div></div></div><div class="col-md-5"><div class="form-group"><label class="col-md-3 control-label">Quantity : </label><div class="col-md-9"><input class="form-control" placeholder="Quantity" name="offer_quantity[]" type="text"></div></div></div><div class="col-md-1"><button class="btn btn-danger deleteProductRow" type="button"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></div></div></div>';
            $('#add_more_offer_products').append(htmlDiv);
            serach_product_select2();

            return false;
        });

        $(document).on("click",".deleteProductRow", function() {
            $(this).parents(".removeProductDiv").remove();
        });
    
    });

</script>
@endsection