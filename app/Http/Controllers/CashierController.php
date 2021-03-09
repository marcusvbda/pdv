<?php

namespace App\Http\Controllers;

use App\Http\Models\{Cashier, Sale};

class CashierController extends Controller
{
	public function pos($id)
	{
		if (!hasPermissionTo('view-pos')) abort(403);
		$cashier = Cashier::with("user")->findOrFail($id);
		return view('admin.cashier.pos', compact("cashier"));
	}
}