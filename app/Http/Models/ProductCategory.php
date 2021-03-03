<?php

namespace App\Http\Models;

use App\Http\Models\SuperAdminAccessModel;

class ProductCategory extends SuperAdminAccessModel
{
	protected $table = "product_categories";

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function products()
	{
		return $this->hasMany(Products::class);
	}
}