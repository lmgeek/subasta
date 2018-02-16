@extends('admin')


@section('content')
	<div class="wrapper wrapper-content">
		@include("home.partials.dashboard_".Auth::user()->type)
	</div>
@endsection

@section('scripts')

    <script>

    </script>
@endsection