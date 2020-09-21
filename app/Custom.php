<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Custom extends Model
{
    public static function __masterLocals()
    {
    	return ['en','guj'];
    }
}
