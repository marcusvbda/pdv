<?php

namespace App\Http\Models;

class Sale extends PoloDefaultModel
{
	protected $table = "sales";
	public $casts = [
		"data" => "object"
	];
	public $appends = ["code"];

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}
}