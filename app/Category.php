<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Category extends Model implements TranslatableContract
{
	use Translatable;
	
	public $translatedAttributes = ['category_name', 'description'];

	protected $table = 'categories';

	protected $guarded = [];

}