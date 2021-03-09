<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\{Cashier, Sale};

class SalesController extends Controller
{
	public function proof($id)
	{
		$sale = Sale::with(["polo"])->findOrFail($id);
		return view("admin.sales.proof", compact("sale"));
	}

	public function storeSale($id, Request $request)
	{
		if (!hasPermissionTo('view-pos')) abort(403);
		$cashier = Cashier::with("user")->findOrFail($id);
		$sale = Sale::create([
			"cashier_id" => $cashier->id,
			"data" => $request->all()
		]);
		return ["success" => true, "sale" => $sale];
	}

	public function cancelSale($id)
	{
		$sale = Sale::findOrFail($id);
		$cashier = $sale->cashier;
		if (!$cashier->is_opened) return abort(404);
		$sale->status = "canceled";
		$sale->save();
		return ["success" => true];
	}
}