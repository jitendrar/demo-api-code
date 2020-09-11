@extends('admin.layouts.layout')

@section('content')
<div class="container">
    <div class="page-content-inner">

        <div class="portlet box green form-fit">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-category"></i>Category Detail
                </div>
                    <a href="{{ $list_url }}"class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-arrow-left"></i>Back</a>
            </div>
            <div class="portlet-body form form-bordered">
                <form class="form-horizontal form-bordered">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Picture:</label>
                        <div class="col-md-9">
                            <p><?php 
                                if(!empty($catImg)){ ?>
                                    <img src="{{ $catImg }}" width="200px" height="200px">
                                    <?php
                                }else{ ?>
                                    <img src="{{ asset('images/coming_soon.png')}}">
                                <?php }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category Name:</label>
                        <div class="col-sm-9">
                            <p> {{  $category->category_name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description:</label>
                        <div class="col-sm-9">
                            <p> {{  $category->description }}</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection