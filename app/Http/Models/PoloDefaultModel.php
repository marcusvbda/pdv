<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\{PoloScope};
use Auth;

class PoloDefaultModel extends DefaultModel
{
	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new PoloScope(with(new static)->getTable()));
		static::creating(function ($model) {
			if (Auth::check()) {
				$user = Auth::user();
				if (!@$model->polo_id && $user->polo_id) $model->polo_id = $user->polo_id;
			}
		});
	}
}