<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Check,
	Text,
	TextArea,
	Upload
};
use App\Http\Models\{Product, CustomField};

use App\Http\Filters\FilterByCustomFields;

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
		$columns["f_images"] = ["label" => "Imagem", "sortable" => false];
		$columns["f_qty"] = ["label" => "Estoque", "sortable_index" => "qty"];
		$columns["f_price"] = ["label" => "Preço"];
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

	public function filters()
	{
		$filters = [];
		foreach (CustomField::where("resource", $this->id)->where("make_filter", true)->get() as $field) {
			$filters[] = new FilterByCustomFields($field);
		}
		return $filters;
	}

	public function fields()
	{
		$fields = [
			"Identificação" => [
				new Upload([
					"label" => "Imagens",
					"field" => "images",
					"preview"  => true,
					"multiple" => true,
					"limit" => 3,
					"accept" => "image/*",
					"list_type" => "picture-card"
				]),
				new Text([
					"label" => "Nome",
					"description" => "Indentificação do Produto",
					"field" => "name",
					"rules" => ["required", "max:255"]
				]),
				new TextArea([
					"label" => "Descrição",
					"field" => "description",
				]),
			],
			"Características" => [
				new Text([
					"label" => "Preço",
					"description" => "Preço Unitário ou por Kgs do produto",
					"field" => "price",
					"type" => "number",
					"rules" => ["required", "min:0.01"]
				]),
				new Check([
					"label" => "Permitir estoque negativo",
					"description" => "Permitir que este produto seja adicionado a uma venda caso seu estoque for zero ou menos, lembrando que ao cadastrar o produto inicialmente seu estoque será zero",
					"field" => "without_qty",
					"default" => true
				]),
			]
		];
		$cards = [];
		foreach (withCustomFields($this->id, $fields) as $key => $value) {
			$cards[] = new Card($key, $value);
		}
		return $cards;
	}

	public function export_columns()
	{
		$fields = [
			"code" => ["label" => "Código"],
			"name" => ["label" => "Nome"],
			"f_qty" => ["label" => "Estoque"],
			"price" => ["label" => "Preço"],
		];
		foreach (CustomField::where("resource", $this->id)->where("show_in_report", true)->get() as $field) {
			$fields[$field->field] = ["label" => $field->name];
		}
		return $fields;
	}
}