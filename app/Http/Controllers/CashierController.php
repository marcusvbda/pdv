<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\{Cashier, CashierConference, Sale, CashierExpense};
use marcusvbda\vstack\Services\Messages;
use Carbon\Carbon;
use Auth;

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
		$cashier = Cashier::with("user")->findOrFail($id);
		$sale = Sale::create([
			"cashier_id" => $cashier->id,
			"data" => $request->all()
		]);
		return ["success" => true, "sale" => $sale];
	}

	public function finish($id)
	{
		$cashier = Cashier::with("user")->findOrFail($id);
		$cashier->closed_at = Carbon::now();
		$cashier->save();
		Messages::send("success", "Caixa encerrado com sucesso !!");
		return ["success" => true];
	}

	public function storeExpense($id, Request $request)
	{
		$cashier = Cashier::with("user")->findOrFail($id);
		CashierExpense::create([
			"cashier_id" => $cashier->id,
			"type" => $request["type"],
			"data" => $request["data"]
		]);
		return ["success" => true];
	}

	public function destroyExpense($cashier_id, $expense_id)
	{
		$expense = CashierExpense::where("cashier_id", $cashier_id)->where("id", $expense_id)->firstOrFail();
		$cashier = $expense->cashier;
		if (!$cashier->is_opened) return abort(404);
		$expense->delete();
		return ["success" => true];
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

	public function details($id)
	{
		$cashier = Cashier::with("user")->findOrFail($id);
		$graph_data = $cashier->sales()->where("status", "paid")->groupBy("data->payment->payment_method->name")
			->selectRaw("sum(json_unquote(json_extract(data, '$.\"payment\".\"total\"'))) as total")
			->selectRaw("json_unquote(json_extract(data, '$.\"payment\".\"payment_method\".\"name\"')) as payment_method")
			->get()
			->pluck("total", "payment_method");

		$balance = $cashier->balance;
		return [
			"success" => true,
			"balance" => $balance,
			"graph_data" => $graph_data
		];
	}

	public function conference($id)
	{
		$cashier = Cashier::isClosed()->with("user")->findOrFail($id);
		return view("admin.cashier.conference", compact("cashier"));
	}

	public function getConference($id)
	{
		$cashier = Cashier::with("user")->findOrFail($id);
		$current_conference = $cashier->conference()->with("user")->first();
		$conference_data = $cashier->sales()
			->where("status", "paid")
			->groupBy("data->payment->payment_method->name")
			->selectRaw("sum(json_unquote(json_extract(data, '$.\"payment\".\"total\"'))) as total")
			->selectRaw("json_unquote(json_extract(data, '$.\"payment\".\"payment_method\".\"name\"')) as payment_method")
			->get()
			->pluck("total", "payment_method")
			->toArray();

		if (!$current_conference) {
			$current_conference = ["data" => []];
			foreach (array_keys($conference_data) as $key) $current_conference["data"][$key] = 0;
		}
		return [
			"success" => true,
			"conference_data" => $conference_data,
			"current_conference" => $current_conference,
			"total_cash_in" => $cashier->entries()->sum("data->value"),
			"total_cash_out" => $cashier->expenses()->sum("data->value"),
		];
	}

	public function conferenceStore($id, Request $request)
	{
		$user = Auth::user();
		CashierConference::where("cashier_id", $id)->delete();
		CashierConference::create([
			"cashier_id" => $id,
			"data" => $request->all(),
			"user_id" => $user->id
		]);
		return ["success" => true];
	}
}