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
                    <label class="control-label">Category Name</label>
                    <input type="text" value="{{ \Request::get("search_cnm") }}" class="form-control" name="search_cnm" />
                </div>
                <div class="col-md-4">
                    <label class="control-label">Status</label>
                    <select name="search_status" class="form-control">
                        <option value="all" {!! \Request::get("search_status") == "all" ? 'selected="selected"':'' !!}>All</option>
                        <option value="1" {!! \Request::get("search_status") == "1" ? 'selected="selected"':'' !!}>Active</option> 
                        <option value="0" {!! \Request::get("search_status") == "0" ? 'selected="selected"':'' !!}>Inactive</option>         
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