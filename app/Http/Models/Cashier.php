<?php

namespace App\Http\Models;

use App\User;

class Cashier extends PoloDefaultModel
{
	protected $table = "cashiers";
	public $appends = [
		"code", "f_created_at"
	];

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
		$balance = $this->initial_balance;
		return toMoney(@$balance ?? 0);
	}

	public function getIsOpenedAttribute()
	{
		return @$this->closed_at ? false : true;
	}

	public function getLabelAttribute()
	{
		$url = "";
		if (hasPermissionTo(('view-pos'))) {
			$code = $this->code;
			if ($this->is_opened) $url = "<a href='/admin/caixas/$code/frente-de-caixa'>Abrir Frente de Caixa</a>";
		}
		return "
			<div class='d-flex flex-column'>
				<b>$code</b>
				$url
			</div>
		";
	}
}