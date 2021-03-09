<?php

namespace App\Http\Models;

use Arr;

class Sale extends PoloDefaultModel
{
	protected $table = "sales";
	public $casts = [
		"data" => "object"
	];
	public $appends = ["f_created_at", "code", "f_status"];
	const _STATUS_ = [
		["value" => "paid", "label" => "Paga"],
		["value" => "canceled", "label" => "Cancelada"]
	];

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function cashier()
	{
		return $this->belongsTo(Cashier::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function getFCreatedAtAttribute()
	{
		return $this->created_at->format("d/m/Y H:i:s");
	}

	public function getFStatusAttribute()
	{
		return $this->getValueFromConst($this->status, static::_STATUS_);
	}

	private function getValueFromConst($index, $options)
	{
		$found = array_filter($options, function ($x) use ($index) {
			if (Arr::get($x, "value") == $index) return $x;
		});
		return Arr::get(current($found), "label");
	}
}