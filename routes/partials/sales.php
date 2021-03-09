<?php

use App\Http\Controllers\SalesController;

Route::group(["prefix" => "vendas"], function () {
	Route::get('{code}/comprovante', [SalesController::class, 'proof'])->middleware(['hashids:code']);
});