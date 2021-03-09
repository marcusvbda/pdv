<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;
use Arr;

class PaymentMethod extends DefaultModel
{
	protected $table = "payment_methods";
	// public $cascadeDeletes = [];
	const _TYPES_ = [
		["value" => "card", "label" => "Cartão"],
		["value" => "money", "label" => "Espécie"]
	];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
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