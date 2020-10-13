<div class="portlet box green form-fit open-log-details-cls open-log-details-cls-{{$detail->id}}" id="log-detail">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa$order"></i>Log Detail
        </div>
    </div>
    <div class="portlet-body">
        <div class="clearfix">&nbsp;</div>
        <table class="table table-bordered table-striped table-condensed flip-content" data-id="{{ $detail->id }}">
            <thead>
                @if($detail->activity_type_id == 18)
                <tr>
                    <th colspan="2" align="center">Delete Following Product From Order</th>
                </tr>
                @elseif($detail->activity_type_id == 19)
                <tr>
                    <th colspan="2" align="center">Add New Product from Order</th>
                </tr>
                @else
                <tr>
                    <th colspan="2" align="center">Edit Following Product</th>
                </tr>
                @endif
                <tr>
                    <th width="20%">Id</th>
                    <td width="80%">{{ $dataValue->id }}</td>
                </tr>
                <tr>
                    <th  width="20%">Order Id</th>
                    <td  width="80%">{{ $dataValue->order_id }}</td>
                </tr>
                <tr>
                    <th width="20%">Product Id</th>
                    <td  width="80%">{{ $dataValue->product_id }}</td>
                </tr>
            </thead>
        </table>
        <table class="table table-bordered table-striped table-condensed flip-content" id="log_detail" name="log_detail" data-id="{{ $detail->id }}">
            <thead>
            @if($detail->activity_type_id == 17)
                <tr>
                    <th width="20%">Field</th>
                    <th width="40%">Old Value</th>
                    <th width="40%">New Value</th>
                </tr>
                <tr>
                    <td width="20%">Quantity</td>
                    <td width="40%">{{ $dataValue->old_quantity }}</td>
                    <td width="40%">{{ $dataValue->new_quantity }}</td>
                </tr> 
                <tr>
                    <td width="20%">Price</td>
                    <td width="40%">{{ $dataValue->old_price }}</td>
                    <td width="40%">{{ $dataValue->new_price }}</td>
                </tr>
                <tr>
                    <td width="20%">Discount</td>
                    <td width="40%">{{ $dataValue->old_discount }}</td>
                    <td width="40%">{{ $dataValue->new_discount }}</td>
                </tr> 
                <tr>
                    <td width="20%">Updated Date</td>
                    <td width="40%">{{ date('Y-m-d h:i:s',strtotime($dataValue->old_updated_at)) }}</td>
                    <td width="40%">{{ date('Y-m-d h:i:s',strtotime($dataValue->new_updated_at)) }}</td>
                </tr>
            </thead>
            @else
            <tr>
                <th width="20%">Quantity</th>
                <td width="80%">{{ $dataValue->old_quantity }}</td>
            </tr>
            <tr>
                <th width="20%">Price</th>
                <td width="80%">{{ $dataValue->old_price }}</td>
            </tr>
            <tr>
                <th width="20%">Discount</th>
                <td width="80%">{{ $dataValue->old_discount }}</td>
            </tr>
            <tr>
                <th width="20%">Updated Date</th>
                <td width="40%">{{ date('Y-m-d h:i:s',strtotime($dataValue->old_updated_at)) }}</td>
            </tr>
            @endif
            <tbody>
            </tbody>
        </table>
    </form>
    </div>
</div>