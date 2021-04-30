<?php

namespace App\Http\Resources;

use App\Http\Models\PaymentMethod;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	Radio
};

class MetodosDePagamento extends Resource
{
	public $model = \App\Http\Models\PaymentMethod::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Métodos de Pagamento";
	}

	public function singularLabel()
	{
		return "Método de Pagamento";
	}

	public function icon()
	{
		return "el-icon-bank-card";
	}

	public function search()
	{
		return ["name"];
	}

	public function canClone()
	{
		return false;
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "#", "sortable_index" => "id"];
		$columns["name"] = ["label" => "Nome"];
		$columns["f_type"] = ["label" => "Tipo", "sortable_index" => "type"];
		return $columns;
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-paymentmethods");
	}

	public function canCreate()
	{
		return hasPermissionTo("create-paymentmethods");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-paymentmethods");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-paymentmethods");
	}

	public function canExport()
	{
		return hasPermissionTo("report-paymentmethods");
	}

	public function canViewReport()
	{
		return false;
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
		$cards[] = new Card('Identificação', [
			new Text([
				"label" => "Nome",
				"description" => "Como será apresentado para seleção no frente de caixa",
				"field" => "name",
				"rules" => ["required", "max:255"]
			]),
			new Radio([
				"label" => "Tipo",
				"description" => "Como o sistema deve se comportar com esta forma de pagamento",
				"field" => "type",
				"options" =>  PaymentMethod::_TYPES_
			])
		]);

		return $cards;
	}
}