@extends('admin.layouts.layout')

@section('styles')
    <link href="{{ asset('css/admin/dropify/dist/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Firstname:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::text('first_name',null,['class'=>'form-control','placeholder'=>'firstname','required'=>'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Lastname:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::text('last_name',null,['class'=>'form-control','placeholder'=>'lastname','required'=>'required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Phone No:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::text('phone',null,['class'=>'form-control','placeholder'=>'Phone No','required'=>'required','id' => 'phone_no']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Address:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::textarea('address',$address,null,['class'=>'form-control','cols' =>10,'rows' =>1,'maxlength' => "400",'placeholder' =>'Address']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Total Balance:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::text('balance',null,['class'=>'form-control','placeholder' => 'balance']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">status:</label>
                            <div class="col-md-9">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio mt-radio-outline">
                                        {!! Form::radio('status',1,[]) !!} active
                                        <span></span>
                                    </label>
                                    <label class="mt-radio mt-radio-outline">
                                        {!! Form::radio('status',0,[]) !!} inactive
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if($isEdit)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Add Balance:</label>
                            <div class="col-md-9">
                                {!! Form::text('add_balance',null,['class'=>'form-control','placeholder' => 'balance']) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!$isEdit)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">password:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::password('password',['class'=>'form-control','placeholder'=>'password','required'=>'required']) !!}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">password:</label>
                            <div class="col-md-9">
                                {!! Form::password('password',['class'=>'form-control','placeholder'=>'password']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Confirm Password:</label>
                            <div class="col-md-9">
                                {!! Form::password('password_confirmation',['class'=>'form-control','placeholder'=>'Confirm Password']) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>   
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
<script src="{{ asset('/js/dropify.js') }}"></script>
<!-- <script src="{{ asset('js/pages/admin/inputmask.js?45413') }}"></script>
 -->
@endsection