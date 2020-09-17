<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{

	protected $table = 'wallet_history';

	protected $guarded = [];

	public static $TRANSACTION_TYPE_CREDIT = "CR";
	
	public static $TRANSACTION_TYPE_DEBIT = "DR";


}
