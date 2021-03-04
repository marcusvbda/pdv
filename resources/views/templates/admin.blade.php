@extends("templates.default")
@section('body')
    @include("templates.navbar")
	@yield("out-content")
    <div class="my-2 container-fluid">
		@yield("breadcrumb")
	</div>
    <div class="container-fluid pb-5" >
        @yield("content")
    </div>
@endsection