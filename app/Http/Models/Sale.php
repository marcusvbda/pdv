<?php

namespace App\Http\Models;

class Sale extends PoloDefaultModel
{
	protected $table = "sales";
	public $casts = [
		"data" => "object"
	];
	public $appends = ["code"];
}