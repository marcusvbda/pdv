<?php

namespace App\Http\Models;

use App\Http\Models\Traits\HasCustomFields;

class Customer extends PoloDefaultModel
{
	use HasCustomFields;
	protected $table = "customers";
	public $casts = [
		"custom_fields" => "object",
		"address" => "object",
	];
}