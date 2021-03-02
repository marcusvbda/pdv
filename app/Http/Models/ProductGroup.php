<?php

namespace App\Http\Models;

use App\Http\Models\SuperAdminAccessModel;

class ProductGroup extends SuperAdminAccessModel
{
	protected $table = "product_groups";

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function products()
	{
		return $this->hasMany(Products::class);
	}
}