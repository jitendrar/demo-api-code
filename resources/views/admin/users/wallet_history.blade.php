<div class="portlet box green form-fit">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa$order"></i>User Wallet History
        </div>
            <a href="{{ $list_url }}" class="btn btn-default pull-right btn-sm mTop5" style="margin-top: 5px;"><i class="fa fa-arrow-left"></i>Back</a>
    </div>
    <div class="portlet-body">
        <div class="clearfix">&nbsp;</div>
        <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
            <thead>
                <tr class="bold">
                    <th width="5%">Id</th>
                    <th width="20">Transaction Amount</th>
                    <th width="20%">Transaction Type</th>
                    <th width="35%">Remark</th>
                    <th width="20%">Date</th>
                </tr>
            </thead>
            @foreach($wallethistory as $history)
            <tbody>
                <td width="5%">{{ $history->id }}</td>
                <td width="20">{{number_format(($history->transaction_amount),2)}}</td>
                <td width="20%">{{$history->transaction_type ?? '-'}}</td>
                <td width="35%">{{$history->remark ?? ''}}</td>
                <td width="20%">{{date('Y-m-d h:i',strtotime($history->created_at))}}</td>
            </tbody>
            @endforeach
        </table>
    </div>
</div>