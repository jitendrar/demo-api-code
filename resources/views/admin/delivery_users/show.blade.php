@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <div class="page-content-inner">

        <div class="portlet box green form-fit">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-product"></i>Delivery User Detail
                </div>
                    <a href="{{ $list_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-arrow-left"></i>Back</a>
            </div>
            <div class="portlet-body form form-bordered">
                <form class="form-horizontal form-bordered">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Image:</label>
                        <div class="col-md-9">
                            <p><?php 
                                if(!empty($deliveryUserImg)){ ?>
                                    <img src="{{ $deliveryUserImg }}" width="200px" height="200px">
                                    <?php
                                }else{ ?>
                                    <img src="{{ asset('images/coming_soon.png')}}">
                                <?php }
                                ?> 
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name:</label>
                        <div class="col-sm-9">
                            <p> {{  $user->first_name.' '.$user->last_name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Phone No:</label>
                        <div class="col-sm-9">
                            <p> {{  $user->phone }}</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection