<?php

use App\Http\Controllers\{CashierController};

Route::group(["prefix" => "caixas"], function () {
	Route::get('{code}/frente-de-caixa', [CashierController::class, 'pos'])->middleware(['hashids:code']);
	Route::post('{code}/store-sale', [CashierController::class, 'storeSale'])->middleware(['hashids:code']);
	Route::get('{code}/vendas/{sale_code}/comprovante', [CashierController::class, 'saleProof'])->middleware(['hashids:code', 'hashids:sale_code']);
	Route::delete('{code}/vendas/{sale_code}/cancel', [CashierController::class, 'cancelSale'])->middleware(['hashids:code', 'hashids:sale_code']);
});