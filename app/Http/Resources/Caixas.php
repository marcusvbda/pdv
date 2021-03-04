<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	BelongsTo,
	Text
};
use App\Http\Models\{Cashier};
use App\User;
use Auth;
use Carbon\Carbon;

class Caixas extends Resource
{
	public $model = Cashier::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Caixas";
	}

	public function singularLabel()
	{
		return "Caixa";
	}

	public function icon()
	{
		return "el-icon-s-finance";
	}

	public function search()
	{
		return ["name"];
	}

	public function table()
	{
		$columns = [];
		$columns["label"] = ["label" => "#", "sortable_index" => "id"];
		$columns["user->name"] = ["label" => "Responsável", "sortable_index" => "user_id"];
		$columns["balance"] = ["label" => "Saldo", "sortable" => false];
		$columns["f_created_at"] = ["label" => "Data de Abertura", "sortable_index" => "created_at"];
		$columns["f_closed_at"] = ["label" => "Data de Fechamento", "sortable_index" => "closed_at"];
		return $columns;
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-cashiers");
	}

	public function canCreate()
	{
		return hasPermissionTo("create-cashiers") && Cashier::isOpened()->count() == 0;
	}

	public function canUpdate()
	{
		return false;
	}

	public function canDelete()
	{
		return false;
	}

	public function canExport()
	{
		return hasPermissionTo("report-cashiers");
	}

	public function canViewReport()
	{
		return hasPermissionTo("report-cashiers");
	}

	public function canImport()
	{
		return false;
	}

	public function canView()
	{
		return false;
	}

	public function fields()
	{
		$cards = [];
		$cards[] = new Card("Identificação", [
			new BelongsTo([
				"label" => "Usuário",
				"description" => "Usuário responsável por este caixa",
				"field" => "user_id",
				"options" => User::where("name", "!=", "root")->selectRaw("id,name as value")->get(),
				"disabled" => true,
				"default" => (string)Auth::user()->id,
				"required" => true
			]),
			new Text([
				"label" => "Data de Abertura",
				"field" => "created_at",
				"disabled" => true,
				"type" => "datetime",
				"default" => Carbon::now()->format("d/m/Y H:i:s"),
				"required" => true
			])
		]);
		$cards[] = new Card("Configurações", [
			new Text([
				"label" => "Saldo",
				"description" => "Saldo inicial do caixa, considerar o valor de troco deixado na abertura do caixa",
				"field" => "initial_balance",
				"type" => "number",
				"default" => 0,
				"required" => true
			])
		]);
		return $cards;
	}

	public function export_columns()
	{
		$fields = [
			"code" => ["label" => "Código"],
			"balance" => ["label" => "Saldo"],
		];
		return $fields;
	}

	public function storeButtonlabel()
	{
		return "<span class='el-icon-plus mr-2'></span>Novo Caixa";
	}

	public function nothingStoredText()
	{
		return "<h4>Nenhum " . strtolower($this->singularLabel()) . " aberto ainda...<h4>";
	}

	public function nothingStoredSubText()
	{
		return "<span>Clique no botão abaixo para abrir o primeiro " .  strtolower($this->singularLabel()) . " ...</span>";
	}
}