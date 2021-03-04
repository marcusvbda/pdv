@extends("templates.default")
@section('title',"Frente de Caixa")

@section('body')
@php
	$permissions = [
		"viewlist_cashiers" => hasPermissionTo('viewlist-cashiers')
	];
@endphp
<opened-pos
	:_cashier='@json($cashier)'
	:_permissions='@json($permissions)'
>
</opened-pos>
@endsection
