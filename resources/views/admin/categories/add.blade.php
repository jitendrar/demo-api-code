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
                    <i class="fa fa-list"></i>Add Category
                </div>
                <a class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;" href="{{ $list_url }}">Back</a>
            </div>
            <div class="portlet-body">
            {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'form-horizontal', 'id' => 'submit-form','redirect-url'=>$list_url]) !!}
            <div class="form-body form">
                <div class="row" id="pull-rightofilePicture">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Picture: <span class="required">*</span></label>
                            <div class="col-md-9">
                                <div class="pull-left">
                                    <?php if(isset($formObj->id) && $formObj->id != 0){ ?>
                                        <input type="file" class="dropify" data-default-file="{{ $catImg}}" name="avatar_id" accept="image/*" data-max-file-size="4M" >
                                    <?php
                                    }else{ ?>
                                        <input type="file" class="dropify" data-default-file="{{ asset('images/coming_soon.png')}}" name="avatar_id" accept="image/*" data-max-file-size="4M" >
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Category Name:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::text('category_name',null,['class'=>'form-control','placeholder'=>'enter category name','required'=>'required']) !!}
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Status:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {{ Form::select('status',[1=>"Active",0=>"In-Active"],null,['class'=>'form-control', 'placeholder'=>'Select status']) }}         
                            </div>
                        </div>
                    </div>
                </div>
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
    $(document).ready(function(){
        $("#cleditor").cleditor({
            width: '100%'
        });
    });

</script>
@endsection