<?php

namespace App\Http\Controllers;

use App\Http\Models\Customer;

class CustomerController extends Controller
{
	public function accept($id)
	{
		$customer = Customer::findOrFail($id);
		$tenant = $customer->tenant;
		if (!@$tenant->data->term) abort(404);
		return view("admin.customers.accept_terms", compact('customer', 'tenant'));
	}

	public function confirmTerm($id)
	{
		$customer = Customer::findOrFail($id);
		$tenant = $customer->tenant;
		if (!@$tenant->data->term) abort(404);
		$customer->accepted_terms = true;
		return ["success" => $customer->save()];
	}
}