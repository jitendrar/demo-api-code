<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $fillable = ['product_name', 'description'];
  	public $timestamps = false;
}
