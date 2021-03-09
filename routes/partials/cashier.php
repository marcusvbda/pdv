<?php

use App\Http\Controllers\{CashierController};

Route::group(["prefix" => "caixas"], function () {
	Route::post('{code}/details', [CashierController::class, 'details'])->middleware(['hashids:code']);
	Route::get('{code}/frente-de-caixa', [CashierController::class, 'pos'])->middleware(['hashids:code']);
	Route::post('{code}/finish', [CashierController::class, 'finish'])->middleware(['hashids:code']);
	Route::post('{code}/store-sale', [CashierController::class, 'storeSale'])->middleware(['hashids:code']);
	Route::post('{code}/store-expense', [CashierController::class, 'storeExpense'])->middleware(['hashids:code']);
	Route::get('{code}/conferencia', [CashierController::class, 'conference'])->middleware(['hashids:code']);
	Route::delete('{code}/expenses/{expense}/destroy', [CashierController::class, 'destroyExpense'])->middleware(['hashids:code', 'hashids:expense']);
	Route::get('{code}/vendas/{sale_code}/comprovante', [CashierController::class, 'saleProof'])->middleware(['hashids:code', 'hashids:sale_code']);
	Route::delete('{code}/vendas/{sale_code}/cancel', [CashierController::class, 'cancelSale'])->middleware(['hashids:code', 'hashids:sale_code']);
});