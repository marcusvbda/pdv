<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	CustomComponent,
	Upload
};
use App\Http\Models\{Customer, CustomField};
use marcusvbda\ValidadorCpfCnpj\Documento as ValidatorDoc;
use App\Http\Filters\FilterByCustomFields;
use Illuminate\Support\Facades\Auth;

class Clientes extends Resource
{
	public $model = Customer::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Clientes";
	}

	public function singularLabel()
	{
		return "Cliente";
	}

	public function icon()
	{
		return "el-icon-user-solid";
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
		$columns["f_images"] = ["label" => "Foto", "sortable" => false];
		$columns["doc"] = ["label" => "CPF"];
		$columns["email"] = ["label" => "Email"];
		if (@Auth::user()->tenant) $columns["f_accepted_terms"] = ["label" => "Termo de Garantia", "sortable_index" => "accepted_terms"];
		foreach (CustomField::where("resource", "clientes")->where("show_in_list", true)->get() as $field) {
			$columns[$field->field] = ["label" => $field->name, "sortable_index" => "custom_fields->" . $field->field];
		}
		return $columns;
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-customers");
	}

	public function canCreate()
	{
		return hasPermissionTo("create-customers");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-customers");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-customers");
	}

	public function canExport()
	{
		return hasPermissionTo("report-customers");
	}

	public function canViewReport()
	{
		return hasPermissionTo("report-customers");
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
		foreach (CustomField::where("resource", "clientes")->where("make_filter", true)->get() as $field) {
			$filters[] = new FilterByCustomFields($field);
		}
		return $filters;
	}

	public function fields()
	{
		$fields = [
			"Identificação" => [
				new Upload([
					"label" => "Foto",
					"field" => "images",
					"preview"  => true,
					"multiple" => true,
					"limit" => 1,
					"accept" => "image/*",
					"list_type" => "picture-card"
				]),
				new Text([
					"label" => "Nome",
					"description" => "Nome completo do cliente",
					"field" => "name",
					"rules" => ["required", "max:255"]
				]),
				new Text([
					"label" => "CPF",
					"field" => "doc",
					"description" => "O Sistema adicionará os pontos automáticamente",
					"mask"  => "###.###.###-##",
					"rules" => ["max:255", function ($attribute, $value, $fail) {
						if (!$value) return;
						$doc = new ValidatorDoc($value);
						if (!($doc)->isValid()) $fail("Digite um CPF ou CNPJ válido.");
					}],
				])
			],
			"Contato" => [
				new Text([
					"label" => "Email",
					"field" => "email",
					"rules" => ["max:255"]
				]),
				new Text([
					"label" => "Telefone",
					"field" => "phone",
					"mask"  => "(##) ####-####",
					"rules" => ["max:255"]
				]),
				new Text([
					"label" => "Celular",
					"field" => "cellphone",
					"mask"  => "(##) #####-####",
					"rules" => ["max:255"]
				]),
			],
			"Localização" => [
				new CustomComponent("<address-component :form='form' :data='data' :errors='errors' />", [
					"field" => "address",
					"label" => "Endereço",
				])
			]
		];
		$cards = [];
		foreach (withCustomFields("clientes", $fields) as $key => $value) {
			$cards[] = new Card($key, $value);
		}
		return $cards;
	}

	public function export_columns()
	{
		$fields = [
			"code" => ["label" => "Código"],
			"name" => ["label" => "Nome"],
			"accepted_terms" => ["label" => "Termo de Garantia", "handler" => function ($row) {
				$options = [
					true => "Aceitou o termo",
					false => "Não aceitou o termo",
				];
				return $options[$row->accepted_terms ?? false];
			}],
		];
		foreach (CustomField::where("resource", "clientes")->where("show_in_report", true)->get() as $field) {
			$fields[$field->field] = ["label" => $field->name];
		}
		return $fields;
	}
}