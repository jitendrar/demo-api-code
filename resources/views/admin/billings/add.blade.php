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
                            <i class="fa fa-list"></i>Add Bill
                            @else
                            <i class="fa fa-list"></i>Edit Bill
                            @endif
                        </div>
                        <a class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;" href="{{ $list_url }}">Back</a>
                    </div>
                    <div class="portlet-body">
                    {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'form-horizontal', 'id' => 'submit-form','redirect-url'=>$list_url]) !!}
                    <div class="form-body form">
                        <div class="row">
				            <div class="col-md-12">
                        		<fieldset class="col-md-12 scheduler-border pull-left">
                                	<legend class="scheduler-border">Details</legend>

				            	<div class="col-md-6">
	                                <div class="row">
					                    <div class="col-md-12">
					                        <div class="form-group">
					                        	<label class="col-md-3 control-label">Bill Date : <span class="required">*</span> </label>
					                            <div class="col-md-9">
					                            	<?php $CurrentDate = date('Y-m-d'); ?>
					                            	{!! Form::text('bill_date',$CurrentDate,['class'=>'form-control','placeholder'=>'Delivery Date','id'=>'BillDate']) !!}
						                        </div>
						                    </div>
						                </div>
				                	</div>

	                                <div class="row">
	                                    <div class="col-md-12">
	                                        <div class="form-group">
	                                            <label class="col-md-3 control-label">Total Bill Price : <span class="required">*</span>
	                                            </label>
	                                            <div class="col-md-9">
	                                                {!! Form::number('total',null,['class'=>'form-control','placeholder' => 'Bill Price','id'=>'total','min' =>0,'step' =>0.01]) !!}
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>

	                                <div class="row">
	                                    <div class="col-md-12">
	                                        <div class="form-group " >
	                                            <label class="col-md-3 control-label">Bill Image : <span class="required">*</span>
	                                            </label>
	                                            <div class="col-md-9">
	                                                <input type="file" class="dropify" name="picture" accept="image/*" data-max-file-size="4M">
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                
	                                <div class="row">
	                                    <div class="col-md-12">
	                                        <div class="form-group">
	                                        <label for="" class="col-md-3 control-label"> Description :<span class="required">*</span>
	                                        </label>
	                                        <div class="col-md-9">
	                                            {!! Form::textarea('description',null,['class' => 'form-control', 'rows' => 5, 'cols' => 40]) !!}
	                                        </div>
	                                        </div>
	                                    </div>
	                                </div>
				            	</div>
                        		</fieldset>
				            </div>
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
	$("#BillDate").datepicker({
		numberOfMonths: 1,
		maxDate: 0,
		dateFormat: 'yy-mm-dd'
	});
</script>

<script type="text/javascript">
    (function($) {
      'use strict';
      $('.dropify').dropify();
    })(jQuery);

    function initDropyfy(){
        (function($)
        {
          'use strict';
          $('.dropify').dropify();
        })(jQuery);
    }
</script>
@endsection
