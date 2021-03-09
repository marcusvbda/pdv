@extends("templates.default")
@section('title',"Compravante de Venda #".$sale->code)

@section('body')
<sale-proof
 :sale='@json($sale)'
>
</sale-proof>
@endsection
