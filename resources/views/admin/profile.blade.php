@extends('admin.layouts.layout')

@section('styles')
<link href="{{ asset('themes/admin/assets/pages/css/profile.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/admin/dropify/dist/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .dropify-preview{
        width: 95% !important;
    }
    .dropify-wrapper{
        width: 95% !important;
    }
</style>
@endsection

@section('content')
 <div class="container">
    <div class="page-content-inner">
        <div class="profile-sidebar">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        Profile Image
                    </div>
                </div>
            <div class="portlet-body profile-sidebar-portlet">
                <div class="profile-userpic">
                    <img src="{{ asset('images/default-medium.png') }}" class="img-responsive" alt="image">
                </div>
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name"> {{ ucfirst($formObj->first_name) }} {{ ucfirst($formObj->last_name) }} </div>
                </div>
                <div class="profile-userbuttons">
                    @if($formObj->status == 1)
                    <button type="button" class="btn btn-circle green btn-sm">Active</button>
                    @endif
                </div>
                <div class="profile-usermenu">
                </div>
            </div>
            </div>
        </div>
        <div class="profile-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet box green">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption bold uppercase">Account</span>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                </li>
                                <li>
                                    <a href="#tab_1_2" data-toggle="tab">Change avtar</a>
                                </li>
                                <li>
                                    <a href="#tab_1_3" data-toggle="tab">change Password</a>
                                </li>
                            </ul>
                        </div>
                        <div class="portlet-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_1">
                                    {!! Form::model($formObj,['method' => $method,'files' => true, 'url' => $action_url,'class' => '', 'id' => 'module-form',"redirect-url"=>$redirectURL]) !!}

                                        <div class="form-group">
                                            <label class="control-label">Firstname:<span class="required">*</span></label>
                                            {!! Form::text('first_name',null,['class'=>'form-control','placeholder'=>'firstname','data-required'=>'true']) !!}
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Lastname:<span class="required">*</span></label>
                                            {!! Form::text('last_name',null,['class'=>'form-control','placeholder'=>'lastname','data-required'=>'true']) !!}
                                        </div> 
                                        <div class="form-group">
                                            <label class="control-label">Email:<span class="required">*</span></label>
                                            {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'email','data-required'=>'true']) !!}
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Phone No:<span class="required">*</span></label>
                                            {!! Form::text('phone',null,['class'=>'form-control','placeholder'=>'enter phone no','data-required'=>'true','id' =>'phone_no']) !!}
                                        </div>
                                        
                                        <div class="margiv-top-10" align="center">
                                            <button type="submit" name="" class="btn green" id="formSubmit"><i class="icon-ok icon-white"></i> {{ $buttonText }}</button>
                                            <a href="{{ route('admin-dashboard') }}" class="btn default"><i class="icon-remove icon-white"></i> cancel </a>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="tab-pane" id="tab_1_2">
                                    {!! Form::model($formObj,['method' => $method,'files' => true, 'url' => $action_url,'class' => '', 'id' => 'avatar-form',"redirect-url"=>$redirectURL]) !!}
                                        <input type="hidden" name="form_type" value="change-avatar">
                                        <div class="row" align="center">
                                            <div class="col-md-4"></div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <input type="file" class="dropify" data-default-file="{{ asset('images/default-medium.png') }}" name="avatar_id" accept="image/*" data-max-file-size="4M" >
                                                </div>
                                            </div>
                                        </div>
                                        <div>&nbsp;</div><hr/>
                                        <div class="margin-top-10" align="center">
                                            <button type="submit" name="" class="btn green" id="formSubmit2"><i class="icon-ok icon-white"></i> {{ $buttonText }}</button>
                                            <a href="{{ route('admin-dashboard') }}" class="btn default"><i class="icon-remove icon-white"></i>cancel</a>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="tab-pane" id="tab_1_3">
                                    {!! Form::model($formObj,['method' => $method,'files' => true, 'url' => $action_url,'class' => '', 'id' => 'password-form',"redirect-url"=>$redirectURL]) !!}
                                        <input type="hidden" name="form_type" value="change-password">
                                        <div class="form-group">
                                            <label class="control-label">Old Password:<span class="required">*</span></label>
                                            {!! Form::password('old_password',['class'=>'form-control','placeholder'=>'old password','id'=>'passwordId','data-required'=>true]) !!}
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">New Password:<span class="required">*</span></label>
                                            {!! Form::password('password',['class'=>'form-control','placeholder'=>'new password','id'=>'passwordId','data-required'=>true]) !!}
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Confirm Password:<span class="required">*</span></label>
                                            {!! Form::password('password_confirmation',['class'=>'form-control','placeholder'=>'confirm password','id'=>'passwordConfirmationId','data-required'=>true]) !!}                                      
                                              </div>
                                        <hr/>
                                        <div class="form-actions" align="center">
                                            <button type="submit" name="" class="btn green" id="formSubmit3"><i class="icon-ok icon-white"></i> {{ $buttonText }}</button>
                                            <a href="{{ route('admin-dashboard') }}" class="btn default"><i class="icon-remove icon-white"></i>cancel </a>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('css/admin/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ asset('/js/dropify.js') }}"></script>
<script src="{{ asset('js/pages/admin/myProfile.js?522') }}"></script>
<!-- <script src="{{ asset('js/pages/admin/inputmask.js?852') }}"></script>
 -->@endsection