var room = 1;
function daily_task() 
{
  room++;
  var OrderDetailsFormHtml = $("#OrderDetailsForm").html();
  var OrderDetailsForm = document.getElementById('OrderDetailsForm');
  var OrderDetailsForm = OrderDetailsForm.innerHTML;

  var first = document.getElementById('category-0');
  var categoryoptions = first.innerHTML;
  var objTo = document.getElementById('daily_task')
  var divtest = document.createElement("div");
  divtest.setAttribute("class", "form-group removeclass"+room);

divtest.innerHTML = `
<div id="OrderDetailsForm" class="OrderDetailsForm">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label class="col-md-3 control-label">Find Category:<span class="required">*</span></label>
        <div class="col-md-9">
           <select class="Scategory form-control  select_category search-select" id="category-0" required="required" data-product="product-`+room+`" name="category[]">
           `+categoryoptions+`
           </select>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="col-md-3 control-label">Find Product:<span class="required">*</span></label>
        <div class="col-md-9">
           <select class="Sproduct form-control  select_product search-select" id="product-`+room+`" required="required" data-stocktype="stocktype-`+room+`" data-stockprice="stockprice-`+room+`" name="product[]">
           </select>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label class="col-md-3 control-label">Stock Type :<span class="required">*</span></label>
        <div class="col-md-9">
           <input class="form-control" placeholder="Stock Type" readonly="readonly" id="stocktype-`+room+`" name="StockType[]" type="text">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="col-md-3 control-label">Stock Price :<span class="required">*</span></label>
        <div class="col-md-9">
           <input class="form-control StockPriceForCount " placeholder="Stock Price" readonly="readonly" id="stockprice-`+room+`" name="StockPrice[]" type="text">
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label class="col-md-3 control-label">Quantity :<span class="required ">*</span></label>
        <div class="col-md-9">
           <div class="col-md-4" style="margin-left: -14px;">
            <input id="quantity-`+room+`" readonly="readonly" type="text" value="1" name="quantity[]" data-stockprice="stockprice-`+room+`" class="form-control QuantityForCount" style="width: 60px;" required="required" >
          </div>
          <div class="col-md-2" style="width: 10%;margin: 5px 0px 0px -50px;">
            <span class="input-group-btn">
              <button data-room="`+room+`" data-quantity="quantity-`+room+`" class="btn-xs add-product-qnt-cal-btn btn red bootstrap-touchspin-down btn-qty-minus pull-right" type="button" data-type="dec">-</button>
            </span>
          </div>
          <div class="col-md-2" style="width: 10%;margin: 5px 0px 0px -20px;">
            <span class="input-group-btn">
              <button data-room="`+room+`" data-quantity="quantity-`+room+`" class="btn-xs add-product-qnt-cal-btn btn blue bootstrap-touchspin-up btn-qty-plus pull-left"  type="button" data-type="inc">+</button>
            </span>
          </div>

        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-9">
              <button class="btn btn-danger" type="button" onclick="remove_education_fields(`+room+`);">
                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
              </button>
        </div>
      </div>
    </div>
  </div>
</div>
`;

objTo.appendChild(divtest);
serach_select2();
return false;
}

function remove_education_fields(rid) {
  $('.removeclass'+rid).remove();
  DisplayAddProducttotalPrice();
}

function serach_select2() {
    $(".search-select").select2();
}