<?php

namespace App\Http\Models;

use App\User;

class Cashier extends PoloDefaultModel
{
	protected $table = "cashiers";
	public $appends = [
		"code", "f_created_at", "balance", "f_closed_at"
	];

	public $dates = [
		"created_at", "updated_at", "closed_at"
	];

	public function ScopeisClosed($query)
	{
		$query->whereNotNull("closed_at");
	}

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
		$balance += $this->sales()->where("status", "paid")->sum("data->payment->total");
		$balance += $this->entries()->sum("data->value");
		$balance -= $this->expenses()->sum("data->value");
		return @$balance ?? 0;
	}

	public function getFBalanceAttribute()
	{
		return toMoney($this->balance);
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
			if ($this->is_opened) $url = "<a class='link f-12' href='/admin/caixas/$code/frente-de-caixa'>Abrir Frente de Caixa</a>";
			else $url = $this->conference_badge;
		}
		return "
			<div class='d-flex flex-column'>
				<b>$code</b>
				$url
			</div>
		";
	}

	public function sales()
	{
		return $this->hasMany(Sale::class);
	}

	public function expenses()
	{
		return $this->hasMany(CashierExpense::class)->where("type", "cash_out");
	}

	public function entries()
	{
		return $this->hasMany(CashierExpense::class)->where("type", "cash_in");
	}

	public function getConferenceBadgeAttribute()
	{
		$conference = $this->conference;
		$code = $this->code;
		if (!$conference) {
			return "<div class='d-flex flex-row align-items-center mt-2'>
						<a href='/admin/caixas/$code/conferencia' class='text-danger f-12'>
							<span class='text-danger el-icon-warning mr-1'></span>Faça a conferência deste caixa !!
						</a>
					</div>";
		}
		return "<div class='d-flex flex-row align-items-center mt-2'>
					<a href='/admin/caixas/$code/conferencia' class='text-success f-12'>
						<span class='text-success el-icon-success mr-1'></span>Visualizar Conferência
					</a>
				</div>";
	}

	public function conference()
	{
		return $this->hasOne(CashierConference::class);
	}
}