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
                    <select name="search_status" class="form-control">
                        <option value="all" {!! \Request::get("search_status") == "all" ? 'selected="selected"':'' !!}>All</option>
                        <option value="P" {!! \Request::get("search_status") == "P" ? 'selected="selected"':'' !!}>Pending</option> 
                        <option value="D" {!! \Request::get("search_status") == "D" ? 'selected="selected"':'' !!}>Delivered</option> 
                    </select>
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