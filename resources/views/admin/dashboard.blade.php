@extends('admin.layouts.layout')
@section('content')

<div class="container">
    <div class="page-content-inner">

<div class="row">
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green" href="#">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $total_User }}">0</span>
                </div>
                <div class="desc">Total User</div>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 yellow" href="#">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $totalProducts }}">0</span>
                </div>
                <div class="desc">Total Available Products</div>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 red" href="#">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $totalCategories }}">0</span>
                </div>
                <div class="desc">Total Categories</div>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green" href="#">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $total_today_orders }} ">0</span>
                </div>
                <div class="desc">Total Today  Orders</div>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 yellow" href="#">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $total_orders }}">0</span> 
                </div>
                <div class="desc">Total Orders</div>
            </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 red" href="#">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $total_inactive_products }}">0</span> 
                </div>
                <div class="desc">Comming Soon Products</div>
            </div>
        </a>
    </div>
    <div class="col-md-12">
        <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bookmark"></i>
                        <span class="caption-subject bold">Orders</span>
                    </div>
                </div>
            <div class="portlet-body">
                <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables" width="100%">
                    <thead>
                        <tr>
                            <th align="left">id</th>
                            <th align="left">Name</th>
                            <th align="left">Price + Delivery Charge</th>
                            <th align="left">Address</th>
                            <th align="left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>    
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
     $(document).ready(function(){
            $.fn.dataTableExt.sErrMode = 'throw';

            var oTableCustom = $('#server-side-datatables').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    "url": "{!! route('orderData') !!}",
                    "data": function ( data )
                    {
                    }
                },
                lengthMenu:
                [
                [25,50,100,150,200],
                [25,50,100,150,200]
                ],
                "order": [[ 0, "ASC" ]],
            columns: [
                {data: 'id', name: 'id'},
                { data: 'userName', name: 'userName' },
                { data: 'totalPrice', name: 'totalPrice'},
                { data: 'address_line_1', name: 'addresses.address_line_1' },
                { data: 'created_at', name: 'created_at' },
            ]
        });
    });
</script>
@endsection
