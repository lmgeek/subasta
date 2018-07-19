@extends('admin')


@section('content')
	@include("home.partials.dashboard_".Auth::user()->type)
@endsection

@section('scripts')

    <script>

    </script>
@endsection