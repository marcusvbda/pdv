<?php

use App\Http\Controllers\{CashierController, SalesController};

Route::group(["prefix" => "caixas"], function () {
	Route::get('{code}/frente-de-caixa', [CashierController::class, 'pos'])->middleware(['hashids:code']);
	Route::post('{code}/store-sale', [SalesController::class, 'storeSale'])->middleware(['hashids:code']);
});