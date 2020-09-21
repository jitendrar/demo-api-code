@extends('admin.layouts.login')

@section('content')

<h3 class="form-title font-green">Sign In</h3>
<div class="clearfix"></div>

{!! Form::open(['route' => 'check_admin_login', 'files' => true, 'class' => 'login-form', 'id' => 'login-form']) !!}  
@csrf

@if(Session::has('error_message'))
<div class="alert alert-danger">
    <button class="close" data-close="alert"></button>
    <span>{!! Session::get('error_message') !!}</span>
</div>
@endif    

<div class="form-group">
    <label class="control-label visible-ie8 visible-ie9">Email</label>
    {!! Form::text('email',null,['class'=>'form-control','placeholder'=> 'Enter Your Email','data-required'=>'true','id' =>'email']) !!}
</div>
<div class="form-group">
    <label class="control-label visible-ie8 visible-ie9">Password</label>

    {!! Form::password('password',['data-required' => 'true','class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Enter Your Password']) !!}
</div>
<div class="clearfix"></div>

<div class="form-actions">
    <button type="submit" class="btn green uppercase pull-right">Login</button>
</div>

<div class="clearfix"></div>
{!! Form::close() !!}  
@stop

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#login-form').submit(function () {
            if (true)
            {
                $('#AjaxLoaderDiv').fadeIn('slow');
                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    enctype: 'multipart/form-data',
                    success: function (result)
                    {
                        $('#AjaxLoaderDiv').fadeOut('slow');
                        if (result.status == 1)
                        {
                            $.bootstrapGrowl(result.msg, {type: 'success success-msg', delay: 4000});
                            window.location = result.goto;
                        }
                        else if (result.status == 2)
                        {
                            window.location = result.goto;
                        }
                        else
                        {
                            $.bootstrapGrowl(result.msg, {type: 'danger error-msg', delay: 4000});
                        }
                    },
                    error: function (error)
                    {
                        $('#AjaxLoaderDiv').fadeOut('slow');
                        $.bootstrapGrowl("Internal server error !", {type: 'danger error-msg', delay: 4000});
                    }
                });
            }
            return false;
        });
    });
</script>
<script src="{{ asset('js/pages/admin/inputmask.js?8522') }}"></script>
@endsection
