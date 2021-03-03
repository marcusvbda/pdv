<?php

namespace App\Http\Models;

use App\User;

class Cashier extends PoloDefaultModel
{
	protected $table = "cashiers";

	public function ScopeisOpened($query)
	{
		$query->whereNull("closed_at");
	}

	public function setInitialBalanceAttribute($value)
	{
		$this->attributes["initial_balance"] = priceToInt($value);
	}

	public function getInitialBalanceAttribute()
	{
		return intToPrice($this->attributes["initial_balance"]);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function getFClosedAtAttribute()
	{
		if (!@$this->closed_at) return;
		return $this->closed_at->format("d/m/Y - H:i:s");
	}

	public function getFCreatedAtAttribute()
	{
		return $this->created_at->format("d/m/Y - H:i:s");
	}

	public function getBalanceAttribute()
	{
		return null;
		$balance = $this->initial_balance;
		return $balance;
	}
}