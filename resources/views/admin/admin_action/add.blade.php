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
                    <i class="fa fa-user"></i>Admin Action
                </div>
                <a class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;" href="{{ $list_url }}">Back</a>
            </div>
            <div class="portlet-body">
            <div class="form-body">
            {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'form-horizontal', 'id' => 'submit-form','redirect-url'=>$list_url]) !!}
            <div class="form-body form">
                <div class="form-group">
                    <label class="col-md-3 control-label">Title:<span class="required">*</span></label>
                    <div class="col-md-6">
                        {!! Form::text('title',null,['class'=>'form-control','placeholder'=>'title','id'=>'titleId','data-required'=>'true']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Remark:<span class="required">*</span></label>
                    <div class="col-md-6">
                        {!! Form::text('remark',null,['class'=>'form-control','placeholder'=>'action_id','id'=>'remarkId','data-required'=>'true']) !!}
                    </div>
                </div>
            </div>
                <div class="form-actions">
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

@endsection