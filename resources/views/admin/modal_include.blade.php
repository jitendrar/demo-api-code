<div class="modal fade bs-modal-lg" id="assign-delivery-boy" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Assign Delivery Boy</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Find Delivery Boy By Name</label>
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::select('load_delivery_user_id',[''=>'Search User']+$deliveryUsers,null,['class'=>'form-control  select_user search-select']) !!}                     
                            </div>                            
                        </div>                                
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            {!! csrf_field() !!}
            <input type="hidden" name="load_delivery_user_id" id="load_delivery_user_id" />
            <button data-href="" type="button" class="btn btn-primary btn-submit-assign-driver">Assign</button>
        </div>
      </div>
      
    </div>
</div>
<div class="modal fade bs-modal-lg" id="add-product" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Product</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Find Product</label>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::select('product',[''=>'Search Product']+$products,null,['class'=>'form-control  select_product search-select','id' => 'product']) !!}                     
                                </div>                            
                            </div>                                
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Find Category</label>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::select('category',[''=>'Search Category']+$categories,null,['class'=>'form-control  select_category search-select','id' => 'category']) !!}                     
                                </div>                            
                            </div>                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Quantity</label>
                            {!! Form::number('quantity',null,['class'=>'form-control',"min" => 1,'id' => 'quantity']) !!} 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            {!! csrf_field() !!}
            <input type="hidden" name="item_id" id="item_id" />
            <button data-href="" type="button" class="btn btn-primary btn-submit-product">Add</button>
        </div>
      </div>
      
    </div>
</div>