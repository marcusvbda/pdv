<?php

namespace App\Http\Models;

use Arr;

class CashierExpense extends PoloDefaultModel
{
	protected $table = "cashier_expenses";
	public $casts = [
		"data" => "object"
	];
	public $appends = ["f_created_at", "code", "f_type"];
	const _TYPES_ = [
		["value" => "cash_in", "label" => "Entrada"],
		["value" => "cash_out", "label" => "Saída"]
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

	public function getFTypeAttribute()
	{
		return $this->getValueFromConst($this->type, static::_TYPES_);
	}

	private function getValueFromConst($index, $options)
	{
		$found = array_filter($options, function ($x) use ($index) {
			if (Arr::get($x, "value") == $index) return $x;
		});
		return Arr::get(current($found), "label");
	}
}