<?php

use Illuminate\Database\Seeder;
use App\Http\Models\CustomField;

class C_CustomFieldsSeeder extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createCorDoCabelo();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	private function createCorDoCabelo()
	{
		DB::table("custom_fields")->truncate();
		CustomField::create([
			"name" => "Tipo de Cabelo",
			"card" => "Aparência",
			"resource" => "clientes",
			"type" => "select",
			"required" => false,
			"options" => ["Cacheado", "Liso", "Ondulado", "Crespo", "Outro"],
			"tenant_id" => 1
		]);
		CustomField::create([
			"name" => "Cor do Cabelo",
			"card" => "Aparência",
			"resource" => "clientes",
			"type" => "select",
			"required" => false,
			"options" => ["Loiro", "Ruivo", "Castanho", "Preto", "Outro"],
			"tenant_id" => 1
		]);
		CustomField::create([
			"name" => "Comprimento do Cabelo",
			"card" => "Aparência",
			"resource" => "clientes",
			"type" => "select",
			"required" => false,
			"options" => ["Muito Curto", "Curto", "Nos Ombros", "Longo", "Muito Longo"],
			"tenant_id" => 1
		]);
	}
}