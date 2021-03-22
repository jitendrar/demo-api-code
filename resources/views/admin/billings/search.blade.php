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
                    <label class="control-label">Bill Date Range</label>
                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                        {!! Form::text('search_start_date',null,['class'=>'form-control search_date_picker','placeholder'=>'Delivery Date','id'=>'search_start_date']) !!}
                        <span class="input-group-addon"> To </span>
                        {!! Form::text('search_end_date',null,['class'=>'form-control search_date_picker','placeholder'=>'Delivery Date','id'=>'search_end_date']) !!}
                    </div>
                </div>
            </div>
			<div class="clearfix">&nbsp;</div>
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
