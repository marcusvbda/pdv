<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Carbon\Carbon;
use App\Http\Models\{Cashier};

class CashierController extends Controller
{
	public function pos($id)
	{
		if (!hasPermissionTo('view-pos')) abort(403);
		$cashier = Cashier::with("user")->findOrFail($id);
		return view('admin.cashier.pos', compact("cashier"));
	}
}