@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <div class="page-content-inner">

        <div class="portlet box green form-fit">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i>User Account
                </div>
                <a href="{{ $list_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
            <div class="portlet-body form form-bordered">
                <form class="form-horizontal form-bordered">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Firstname:</label>
                        <div class="col-sm-9">
                            <p> {{  $user->first_name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Lastname:</label>
                        <div class="col-sm-9">
                            <p> {{  $user->last_name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Phone No:</label>
                        <div class="col-sm-9">
                            <p> {{ $user->phone }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Balance:</label>
                        <div class="col-sm-9">
                            <p> {{ $user->balance }}</p>
                        </div>
                    </div>
                    @if(isset($address) && !empty($address->user_id))
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Address:</label>
                        <div class="col-sm-9">
                            <p> {{ $address->address }}</p>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

@endsection