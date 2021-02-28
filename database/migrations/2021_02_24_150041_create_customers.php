<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomers extends Migration
{
	public function up()
	{
		Schema::create('customers', function (Blueprint $table) {
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('email')->nullable();
			$table->longtext('images')->nullable();
			$table->string('doc')->nullable();
			$table->string('phone')->nullable();
			$table->string('cellphone')->nullable();
			$table->jsonb('address')->nullable();
			$table->jsonb('custom_fields')->nullable();
			$table->boolean('accepted_terms')->default(false);
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
		Schema::dropIfExists('customers');
	}
}