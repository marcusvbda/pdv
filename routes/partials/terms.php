<?php

use App\Http\Controllers\CustomerController;

Route::group(["prefix" => "termo-de-garantia"], function () {
	Route::get('{code}', [CustomerController::class, 'accept'])->middleware(['hashids:code'])->name("accept_terms");
	Route::post('{code}/aceitar', [CustomerController::class, 'confirmTerm'])->middleware(['hashids:code']);
});