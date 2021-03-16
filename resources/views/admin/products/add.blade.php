@extends('admin.layouts.layout')

@section('styles')
<link href="{{ asset('js/admin/cleditor/jquery.cleditor.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/admin/dropify/dist/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('themes/admin/assets/global/plugins/dropzone/basic.min.css')}}" type="text/css" />
<link rel="stylesheet" href="{{ asset('themes/admin/assets/global/plugins/jquery-file-upload/dropzone.min.css')}}" type="text/css" />
<style type="text/css">
    .dropify-wrapper{
        height: 60%;
        width:60%;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-content-inner">
        <div class="row autoResizeHeight">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            @if($isEdit == 0)
                            <i class="fa fa-list"></i>Add Product
                            @else
                            <i class="fa fa-list"></i>Edit Product
                            @endif                        
                        </div>
                        <a class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;" href="{{ $list_url }}">Back</a>
                    </div>
                    <div class="portlet-body">
                    {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'form-horizontal', 'id' => 'submit-form','redirect-url'=>$list_url]) !!}
                    <div class="form-body form">
                        <div class="row">
                        <fieldset class="col-md-6 scheduler-border pull-left">
                                <legend class="scheduler-border">Details</legend>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Category:<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                {!! Form::select('category_id[]',$categories,array_keys($defaultCategories),['class' => 'category_select','multiple' => 'multiple']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 @foreach($languages as $lng => $val)
                                <?php 
                                    $title = null;
                                    $description = null;
                                    $units_stock_type = null;
                                    $units_in_stock = null;
                                    $unity_price = null;
                                    if(isset($formObj->id) && !empty($formObj->id)){
                                        $title = $formObj->translate($val)->product_name;
                                        $description = $formObj->translate($val)->description;
                                        $units_stock_type = $formObj->translate($val)->units_stock_type;
                                        $units_in_stock = $formObj->translate($val)->units_in_stock;
                                        $unity_price = $formObj->translate($val)->unity_price;
                                }?>
                                <div class="clearfix">&nbsp;</div>
                                <div class="note note-info">
                                    <div class="row">
                                        <div class="col-md-6" style="padding-left: 30px; height: 14px;">
                                            <h4>For {{ $val }}</h4>
                                        </div>   
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="" class=" col-md-3 control-label">Product Title [{{ $val }}]:<span class="required">*</span></label>
                                             </label>
                                            <div class="col-md-9">
                                            {!! Form::text('product_name['.$val.']',$title,['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label for="" class="col-md-3 control-label"> Description [{{ $val }}] :<span class="required">*</span>
                                        </label>
                                        <div class="col-md-9">
                                            {!! Form::textarea('description['.$val.']',$description,['class' => 'form-control']) !!}
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if($val != 'guj'){ ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Unit Type [{{ $val }}]:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::text('units_stock_type['.$val.']',$units_stock_type,['class'=>'form-control','placeholder' =>'Unit Type','id'=>'units_stock_type']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Unit Stock [{{ $val }}]:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::number('units_in_stock['.$val.']',$units_in_stock,['class'=>'form-control','placeholder' => 'Unit Stock','id'=>'units_in_stock','min' =>0,'step' =>0.01]) !!}
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Unit Price [{{ $val }}]:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::number('unity_price['.$val.']',$unity_price,['class'=>'form-control','placeholder' => 'Unit Price','id'=>'unity_price','min' =>0,'step' =>0.01]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                @endforeach
                                <div class="clearfix">&nbsp;</div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Status:<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                {{ Form::select('status',[1=>"Active",0=>"In-Active"],null,['class'=>'form-control', 'placeholder'=>'Select status']) }}         
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </fieldset>
                        <fieldset class="col-md-6 scheduler-border pull-right">
                            <legend class="scheduler-border">Images</legend>
                            <div class="row">
                            <div class="col-md-12">
                                 <div class="col-md-6">
                                     <a class="btn btn-xm btn-primary" id="add-img-btn"><i class="fa fa-plus"></i></a>
                                 </div>
                                 <div class="col-md-6">Primary ?</div>
                            </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div id="mult-img-div">
                                @if(isset($productImg) && !empty($productImg))
                                @foreach($productImg as $img)
                                <div class="row">
                                <div class="col-md-12">
                                <div class="form-group mult-img-prnt">
                                    <div class="col-md-6">
                                        <img src="{{ asset($img->src) }}" name="" height="60%" width="60%">
                                    </div>
                                    <div class="col-md-3">
                                       <input type="radio" name="is_primary" <?php  echo ($img->is_primary == 1)?'checked':'' ?> class="check-box1" value = "{{$img->id}}">
                                    </div> 
                                    <div class="col-md-3">
                                        <a class="btn btn-xs btn-danger remove-img-button" data-id="{{ $img->id }}" href="{{ route('products.deleteImage',['id' => $img->id]) }}"><i class="fa fa-close"></i></a>
                                    </div>
                                </div>
                                </div>
                                </div>
                                @endforeach
                                @else
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="form-group mult-img-prnt">
                                        <div class="col-md-6">
                                             <input type="file" class="dropify" name="multi_img[]" accept="image/*" data-max-file-size="4M">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group form-md-radios">
                                            <input type="radio" name="is_primary" value="" class="check-box">
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            </div>
                        </fieldset>
                    </div>
                        <div class="row">
                            <div class="col-md-12" align="center">
                                <button type="submit" class="btn btn-success" id="submitBtn">{!! $buttonText !!}</button>
                                <a href="{{ $list_url }}" class="btn btn-default"><i class="fa fa-remove"></i>Cancel</a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('/themes/admin/assets/global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('/themes/admin/assets/pages/scripts/components-select2.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('js/multiselect/jquery.multiselect.js')}}" type="text/javascript"></script>
<script src="{{ asset('js/admin/cleditor/jquery.cleditor.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/admin/cleditor/jquery.cleditor.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('css/admin/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ asset('js/pages/admin/inputmask.js?45413') }}"></script>
<script type="text/javascript">
    (function($) {
      'use strict';
      $('.dropify').dropify();
    })(jQuery);
    function isPrimary(){
      var i = 0;
      $('.check-box').each(function(){
        $(this).val(i);
        i++;
      });
    }
    function initDropyfy(){
        (function($)
        {
          'use strict';
          $('.dropify').dropify();
        })(jQuery);
    }
    $(document).ready(function(){
        isPrimary();
        var radio = 2;
        $(document).on('click','#add-img-btn',function(){
            var img_var = '<div class="row"><div class="col-md-12"><div class="form-group mult-img-prnt"><div class="col-md-6"><input type="file" class="dropify" data-default-file="" name="multi_img[]" accept="image/*" data-max-file-size="4M" ></div><div class="col-md-3"><input type="radio" name="is_primary" value="" class="check-box"></div><div class="col-md-3"><a class="btn btn-xs btn-danger remove-img-btn" ><i class="fa fa-close"></i></a></div></div></div></div>';
            $('#mult-img-div').append(img_var);
            radio +=1;
            isPrimary();
            initDropyfy();
        });
        $(document).on('click','.remove-img-btn',function(){
            if(confirm("are you sure ?") == true)
            {
                $(this).parent().parent().remove();
            }else{
                return false;

            }
        });
        $(document).on('click', '.remove-img-button', function () {
            $text = deleteConfirmMSG;
            if (confirm($text))
            {
                $url = $(this).attr('href');
                $('#global_delete_form').attr('action', $url);
                $('#global_delete_form #delete_id').val($(this).data('id'));
                $('#global_delete_form').submit();
            }
            return false;
        });
        
       $("#cleditor").cleditor({
            width: '100%'
        });
        $(".category_select").select2({
            placeholder: "Search Category",
            allowClear: true,
            minimumInputLength: 2,
            width: null,
            multiselect:true,
        });
    });
</script>

@endsection
