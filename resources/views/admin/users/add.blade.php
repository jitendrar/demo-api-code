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
                            <label class="col-md-3 control-label">Total Balance:<span class="required">*</span></label>
                            <div class="col-md-9">
                                {!! Form::text('balance',null,['class'=>'form-control','placeholder' => 'balance']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">status:</label>
                            <div class="col-md-9">
                                {{ Form::select('status',[1=>"Active",0=>"In-Active"],null,['class'=>'form-control', 'placeholder'=>'Select status']) }}  
                            </div>
                        </div>
                    </div>
                </div> 
                </fieldset>
                <fieldset>
                    <legend>Address Details</legend>
                    <?php
                    $address_line_1 = '';
                    $address_line_2 = '';
                    $city = '';
                    $zipcode = '';
                    $address_status = '';
                    $prim_address = '';
                    if(!empty($address)){
                        $address_line_1 = $address->address_line_1;
                        $address_line_2 = $address->address_line_2;
                        $city = $address->city;
                        $zipcode = $address->zipcode;
                        $address_status = $address->status;
                        $prim_address = $address->primary_address;
                    } 
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address Line 1:<span class="required">*</span></label>
                                <div class="col-md-9">    
                                    {!! Form::textarea('address_line_1',$address_line_1,['class'=>'form-control','cols' =>9,'rows' =>1,'placeholder' =>'Address']) !!}
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address Line 2:</label>
                                <div class="col-md-9">    
                                    {!! Form::textarea('address_line_2',$address_line_2,['class'=>'form-control','cols' =>9,'rows' =>1,'placeholder' =>'Address']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">City:</label>
                                <div class="col-md-9">    
                                    {!! Form::text('city',$city,['class'=>'form-control','placeholder' =>'enter city']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Zipcode</label>
                                <div class="col-md-9">    
                                    {!! Form::text('zipcode',$zipcode,['class'=>'form-control','placeholder' =>'enter zip code']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address status:</label>
                                <div class="col-md-9">
                                    {{ Form::select('address_status',[1=>"Active",0=>"In-Active"],$address_status,['class'=>'form-control', 'placeholder'=>'Select status']) }}  
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Primary Address or not?</label>
                                <div class="col-md-9">
                                    {{ Form::select('prim_address',[1=>"Primary Address",0=>"Other"],$prim_address,['class'=>'form-control', 'placeholder'=>'Select Address value']) }}  
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