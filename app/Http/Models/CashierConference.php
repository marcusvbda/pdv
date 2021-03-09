<?php

namespace App\Http\Models;

use Arr;
use App\User;

class CashierConference extends PoloDefaultModel
{
	protected $table = "cashier_conferences";
	public $casts = [
		"data" => "object"
	];

	public $appends = ["f_created_at"];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function cashier()
	{
		return $this->belongsTo(Cashier::class);
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function getFCreatedAtAttribute()
	{
		return $this->created_at->format("d/m/Y H:i:s");
	}
}