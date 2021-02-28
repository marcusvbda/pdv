<?php

namespace App\Http\Models;

use App\Http\Models\Traits\HasCustomFields;

class Customer extends PoloDefaultModel
{
	use HasCustomFields;
	protected $table = "customers";
	public $appends = ["code"];
	public $casts = [
		"custom_fields" => "object",
		"address" => "object",
		"images" => "array",
	];

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

	public function getFAcceptedTermsAttribute()
	{
		$url = route('accept_terms', ['code' => $this->code]);
		$link = "<a href='$url' target='_BLANK'>$url</a>";
		$alerts = [
			false => "<el-alert title='Ainda não Aceitou os termo de garantia' type='error' :closable='false' show-icon>
						<p class='mb-0'>Envie este link ($link)</p>
						<p class='mb-0'> para seu cliente e aguarde o aceite do termo de garantia</p>
					 </el-alert>",
			true => "<el-alert title='Aceitou o termo de garantia' type='success' :closable='false' show-icon/>"
		];
		return $alerts[@$this->accepted_terms ?? false];
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}
}