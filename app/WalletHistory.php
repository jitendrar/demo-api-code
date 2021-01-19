<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Config;

class WalletHistory extends Model
{

	protected $table = 'wallet_history';

	protected $guarded = [];

	public static $TRANSACTION_TYPE_CREDIT = "CR";
	
	public static $TRANSACTION_TYPE_DEBIT = "DR";

	public static function AddReferaalMoney($newuser=array())
	{
		if(isset($newuser['referralfrom']) && !empty($newuser['referralfrom']))
		{
			$user = User::where('referralcode','=',$newuser['referralfrom'])->first();
			if($user) {
				$desc = $newuser['first_name'].' '.$newuser['last_name']." is registered successfully and you get referral";
				$REFERRAL_MONEY         = Config::GetConfigurationList(Config::$REFERRAL_MONEY);
				$user->balance +=$REFERRAL_MONEY;
				$user->save();
				$obj = new WalletHistory();
				$obj->order_id = -1; 
				$obj->user_id = $user->id;
				$obj->user_balance = $user->balance;
				$obj->transaction_amount = $REFERRAL_MONEY;
				$obj->transaction_type = WalletHistory::$TRANSACTION_TYPE_CREDIT;;
				$obj->remark = $desc;
				$obj->save();
			}
		}
	}

}
