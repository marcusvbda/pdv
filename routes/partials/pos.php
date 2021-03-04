<?php

use App\Http\Controllers\CashierController;

Route::group(["prefix" => "caixas"], function () {
	Route::get('{code}/frente-de-caixa', [CashierController::class, 'pos'])->middleware(['hashids:code']);
});