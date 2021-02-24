<?php

namespace App\Http\Models;

use App\Http\Models\CustomField;

class Customer extends PoloDefaultModel
{
	protected $table = "customers";
	public $casts = [
		"custom_fields" => "object",
		"address" => "object",
	];

	public function setAttribute($key, $value)
	{
		$custom_fields = CustomField::where("resource", "clientes")->get()->pluck("field")->toArray();
		if (!in_array($key, $custom_fields)) return parent::setAttribute($key, $value);
		return $this->setCustomField($key, $value);
	}

	public function getAttribute($key)
	{
		$custom_fields = CustomField::where("resource", "clientes")->get()->pluck("field")->toArray();
		if (!in_array($key, $custom_fields)) return parent::getAttribute($key);
		return $this->getCustomField($key);
	}

	private function setCustomField($index, $value)
	{
		$_data = (object)$this->custom_fields ?? [];
		$_data->{$index} = $value;
		$this->custom_fields = $_data;
	}

	private function getCustomField($index)
	{
		return @$this->custom_fields->{$index};
	}
}