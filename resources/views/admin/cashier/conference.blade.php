@extends("templates.admin")
@section('title',"Conferência do Caixa ".$cashier->code)
@section("breadcrumb")
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin" class="link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/caixas" class="link">Caixas</a></li>
                    <li class="breadcrumb-item"><a href="/admin/caixas/{{ $cashier->code }}/conferencia" class="link">Conferência de Caixa</a></li>
                </ol>
            </nav>
        </nav>
    </div>
</div>
@endsection
@section("content")
<table-conference
	cashier_id='{{$cashier->id}}' complete
>
</table-conference>
@endsection
