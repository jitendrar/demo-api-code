<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-search"></i>Advance Search 
        </div>
        <div class="tools">
            <a href="javascript:;" class="expand"> </a>
        </div>                    
    </div>
    
    <div class="portlet-body" style="display: none">  
        <form id="search-frm">
            <div class="row">    
                <div class="col-md-4">
                    <label class="control-label">IDs</label>
                    <input type="text" value="{{ \Request::get("search_id") }}" class="form-control" name="search_id" />
                </div>

                <div class="col-md-4">
                    <label class="control-label">UserName</label>
                      {!! Form::select('search_fnm',[''=>'Search User']+$users,null,['class'=>'form-control search-select']) !!}
                </div>                                 
                <div class="col-md-4">
                    <label class="control-label">Order No</label>
                    <input type="text" value="{{ \Request::get("search_oid") }}" class="form-control" name="search_oid" />
                </div>
            </div>
			<div class="clearfix">&nbsp;</div>
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label">Delivery User</label>
                    {!! Form::select('search_delivery_user',[''=>'Search User']+$allDeliveryUser,null,['class'=>'form-control search-select']) !!}
                </div>
                <div class="col-md-4">
                    <label class="control-label">Status</label>
                    <select multiple="multiple" name="search_status[]" class="input-large form-control search-select">
                        <option value="" >Search Status</option>
                        <option value="all" {!! \Request::get("search_status") == "all" ? 'selected="selected"':'' !!}>All</option>
                        <option selected="" value="P" {!! \Request::get("search_status") == "P" ? 'selected="selected"':'' !!}>Pending</option> 
                        <option selected="" value="D" {!! \Request::get("search_status") == "D" ? 'selected="selected"':'' !!}>Delivered</option>  
                        <option value="C" {!! \Request::get("search_status") == "C" ? 'selected="selected"':'' !!}>Cancel</option> 
                    </select>
                </div>
                 
            </div> 
            <div class="clearfix">&nbsp;</div>
            <div class="row">
                <div class="col-md-8">
                    <label class="control-label">Pending Order Date Range</label>
                    <div class="input-group  date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                        {!! Form::text('search_start_date',null,['class'=>'form-control search_date_picker ','placeholder'=>'Order Date','id'=>'search_start_date']) !!}
                        <span class="input-group-addon"> To </span>
                        {!! Form::text('search_end_date',null,['class'=>'form-control search_date_picker input-large','placeholder'=>'Order Date','id'=>'search_end_date']) !!}
                    </div>
                </div>
            </div>
            &nbsp;
                <div class="row" align="center">                     
                    <input type="submit" class="btn blue mTop25" value="Search"/>
                    &nbsp;
                    <a href="{{ $list_url }}" class="btn red mTop25">Reset</a>
                </div> 
            </div>                
        </form>
    </div>    
</div>