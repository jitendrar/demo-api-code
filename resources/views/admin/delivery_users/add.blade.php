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
                    <i class="fa fa-user"></i>User Account
                </div>
                <a class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;" href="{{ $list_url }}">Back</a>
            </div>
            <div class="portlet-body">
            <div class="form-body">
            {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'form-horizontal', 'id' => 'submit-form','redirect-url'=>$list_url]) !!}
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">User Details</legend>
            <div class="pull-left">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Picture: <span class="required">*</span></label>
                            <div class="col-md-9">
                                <div class="pull-left">
                                    <?php if(isset($formObj->id) && $formObj->id != 0){ ?>
                                        <input type="file" class="dropify" data-default-file="{{ $deliveryUserImg}}" name="avatar_id" accept="image/*" data-max-file-size="4M" >
                                    <?php
                                    }else{ ?>
                                        <input type="file" class="dropify" data-default-file="{{ asset('images/coming_soon.png')}}" name="avatar_id" accept="image/*" data-max-file-size="4M" >
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Firstname:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::text('first_name',null,['class'=>'form-control','placeholder'=>'firstname','required'=>'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Lastname:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::text('last_name',null,['class'=>'form-control','placeholder'=>'lastname','required'=>'required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Phone No:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::text('phone',null,['class'=>'form-control','placeholder'=>'Phone No','required'=>'required','id' => 'phone_no']) !!}
                            </div>
                        </div>
                    </div>
                     <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 control-label">status:</label>
                            <div class="col-md-9">
                                {{ Form::select('status',[1=>"Active",0=>"In-Active"],null,['class'=>'form-control', 'placeholder'=>'Select status']) }}  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </fieldset>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-12" align="center">
                            <button type="submit" class="btn btn-success" id="submitBtn">{!! $buttonText !!}</button>
                            <a href="{{ $list_url }}" class="btn btn-default"><i class="fa fa-remove"></i>Cancel</a>
                        </div>
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
<script src="{{ asset('css/admin/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ asset('js/admin/cleditor/jquery.cleditor.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/admin/cleditor/jquery.cleditor.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/dropify.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#cleditor").cleditor({
            width: '100%'
        });
    });
</script>
@endsection