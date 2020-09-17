@extends('admin.layouts.layout')

@section('styles')
<link href="{{ asset('js/admin/cleditor/jquery.cleditor.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('themes/admin/assets/global/plugins/dropzone/basic.min.css')}}" type="text/css" />
<link rel="stylesheet" href="{{ asset('themes/admin/assets/global/plugins/jquery-file-upload/dropzone.min.css')}}" type="text/css" />

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
                        <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Details</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Category:<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                {!! Form::select('category_id',$categories,null,['class'=>'form-control','placeholder'=>'Select category','id' => 'category_select',]) !!}
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
                                    <div class="col-md-10" style="padding-left: 30px; height: 14px;">
                                        <h4>For {{ $val }}</h4>
                                    </div>   
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class=" col-md-3 control-label">Product Title [{{ $val }}]<span class="required">*</span></label>
                                         </label>
                                        <div class="col-md-9">
                                        {!! Form::text('product_name['.$lng.'][]',$title,['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="" class="col-md-3 control-label"> Description [{{ $val }}]
                                    </label>
                                    <div class="col-md-6">
                                        {!! Form::textarea('description['.$lng.'][]',$description,['class' => 'form-control cleditor']) !!}
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Unit Type:<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            {!! Form::text('units_stock_type['.$lng.'][]',$units_stock_type,['class'=>'form-control','placeholder' =>'Unit Type','id'=>'units_stock_type']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Unit Stock:<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            {!! Form::number('units_in_stock['.$lng.'][]',$units_in_stock,['class'=>'form-control','placeholder' => 'Unit Stock','id'=>'units_in_stock','min' =>0,'step' =>0.01]) !!}
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Unit Price:<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            {!! Form::number('unity_price['.$lng.'][]',$unity_price,['class'=>'form-control','placeholder' => 'Unit Price','id'=>'unity_price','min' =>0,'step' =>0.01]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="clearfix">&nbsp;</div>
                               <!--  <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Product Name (EN):<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                {!! Form::text('product_name',null,['class'=>'form-control','placeholder'=>'enter product name','required'=>'required']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Description (EN):<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                {!! Form::textarea('description',null,['class'=>'form-control cleditor','placeholder'=>'description','required'=>'required','cols' =>20,'rows' =>4,'id'=>'cleditor1']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Product Name (GUJ):<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::text('product_name',null,['class'=>'form-control','placeholder'=>'enter product name','required'=>'required']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        
                                    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Description (GUJ):<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::textarea('description',null,['class'=>'form-control cleditor','placeholder'=>'description','required'=>'required','cols' =>20,'rows' =>4,'id'=>'cleditor1']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Unit Type:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::text('units_stock_type',null,['class'=>'form-control','placeholder' =>'Unit Type','id'=>'units_stock_type']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Unit Stock:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::number('units_in_stock',null,['class'=>'form-control','placeholder' => 'Unit Stock','id'=>'units_in_stock','min' =>0,'step' =>0.01]) !!}
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Unit Price:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::number('unity_price',null,['class'=>'form-control','placeholder' => 'Unit Price','id'=>'unity_price','min' =>0,'step' =>0.01]) !!}
                                                </div>
                                            </div>
                                        </div>-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Status:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {{ Form::select('status',[1=>"Active",0=>"In-Active"],null,['class'=>'form-control', 'placeholder'=>'Select status']) }}         
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        </fieldset>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Images</legend>
                           <a class="btn btn-primary" id="add-img-btn">Add</a>
                            <div id="mult-img-div">
                                @if(isset($productImg) && !empty($productImg))
                                @foreach($productImg as $img)
                                <div class="form-group mult-img-prnt">
                                    <label class="col-md-3 control-label">Image
                                    </label>
                                    <div class="col-md-3">
                                        <img src="{{ asset('uploads/products/'.$formObj->id.'/'.$img->src)}}" name="" height="50px" width="60px">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="radio" name="is_primary" <?php  echo ($img->is_primary == 1)?'checked':'' ?> class="check-box1" value = "{{$img->id}}"> Is primary ?
                                    </div> 
                                    <div class="col-md-3">
                                        <a class="btn btn-danger remove-img-button" data-id="{{ $img->id }}" href="{{ route('products.deleteImage',['id' => $img->id]) }}">Remove</a>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                <div class="form-group mult-img-prnt">
                                    <label class="col-md-3 control-label">Image
                                    </label>
                                    <div class="col-md-3">
                                        <input type="file" name="multi_img[]">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="radio" name="is_primary" value="" class="check-box"> Is primary ?
                                    </div>

                                </div>
                            </div>
                        </fieldset>
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
<script src="{{ asset('js/admin/cleditor/jquery.cleditor.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/admin/cleditor/jquery.cleditor.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('themes/admin/assets/global/plugins/jquery-file-upload/dropzone.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('js/pages/admin/inputmask.js?45413') }}"></script>
<script type="text/javascript">
    function isPrimary(){
      var i = 0;
      $('.check-box').each(function(){
        $(this).val(i);
        i++;
      });
    }
    $(document).ready(function(){
        isPrimary();
        var radio = 2;
        $(document).on('click','#add-img-btn',function(){
            var img_var = '<div class="form-group mult-img-prnt"><label class="col-md-3 control-label">Image</label><div class="col-md-3"><input type="file" name="multi_img[]"></div><div class="col-md-3"><input type="radio" class ="check-box" name="is_primary" value=""> Is primary ?</div><div class="col-md-3"><a class="btn btn-danger remove-img-btn" >Remove</a></div></div>';
            $('#mult-img-div').append(img_var);
            radio +=1;
            isPrimary();
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
    });
</script>

@endsection
