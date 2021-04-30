<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text
};

class CategoriasDeProduto extends Resource
{
	public $model = \App\Http\Models\ProductCategory::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Categorias de Produto";
	}

	public function singularLabel()
	{
		return "Categorias de Produto";
	}

	public function icon()
	{
		return "el-icon-s-flag";
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
		return $columns;
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-products-groups");
	}

	public function canCreate()
	{
		return hasPermissionTo("create-products-groups");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-products-groups");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-products-groups");
	}

	public function canExport()
	{
		return hasPermissionTo("report-products-groups");
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
				"field" => "name",
				"rules" => ["required", "max:255"]
			]),
		]);
		return $cards;
	}
}