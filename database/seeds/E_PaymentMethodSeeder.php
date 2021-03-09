<?php

use Illuminate\Database\Seeder;
use App\Http\Models\PaymentMethod;

class E_PaymentMethodSeeder extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createPaymentMethods();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	private function createPaymentMethods()
	{
		DB::table("payment_methods")->truncate();
		PaymentMethod::create(["name" => "Dinheiro", "type" => "money", "tenant_id" => 1]);
		PaymentMethod::create(["name" => "Crédito", "type" => "card", "tenant_id" => 1]);
		PaymentMethod::create(["name" => "Débito", "type" => "card", "tenant_id" => 1]);
	}
}