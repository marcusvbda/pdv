<?php

use Illuminate\Database\Seeder;
use App\Http\Models\Product;

class D_ExampleProductsSeeder extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createProducts();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	private function createProducts()
	{
		DB::table("products")->truncate();
		for ($i = 0; $i < 100; $i++) {
			Product::create([
				"name" => "Produto Teste $i",
				"price" => rand(1, 150),
				"product_category_id" => 1,
				"polo_id" => 1,
				"tenant_id" => 1,
			]);
		}
	}
}