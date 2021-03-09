<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\{Cashier, Sale};

class CashierController extends Controller
{
	public function pos($id)
	{
		if (!hasPermissionTo('view-pos')) abort(403);
		$cashier = Cashier::with("user")->findOrFail($id);
		return view('admin.cashier.pos', compact("cashier"));
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

	public function cancelSale($cashier_id, $sale_id)
	{
		$sale = Sale::where("cashier_id", $cashier_id)->where("id", $sale_id)->firstOrFail();
		$cashier = $sale->cashier;
		if (!$cashier->is_opened) return abort(404);
		$sale->status = "canceled";
		$sale->save();
		return ["success" => true];
	}

	public function saleProof($cashier_id, $sale_id)
	{
		$sale = Sale::where("cashier_id", $cashier_id)->where("id", $sale_id)->with(["polo"])->firstOrFail();
		return view("admin.sales.proof", compact("sale"));
	}
}