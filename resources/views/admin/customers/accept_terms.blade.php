@extends("templates.default")
@section("title","Termo de Garantia")
@section('body')
   <accept-terms :customer='@json($customer)' :tenant='@json($tenant)'></accept-terms>
@endsection