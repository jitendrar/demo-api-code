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

	/**
	* TRANSACTION METHOD = 1
	* value 1 that mean money credited by referral 
	*
	*/
	public static $TRANSACTION_METHOD_REFERRAL = 1;


	public static function AddReferaalMoney($newuser=array())
	{
		if(isset($newuser['referralfrom']) && !empty($newuser['referralfrom']))
		{
			$user = User::where('referralcode','=',$newuser['referralfrom'])->first();
			if($user) {
				$desc = $newuser['first_name'].' '.$newuser['last_name']."'s first order delivered and you got referral.";
				$REFERRAL_MONEY         = Config::GetConfigurationList(Config::$REFERRAL_MONEY);
				$user->balance +=$REFERRAL_MONEY;
				$user->save();
				
				$newuser->is_referral_done = 1;
				$newuser->save();
				
				$obj = new WalletHistory();
				$obj->order_id = -1; 
				$obj->user_id = $user->id;
				$obj->user_balance = $user->balance;
				$obj->transaction_amount 	= $REFERRAL_MONEY;
				$obj->transaction_type 		= WalletHistory::$TRANSACTION_TYPE_CREDIT;
				$obj->transaction_method 	= WalletHistory::$TRANSACTION_METHOD_REFERRAL;
				$obj->remark = $desc;
				$obj->save();
			}
		}
	}

}
