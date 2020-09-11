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
                            <i class="fa fa-list"></i>Add Product
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
                                                <label class="col-md-3 control-label">Product Name:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::text('product_name',null,['class'=>'form-control','placeholder'=>'enter product name','required'=>'required']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Category:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::select('category_id',$categories,null,['class'=>'form-control','placeholder'=>'Select category','required'=>'required','id' => 'category_select',]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Description:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {!! Form::textarea('description',null,['class'=>'form-control cleditor','placeholder'=>'description','required'=>'required','cols' =>20,'rows' =>4,'id'=>'cleditor']) !!}
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
                                        </div>
                                        @if($isEdit)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Status::</label>
                                                <div class="col-md-9">
                                                    <div class="mt-radio-inline">
                                                        <label class="mt-radio mt-radio-outline">
                                                            {!! Form::radio('status',1,[]) !!}Active
                                                            <span></span>
                                                        </label>
                                                        <label class="mt-radio mt-radio-outline">
                                                            {!! Form::radio('status',0,[]) !!}In-Active
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Status:<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    {{ Form::select('status',[1=>"Active",0=>"In-Active"],null,['class'=>'form-control', 'placeholder'=>'Select status']) }}         
                                                </div>
                                            </div>
                                        </div>
                                        @endif
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
                                        <img src="{{ asset('uploads/products/'.$formObj->id.'/'.$img->src)}}" name="multi_img[]" height="50px" width="60px">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="radio" name="is_primary" checked ="(img->is_primary == 1)" class="check-box"> Is primary ?
                                    </div> 
                                    <div class="col-md-3">
                                        <a class="btn btn-danger remove-img-button" data-id="{{ $formObj->id }}" href="{{ route('products.deleteImage',['id' => $formObj->id]) }}">Remove</a>
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
                                        <input type="radio" name="is_primary" value="1" class="check-box"> Is primary ?
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
            alert(12);

        $text = deleteConfirmMSG;

        if (confirm($text))
        {
            $url = $(this).attr('href');
            alert($url);
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
