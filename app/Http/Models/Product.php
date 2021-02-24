<?php

namespace App\Http\Models;

use App\Http\Models\Traits\HasCustomFields;

class Product extends PoloDefaultModel
{
	use HasCustomFields;
	protected $table = "products";
	public $casts = [
		"custom_fields" => "object"
	];
}