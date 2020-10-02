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
                <div class="col-md-6">
                    <label class="control-label">Activity Type</label>
                    {!! Form::select('activity_type',[''=>'Select']+$activityTypeList,null,['class'=>'form-control search-select']) !!}
                </div>

                <div class="col-md-6">
                    <label class="control-label">Action ID</label>
                     <input type="text" value="{{ \Request::get('action_id') }}" class="form-control" name="action_id" />
                </div>             
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label col-md-3">Date</label>
                    <div class="col-md-9">
                        <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="mm/dd/yyyy">
                            <input type="text" class="form-control" value="{{ \Request::get('search_start_date') }}" name="search_start_date" id="start_date" placeholder="from date">
                            <span class="input-group-addon"> To </span>
                            <input type="text" class="form-control" value="{{ \Request::get('search_end_date') }}" name="search_end_date" id="end_date" placeholder="to date"> 
                        </div>
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