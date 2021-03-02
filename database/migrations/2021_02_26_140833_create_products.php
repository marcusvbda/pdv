<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducts extends Migration
{
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->longText('images')->nullable();
			$table->string('name');
			$table->longtext('description')->nullable();
			$table->integer('price')->default(0);
			$table->boolean('without_qty')->default(true);
			$table->integer('qty')->default(0);
			$table->jsonb('custom_fields');
			$table->unsignedBigInteger('product_group_id')->nullable();
			$table->foreign('product_group_id')
				->references('id')
				->on('product_groups')
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
		Schema::dropIfExists('products');
	}
}