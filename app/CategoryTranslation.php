<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{

	protected $fillable = ['category_name', 'description'];
	public $timestamps = false;

}
