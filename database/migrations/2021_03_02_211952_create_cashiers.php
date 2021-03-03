<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashiers extends Migration
{
	public function up()
	{
		Schema::create('cashiers', function (Blueprint $table) {
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->integer('initial_balance')->default(0);
			$table->unsignedBigInteger('user_id')->nullable();
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onDelete('restrict');
			$table->unsignedBigInteger('polo_id')->nullable();
			$table->foreign('polo_id')
				->references('id')
				->on('polos')
				->onDelete('restrict');
			$table->unsignedBigInteger('tenant_id');
			$table->foreign('tenant_id')
				->references('id')
				->on('tenants')
				->onDelete('restrict');
			$table->dateTime("closed_at")->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('cashiers');
	}
}