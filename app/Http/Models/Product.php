<?php

namespace App\Http\Models;

use App\Http\Models\Traits\{HasCustomFields, CastNumbersToInt};

class Product extends PoloDefaultModel
{
	use HasCustomFields;
	protected $table = "products";
	public $casts = [
		"custom_fields" => "object",
		"images" => "array",
		"without_qty" => "boolean"
	];

	public static $types = [
		["id" => "P", "value" => "Produto Físico"],
		["id" => "S", "value" => "Serviço"],
	];

	public function setPriceAttribute($value)
	{
		$this->attributes["price"] = priceToInt($value);
	}

	public function setQtyAttribute($value)
	{
		$this->attributes["qty"] = priceToInt($value);
	}

	public function getQtyAttribute()
	{
		return intToPrice($this->attributes["qty"]);
	}

	public function getPriceAttribute()
	{
		return intToPrice($this->attributes["price"]);
	}

	public function getFPriceAttribute()
	{
		return toMoney($this->price);
	}

	public function getFTypeAttribute()
	{
		$type = $this->type;
		$selected_type = current(array_filter(Self::$types, function ($row) use ($type) {
			return $row["id"] == $type;
		}));
		return $selected_type["value"];
	}


	public function getFImagesAttribute()
	{
		$image =  @$this->images[0];
		if (!$image) return;
		$div = "<div class='image-list-preview'>";
		foreach ($this->images as $image) {
			$div .= "<el-image 
						class='image'
						src='$image' 
						:preview-src-list='" . json_encode($this->images) . "'>
					</el-image>";
		}
		$div .= "</div>";
		return $div;
	}

	public function getFQtyAttribute()
	{
		$qty = $this->qty;
		return $qty . " Unidade" . ($qty > 1 ? 's' : '');
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function category()
	{
		return $this->belongsTo(ProductCategory::class, "product_category_id", "id");
	}
}