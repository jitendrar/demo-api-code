<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model {

	protected $table = 'billings';

	protected $guarded = [];


	public static function getAttachment($picture = 0)
    {
        $img = asset('images/coming_soon.png');
        if($picture) {
	        $img = asset($picture);
	        if(file_exists(public_path().$picture)) {
	            $img = $img;
	        }
        }
        return $img;
    }

}
