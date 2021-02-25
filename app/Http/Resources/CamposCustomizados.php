<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	CustomComponent,
	BelongsTo,
	Check,
	TextArea,
};
use App\Http\Models\CustomField;
use Auth;

class CamposCustomizados extends Resource
{
	public $model = CustomField::class;


	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Campos Customizados";
	}

	public function singularLabel()
	{
		return "Campo Customizado";
	}

	public function icon()
	{
		return "el-icon-circle-plus";
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
		$columns["card"] = ["label" => "Nome do Card"];
		$columns["type"] = ["label" => "Tipo"];
		return $columns;
	}

	public function canViewList()
	{
		return Auth::user()->hasRole(["super-admin"]);
	}

	public function canCreate()
	{
		return Auth::user()->hasRole(["super-admin"]);
	}

	public function canUpdate()
	{
		return Auth::user()->hasRole(["super-admin"]);
	}

	public function canDelete()
	{
		return Auth::user()->hasRole(["super-admin"]);
	}

	public function canExport()
	{
		return Auth::user()->hasRole(["super-admin"]);
	}

	public function canViewReport()
	{
		return Auth::user()->hasRole(["super-admin"]);
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
		return  [
			new Card("Identificação", [
				new Text([
					"label" => "Nome do Card",
					"description" => "Identificação do card o qual vai ser incluído o campo",
					"field" => "card",
					"rules" => ["required", "max:255"]
				]),
				new Text([
					"label" => "Nome",
					"description" => "Apenas para identificação",
					"field" => "name",
					"rules" => ["required", "max:255"]
				]),
				new TextArea([
					"label" => "Descrição do Campo",
					"description" => "Aparecerá abaixo do titulo como está descrição",
					"field" => "description",
				]),
			]),
			new Card("Configurações", [
				new BelongsTo([
					"label" => "Recurso",
					"description" => "Em qual recurso esse campo personalizado deve-se aplicar",
					"field" => "resource",
					"options" => ["produtos", "clientes"],
					"rules" => ["required", "max:255"]
				]),
				new BelongsTo([
					"label" => "Tipo",
					"description" => "Tipo do Campo Customizado",
					"field" => "type",
					"default" => "text",
					"options" => ["text", "select"],
					"rules" => ["required", "max:255"]
				]),
				new CustomComponent("<custom-field-options :form='form' :data='data' :errors='errors' />", [
					"field" => "options",
					"label" => "Opções",
				]),
				new Check([
					"label" => "Obrigatório",
					"description" => "Se sim o campo será setado como required",
					"field" => "required",
					"default" => true
				]),
				new Check([
					"label" => "Mostrar na listagem do Recurso",
					"field" => "show_in_list",
					"default" => true
				]),
				new Check([
					"label" => "Mostrar no relatório",
					"field" => "show_in_report",
					"default" => true
				]),
				new Check([
					"label" => "Criar Filtro",
					"field" => "make_filter",
					"default" => true
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