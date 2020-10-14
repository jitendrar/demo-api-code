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
                            <label for="">Find Category</label>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::select('category',[''=>'--- Select --- ']+$categories,null,['class'=>'form-control  select_category search-select','id' => 'category']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Find Product</label>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::select('product',[''=>'Search Product'],null,['class'=>'form-control  select_product search-select','id' => 'product']) !!}
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
                            Stock Type / Price :: <span id="StockTypePrice" > </span>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="unity_price" id="unity_price" class="form-control qty-price">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-1" style="margin: 5px -15px 0px 2px;">
                        <div class="form-group">
                            <label>Quantity</label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="col-md-2">
                            <input id="quantity" type="text" value="0" name="quantity" class="form-control" style="width: 60px;">
                        </div>
                        <div class="col-md-2" style="width: 10%;margin: 5px 0px 0px -50px;">
                            <span class="input-group-btn">
                                <button class="btn-xs add-product-qnt-cal-btn btn red bootstrap-touchspin-down btn-qty-minus pull-right" type="button" data-type="dec">-</button>
                            </span>
                        </div>
                        <div class="col-md-2" style="width: 10%;margin: 5px 0px 0px -20px;">
                            <span class="input-group-btn">
                                <button class="btn-xs add-product-qnt-cal-btn btn blue bootstrap-touchspin-up btn-qty-plus pull-left"  type="button" data-type="inc">+</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            Total Price :: <span id="TotalPriceOfProduct" > </span>
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