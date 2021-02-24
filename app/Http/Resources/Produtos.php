<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
};
use App\Http\Models\Product;

class Produtos extends Resource
{
	public $model = Product::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Produtos";
	}

	public function singularLabel()
	{
		return "Produto";
	}

	public function icon()
	{
		return "el-icon-s-flag";
	}

	public function search()
	{
		return ["name"];
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "#", "sortable_index" => "id"];
		$columns["name"] = ["label" => "Nome"];
		return $columns;
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-products");
	}

	public function canCreate()
	{
		return hasPermissionTo("create-products");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-products");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-products");
	}

	public function canExport()
	{
		return hasPermissionTo("report-products");
	}

	public function canViewReport()
	{
		return hasPermissionTo("report-products");
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
		return [
			new Card("Identificação",  [
				new Text([
					"label" => "Nome",
					"description" => "Indentificação do Produto",
					"field" => "name",
					"rules" => ["required", "max:255"]
				]),
			])
		];
	}

	public function export_columns()
	{
		return [
			"code" => ["label" => "Código"],
			"name" => ["label" => "Nome"],
		];
	}
}